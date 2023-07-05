<?php

namespace App\Jobs;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckServiceStatus implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   /**
    * Create a new job instance.
    *
    * @return void
    */
   public function __construct()
   {
      //
   }

   /**
    * Execute the job.
    *
    * @return void
    */
   public function handle()
   {
      // Get all service that have a pause date set
      $services = Service::where('pause_until', '!=',  null)->get();
      
      foreach($services as $service) {
         // Pause if service was set to be paused today
         if ($service->pause_from === now()->format('Y-m-d')) {
            $service->update([
               'service_status_id' => 2
            ]);
         }
         // Resume service if the date is set to resume today
         if ($service->pause_until === now()->format('Y-m-d')) {
            $service->update([
               'service_status_id' => 1,
               'pause_from' => null,
               'pause_until' => null
            ]);
         }
      }
   }
}
