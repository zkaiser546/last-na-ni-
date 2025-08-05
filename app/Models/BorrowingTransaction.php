<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingTransaction extends Model
{
    protected $guarded = [];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function record(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Record::class);
    }
}
