<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Phone;

class ImportPhone extends Command
{
    protected $signature = 'import:phone';
    protected $description = 'Import dataset phone dari CSV ke database';

    public function handle()
    {
        $path = storage_path('app/public/PHONE_DATA.csv');

        if (!file_exists($path)) {
            $this->error("File not found at: $path");
            return 1;
        }

        $csv = fopen($path, 'r');
        $headers = array_map('trim', fgetcsv($csv, 1000, ';'));

        $count = 0;

        while (($row = fgetcsv($csv, 1000, ';')) !== false) {
            $row = array_map('trim', $row);

            if (count($row) !== count($headers)) {
                continue; // skip baris tidak valid
            }

            $data = array_combine($headers, $row);

            Phone::create([
                'company_name'     => $data['Company Name'] ?? null,
                'model_name'       => $data['Model Name'] ?? null,
                'mobile_weight'    => (int) filter_var($data['Mobile Weight'] ?? 0, FILTER_SANITIZE_NUMBER_INT),
                'ram'              => (int) ($data['RAM'] ?? 0),
                'front_camera'     => $data['Front Camera'] ?? null,
                'back_camera'      => $data['Back Camera'] ?? null,
                'processor'        => $data['Processor'] ?? null,
                'battery_capacity' => (int) ($data['Battery Capacity'] ?? 0),
                'screen_size'      => $data['Screen Size'] ?? null,
                'launched_year'    => (int) ($data['Launched Year'] ?? 0),
                'price'            => (int) ($data['Price'] ?? 0),
            ]);

            $count++;
        }

        fclose($csv);

        $this->info("Berhasil mengimpor $count data handphone.");
        return 0;
    }
}
