<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ClientIp extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'client_ip',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
