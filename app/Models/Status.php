<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperStatus
 */
class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function polls()
    {
        return $this->hasMany(Poll::class);
    }

    public static function getCount()
    {
        return Poll::query()
            ->selectRaw("count(*) as all_statuses")
            ->selectRaw("count(case when status_id = 1 then 1 end) as open")
            ->selectRaw("count(case when status_id = 2 then 1 end) as accepted")
            ->selectRaw("count(case when status_id = 3 then 1 end) as rejected")
            ->first()
            ->toArray();
    }
}
