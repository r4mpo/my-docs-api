<?php

namespace App\Models\Docs;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MyDoc extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type_id',
        'user_id',
        'file'
    ];

    public function type()
    {
        return $this->hasOne(Type::class, 'id', 'type_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    const PUBLIC_PATH_FILES = "api/docs/";
}
