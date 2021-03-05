<?php

namespace App\Http\Controllers;

use App\Http\Requests\IngresoFormRequest;
use App\Models\DetalleIngreso;
use App\Models\Ingreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;


class IngresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request) {
            $query = trim($request->get('searchText'));
            $ingresos = DB::table('ingreso as i')
                ->join('persona as p', 'i.idProveedor', '=', 'p.idPersona')
                ->join('detalle_ingreso as di', 'i.idIgreso', '=', 'di.idIngreso')
                ->select('i.idIngreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado', DB::raw('sum(di.cantidad*precio_compra) as total'))
                ->where('i.num_comprobante', 'LIKE', '%' . $query . '%')
                ->orderBy('idIngreso', 'desc')
                ->groupBy('i.idIngreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado')
                ->paginate(7);
            return view('compras.ingreso.index', ["ingresos" => $ingresos, "searchText" => $query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $personas = DB::table('persona')->where('tipo_persona', '=', 'Proveedor')->get();
        $articulos = DB::table('articulo as art')
            ->select(DB::raw('CONCAT(art.codigo," ",art.nombre) AS articulo'), 'art.idArticulo')
            ->where('art.estado', '=', 'Activo')
            ->get();
        return view("compras.ingreso.create", ["personas" => $personas, "articulos" => $articulos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\IngresoFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IngresoFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $ingreso = new Ingreso();
            $ingreso->idProveedor = $request->get('idProveedor');
            $ingreso->tipo_comprobante = $request->get('tipo_comprobante');
            $ingreso->serie_comprobante = $request->get('serie_comprobante');
            $ingreso->num_comprobante = $request->get('num_comprobante');
            $myTime = Carbon::now('America/Argentina/Jujuy');
            $ingreso->fecha_hora = $myTime->toDateTimeString();
            $ingreso->impuesto = '21';
            $ingreso->estado = 'A';
            $ingreso->save();

            $idArticulo = $request->get('idArticulo');
            $cantidad = $request->get('cantidad');
            $precio_compra = $request->get('precio_compra');
            $precio_venta = $request->get('prcio_venta');

            $cont = 0;
            while ($cont < count($idArticulo)) {
                $detalle = new DetalleIngreso();
                $detalle->idIngreso = $ingreso->idIngreso;
                $detalle->idArticulo = $idArticulo[$cont];
                $detalle->cantidad = $cantidad[$cont];
                $detalle->precio_compra = $precio_compra[$cont];
                $detalle->precio_venta = $precio_venta[$cont];
                $detalle->save();
                $cont = $cont + 1;
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect('compras/ingreso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ingreso=DB::table('ingreso as i')
        ->join('persona as p', 'i.idProveedor', '=', 'p.idPersona')
        ->join('detalle_ingreso as di', 'i.idIgreso', '=', 'di.idIngreso')
        ->select('i.idIngreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado', DB::raw('sum(di.cantidad*precio_compra) as total'))
        ->where('idIngreso','=',$id)
        ->first();
        $detalles=DB::table('detalle_ingreso as d')
        ->join('articulo as a','a.idArticulo','=','d.idArticulo')
        ->select('a.nombre as articulo','d.cantidad','d.precio_compra','d.precio_venta')
        ->where('d.idIngreso','=',$id)
        ->get();

        return view('compras.ingreso.show',["ingreso"=>$ingreso, "detalles"=>$detalles]);

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
        $ingreso=Ingreso::findOrFail($id);
        $ingreso->estado='C'; // C: cancelado
        $ingreso->update();
        return redirect('compras/ingreso');
    }
}
