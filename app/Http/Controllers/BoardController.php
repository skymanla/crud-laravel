<?php

namespace App\Http\Controllers;

use App\Services\BoardService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BoardController extends Controller
{
    use ApiResponse;

    public function __construct(
        private BoardService $boardService
    ) {}

    // get list
    public function getList($boardIdx, Request $request)
    {
        $data = $this->boardService->getList($request, $boardIdx);
        return $this->success($data);
    }

    // board id read
    public function readContents($boardIdx, $idx, Request $request)
    {
        try {
            $data = $this->boardService->readContents($idx, $request);
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), $e->getCode());
        }
    }

    // post board write
    public function writeContents(Request $request)
    {
        // 카테고리랑 보드 아이디 전달
        $board = $this->boardService->getBoardList();
        $contentsCategory = $this->boardService->getContentsCategory();
        return $this->success([
            'board' => $board,
            'category' => $contentsCategory
        ]);
    }

    public function postWriteContents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:20',
            'boardIdx' => 'required|integer',
            'contentCategoryIdx' => 'required|integer',
            'contents' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            throw new \Exception($validator->errors(), 400);
        }
        try {
            $this->boardService->writeContents($request);
            return $this->success(null, '게시글 등록이 완료되었습니다');
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), $e->getCode());
        }
    }

    public function getModifyContents(Request $request, $idx)
    {
        try {
            $data = $this->boardService->modifyContents($idx, $request);
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), $e->getCode());
        }
    }

    public function updateModifyContents(Request $request, $idx)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:20',
                'contentCategoryIdx' => 'required|integer',
                'contents' => 'required|string|max:255'
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors(), 400);
            }
            $this->boardService->updateModifyContents($request, $idx);
            return $this->success(null, '게시글 수정이 완료되었습니다');
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), $e->getCode());
        }
    }

    public function deleteContents(Request $request, $idx)
    {
        try {
            $this->boardService->deleteBoardContents($idx, $request);
            return $this->success(null, '게시글 삭제가 완료되었습니다');
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), $e->getCode());
        }
    }

    public function getComments($contentsIdx, Request $request)
    {
        $data = $this->boardService->getComment($request, $contentsIdx);
        return $this->success($data);
    }

    public function postWriteComments(Request $request, $contentsIdx)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comments' => 'required|string|max:100'
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors(), 400);
            }
            $this->boardService->writeComment($request, $contentsIdx);
            return $this->success(null, '코멘트 등록이 완료되었습니다');
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), $e->getCode());
        }
    }

    public function updateModifyComments(Request $request, $idx)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comments' => 'required|string|max:100'
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors(), 400);
            }
            $this->boardService->updateModifyContents($request, $idx);
            return $this->success(null, '코멘트 수정이 완료되었습니다');
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), $e->getCode());
        }

    }

    public function deleteComments(Request $request, $idx)
    {
        try {
            $this->boardService->deleteComments($request, $idx);
            return $this->success(null, '코멘트 삭제가 완료되었습니다');
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), $e->getCode());
        }
    }
}
