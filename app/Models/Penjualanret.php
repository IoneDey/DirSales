<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualanret extends Model {
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['joinUser', 'joinTimsetuppaket', 'joinBarang'];

    public function joinUser() {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function joinTimsetuppaket() {
        return $this->belongsTo(Timsetuppaket::class, 'timsetuppaketid', 'id');
    }

    public function joinBarang() {
        return $this->belongsTo(Barang::class, 'barangid', 'id');
    }
}
