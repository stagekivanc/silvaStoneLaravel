<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'surname',
        'company',
        'phone',
        'email',
        'subject',
        'message',
        'is_read',
        'ip_address',
        'user_agent'
    ];
}
