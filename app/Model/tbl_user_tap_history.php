<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class tbl_user_tap_history extends Model {

       protected $table = 'tbl_user_tap_history';
       public $timestamps = false;
       protected $primaryKey = 'tap_history_id';

}
