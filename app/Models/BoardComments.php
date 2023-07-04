<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardComments extends Model
{
    use HasFactory;

    protected $table = 'board_comments';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->timestamps = false;
        $this->primaryKey = 'idx';
    }

    public function boardContent()
    {
        return $this->hasOne(BoardContents::class, 'idx', 'board_contents_idx');
    }
}
