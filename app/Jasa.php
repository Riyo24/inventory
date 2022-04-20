<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jasa extends Model
{
    protected $table = "jasa";
    protected $fillable = ['nama','keterangan','harga'];
    protected $hidden = ['created_at','updated_at'];
}
