<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DesempenhoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consultores = DB::table('CAO_USUARIO')
        ->leftjoin('PERMISSAO_SISTEMA', 'CAO_USUARIO.CO_USUARIO', '=', 'PERMISSAO_SISTEMA.CO_USUARIO')
        ->select('CAO_USUARIO.CO_USUARIO as nombre_usuario')
        ->where('PERMISSAO_SISTEMA.CO_SISTEMA', '=', 1)
        ->where('PERMISSAO_SISTEMA.IN_ATIVO', '=', 'S')
        ->whereIn('PERMISSAO_SISTEMA.CO_TIPO_USUARIO', [0,1,2])
        ->get();
        return view('pages.desempenho', ['consultores' => $consultores]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function filter(Request $request)
    {
        dd($request->all());
    }
}
