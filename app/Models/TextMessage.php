<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextMessage extends Model
{
    //

    protected $fillable = ['message', 'response', 'sent_to', 'sent_by', 'status','remarks'];


    const STATUS = [
        'Pending' => 'Pending',
        'Accepted' => 'Accepted',
        'Rejected' => 'Rejected',
    ];

    public function sentTo()
    {
        return $this->belongsTo(User::class, 'sent_to');
    }

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

}
