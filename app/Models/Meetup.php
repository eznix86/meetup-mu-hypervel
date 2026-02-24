<?php

declare(strict_types=1);

namespace App\Models;

use Hypervel\Database\Eloquent\Factories\HasFactory;

class Meetup extends Model
{
    use HasFactory;

    protected array $fillable = [
        'type',
        'community',
        'title',
        'abstract',
        'location',
        'registration',
        'date',
        'capacity',
    ];

    protected array $casts = [
        'id' => 'integer',
        'date' => 'datetime',
        'capacity' => 'integer',
    ];
}
