<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    protected $table = "persons";

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class,'position_id');
    }
}
