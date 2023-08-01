<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Throwable;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // verifica se houve alguma exceção ao comunicar com a base de dados
        if ($exception instanceof QueryException) {
            if ($request->ajax()) {
                // retorna em json com status HTTP 500
                return response()->json(['errors' => ['Erro ao comunicar com o servidor.']], 500);
            } else {
                // mostra diretamente a página 500
                $response = response()->view('500', [
                    'success' => false,
                    'errors' => ['Erro ao comunicar com o servidor.']
                ]);

                $response->exception = $exception;
                return $response;
            }
        }

        // Verifica se a exceção é uma ErrorException
        if ($exception instanceof \ErrorException) {
            // mostra diretamente a página 500
            $response = response()->view('500', [
                'success' => false,
                'errors' => ['Erro ao comunicar com o servidor.']
            ]);

            $response->exception = $exception;
            return $response;
        }

        return parent::render($request, $exception);
    }
}
