
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'pelanggans',
            'layanans',
            'rewards',
            'pemesanans',
            'pengaturans',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, 'laundry_id')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->foreignId('laundry_id')
                        ->nullable()
                        ->constrained('laundries')
                        ->nullOnDelete();
                });
            }
        }

        // Hubungkan pemilik pertama dengan laundry miliknya.
        $owner = DB::table('users')->orderBy('id')->first();

        if (!$owner) {
            return;
        }

        $laundryId = $owner->laundry_id ?? null;

        if (!$laundryId) {
            $laundry = DB::table('laundries')
                ->where('owner_id', $owner->id)
                ->first();

            if (!$laundry) {
                $settings = DB::table('pengaturans')
                    ->orderBy('id')
                    ->first();

                $laundryId = DB::table('laundries')->insertGetId([
                    'owner_id' => $owner->id,
                    'nama_laundry' => $settings->nama_laundry
                        ?? ('Laundry ' . $owner->name),
                    'alamat' => null,
                    'no_hp' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $laundryId = $laundry->id;
            }

            DB::table('users')
                ->where('id', $owner->id)
                ->update(['laundry_id' => $laundryId]);
        }

        // Masukkan data lama ke laundry pemilik pertama.
        foreach ($tables as $table) {
            DB::table($table)
                ->whereNull('laundry_id')
                ->update(['laundry_id' => $laundryId]);
        }
    }

    public function down(): void
    {
        $tables = [
            'pengaturans',
            'pemesanans',
            'rewards',
            'layanans',
            'pelanggans',
        ];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'laundry_id')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropConstrainedForeignId('laundry_id');
                });
            }
        }
    }
};
