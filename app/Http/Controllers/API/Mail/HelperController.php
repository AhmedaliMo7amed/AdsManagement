<?php

namespace App\Http\Controllers\API\Mail;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Advertiser;
use App\Traits\GeneralTrait;
use App\Mail\ScheduleMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class HelperController extends Controller
{

    use GeneralTrait;
    public function check()
    {

        // All Mails are sended to ** MAIL TRAP **
        // I will include Screenshots with the testing email
        // This is an testing controller but the process done by Command & Kernal Daily at 20:00PM

        $details = "we remind you that you have ads at the next day";
        $ads = Ad::all();
        if (is_null($ads)) {
            return $this->returnError('No Ads Founded In Database');
        }
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
        return $this->returnSuccessMessage('Mail Porcess Has Been Done Successfully');


    }
}
