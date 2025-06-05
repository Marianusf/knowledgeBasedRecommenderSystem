<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Car;

class ImportCars extends Command
{
    protected $signature = 'import:cars';
    protected $description = 'Import dataset mobil dari CSV ke database';

    public function handle(): int
    {
        $path = storage_path('app/mobil_dataset_indo_1000_updated.csv');

        if (!file_exists($path)) {
            $this->error('File CSV tidak ditemukan di storage/app/');
            return Command::FAILURE;
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            Car::create(array_combine($header, $row));
        }

        fclose($file);

        $this->info('Import selesai!');
        return Command::SUCCESS;
    }
}
