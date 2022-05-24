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

    public function consultarRelatorio(Request $request)
    {
        $inputs = $request->all();
        $callback = [];
        for ($i=0; $i < count($inputs["consultores"]); $i++) {
            $data = [];
            $data['consultor'] = $inputs["consultores"][$i];
            $data['receita_liquida'] = 0;
            $data['comissao'] = 0;
            $data['custo_fixo'] = 0;
            $data['lucro'] = 0;

            // receita liquida
            $valor = DB::table('cao_os')
            ->leftjoin('cao_fatura', 'cao_os.co_os', '=', 'cao_fatura.co_os')
            ->select('cao_fatura.valor', 'cao_fatura.data_emissao', 'cao_fatura.total_imp_inc','cao_fatura.comissao_cn')
            ->where('cao_os.co_usuario', '=', $inputs["consultores"][$i])
            ->whereBetween('cao_fatura.data_emissao', [$inputs["desde"], $inputs["hasta"]])
            ->get();

            $valor_orden = 0;
            $imp_total = 0;
            //recorrido para validar cada una de las OS y asi saber el calculo exacto para cada 1
            foreach ($valor as $orden_serv) {

                // Calculo de Receita Liquida
                $valor_orden = $orden_serv->valor - (($orden_serv->valor * $orden_serv->total_imp_inc)/ 100);
                $data['receita_liquida'] += $valor_orden;

                // Calculo de Comissao
                $comision_orden = (($orden_serv->valor - (($orden_serv->valor * $orden_serv->total_imp_inc)/ 100)) * $orden_serv->comissao_cn)/100;
                $data['comissao'] += $comision_orden;
            }

            // calculo de custo fixo
            $custo_fixo = DB::table('cao_salario')
            ->select('brut_salario')
            ->where('co_usuario', '=', $inputs["consultores"][$i])
            ->first();

            $data['custo_fixo'] = $custo_fixo->brut_salario ?? 0;
            // Calculo de Lucro
            $data['lucro'] = $data['receita_liquida'] - ($data['custo_fixo'] + $data['comissao']);

            //le doy format a cada registro
            $data['receita_liquida'] = number_format($data['receita_liquida'], 2,',','.');
            $data['custo_fixo'] = number_format($data['custo_fixo'], 2,',','.');
            $data['comissao'] = number_format($data['comissao'], 2,',','.');
            $data['lucro'] = number_format($data['lucro'], 2,',','.');
            // Incluyo la data al array del callback
            array_push($callback, $data);

        }
        return response()->json(['result' => $callback]);

    }

    public function consultarPizza(Request $request)
    {
        $inputs = $request->all();
        $callback = [];
        for ($i=0; $i < count($inputs["consultores"]); $i++) {
            $data = [];
            $data['consultor'] = $inputs["consultores"][$i];
            $data['receita_liquida'] = 0;

            // receita liquida
            $valor = DB::table('cao_os')
            ->leftjoin('cao_fatura', 'cao_os.co_os', '=', 'cao_fatura.co_os')
            ->select('cao_fatura.valor', 'cao_fatura.data_emissao', 'cao_fatura.total_imp_inc','cao_fatura.comissao_cn')
            ->where('cao_os.co_usuario', '=', $inputs["consultores"][$i])
            ->whereBetween('cao_fatura.data_emissao', [$inputs["desde"], $inputs["hasta"]])
            ->get();

            $valor_orden = 0;
            //recorrido para validar cada una de las OS y asi saber el calculo exacto para cada 1
            foreach ($valor as $orden_serv) {

                // Calculo de Receita Liquida
                $valor_orden = $orden_serv->valor - (($orden_serv->valor * $orden_serv->total_imp_inc)/ 100);
                $data['receita_liquida'] += $valor_orden;
            }

            //le doy format a cada registro
            //$data['receita_liquida'] = number_format($data['receita_liquida'], 2,',','.');
            // Incluyo la data al array del callback
            array_push($callback, $data);

        }
        return response()->json(['result' => $callback]);

    }

    public function consultarBar(Request $request)
    {
        $inputs = $request->all();
        $callback = [];
        for ($i=0; $i < count($inputs["consultores"]); $i++) {
            $data = [];
            $data['consultor'] = $inputs["consultores"][$i];
            $data['receita_liquida'] = 0;

            // receita liquida
            $valor = DB::table('cao_os')
            ->leftjoin('cao_fatura', 'cao_os.co_os', '=', 'cao_fatura.co_os')
            ->select('cao_fatura.valor', 'cao_fatura.data_emissao', 'cao_fatura.total_imp_inc','cao_fatura.comissao_cn')
            ->where('cao_os.co_usuario', '=', $inputs["consultores"][$i])
            ->whereBetween('cao_fatura.data_emissao', [$inputs["desde"], $inputs["hasta"]])
            ->get();

            $valor_orden = 0;
            //recorrido para validar cada una de las OS y asi saber el calculo exacto para cada 1
            foreach ($valor as $orden_serv) {

                // Calculo de Receita Liquida
                $valor_orden = $orden_serv->valor - (($orden_serv->valor * $orden_serv->total_imp_inc)/ 100);
                $data['receita_liquida'] += $valor_orden;
            }

            //le doy format a cada registro
            //$data['receita_liquida'] = number_format($data['receita_liquida'], 2,',','.');
            // Incluyo la data al array del callback
            array_push($callback, $data);

        }
        return response()->json(['result' => $callback]);

    }
}
