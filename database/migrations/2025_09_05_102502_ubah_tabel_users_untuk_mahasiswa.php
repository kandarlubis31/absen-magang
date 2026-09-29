<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim')->unique()->after('id');
            $table->string('jurusan')->nullable()->after('nim'); // Sudah ada di sini
            $table->string('universitas')->nullable()->after('jurusan'); // Sudah ada di sini
            $table->string('no_hp')->nullable()->after('email');
            $table->text('alamat')->nullable()->after('universitas');
            $table->string('role')->default('mahasiswa')->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nim', 'jurusan', 'universitas', 'no_hp', 'alamat', 'role']);
        });
    }
};