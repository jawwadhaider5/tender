<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    protected $table = "clients";

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

      
    public function comments()
    {
        return $this->hasMany(ClientComment::class, 'client_id')->orderBy('created_at', 'desc');
    }
    public function responds()
    {
        return $this->hasMany(ClientRespond::class, 'client_id')->orderBy('created_at', 'desc');
    }
    public function files()
    {
        return $this->hasMany(ClientFile::class, 'client_id')->orderBy('created_at', 'desc');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    public function persons()
    {
        return $this->hasMany(Person::class);
    }
    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }

    public function future_clients()
    {
        return $this->hasMany(FutureClient::class);
    }
 
}
