<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardReadHistory extends Model
{
    use HasFactory;

    protected $table = 'board_read_history';
    protected $fillable = [
        'ip',
        'board_contents_idx'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->timestamps = false;
        $this->primaryKey = 'idx';
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function boardContents()
    {
        return $this->hasOne(BoardContents::class, 'idx', 'board_contents_idx');
    }
}
