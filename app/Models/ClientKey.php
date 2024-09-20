<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ClientKey extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'private_key',
        'public_key',
    ];

    public function clients()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
