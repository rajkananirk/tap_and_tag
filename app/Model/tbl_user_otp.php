<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class tbl_user_otp extends Model {

       protected $table = 'tbl_user_otp';
       public $timestamps = false;
       protected $primaryKey = 'otp_id';

}
