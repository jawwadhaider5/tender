<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = "tender_files";
 
    public function tender()
    {
        return $this->belongsTo(Tender::class,'tender_id');
    }

    public function uploaded_by()
    {
        return $this->belongsTo(User::class,'uploaded_by');
    }
}
