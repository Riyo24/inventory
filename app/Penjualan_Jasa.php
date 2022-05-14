<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penjualan_Jasa extends Model
{
    protected $table = 'penjualan_jasa';

    protected $fillable = ['jasa_id','customer_id','tanggal', 'harga', 'keterangan'];

    protected $hidden = ['created_at','updated_at'];

    public function jasa()
    {
        return $this->belongsTo(Jasa::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
