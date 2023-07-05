<?php

return [

   /*
   |--------------------------------------------------------------------------
   | Third Party Services
   |--------------------------------------------------------------------------
   |
   | This file is for storing the credentials for third party services such
   | as Mailgun, Postmark, AWS and more. This file provides the de facto
   | location for this type of information, allowing packages to have
   | a conventional file to locate the various service credentials.
   |
   */

   'app_url' => [
      'url' => env('APP_URL'),
      'admin_url' => env('ADMIN_URL')
   ],

   'mailgun' => [
      'domain' => env('MAILGUN_DOMAIN'),
      'secret' => env('MAILGUN_SECRET'),
      'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
   ],

   'postmark' => [
      'token' => env('POSTMARK_TOKEN'),
   ],

   'ses' => [
      'key' => env('AWS_ACCESS_KEY_ID'),
      'secret' => env('AWS_SECRET_ACCESS_KEY'),
      'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
   ],

   'mpesa' => [
      'consumer_key' => env('MPESA_CONSUMER_KEY'),
      'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
      'env' => env('MPESA_ENV'),
      'business_shortcode' => env('MPESA_BUSINESS_SHORTCODE'),
      'initiator_name' => env('MPESA_INITIATOR_NAME'),
      'initiator_password' => env('MPESA_INITIATOR_PASSWORD'),
      'lipa_passkey' => env('MPESA_LIPA_NA_MPESA_PASSKEY'),
      'stk_push_url_sandbox' => env('MPESA_STK_PUSH_URL_SANDBOX'),
      'stk_push_url_live' => env('MPESA_STK_PUSH_URL_LIVE'),
      'access_token_url_sandbox' => env('MPESA_ACCESS_TOKEN_URL_SANDBOX'),
      'access_token_url_live' => env('MPESA_ACCESS_TOKEN_URL_LIVE'),
      'b2c_url_sandbox' => env('MPESA_B2C_URL_SANDBOX'),
      'b2c_url_live' => env('MPESA_B2C_URL_LIVE'),
      'query_url_sandbox' => env('MPESA_QUERY_URL_SANDBOX'),
      'query_url_live' => env('MPESA_QUERY_URL_LIVE'),
   ],

   'facebook' => [
      'client_id' => env('FACEBOOK_CLIENT_ID'),
      'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
      'redirect' => env('APP_URL').'/auth/facebook/callback',
   ],

   'google' => [
      'client_id' => env('GOOGLE_CLIENT_ID'),
      'client_secret' => env('GOOGLE_CLIENT_SECRET'),
      'redirect' => env('APP_URL').'/auth/google/callback',
   ],

   'maps' => [
      'key' => env('GOOGLE_MAPS_KEY'),
      'partial_key' => env('MAPS_KEY')
   ],

   'sms' => [
     'base_url' => env('TEXT_SMS_BASE_URL'),
     'partner_id' => env('TEXT_SMS_PARTNER_ID'),
     'api_key' => env('TEXT_SMS_API_KEY'),
     'sender_id' => env('TEXT_SMS_SENDER_ID')
   ],

   'pesapal' => [
      'env' => env('PESAPAL_ENV'),
      'sandbox_consumer_key' => env('PESAPAL_SANDBOX_CONSUMER_KEY'),
      'live_consumer_key' => env('PESAPAL_LIVE_CONSUMER_KEY'),
      'sandbox_consumer_secret' => env('PESAPAL_SANDBOX_CONSUMER_SECRET'),
      'live_consumer_secret' => env('PESAPAL_LIVE_CONSUMER_SECRET'),
      'sandbox_base_url' => env('PESAPAL_SANDBOX_BASE_URL'),
      'live_base_url' => env('PESAPAL_LIVE_BASE_URL'),
   ],

   'recaptcha' => [
      'site_key' => env('RECAPTCHA_SITE_KEY'),
      'secret_key' => env('RECAPTCHA_SECRET_KEY'),
   ],

];
