<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class tbl_user_custom_social_link extends Model {

       protected $table = 'tbl_user_custom_social_link';
       public $timestamps = false;
       protected $primaryKey = 'social_id';

}
