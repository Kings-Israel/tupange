<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Infobip\Api\SendSmsApi;
use Infobip\Configuration;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendSms implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   public $phone_number;
   public $content;

   /**
    * Create a new job instance.
    *
    * @return void
    */
   public function __construct($phone_number, $content)
   {
      $this->phone_number = $phone_number;
      $this->content = $content;
   }

   /**
    * Execute the job.
    *
    * @return void
    */
   public function handle()
   {
      $response = Http::post(config('services.sms.base_url'), [
         'apikey' => config('services.sms.api_key'),
         'partnerID' => config('services.sms.partner_id'),
         'shortcode' => config('services.sms.sender_id'),
         'message' => $this->content,
         'mobile' => $this->phone_number
      ]);

      info($response);
   }
}
