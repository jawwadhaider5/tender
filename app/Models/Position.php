<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = "positions";

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    } 
    public function clients()
    {
        return $this->hasMany(Client::class);
    } 
}
