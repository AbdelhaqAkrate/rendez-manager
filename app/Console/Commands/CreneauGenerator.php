<?php

namespace App\Console\Commands;

use App\Models\Creaneau\Creaneau;
use App\Services\Creaneau\CreaneauService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class CreneauGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:creaneau-generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    private $creaneauService;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->creaneauService)
        {
            $this->creaneauService = $this->creaneauService ?? app(CreaneauService::class);
        }
        $today = Carbon::now()->toDateString();
        $creaneau = [
            [Creaneau::START_TIME_COLUMN => "8:00", Creaneau::END_TIME_COLUMN => "8:30", Creaneau::DAY_COLUMN => $today],
            [Creaneau::START_TIME_COLUMN => "9:00", Creaneau::END_TIME_COLUMN => "9:30", Creaneau::DAY_COLUMN => $today],
            [Creaneau::START_TIME_COLUMN => "9:30", Creaneau::END_TIME_COLUMN => "10:00", Creaneau::DAY_COLUMN => $today],
            [Creaneau::START_TIME_COLUMN => "10:00", Creaneau::END_TIME_COLUMN => "10:30", Creaneau::DAY_COLUMN => $today],
            [Creaneau::START_TIME_COLUMN => "10:30", Creaneau::END_TIME_COLUMN => "11:00", Creaneau::DAY_COLUMN => $today],
            [Creaneau::START_TIME_COLUMN => "11:30", Creaneau::END_TIME_COLUMN => "12:00", Creaneau::DAY_COLUMN => $today],
            [Creaneau::START_TIME_COLUMN => "14:00", Creaneau::END_TIME_COLUMN => "14:30", Creaneau::DAY_COLUMN => $today],
            [Creaneau::START_TIME_COLUMN => "14:30", Creaneau::END_TIME_COLUMN => "15:00", Creaneau::DAY_COLUMN => $today],
            [Creaneau::START_TIME_COLUMN => "15:00", Creaneau::END_TIME_COLUMN => "15:30", Creaneau::DAY_COLUMN => $today],
            [Creaneau::START_TIME_COLUMN => "15:30", Creaneau::END_TIME_COLUMN => "16:00", Creaneau::DAY_COLUMN => $today],
            [Creaneau::START_TIME_COLUMN => "16:00", Creaneau::END_TIME_COLUMN => "16:30", Creaneau::DAY_COLUMN => $today],
            [Creaneau::START_TIME_COLUMN => "16:30", Creaneau::END_TIME_COLUMN => "17:00", Creaneau::DAY_COLUMN => $today],
            [Creaneau::START_TIME_COLUMN => "17:00", Creaneau::END_TIME_COLUMN => "17:30", Creaneau::DAY_COLUMN => $today],
            [Creaneau::START_TIME_COLUMN => "17:30", Creaneau::END_TIME_COLUMN => "18:00", Creaneau::DAY_COLUMN => $today],
        ];
        $alreadyInserted = $this->alreadyInserted($today);
        if(!$alreadyInserted)
        {
            foreach($creaneau as $item)
            {
                $this->creaneauService->create($item);
            }
        }
    }

    private function alreadyInserted($today)
    {
        return Creaneau::where('day', $today)->count() === 0 ? false : true ;
    }
}
