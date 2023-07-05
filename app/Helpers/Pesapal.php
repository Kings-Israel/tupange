<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use App\Models\PesapalNotificationUrl;

class Pesapal
{
   public function env()
   {
      return config('services.pesapal.env');
   }

   public static function baseUrl()
   {
      if (self::env() === 'sandbox') {
         return config('services.pesapal.sandbox_base_url');
      }

      return config('services.pesapal.live_base_url');
   }

   public static function accessToken()
   {
      if (self::env() === 'sandbox') {
         $consumer_key = config('services.pesapal.sandbox_consumer_key');
         $consumer_secret = config('services.pesapal.sandbox_consumer_secret');
      } else {
         $consumer_key = config('services.pesapal.live_consumer_key');
         $consumer_secret = config('services.pesapal.live_consumer_secret');
      }

      $url = self::baseUrl().'/api/Auth/RequestToken';

      $response = Http::withHeaders([
                     'Content-Type' => 'application/json',
                     'Accept' => 'application/json',
                  ])
                  ->post($url, [
                     'consumer_key' => $consumer_key,
                     'consumer_secret' => $consumer_secret,
                  ]);

      return json_decode($response)->token;
   }

   public function registerOrderPaymentIpnUrls()
   {
      $url = self::baseUrl().'/api/URLSetup/RegisterIPN';

      $token = self::accessToken();

      // Register IPN for Order Payment
      $order_response = Http::timeout(5)
      ->withHeaders([
         'Content-Type' => 'application/json',
         'Accept' => 'application/json',
      ])
      ->withToken($token)
      ->post($url, [
         'url' => route('pesapal.order.payment.notification'),
         'ipn_notification_type' => 'POST'
      ]);

      PesapalNotificationUrl::create([
         'url' => json_decode($order_response)->url,
         'ipn_id' => json_decode($order_response)->ipn_id,
         'payment_purpose' => 'Order Payment'
      ]);
   }

   public function registerProgramPaymentIpnUrls()
   {
      $url = self::baseUrl().'/api/URLSetup/RegisterIPN';

      $token = self::accessToken();

      // Register IPN for Program payment
      $program_response = Http::timeout(5)
         ->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
         ])
         ->withToken($token)
         ->post($url, [
            'url' => route('pesapal.program.payment.notification'),
            'ipn_notification_type' => 'POST'
         ]);

      PesapalNotificationUrl::create([
         'url' => json_decode($program_response)->url,
         'ipn_id' => json_decode($program_response)->ipn_id,
         'payment_purpose' => 'Program Payment'
      ]);
   }

   public function registerTicketPaymentIpnUrls()
   {
      $url = self::baseUrl().'/api/URLSetup/RegisterIPN';

      $token = self::accessToken();

      // Register IPN for Ticket Payment
      $ticket_response = Http::timeout(5)
         ->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
         ])
         ->withToken($token)
         ->post($url, [
            'url' => route('pesapal.ticket.payment.notification'),
            'ipn_notification_type' => 'POST'
         ]);

      PesapalNotificationUrl::create([
         'url' => json_decode($ticket_response)->url,
         'ipn_id' => json_decode($ticket_response)->ipn_id,
         'payment_purpose' => 'Ticket Payment'
      ]);
   }

   public function getRegisteredIpns()
   {
      $url = self::baseUrl().'/api/URLSetup/GetIpnList';
      $response = Http::withToken(self::accessToken())->get($url);

      return $response;
   }
}
