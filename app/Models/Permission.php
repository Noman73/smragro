<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $fillable=['name','part_id'];
    public function part()
    {
        return $this->belongsTo(Part::class,'part_id','id');
    }
}
