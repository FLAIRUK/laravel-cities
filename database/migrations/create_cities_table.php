<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function getConnection(): ?string
    {
        return config('cities.connection');
    }

    public function up(): void
    {
        Schema::create(config('cities.table', 'cities'), function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->char('code', 3)->unique();
            $table->string('name');
            $table->char('country_code', 2)->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('cities.table', 'cities'));
    }
};
