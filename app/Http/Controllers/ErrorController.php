<?php

namespace App\Http\Controllers;

class ErrorController extends Controller
{
    /**
     * Ir para a página 404 quando alguma rota não é encontrada.
     *
     * @return \Illuminate\Http\Response
     */
    public function handle404()
    {
        return view('404');
    }


    /**
     * Ir para a página 503 quando algum erro fatal acontece com o servidor.
     *
     * @return \Illuminate\Http\Response
     */
    public function handle500()
    {
        return view('500');
    }
}
