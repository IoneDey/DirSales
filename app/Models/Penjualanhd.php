<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualanhd extends Model {
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['joinUser', 'joinTimSetup', 'joinPenjualandt'];

    public function joinUser() {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function joinTimSetup() {
        return $this->belongsTo(Timsetup::class, 'timsetupid', 'id');
    }

    public function joinPenjualandt() {
        return $this->hasMany(Penjualandt::class, 'penjualanhdid', 'id');
    }

    public function getHargajualTotalAttribute() {
        return $this->joinPenjualandt->sum(function ($penjualandt) {
            return $penjualandt->jumlah * $penjualandt->joinTimSetupPaket->hargajual;
        });
    }
}
