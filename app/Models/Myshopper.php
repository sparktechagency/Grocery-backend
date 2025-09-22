<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Myshopper extends Model
{
    use HasFactory;

    protected $fillable=['user_id','shopper_id'];

    public function shopper()
    {
    return $this->belongsTo(User::class, 'shopper_id');
    }
    public function userlocation()
{
    return $this->hasOne(Userlocation::class, 'user_id');
}
}
