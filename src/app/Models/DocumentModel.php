<?php

namespace App\Models;

use App\Enums\DocumentTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentModel extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable 
        = [
            'type',
            'number',
            'user_id',
        ];

    protected $casts
        = [
            'type' => DocumentTypeEnum::class,
            'user_id' => 'integer',
        ];
}