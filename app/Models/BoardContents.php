<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardContents extends Model
{
    use HasFactory;

    protected $table = 'board_contents';
    protected $hidden = ['pwd'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->timestamps = false;
        $this->primaryKey = 'idx';
    }

    public function boardComments()
    {
        return $this->hasMany(BoardComments::class, 'board_contents_idx', 'idx')->where('is_delete', '=', 'N');
    }

    public function contentsCategory()
    {
        return $this->hasOne(BoardCategory::class, 'idx', 'contents_category_idx');
    }

    public function board()
    {
        return $this->hasOne(Board::class, 'idx', 'board_idx');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
