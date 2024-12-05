<?php

namespace App\Enums;

enum CartType: string {
  case SHOPPING = 'shopping';
  case WISHLIST = 'wishlist';
  case SAVED_FOR_LATER = 'saved_for_later';
  case INVESTMENT = 'investment';
}
