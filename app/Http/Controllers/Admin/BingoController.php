<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BingoHash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Typography\FontFactory;
use ZipArchive;

class BingoController extends Controller
{
    public function index()
    {
        $events = BingoHash::query()
            ->selectRaw('event_slug, COUNT(*) as total, MAX(created_at) as last_generated')
            ->groupBy('event_slug')
            ->orderBy('last_generated', 'desc')
            ->get();

        return Inertia::render('Admin/Bingo/Index', [
            'events' => $events,
        ]);
    }

    public function store(Request $request)
    {
        set_time_limit(600);
        ini_set('memory_limit', '512M');

        $validator = Validator::make($request->all(), [
            'event_slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->boolean('is_new') && BingoHash::where('event_slug', $value)->exists()) {
                        $fail('El identificador del evento ya existe. Por favor elija otro o seleccione el evento de la lista para continuar.');
                    }
                },
            ],
            'quantity' => 'required|integer|min:1|max:1000',
            'start_sequence' => 'required|integer|min:1',
            'background_image' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $eventSlug = $request->input('event_slug');
        $quantity = $request->input('quantity');
        $startSequence = $request->input('start_sequence');
        $backgroundImageFile = $request->file('background_image');

        $tempDir = storage_path('app/temp_bingo_' . uniqid());
        File::makeDirectory($tempDir);

        $zipDir = storage_path('app/bingo-zips');
        File::ensureDirectoryExists($zipDir);

        $zipFileName = $eventSlug . '_' . time() . '.zip';
        $localZipPath = $zipDir . '/' . $zipFileName;

        $zip = new ZipArchive();
        if ($zip->open($localZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return Redirect::back()->with('error', 'No se pudo crear el archivo ZIP local.');
        }

        for ($i = 0; $i < $quantity; $i++) {
            $sequence = $startSequence + $i;
            $cardItems = $this->generateUniqueCardItems($eventSlug);

            if ($backgroundImageFile) {
                $image = Image::read($backgroundImageFile->getRealPath());
            } else {
                $image = Image::create(768, 1344)->fill('ffffff');
            }

            $this->drawDefaultBingoHeaders($image);
            $this->drawCardNumber($image, $sequence);
            $this->drawBingoGrid($image, $cardItems);

            $fileName = $eventSlug . '_' . $sequence . '.png';
            $filePath = $tempDir . '/' . $fileName;
            $image->save($filePath);

            $zip->addFile($filePath, $fileName);
        }

        $zip->close();
        File::deleteDirectory($tempDir);

        $downloadUrl = route('admin.bingo.download', ['filename' => $zipFileName]);

        return Redirect::route('admin.bingo.index')
            ->with('success', '¡Bingo generado! La descarga comenzará en breve.')
            ->with('download_url', $downloadUrl);
    }

    public function destroy($event_slug)
    {
        $count = BingoHash::where('event_slug', $event_slug)->delete();

        if ($count === 0) {
            return Redirect::back()->with('error', 'El evento no existe.');
        }

        $zipDir = storage_path('app/bingo-zips');
        $files = File::glob($zipDir . '/' . $event_slug . '_*.zip');
        foreach ($files as $file) {
            File::delete($file);
        }

        return Redirect::back()->with('success', 'Evento eliminado correctamente.');
    }

    public function download($filename)
    {
        $path = storage_path('app/bingo-zips/' . $filename);

        if (!File::exists($path)) {
            return Redirect::route('admin.bingo.index')->with('error', 'El archivo ya no está disponible.');
        }

        return response()->download($path)->deleteFileAfterSend();
    }

    public function downloadTemplate()
    {
        $image = Image::create(768, 1344)->fill('ffffff');

        $this->drawDefaultBingoHeaders($image);

        $this->drawCardNumber($image, '0001');

        $dummyItems = array_fill(0, 25, '88');
        $dummyItems[12] = null;

        $this->drawBingoGrid($image, $dummyItems);

        $tempPath = storage_path('app/bingo-template-guide.png');
        $image->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    private function drawCardNumber($image, $sequence)
    {
        $image->text('Cartón N°: ' . $sequence, 384, 70, function (FontFactory $font) {
            $font->file(public_path('fonts/Roboto-Bold.ttf'));
            $font->size(55);
            $font->color('000000');
            $font->align('center');
            $font->valign('middle');
        });
    }

    private function drawDefaultBingoHeaders($image)
    {
        $letters = ['B', 'I', 'N', 'G', 'O'];
        $x = 100;
        foreach ($letters as $letter) {
            $image->text($letter, $x, 175, function (FontFactory $font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(105);
                $font->color('000000');
                $font->align('center');
                $font->valign('middle');
            });
            $x += 142;
        }
    }

    private function drawBingoGrid($image, $items)
    {
        $colWidth = 142;
        $rowHeight = 142;
        $startX = 100;
        $startY = 315;

        foreach ($items as $index => $item) {
            if ($item === null) {
                continue;
            }

            $col = $index % 5;
            $row = floor($index / 5);
            $x = $startX + ($col * $colWidth);
            $y = $startY + ($row * $rowHeight);

            $image->text((string)$item, $x, $y, function (FontFactory $font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(85);
                $font->color('000000');
                $font->align('center');
                $font->valign('middle');
            });
        }
    }

    private function generateUniqueCardItems($eventSlug)
    {
        $maxAttempts = 100;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $colB = $this->getRandomNumbers(1, 15, 5);
            $colI = $this->getRandomNumbers(16, 30, 5);
            $colN = $this->getRandomNumbers(31, 45, 4);
            $colG = $this->getRandomNumbers(46, 60, 5);
            $colO = $this->getRandomNumbers(61, 75, 5);

            array_splice($colN, 2, 0, [null]);

            $cardItems = [];
            for ($row = 0; $row < 5; $row++) {
                $cardItems[] = $colB[$row];
                $cardItems[] = $colI[$row];
                $cardItems[] = $colN[$row];
                $cardItems[] = $colG[$row];
                $cardItems[] = $colO[$row];
            }

            $hashNumbers = array_filter($cardItems);
            $hash = md5(implode(',', $hashNumbers));

            if (BingoHash::where('event_slug', $eventSlug)->where('card_hash', $hash)->exists()) {
                continue;
            }

            BingoHash::create(['event_slug' => $eventSlug, 'card_hash' => $hash]);

            return $cardItems;
        }
        throw new \Exception("No se pudo generar un cartón único tras {$maxAttempts} intentos.");
    }

    private function getRandomNumbers($min, $max, $count)
    {
        $numbers = range($min, $max);
        shuffle($numbers);
        $selected = array_slice($numbers, 0, $count);
        sort($selected);
        return $selected;
    }
}
