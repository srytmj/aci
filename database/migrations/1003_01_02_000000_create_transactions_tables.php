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
        Schema::create('rekening_bank', function (Blueprint $table) {
            $table->id('id_rekening_bank');
            $table->string('nama_bank', 100);
            $table->string('no_rekening', 50);
            $table->string('nama_pemilik', 100);
            $table->timestamps();
        });

        DB::table('rekening_bank')->insert([
            ['nama_bank' => 'BCA', 'no_rekening' => '123-456-789', 'nama_pemilik' => 'CV Zahfran Mulai Abadi'],
            ['nama_bank' => 'Mandiri', 'no_rekening' => '444-555-666', 'nama_pemilik' => 'CV Zahfran Mulai Abadi'],
            ['nama_bank' => 'BNI', 'no_rekening' => '555-123-777', 'nama_pemilik' => 'CV Zahfran Mulai Abadi'],
        ]);

        Schema::create('kas', function (Blueprint $table) {
            $table->id('id_kas');
            $table->string('nama_kas', 100);
            $table->string('jenis', 50); // tunai, bank, proyek
            $table->string('status', 100); // aktif, non-aktif
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        DB::table('kas')->insert([
            ['nama_kas' => 'Kas Besar', 'jenis' => 'Tunai', 'status' => 'Aktif', 'deskripsi' => 'Kas utama perusahaan'],
            ['nama_kas' => 'Kas Kecil', 'jenis' => 'Tunai', 'status' => 'Aktif', 'deskripsi' => 'Petty cash operasional'],
            ['nama_kas' => 'Rekening BNI 001', 'jenis' => 'Bank', 'status' => 'Aktif', 'deskripsi' => 'Rekening bank perusahaan'],
            ['nama_kas' => 'Kas Proyek A', 'jenis' => 'Kas Proyek', 'status' => 'Aktif', 'deskripsi' => 'Kas lapangan Proyek A'],
        ]);

        Schema::create('rekening_kas', function (Blueprint $table) {
            $table->id('id_rekening_kas');

            $table->foreignId('id_kas')->constrained('kas', 'id_kas')->cascadeOnDelete();
            $table->foreignId('id_rekening_bank')->nullable()->constrained('rekening_bank', 'id_rekening_bank')->cascadeOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        DB::table('rekening_kas')->insert([
            ['id_kas' => 1, 'id_rekening_bank' => 2, 'keterangan' => 'Kas Besar terhubung ke rekening Mandiri'],
            ['id_kas' => 2, 'id_rekening_bank' => 1, 'keterangan' => 'Kas Kecil terhubung ke rekening BCA'],
            ['id_kas' => 3, 'id_rekening_bank' => 3, 'keterangan' => 'Rekening BNI 001 terhubung ke rekening BNI'],
            ['id_kas' => 4, 'id_rekening_bank' => 3, 'keterangan' => 'Kas Proyek A terhubung ke rekening BNI'],
        ]);

        // termin proyek
        Schema::create('termin_proyek', function (Blueprint $table) {
            $table->id('id_termin_proyek');

            $table->foreignId('id_proyek')->constrained('proyek', 'id_proyek')->cascadeOnDelete();
            $table->foreignId('id_tipe_termin')->constrained('tipe_termin', 'id_tipe_termin')->cascadeOnDelete();

            $table->decimal('nominal', 18, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        DB::table('termin_proyek')->insert([
            // Proyek 1 (1 Termin)
            ['id_proyek' => 1, 'id_tipe_termin' => 3, 'nominal' => 1000000, 'keterangan' => 'Pelunasan Proyek Gedung A'],

            // Proyek 2 (2 Termin)
            ['id_proyek' => 2, 'id_tipe_termin' => 1, 'nominal' => 1000000, 'keterangan' => 'DP Renovasi Kantor B'],
            ['id_proyek' => 2, 'id_tipe_termin' => 3, 'nominal' => 1000000, 'keterangan' => 'Pelunasan Renovasi Kantor B'],

            // Proyek 3 (3 Termin - Contoh kosongan sesuai logic controller baru lo)
            ['id_proyek' => 3, 'id_tipe_termin' => 1, 'nominal' => 0, 'keterangan' => 'Termin 1 (Belum Diatur)'],
            ['id_proyek' => 3, 'id_tipe_termin' => 2, 'nominal' => 0, 'keterangan' => 'Termin 2 (Belum Diatur)'],
            ['id_proyek' => 3, 'id_tipe_termin' => 3, 'nominal' => 0, 'keterangan' => 'Termin 3 (Belum Diatur)'],
        ]);

        // kas masuk
        Schema::create('kas_masuk', function (Blueprint $table) {
            $table->id('id_kas_masuk');
            $table->string('no_form', 50)->unique();
            $table->date('tanggal_masuk');

            // Relasi
            $table->foreignId('id_kategori_masuk')->constrained('kategori_kas_masuk', 'id_kategori_masuk');
            $table->foreignId('id_proyek')->nullable()->constrained('proyek', 'id_proyek');
            $table->foreignId('id_metode_bayar')->nullable()->constrained('metode_bayar', 'id_metode_bayar');
            $table->foreignId('id_termin_proyek')->nullable()->constrained('termin_proyek', 'id_termin_proyek');

            $table->decimal('nominal', 18, 2);
            $table->text('keterangan');
            $table->string('upload_bukti')->nullable();
            $table->timestamps();
        });

        DB::table('kas_masuk')->insert([
            ['no_form' => 'KM-20260101-001', 'tanggal_masuk' => '2026-01-01', 'id_kategori_masuk' => 1, 'id_proyek' => 1, 'id_metode_bayar' => 1, 'id_termin_proyek' => 1, 'nominal' => 1000000, 'keterangan' => 'Kas masuk Proyek A', 'upload_bukti' => 'km-001-2026.jpg'],
            ['no_form' => 'KM-20260102-001', 'tanggal_masuk' => '2026-01-02', 'id_kategori_masuk' => 2, 'id_proyek' => 2, 'id_metode_bayar' => 2, 'id_termin_proyek' => 2, 'nominal' => 2000000, 'keterangan' => 'Kas masuk Proyek B', 'upload_bukti' => 'km-002-2026.jpg'],
        ]);

        // kas keluar
        Schema::create('kas_keluar', function (Blueprint $table) {
            $table->id('id_kas_keluar');
            $table->string('no_form', 50)->unique(); // KK-001/2026
            $table->date('tanggal_keluar');

            // Relasi
            $table->foreignId('id_kategori_keluar')->constrained('kategori_kas_keluar', 'id_kategori_keluar');
            $table->foreignId('id_proyek')->nullable()->constrained('proyek', 'id_proyek');
            $table->foreignId('id_vendor')->nullable()->constrained('vendor', 'id_vendor');
            $table->foreignId('id_metode_bayar')->nullable()->constrained('metode_bayar', 'id_metode_bayar');

            $table->decimal('nominal', 18, 2);
            $table->text('keterangan');
            $table->string('upload_bukti')->nullable();
            $table->timestamps();
        });

        DB::table('kas_keluar')->insert([
            ['no_form' => 'KK-20260101-001', 'tanggal_keluar' => '2026-01-01', 'id_kategori_keluar' => 1, 'id_proyek' => 1, 'id_vendor' => 1, 'id_metode_bayar' => 1, 'nominal' => 1000000, 'keterangan' => 'Kas keluar Proyek A', 'upload_bukti' => 'kk-001-2026.jpg'],
            ['no_form' => 'KK-20260102-001', 'tanggal_keluar' => '2026-01-02', 'id_kategori_keluar' => 2, 'id_proyek' => 2, 'id_vendor' => 2, 'id_metode_bayar' => 2, 'nominal' => 2000000, 'keterangan' => 'Kas keluar Proyek B', 'upload_bukti' => 'kk-002-2026.jpg'],
        ]);

        // pembayaran detail
        Schema::create('pembayaran_detail', function (Blueprint $table) {
            $table->id('id_pembayaran_detail');

            $table->foreignId('id_termin_proyek')->constrained('termin_proyek', 'id_termin_proyek')->cascadeOnDelete();
            $table->foreignId('id_kas_masuk')->constrained('kas_masuk', 'id_kas_masuk')->cascadeOnDelete();

            $table->decimal('nominal', 18, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        DB::table('pembayaran_detail')->insert([
            ['id_termin_proyek' => 1, 'id_kas_masuk' => 1, 'nominal' => 1000000, 'keterangan' => 'Pembayaran detail Proyek A'],
            ['id_termin_proyek' => 2, 'id_kas_masuk' => 2, 'nominal' => 2000000, 'keterangan' => 'Pembayaran detail Proyek B'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_detail');
        Schema::dropIfExists('kas_masuk');
        Schema::dropIfExists('termin_proyek');
        Schema::dropIfExists('rekening_kas');
        Schema::dropIfExists('rekening_bank');
        Schema::dropIfExists('kas_keluar');
        Schema::dropIfExists('kas');

    }
};
