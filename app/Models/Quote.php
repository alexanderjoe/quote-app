<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote',
        'author',
        'image',
        'gpt',
    ];

    public function getUrl()
    {
        return Storage::url($this->image);
    }
}
