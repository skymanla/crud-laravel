<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('login/fail', function () {
    return response()->json([
        'message' => '로그인이 필요합니다'
    ], 401);
})->name('api.login.fail');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', function (Request $request) {
        $auth = auth()->user();
        if (!$auth) {
            abort(401, '존재하지 않는 회원입니다');
        }
        return auth()->user();
    })->name('api.me');

    Route::prefix('board')->name('api.board.')->group(function () {
        # 게시판 리스트
        Route::get('{boardIdx}', [BoardController::class, 'getList'])->where(['boardIdx' => '[0-9]+'])->name('list');
        # 게시글 읽기
        Route::get('{boardIdx}/{contentIdx}', [BoardController::class, 'readContents'])->where(['boardIdx' => '[0-9]+', '$idx' => '[0-9]+'])->name('read');
        # 게시글 작성 페이지(게시글 저장 위치 및 카테고리 정보 전달용)
        Route::get('write', [BoardController::class, 'writeContents'])->name('write');
        # 게시글 수정 페이지
        Route::get('modify/{$idx}', [BoardController::class, 'getModifyContents'])->where(['idx' => '[0-9]+'])->name('get.modify');
        # 게시글 수정 update
        Route::put('modify/{$idx}', [BoardController::class, 'updateModifyContents'])->where(['idx' => '[0-9]+'])->name('put.modify');
        # 게시글 삭제
        Route::delete('delete/{$idx}', [BoardController::class, 'deleteContents'])-> where(['idx' => '[0-9]+'])->name('delete');
        # 코멘트 관려
        Route::prefix('comments')->name('.comments')->group(function () {
            # 게시글 내 코멘트 가져오기
            Route::get('list/{contentsIdx}', [BoardController::class, 'getComments'])->where(['contentsIdx' => '[0-9]+'])->name('list');
            # 코멘트 수정
            Route::put('update/{idx}', [BoardController::class, 'updateModifyComments'])->where(['idx' => '[0-9]+'])->name('update');
            # 코멘트 삭제
            Route::delete('delete/{idx}', [BoardController::class, 'deleteComments'])->where(['idx' => '[0-9]+'])->name('delete');
        });
    });
});
