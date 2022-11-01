<?php

namespace App\Console\Commands;

use App\Mail\ScheduleMail;
use App\Models\Ad;
use App\Models\Advertiser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class notify extends Command
{

    protected $signature = 'schedule:email';


    protected $description = 'Daily email at 08:00 PM that will be sent to advertisers who have ads the next day as a remainder';


    public function handle()
    {
        $details = "we remind you that you have ads at the next day";
        $ads = Ad::all();

        foreach ($ads as $ad)
        {

            $today = \Carbon\Carbon::now();
            $start_date_ob = new \Carbon\Carbon($ad->start_date);
            $difference = $start_date_ob->diffInDays($today);
            $adv_id = $ad->advertiser_id;

            if ($difference == 0)
            {
                $advertiserInfo = Advertiser::where('id',$adv_id)->first();
                Mail::to($advertiserInfo->email)->send(new ScheduleMail($details));
            }

        }

    }
}
