<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self Birthday()
 * @method static self Wedding()
 * @method static self Graduation()
 * @method static self Baby Shower()
 * @method static self Bridal Party()
 * @method static self Bachelor Party()
 * @method static self Bachellorette Party()
 * @method static self Funeral()
 * @method static self Family Gathering()
 * @method static self Retirement Party()
 * @method static self Other()
 */

final class EventTypes extends Enum
{
   public static function events()
   {
      return [
         array('name' => 'Birthday'),
         array('name' => 'Wedding'),
         array('name' => 'Graduation'),
         array('name' => 'Baby Shower'),
         array('name' => 'Bridal Party'),
         array('name' => 'Bachelor Party'),
         array('name' => 'Bachellorette Party'),
         array('name' => 'Funeral'),
         array('name' => 'Family Gathering'),
         array('name' => 'Retirement Party'),
         array('name' => 'Other'),
      ];
   }
   public static function labels()
   {
      return [
         'Birthday' => 'Birthday',
         'Wedding' => 'Wedding',
         'Graduation' => 'Graduation',
         'Baby Shower' => 'Baby Shower',
         'Bridal Party' => 'Bridal Party',
         'Bachelor Party' => 'Bachelor Party',
         'Bachellorette Party' => 'Bachellorette Party',
         'Funeral' => 'Funeral',
         'Family Gathering' => 'Family Gathering',
         'Retirement Party' => 'Retirement Party',
         'Retirement Party' => 'Retirement Party',
         'Other' => 'Other'
      ];
   }
}
