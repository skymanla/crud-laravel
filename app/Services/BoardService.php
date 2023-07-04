<?php

namespace App\Services;

use App\Models\Board;
use App\Models\BoardContents;
use App\Models\BoardReadHistory;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BoardService
{
    public function __construct() {}
    public function getList($request, $boardIdx)
    {
        $board = Board::with([
            'boardContents',
            'boardContents.contentsCategory'
        ])
            ->when(!empty($request->sfl), function (Builder $query) use ($request) {
                return $query->where($request->stx, 'LIKE', "%$request->sfl%");
            })
            ->where('idx', '=', $boardIdx)
            ->paginate($request->page ?? 10);

        return [
            'currentPage' => $board->currentPage(),
            'lastPage' => $board->lastPage(),
            'items' => $board->items()
        ];
    }

    public function writeContents($request)
    {
        // 5분 이내 연속 등록 방지
        $writeCount = Board::where('ip', '=', $request->ip)->where('created_dt', '>=', Carbon::now()->subMinutes(5))->count();
        if ($writeCount > 0) {
            return false;
        }
        DB::transaction(function () use ($request) {
            Board::create([
                'title' => $request->title,
                'contents' => $request->contents,
                'contents_category_idx' => $request->categoryIdx,
                'board_idx' => $request->boardIdx,
                'writer' => $request->writer,
                'pwd' => Hash::make($request->pwd),
                'ip' => $request->ip
            ]);
        });
    }

    public function readContents($idx, $request)
    {
        // hit count update
        $contents = BoardContents::with([
            'board',
            'boardComments'
        ])
            ->withCount('boardComments')
            ->where('idx', '=', $idx)->firstOrFail();
        // 하루
        $readCount = BoardReadHistory::where('ip', $request->ip())
            ->where('board_contents_idx', $idx)
            ->where(DB::raw("date_format(created_dt, '%Y-%m-%d')"), Carbon::now()->toDateString())
            ->count();
        if ($readCount < 1) {
            $contents->hit_count = $contents->hit_count + 1;
            $contents->save();

            DB::transaction(function () use ($idx, $request) {
                // hit create
                BoardReadHistory::create([
                    'ip' => $request->ip(),
                    'board_contents_idx' => $idx
                ]);
            });
        }
        return $contents;
    }

    public function createReadIp($ip)
    {
        // ip update
    }
    public function isContentsOwner($idx, $pwd)
    {
        $contents = Board::where('idx', '=', $idx)->first();
        if (!Hash::check($pwd, $contents->pwd)) {
            return false;
        }
        session('boardOwner', [
            'idx' => $idx,
            'owner' => true
        ]);
        return true;
    }

    public function modifyContents($idx)
    {
        if (session()->has('boardOwner') && session()->get('boardOwner.owner') === true && session()->get('boardOwner.idx') === $idx) {
            return Board::where('idx', '=', $idx)->first();
        }
        return null;
    }

    public function updateModifyContents($request, $idx)
    {

    }
}
