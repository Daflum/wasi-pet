<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('dogs', 'pets');

        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->renameColumn('dog_id', 'pet_id');
        });

        Schema::table('pets', function (Blueprint $table) {
            $table->string('type')->default('dog')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->renameColumn('pet_id', 'dog_id');
        });

        Schema::rename('pets', 'dogs');
    }
};
