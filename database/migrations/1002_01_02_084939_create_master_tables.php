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
        // Tabel Pemberi Proyek
        Schema::create('pemberi_proyek', function (Blueprint $table) {
            $table->id('id_pemberi');
            $table->string('jenis', 50); // Perorangan, Swasta, Pemerintah
            $table->string('nama', 150);
            $table->string('alamat', 255);
            $table->string('penanggung_jawab', 255);
            $table->string('no_telp', 20);
            $table->string('email', 100);
            $table->timestamps();
        });

        DB::table('pemberi_proyek')->insert([
            ['jenis'=> 'Pemerintah', 'nama'=> 'Dinas PUPR Kota Serang', 'alamat'=> 'Jl. Mayor Syafei No. 12, Kota Serang', 'penanggung_jawab'=> 'Syarifudin, ST', 'no_telp'=> '0812-3456-7890', 'email'=> 'puprserang@serangkota.go.id'],
            ['jenis'=> 'Swasta', 'nama'=> 'PT Maju Sejahtera Konstruksi', 'alamat'=> 'Jl. Industri No. 88, Cilegon', 'penanggung_jawab'=> 'Ahmad Rudi', 'no_telp'=> '0813-9876-5520', 'email'=> 'info@maju-sejahtera.co.id'],
            ['jenis'=> 'Perorangan', 'nama'=> 'Bapak Hadi Sutrisno', 'alamat'=> 'Jl. Trip Jamaksari No. 5, Serang', 'penanggung_jawab'=> 'Hadi Sutrisno', 'no_telp'=> '0812-2244-3344', 'email'=> '-'],
        ]);
        
        // Tabel Proyek 
        Schema::create('proyek', function (Blueprint $table) {
            $table->id('id_proyek');
            $table->string('nama', 150);
            $table->foreignId('id_pemberi')->constrained('pemberi_proyek', 'id_pemberi')->cascadeOnDelete();
            $table->decimal('nilai_kontrak', 18, 2);
            $table->integer('jumlah_termin');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('status'); // aktif, non-aktif
            $table->string('deskripsi', 255)->nullable();
            $table->timestamps();
        });

        DB::table('proyek')->insert([
            ['nama'=> 'Proyek 1', 'id_pemberi'=> 1, 'nilai_kontrak'=> 1000000, 'jumlah_termin'=> 1, 'tanggal_mulai'=> '2026-01-01', 'tanggal_selesai'=> '2026-01-01', 'status'=> 'aktif', 'deskripsi'=> 'Proyek 1'],
            ['nama'=> 'Proyek 2', 'id_pemberi'=> 2, 'nilai_kontrak'=> 2000000, 'jumlah_termin'=> 2, 'tanggal_mulai'=> '2026-01-01', 'tanggal_selesai'=> '2026-01-01', 'status'=> 'aktif', 'deskripsi'=> 'Proyek 2'],
            ['nama'=> 'Proyek 3', 'id_pemberi'=> 3, 'nilai_kontrak'=> 3000000, 'jumlah_termin'=> 3, 'tanggal_mulai'=> '2026-01-01', 'tanggal_selesai'=> '2026-01-01', 'status'=> 'nonaktif', 'deskripsi'=> 'Proyek 3'],
        ]);

        // Tabel Vendor
        Schema::create('vendor', function (Blueprint $table) {
            $table->id('id_vendor');
            $table->string('nama', 150);
            $table->string('alamat', 255);
            $table->string('penanggung_jawab', 255);
            $table->string('no_telp', 20);
            $table->string('email', 100);
            $table->timestamps();
        });

        DB::table('vendor')->insert([
            ['nama'=> 'CV Makmur Jaya', 'alamat'=> 'Jl. Raya Serang No. 12, Banten', 'penanggung_jawab'=> 'Budi Santoso', 'no_telp'=> '081234567890', 'email'=> 'cs@makmurjaya.com'],
            ['nama'=> 'UD Sumber Rezeki', 'alamat'=> 'Jl. A. Yani No. 33, Serang', 'penanggung_jawab'=> 'Dedi', 'no_telp'=> '082233445566', 'email'=> '-'],
            ['nama'=> 'Toko Bangunan “Pak Udin”', 'alamat'=> 'Pasar Lama Serang, Banten', 'penanggung_jawab'=> 'Udin', 'no_telp'=> '081278889900', 'email'=> '-'],
            ['nama'=> 'PT Beton Prima', 'alamat'=> 'Kawasan Industri Cikande', 'penanggung_jawab'=> 'Rita', 'no_telp'=> '081299223344', 'email'=> 'sales@betonprima.co.id'],
        ]);

        Schema::create('metode_bayar', function (Blueprint $table) {
            $table->id('id_metode_bayar');
            $table->string('nama_metode_bayar', 50);
            $table->string('deskripsi', 255);
            $table->timestamps();
        });

        DB::table('metode_bayar')->insert([
            ['nama_metode_bayar'=> 'Cash', 'deskripsi'=> 'Cash'],
            ['nama_metode_bayar'=> 'Bank', 'deskripsi'=> 'Bank'],
        ]);

        // kas masuk
        Schema::create('kategori_kas_masuk', function (Blueprint $table) {
            $table->id('id_kategori_masuk');
            $table->string('nama_kategori', 100);
            $table->text('deskripsi')->nullable();

            // MODIFIKASI: Mapping ke COA agar Jurnal Otomatis [PENTING]
            // Saat user pilih kategori "Beli Material", sistem tahu ini masuk akun Beban Material
            // $table->foreignId('id_coa_debit')->nullable()->constrained('coa', 'id_coa');

            $table->timestamps();
        });

        DB::table('kategori_kas_masuk')->insert([
            ['nama_kategori'=> 'Pembayaran Proyek - Termin', 'deskripsi'=> 'Pembayaran berdasarkan progres pekerjaan'],
            ['nama_kategori'=> 'Pembayaran Proyek - Uang Muka', 'deskripsi'=> 'Pembayaran berdasarkan progres pekerjaan'],
            ['nama_kategori'=> 'Pembayaran Proyek - Full Payment', 'deskripsi'=> 'Pembayaran berdasarkan progres pekerjaan'],
            ['nama_kategori'=> 'Penambahan Modal', 'deskripsi'=> 'Penambahan Modal'],
            ['nama_kategori'=> 'Piutang Bank', 'deskripsi'=> 'Piutang Bank'],
        ]);

        // kas keluar
        Schema::create('kategori_kas_keluar', function (Blueprint $table) {
            $table->id('id_kategori_keluar');
            $table->string('nama_kategori', 100);
            $table->text('deskripsi')->nullable();

            // MODIFIKASI: Mapping ke COA agar Jurnal Otomatis [PENTING]
            // Saat user pilih kategori "Beli Material", sistem tahu ini masuk akun Beban Material
            // $table->foreignId('id_coa_debit')->nullable()->constrained('coa', 'id_coa');

            $table->timestamps();
        });

        DB::table('kategori_kas_keluar')->insert([
            ['nama_kategori'=> 'Pembelian Material', 'deskripsi'=> 'Pembelian barang/bahan ke vendor'],
            ['nama_kategori'=> 'Tenaga Kerja Proyek', 'deskripsi'=> 'Pembayaran tukang, mandor, pekerja lapangan'],
            ['nama_kategori'=> 'Tenaga Kerja Kantor', 'deskripsi'=> 'Gaji administratif kantor / staf internal'],
            ['nama_kategori'=> 'Operasional Proyek', 'deskripsi'=> 'Transportasi, ATK, listrik lapangan, dll.'],
            ['nama_kategori'=> 'Lain-Lain', 'deskripsi'=> 'Pengeluaran lain yang tidak terklasifikasi'],
        ]);

        Schema::create('tipe_termin', function (Blueprint $table) {
            $table->id('id_tipe_termin');
            $table->string('nama_termin', 100); // uang muka, termin, full payment
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        DB::table('tipe_termin')->insert([
            ['nama_termin'=> 'Uang Muka', 'deskripsi'=> 'Pembayaran awal sebelum pekerjaan'],
            ['nama_termin'=> 'Termin', 'deskripsi'=> 'Pembayaran progres berdasarkan persentase'],
            ['nama_termin'=> 'Full Payment', 'deskripsi'=> 'Pelunasan nilai kontrak 100%'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyek');
        Schema::dropIfExists('pemberi_proyek');
        Schema::dropIfExists('vendor');
        Schema::dropIfExists('metode_bayar');
        Schema::dropIfExists('kategori_kas_masuk');
        Schema::dropIfExists('kategori_kas_keluar');
        Schema::dropIfExists('tipe_termin');
    }
};
