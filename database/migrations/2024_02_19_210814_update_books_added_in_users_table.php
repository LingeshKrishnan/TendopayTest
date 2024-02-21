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
        Schema::table('users', function (Blueprint $table) {
            $table->string('books_added')->nullable()->change();
            $table->string('books_lended')->nullable()->change();
            $table->string('number_of_books_added')->nullable()->change();
            $table->string('number_of_books_lended')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('books_added')->change();
            $table->string('books_lended')->change();
            $table->string('number_of_books_added')->change();
            $table->string('number_of_books_lended')->change();
        });
    }
};
