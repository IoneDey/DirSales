<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timsetupbarang extends Model {
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['joinUser', 'joinBarang'];

    public function joinUser() {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function joinBarang() {
        return $this->belongsTo(Barang::class, 'barangid', 'id');
    }
}
