<?php

namespace App\Http\Controllers;

use App\Services\BoardService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

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
    public function readContents($boardIdx, $contentIdx, Request $request)
    {
        $data = $this->boardService->readContents($contentIdx, $request);
        return $this->success($data);
    }

    // post board write
    public function writeContents(Request $request)
    {

    }

    // modify board contents
    // delete board contents
    // write board comment
    // modify board comment
    // delete board comment
}
