<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controlador responsável por tratar das páginas de erros.
 */
class ErrorController extends Controller
{
    /**
     * Ir para esta página quando existe uma erro que impede o funcionamento da aplicação.
     *
     * @return  \Illuminate\Http\Response  A resposta HTTP.
     */
    public function fatalError()
    {
        return response()->view('500', [], 500);
    }


    /**
     * Mostrar esta página quando o utilizador tenta aceder uma rota que não foi encontrada.
     *
     * @return  \Illuminate\Http\Response   A resposta HTTP.
     */
    public function notFound()
    {
        return response()->view('404', [], 404);
    }
}
