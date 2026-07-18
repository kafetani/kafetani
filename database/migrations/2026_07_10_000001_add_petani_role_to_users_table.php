<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan role 'petani' ke enum kolom users.role.
 *
 * Role ini digunakan oleh Petani Lokal sebagai aktor sistem penuh (lihat
 * SRS Kafetani v1.1 Bab 2.3 & Bab 6): mereka login dengan akun sendiri,
 * bukan sekadar data pasif yang di-CRUD admin di tabel `farmers`.
 *
 * MySQL/MariaDB tidak mendukung ALTER COLUMN ... ADD VALUE untuk enum
 * secara langsung lewat Schema Builder, jadi kita pakai raw SQL untuk itu.
 *
 * PENTING (bug yang diperbaiki): kolom enum di SQLite (dibuat lewat
 * $table->enum() pada migration awal) ITU TETAP di-enforce sebagai CHECK
 * constraint asli oleh Laravel — bukan sekadar TEXT bebas seperti asumsi
 * versi migration sebelumnya. Akibatnya, di SQLite (dipakai oleh test
 * suite dan lingkungan dev lokal) role 'petani' sebenarnya SELALU ditolak
 * oleh database meski migration ini "sukses" jalan, karena cabang SQLite
 * tidak melakukan apa-apa. SQLite tidak bisa ALTER CHECK constraint di
 * tempat, jadi kita ikuti pola rebuild tabel yang didokumentasikan resmi
 * oleh SQLite (buat tabel baru, salin data, hapus tabel lama, ganti nama).
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'kasir', 'user', 'petani') NOT NULL DEFAULT 'user'");

            return;
        }

        if ($driver === 'sqlite') {
            $this->rebuildSqliteUsersTable(['admin', 'kasir', 'user', 'petani']);
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            // Pastikan tidak ada user dengan role 'petani' tersisa sebelum
            // mengecilkan kembali enum, supaya rollback tidak gagal.
            DB::table('users')->where('role', 'petani')->update(['role' => 'user']);
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'kasir', 'user') NOT NULL DEFAULT 'user'");

            return;
        }

        if ($driver === 'sqlite') {
            DB::table('users')->where('role', 'petani')->update(['role' => 'user']);
            $this->rebuildSqliteUsersTable(['admin', 'kasir', 'user']);
        }
    }

    /**
     * Bangun ulang tabel `users` di SQLite dengan daftar CHECK constraint
     * role yang baru, mengikuti pola resmi SQLite untuk mengubah skema
     * yang tidak didukung ALTER TABLE biasa (mis. mengubah CHECK
     * constraint): matikan FK sementara, buat tabel baru, salin data,
     * hapus tabel lama, lalu ganti nama tabel baru.
     *
     * @param  array<int, string>  $roles
     */
    private function rebuildSqliteUsersTable(array $roles): void
    {
        $enumList = collect($roles)->map(fn ($role) => "'{$role}'")->implode(', ');

        DB::statement('PRAGMA foreign_keys=off');

        DB::transaction(function () use ($enumList) {
            DB::statement("
                CREATE TABLE users_new (
                    id integer primary key autoincrement not null,
                    nama varchar not null,
                    email varchar not null,
                    password varchar not null,
                    role varchar check (\"role\" in ({$enumList})) not null default 'user',
                    google_id varchar,
                    avatar varchar,
                    remember_token varchar
                )
            ");

            DB::statement('
                INSERT INTO users_new (id, nama, email, password, role, google_id, avatar, remember_token)
                SELECT id, nama, email, password, role, google_id, avatar, remember_token FROM users
            ');

            DB::statement('DROP TABLE users');
            DB::statement('ALTER TABLE users_new RENAME TO users');
            DB::statement('CREATE UNIQUE INDEX users_email_unique ON users (email)');
            DB::statement('CREATE UNIQUE INDEX users_google_id_unique ON users (google_id)');
        });

        DB::statement('PRAGMA foreign_keys=on');
    }
};
