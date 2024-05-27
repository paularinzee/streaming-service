<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';

    protected $fillable = [
        // "show_id",
        // "user_name",
        // "image",
        // "comment"
        "name",
        


    ];

    public $timestamps = true;
}
