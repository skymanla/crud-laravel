<?php

namespace App\Services;

use App\Models\Board;
use App\Models\BoardCategory;
use App\Models\BoardComments;
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

    public function getBoardList()
    {
        return Board::where('is_use', 'Y')->get();
    }

    public function getContentsCategory()
    {
        return BoardCategory::where('is_use', 'Y')->get();
    }

    public function getList($request, $boardIdx)
    {
        $board = Board::with([
            'boardContents',
            'boardContents.contentsCategory',
            'boardContents.user'
        ])
            ->when(!empty($request->sfl), function (Builder $query) use ($request) {
                return $query->where($request->stx, 'LIKE', "%$request->sfl%");
            })
            ->where('idx', '=', $boardIdx)
            ->paginate($request->page ?? 10);
        $category = $this->getContentsCategory();
        $boardList = $this->getBoardList();

        return [
            'currentPage' => $board->currentPage(),
            'lastPage' => $board->lastPage(),
            'items' => $board->items(),
            'category' => $category,
            'boardList' => $boardList
        ];
    }

    public function writeContents($request): void
    {
        // 5분 이내 연속 등록 방지
        $writeCount = BoardContents::where('user_id', '=', $request->user()->id)->where('created_dt', '>=', Carbon::now()->subMinutes(5))->count();
        if ($writeCount > 0) {
            throw new \Exception('연속해서 게시글을 작성할 수 없습니다', 422);
        }
        try {
            DB::transaction(function () use ($request) {
                Board::create([
                    'title' => $request->title,
                    'contents' => $request->contents,
                    'contents_category_idx' => $request->categoryIdx,
                    'board_idx' => $request->boardIdx,
                    'user_id' => $request->user()->id,
                    'ip' => $request->ip
                ]);
            });
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage(), 500);
        } finally {
            DB::commit();
        }
    }

    public function readContents($idx, $request)
    {
        try {
            $contents = BoardContents::with([
                'board',
                'user',
                'contentsCategory'
            ])
                ->withCount('boardComments')
                ->where('idx', '=', $idx)->firstOrFail();
            // 하루
            $readCount = BoardReadHistory::where('user_id', $request->user()->id)
                ->where('board_contents_idx', $idx)
                ->where(DB::raw("date_format(created_dt, '%Y-%m-%d')"), Carbon::now()->toDateString())
                ->count();
            if ($readCount < 1) {
                $contents->hit_count = $contents->hit_count + 1;
                DB::transaction(function () use ($idx, $request, $contents) {
                    // hit create
                    BoardReadHistory::create([
                        'ip' => $request->ip(),
                        'board_contents_idx' => $idx,
                        'user_id' => $request->user()->id
                    ]);
                    $contents->save();
                });
            }
            return $contents;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('게시글을 찾을 수 없습니다', 400);
        } finally {
            DB::commit();
        }
    }

    public function getComment($request, $idx)
    {
        $comment = BoardComments::with([
            'user'
        ])
            ->where('board_contents_idx', $idx)
            ->where('is_delete', 'N')
            ->orderBy('idx', 'asc')
            ->paginate($request->page ?? 20);
        return [
            'currentPage' => $comment->currentPage(),
            'lastPage' => $comment->lastPage(),
            'items' => $comment->items()
        ];
    }

    private function isContentsOwner($idx, $request): void
    {
        $contents = BoardContents::where('idx', '=', $idx)->first();
        if ($contents->user_id !== $request->user()->id) {
            throw new \Exception('권한이 없습니다', 401);
        }
    }

    public function modifyContents($idx, $request)
    {
        try {
            $this->isContentsOwner($idx, $request);
            return BoardContents::where('idx', '=', $idx)->firstOrFail();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updateModifyContents($request, $idx): void
    {
        try {
            $this->isContentsOwner($idx, $request);
            DB::transaction(function () use ($request, $idx) {
                BoardContents::where('idx', $idx)
                    ->update([
                        'title' => $request->title,
                        'contents' => $request->contents,
                        'contents_category_idx' => $request->contentsCategoryIdx,
                        'updated_dt' => Carbon::now()
                    ]);
            });
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('수정 중 오류가 발생하였습니다', 500);
        } finally {
            DB::commit();
        }
    }

    public function deleteBoardContents($idx, $request): void
    {
        try {
            $this->isContentsOwner($idx, $request);
            DB::transaction(function () use ($idx) {
                BoardContents::where('idx', $idx)->update([
                    'is_delete' => 'Y',
                    'deleted_dt' => Carbon::now()
                ]);
            });
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('삭제 중 오류가 발생하였습니다', 500);
        } finally {
            DB::commit();
        }
    }

    private function isCommentOwner($request, $idx): void
    {
        $contents = BoardComments::where('idx', '=', $idx)->first();
        if ($contents->user_id !== $request->user()->id) {
            throw new \Exception('권한이 없습니다', 401);
        }
    }
    public function writeComment($request, $contentsIdx): void
    {
        try {
            DB::transaction(function () use ($request, $contentsIdx) {
                BoardComments::create([
                    'board_contents_idx' => $contentsIdx,
                    'comments' => $request->comment,
                    'user_id' => $request->user()->id,
                    'ip' => $request->ip()
                ]);
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updateModifyComments($request, $idx): void
    {
        try {
            $this->isCommentOwner($idx, $request);
            DB::transaction(function () use ($request, $idx) {
                BoardComments::where('idx', $idx)
                    ->update([
                        'comments' => $request->comment,
                        'updated_dt' => Carbon::now()
                    ]);
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function deleteComments($request, $idx): void
    {
        try {
            $this->isCommentOwner($idx, $request);
            DB::transaction(function () use ($request, $idx) {
                BoardComments::where('idx', $idx)
                    ->update([
                        'is_delete' => 'Y',
                        'deleted_dt' => Carbon::now()
                    ]);
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
