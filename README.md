# WasiPet

WasiPet is a web application that supports pet management and adoption at Adra Uni. Visitors can browse animals, submit adoption applications, and record donations with payment receipts. The admin panel brings together pet management, adoption applications, donations, payment methods, and site settings.

## Features

- **Public pet catalog:** featured animals, a paginated listing with filters for name, species, gender, size, and age, and individual pet profiles accessed through slugs.
- **Adoption applications:** a public application form and an administrative review workflow. When an application is approved, an observer marks the pet as adopted, closes its other pending applications, and emails those applicants.
- **Donations and pet sponsorship:** records general donations or contributions associated with a pet, accepts payment receipt uploads, and supports administrative review with statuses and notes. The application records payments made externally; it does not integrate a payment gateway.
- **Administration:** pet forms and status updates, adoption and donation review, payment method management with bank details or QR codes, social links, and a homepage image.
- **Digital bingo:** generates PNG cards packaged in a ZIP, checks for duplicates within each event, and provides a downloadable design guide. ZIP files are stored temporarily and deleted after download.
- **Admin access:** session authentication, password recovery, email verification, and authorization through the `admin` role. Public user registration is disabled.

## Technology stack

| Area | Technologies |
| --- | --- |
| Backend | PHP `^8.2`, Laravel 12, Eloquent, Laravel Breeze |
| Frontend | Vue 3 with `<script setup>`, Inertia.js 2, Vuetify 3, Material Design Icons |
| Integration and build | Ziggy, Vite 6, `vite-plugin-vuetify`, Sass |
| Data and local development | MySQL 8.4, Docker Compose, Laravel Sail with PHP 8.5, Mailpit; MinIO included in Compose |
| Files and images | Cloudinary, Intervention Image 3, PHP ZipArchive extension |
| Verification | PHPUnit 11, Laravel Pint |

Resolved dependency versions are pinned in `composer.lock` and `package-lock.json`.

## Architecture

Laravel handles routing, validation through Form Requests, data access, and domain rules. Controllers deliver pages and data through `Inertia::render`; Vue renders the interface and uses `useForm` to submit forms. Vuetify supplies the UI components.

Business code separates **Admin** and **Public** controllers and pages. Authentication and profile controllers retain the Breeze structure. Admin routes, including the `/admin` entry point, require the `auth`, `verified`, and `admin` middleware.

```text
app/
├── Http/Controllers/{Admin,Public,Auth}/
├── Http/Requests/{Admin,Public,Auth}/
├── Http/Middleware/
├── Models/
├── Observers/
└── Notifications/
resources/js/
├── Pages/{Admin,Public,Auth,Profile}/
├── Layouts/
└── Components/
database/             # Migrations, factories, and seeders
routes/web.php        # Public and admin route groups
routes/auth.php       # Authentication
tests/                # PHPUnit
compose.yaml          # Local Sail environment
AGENTS.md             # Persistent development rules
```

Models contain relationships, scopes, and accessors. `AdoptionRequestObserver` coordinates application closure when an adoption is approved. Payment methods have their own table, while `settings` stores global configuration.

Image and payment receipt uploads use the Cloudinary disk, with their URLs stored in the database. The bingo generator processes its template locally and uses `storage/app/bingo-zips` for temporary files. MinIO is part of the local environment but does not replace Cloudinary in the implemented upload workflows.

## Installation and local development

Git, Docker, and Docker Compose are required. Use WSL2 on Windows. PHP, Composer, Artisan, and npm run inside Docker, following [AGENTS.md](AGENTS.md).

1. Clone the repository, enter its directory, and copy the environment configuration:

   ```bash
   cp .env.example .env
   ```

   Set `DB_PASSWORD` in `.env` to a local development password before starting MySQL. The connection uses `DB_HOST=mysql`, `DB_DATABASE=wasi_pet`, and `DB_USERNAME=sail`. If port 80 is occupied, set `APP_PORT` and adjust `APP_URL` to match. You can also change `FORWARD_DB_PORT` if another MySQL instance is running locally.

2. For a fresh clone without `vendor/`, install the initial dependencies using the Laravel Sail bootstrap image. This provides `vendor/bin/sail` without installing PHP or Composer on the host:

   ```bash
   docker run --rm \
     -u "$(id -u):$(id -g)" \
     -v "$PWD:/var/www/html" \
     -w /var/www/html \
     laravelsail/php84-composer:latest \
     composer install --no-interaction --ignore-platform-reqs
   ```

   Platform requirements are checked again inside the Sail runtime in the next step.

3. Start the environment and prepare the application:

   ```bash
   ./vendor/bin/sail up -d
   ./vendor/bin/sail composer install
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate --seed
   ./vendor/bin/sail npm ci
   ./vendor/bin/sail npm run dev
   ```

   Open `http://localhost` or the URL configured in `APP_URL`. Mailpit lets you inspect local emails at `http://localhost:8025`.

4. To enable file uploads, set `CLOUDINARY_URL` in your `.env` using your account's configuration value. You can explore the catalog and admin panel without credentials, but uploads require a configured account. Do not add real credentials to `.env.example` or the repository.

`DatabaseSeeder` creates sample data and two users only in the `local` or `development` environments. To explore the local admin panel, use `admin@wasipet.pe` with the factory password `password`. This account is created with a verified email and must not be used in a shared environment. Bank account numbers, payment receipts, and images in the seeders are examples and should be reviewed before any real use.

To stop the environment:

```bash
./vendor/bin/sail down
```

## Build and tests

```bash
# Build the frontend
./vendor/bin/sail npm run build

# Run the existing test suite
./vendor/bin/sail artisan test

# Alternative defined in composer.json: clear configuration and run the suite
./vendor/bin/sail composer test

# Check PHP formatting without modifying files
./vendor/bin/sail pint --test
```

`phpunit.xml` uses a separate MySQL database named `testing`, which Sail creates when initializing its volume. If an existing volume does not contain it, prepare that database and its permissions before running the suite. Tests using `RefreshDatabase` recreate its tables; always use a dedicated test database.

The suite focuses on authentication, profiles, and admin access protection. Business logic coverage is limited: there is no comprehensive suite for adoptions, donations, Cloudinary uploads, or bingo generation. There is also no frontend test command defined in `package.json`.

## Project status and known limitations

The project retains its MVP scope. Before using real data, review age persistence in the admin pet forms (`age` versus `birth_date`), compatibility between configurable payment methods and the donation enum, sample assets on public pages, and dependency security advisories.
