<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_jabatan', function (Blueprint $table) {
            $table->id('id_jabatan');
            $table->string('nama_jabatan', 50); // karyawan, proyek, pembayaran, dokumen, umum
        });

        DB::table('user_jabatan')->insert([
            ['nama_jabatan' => 'Karyawan'],
            ['nama_jabatan' => 'Proyek'],
            ['nama_jabatan' => 'Pembayaran'],
            // ['nama_jabatan' => 'Dokumen'],
            ['nama_jabatan' => 'Umum'],
        ]);

        Schema::create('user_level', function (Blueprint $table) {
            $table->id('id_level');
            $table->string('nama_level', 50);
        });

        DB::table('user_level')->insert([
            ['nama_level' => 'Admin'],
            ['nama_level' => 'User'],
        ]);

        Schema::create('users', function (Blueprint $table) {
            $table->id('id'); // Sesuaikan PK
            $table->string('name', 50)->unique();
            $table->string('nama_lengkap', 100)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Relasi sesuai file Word kamu
            $table->foreignId('id_level')->constrained('user_level', 'id_level')->default(2)->nullable();
            $table->foreignId('id_jabatan')->constrained('user_jabatan', 'id_jabatan')->default(2)->nullable();

            $table->datetime('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert([
            ['name' => 'admin', 'email' => 'admin@admin.com', 'id_level' => 1, 'id_jabatan' => 5], // Admin (All)
            ['name' => 'finance', 'email' => 'finance@test.com', 'id_level' => 2, 'id_jabatan' => 3], // Finance (COA, Transaksi, Jurnal)
            ['name' => 'proyek', 'email' => 'proyek@test.com', 'id_level' => 2, 'id_jabatan' => 2], // Proyek (Proyek, Vendor)
            ['name' => 'umum', 'email' => 'umum@test.com', 'id_level' => 2, 'id_jabatan' => 5], // Umum (Dashboard Only)
            ['name' => 'karyawan', 'email' => 'karyawan@test.com', 'id_level' => 2, 'id_jabatan' => 1], // Karyawan (Dashboard Only)
        ]);

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_jabatan');
        Schema::dropIfExists('user_level');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
