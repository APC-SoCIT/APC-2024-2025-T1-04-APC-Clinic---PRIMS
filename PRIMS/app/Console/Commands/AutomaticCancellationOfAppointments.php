<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class AutomaticCancellationOfAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:automatic-cancellation-of-appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically cancel approved appointments that are not updated by the patient/staff within 24 hours of the appointment date.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = Appointment::where('status', 'approved')
            ->where('created_at', '<', Carbon::now()->subDay())
            ->update([
                'status' => 'cancelled',
                'status_updated_at' => Carbon::now(),
                'status_updated_by' => null,
            ]);

        $this->info("{$count} appointments were automatically canceled.");
    }
}
