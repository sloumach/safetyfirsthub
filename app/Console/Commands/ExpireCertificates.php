<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Certificate;
use Carbon\Carbon;

class ExpireCertificates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificates:expire';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire les certificats après 3 mois';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Certificate::where('available', true)
            ->where('created_at', '<', Carbon::now()->subMonths(3))
            ->update(['available' => false]);

        $this->info('Les certificats expirés ont été désactivés.');
    }
}
