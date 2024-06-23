<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penagihan extends Model {
    use HasFactory;
    protected $guarded = ['id'];
    // protected $with = ['joinPenjualanhd', 'joinTimsetup', 'joinUser'];
    protected $with = ['joinTimsetup', 'joinUser'];

    // public function joinPenjualanhd() {
    //     return $this->belongsTo(Penjualanhd::class, 'nota', 'nota')
    //         ->whereColumn('penagihans.timsetupid', 'penjualanhds.timsetupid');
    // }

    public function joinTimsetup() {
        return $this->belongsTo(Timsetup::class, 'timsetupid');
    }

    public function joinUser() {
        return $this->belongsTo(User::class, 'userid', 'id');
    }
}
