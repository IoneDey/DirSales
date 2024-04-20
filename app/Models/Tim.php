<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tim extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['joinUser', 'joinPt'];

    public function joinUser()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function joinPt()
    {
        return $this->belongsTo(Pt::class, 'ptid', 'id');
    }
}
