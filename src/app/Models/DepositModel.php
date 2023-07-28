<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepositModel extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'deposits';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable 
        = [
            'status',
            'amount',
            'payee_id',
        ];

    protected $casts
        = [
            'payee_id' => 'integer',
        ];
}