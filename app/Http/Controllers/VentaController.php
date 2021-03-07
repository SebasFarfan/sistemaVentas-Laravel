<?php

namespace App\Http\Controllers;

use App\Http\Requests\VentaFormRequest;
use App\Models\DetalleVenta;
use App\Models\Venta;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;

class VentaController extends Controller
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
            $ventas = DB::table('venta as v')
                ->join('persona as p', 'v,idCliente', '=', 'p.idPersona')
                ->join('detalle_venta as dv', 'v.idVenta', '=', 'dvIdVenta')
                ->select('v.idVenta', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.impuesto', 'v.estado', 'v.total_venta')
                ->where('v.num_comprobante', 'LIKE', '%' . $query . '%')
                ->orderByDesc('v.idVenta')
                ->groupBy('v.idVenta', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.impuesto', 'v.estado')
                ->paginate(7);
            return view('ventas.venta.index', ["ventas" => $ventas, "searchText" => $query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //el precio de venta será un promedio de todos los precio con que ha ingresado ese articulo
        $personas = DB::table('persona')->where('tipo_persona', '=', 'Cliente');
        $articulos = DB::table('articulo as art')
            ->join('detalle_ingreso as di', 'art.idArticulo', '=', 'di.idArticulo')
            ->select(
                DB::raw('CONCAT(art.codigo, " ", art.nombre) AS articulo'),
                'art.idArticulo',
                'art.stock',
                DB::raw('avg(di.precio_venta) as precio_promedio')
            )
            ->where('art.estado', '=', 'Activo')
            ->where('art.stock', '>', '0')
            ->groupBy('articulo', 'art.idArticulo', 'art.stock')
            ->get();
        return view('ventas.venta.create', ["personas" => $personas, "articulos" => $articulos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VentaFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $venta = new Venta();
            $venta->idCliente = $request->get('idCliente');
            $venta->tipo_comprobante = $request->get('tipo_comprobante');
            $venta->serie_comprobante = $request->get('serie_comprobante');
            $venta->num_comprobante = $request->get('num_comprobante');
            $venta->total_venta = $request->get('total_venta');

            $myTime = Carbon::now('America/Argentina/Jujuy');
            $venta->fecha_hora = $myTime->toDateTimeString();
            $venta->impuesto = '21';
            $venta->estado = 'A';
            $venta->save();

            $idArticulo = $request->get('idArticulo');
            $cantidad = $request->get('cantidad');
            $descuento = $request->get('descuento');
            $precio_venta = $request->get('precio_venta');

            $cont = 0;
            while ($cont < count($idArticulo)) {
                $detalle = new DetalleVenta();
                $detalle->idVenta = $venta->idVenta;
                $detalle->idArticulo = $idArticulo[$cont];
                $detalle->cantidad = $cantidad[$cont];
                $detalle->descuento = $descuento[$cont];
                $detalle->precio_venta = $precio_venta[$cont];
                $detalle->save();
                $cont = $cont + 1;
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect('ventas/venta');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $venta = DB::table('venta as v')
            ->join('persona as p', 'v,idCliente', '=', 'p.idPersona')
            ->join('detalle_venta as dv', 'v.idVenta', '=', 'dvIdVenta')
            ->select('v.idVenta', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.impuesto', 'v.estado', 'v.total_venta')
            ->where('v.idVenta', '=', $id)
            ->first();
        $detalles = DB::table('detalle_venta as d')
            ->join('articulo as a', 'd.idArticulo', '=', 'a.idArticulo')
            ->select('a.nombre as articulo', 'd.cantidad', 'd.descuento', 'd.precio_venta')
            ->where('d.idVenta', '=', $id)
            ->get();
        return view('ventas.venta.show', ["venta" => $venta, "detalles" => $detalles]);
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
        $venta = Venta::findOrFail($id);
        $venta->estado = 'C';
        $venta->update();
        return redirect('ventas/venta');
    }
}
