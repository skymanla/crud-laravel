<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $table = 'board';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->timestamps = false;
        $this->primaryKey = 'idx';
    }

    public function boardContents()
    {
        return $this->hasMany(BoardContents::class, 'board_idx', 'idx')
            ->where('is_delete', '=', 'N')
            ->orderBy('created_dt', 'desc');
    }
}
