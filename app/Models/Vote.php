<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperVote
 */
class Vote extends Model
{
    use HasFactory;

    protected $guarded = [];
}
