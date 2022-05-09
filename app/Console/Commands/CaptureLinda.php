<?php

namespace App\Console\Commands;

use App\Models\ClickCount;
use Illuminate\Console\Command;

class CaptureLinda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capture:linda';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read lifetime click count from Linda, the dash printer and store it in this database...somewhere';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url='https://cloud.dashcg.com/getlinda.php';
        $linda=file_get_contents($url);
        $linda_json = json_decode($linda);
        
        // if($linda_json->toner->black == '10' && $linda_json->toner->yellow == '10' && $linda_json->toner->cyan == '10' && $linda_json->toner->magenta == '10'){
        //     echo "Linda is asleep - no stats to pull\n\r";
        //     exit;
        // }
        
        $click_count = New ClickCount;
        $click_count->click_count = $linda_json->counter;
        $click_count->cyan = $linda_json->toner->cyan;
        $click_count->magenta = $linda_json->toner->magenta;
        $click_count->yellow = $linda_json->toner->yellow;
        $click_count->black = $linda_json->toner->black;
        $click_count->save();

    }
}
