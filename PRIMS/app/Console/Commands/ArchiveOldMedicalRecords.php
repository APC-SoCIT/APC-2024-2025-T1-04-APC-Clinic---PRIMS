<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ArchiveOldMedicalRecords extends Command
{
    protected $signature = 'medical:archive-old';
    protected $description = 'Archive medical records older than 5 years';

    public function handle()
    {
        $fiveYearsAgo = Carbon::now()->subYears(5);

        MedicalRecord::whereNull('archived_at')
            ->where('created_at', '<=', $fiveYearsAgo)
            ->update(['archived_at' => now()]);

        $this->info('Old medical records have been archived.');
    }
}
