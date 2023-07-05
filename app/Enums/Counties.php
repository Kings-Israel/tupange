<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

final class Counties extends Enum
{
   public static function counties()
   {
      return [
         array('name' => 'BOMET'),
			array('name' => 'BUNGOMA'),
			array('name' => 'BUSIA'),
			array('name' => 'ELGEYO/MARAKWET'),
			array('name' => 'EMBU'),
			array('name' => 'GARISSA'),
			array('name' => 'HOMA BAY'),
			array('name' => 'ISIOLO'),
			array('name' => 'KAJIADO'),
			array('name' => 'KAKAMEGA'),
			array('name' => 'KERICHO'),
			array('name' => 'KIAMBU'),
			array('name' => 'KILIFI'),
			array('name' => 'KIRINYAGA'),
			array('name' => 'KISII'),
			array('name' => 'KISUMU'),
			array('name' => 'KITUI'),
			array('name' => 'KWALE'),
			array('name' => 'LAIKIPIA'),
			array('name' => 'LAMU'),
			array('name' => 'MACHAKOS'),
			array('name' => 'MAKUENI'),
			array('name' => 'MANDERA'),
			array('name' => 'MARSABIT'),
			array('name' => 'MERU'),
			array('name' => 'MIGORI'),
			array('name' => 'MOMBASA'),
			array('name' => 'MURANGA'),
			array('name' => 'NAIROBI'),
			array('name' => 'NAKURU'),
			array('name' => 'NANDI'),
			array('name' => 'NAROK'),
			array('name' => 'NYAMIRA'),
			array('name' => 'NYANDARUA'),
			array('name' => 'NYERI'),
			array('name' => 'SAMBURU'),
			array('name' => 'SIAYA'),
			array('name' => 'TAITA TAVETA'),
			array('name' => 'TANA RIVER'),
			array('name' => 'THARAKA - NITHI'),
			array('name' => 'TRANS NZOIA'),
			array('name' => 'TURKANA'),
			array('name' => 'UASIN GISHU'),
			array('name' => 'VIHIGA'),
			array('name' => 'WAJIR'),
			array('name' => 'WEST POKOT'),
			array('name' => 'BARINGO')
      ];
   }
}
