<?php

namespace App\Models\Docs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'abbreviation'
    ];
    

    public function my_docs()
    {
        return $this->hasMany(MyDoc::class, 'type_id', 'id');
    }
}