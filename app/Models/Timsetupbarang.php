<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timsetupbarang extends Model {
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['joinBarang'];

    public function joinBarang() {
        return $this->belongsTo(Barang::class, 'barangid', 'id');
    }
}
