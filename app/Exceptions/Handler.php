<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\ExclusiveLockException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            return redirect('/login');
        }

        if ($exception instanceof ExclusiveLockException) {
            return redirect()->back()->with('error', ' 他のユーザーが操作しています。');
        }

        if ($request->wantsJson()) {

            $response['status']  = 1;

            if ($exception instanceof ReservationUpdateException) {
                $response['code_number']  = '04';
                $response['code_detail']  = '12';
                $response['message']  = '予約ステータス更新エラー';
                return response()->json($response, 400);
            }

            if ($exception instanceof ReservationDateException) {
                $response['code_number']  = '00';
                $response['code_detail']  = '03';
                // 受付期間外？
                $response['message']  = '予約枠埋まり';
                return response()->json($response, 400);
            }

            if ($exception instanceof ReservationFrameException) {
                $response['code_number']  = '00';
                $response['code_detail']  = '04';
                $response['message']  = '予約枠なし';
                return response()->json($response, 400);
            }

            if (
                $exception instanceof JsonEncodingException || $exception instanceof MassAssignmentException
                || $exception instanceof ModelNotFoundException || $exception instanceof RelationNotFoundException || $exception instanceof QueryException
            ) {
                $response['code_number']  = '00';
                $response['code_detail']  = '02';
                $response['message']  = 'DB接続エラー';
                return response()->json($response, 400);
            }

            $response['code_number']  = '01';
            $response['code_detail']  = '01';
            $response['message']  = '内部エラー';
            return response()->json($response, 500);
        }

        return parent::render($request, $exception);
    }
}
