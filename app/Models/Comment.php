<?php

namespace App\Models;

use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperComment
 */
class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $perPage = 20;

    protected $fillable = [
        'poll_id',
        'user_id',
        'body',
        'status_id',
        'is_status_update',
        'is_voting'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
