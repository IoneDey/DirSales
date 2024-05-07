<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualandt extends Model {
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['joinTimSetupPaket'];

    public function joinTimSetupPaket() {
        return $this->belongsTo(Timsetuppaket::class, 'timsetuppaketid', 'id');
    }

    // public function joinPenjualanhd() {
    //     return $this->belongsTo(Penjualanhd::class, 'penjualanhdid', 'id');
    // }
}
