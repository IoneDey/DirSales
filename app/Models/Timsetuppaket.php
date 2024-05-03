<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timsetuppaket extends Model {
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['joinTimSetupBarang'];

    public function joinTimSetupBarang() {
        return $this->hasMany(Timsetupbarang::class, 'timsetuppaketid');
    }
}
