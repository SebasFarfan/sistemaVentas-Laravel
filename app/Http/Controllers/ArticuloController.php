<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticuloFormRequest;
use App\Models\Articulo;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request){
            $query=trim($request->get('searchText'));
            $articulos=DB::table('articulo as a')
            ->join('categoria as c', 'a.idCategoria','=','c.idCategoria')
            ->select('a.idArticulo', 'a.nombre', 'a.codigo', 'a.stock', 'c.nombre as categoria', 'a.descripcion', 'a.imagen', 'a.estado')
            ->where('a.nombre','LIKE', '%'.$query.'%')
            ->orwhere('a.codigo','LIKE', '%'.$query.'%')
            ->orderBy('idArticulo', 'desc')
            ->paginate(7);
            return view('almacen.articulo.index',["articulos"=>$articulos, "searchText"=>$query]);

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias=DB::table('categoria')->where('condicion','=','1')->get();
        return view('almacen.articulo.create', ['categorias'=>$categorias]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticuloFormRequest $request)
    {
        $articulo = new Articulo;
        $articulo->idCategoria=$request->get('idCategoria');
        $articulo->codigo=$request->get('codigo');
        $articulo->nombre=$request->get('nombre');
        $articulo->stock=$request->get('stock');
        $articulo->descripcion=$request->get('descripcion');
        $articulo->estado='Activo';
        $esImagen = $request->hasFile('imagen');
        // $file = Input::file('imagen');
        // if (Input::hasFile('imagen')) {
        if ($esImagen){
            $file=$request->file('imagen');
            $file->move(public_path().'/imagenes/articulos/', $file->getClientOriginalName());
            $articulo->imagen=$file->getClientOriginalName();
        }
        $articulo->save();
        return redirect('almacen/articulo');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view("almacen.articulo.show",["articulo"=>Articulo::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $articulo = Articulo::findOrFail($id);
        $categorias = DB::table('categoria')->where('condicion','=','1')->get();
        return view("almacen.articulo.edit", ["articulo"=>$articulo, "categorias"=>$categorias]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticuloFormRequest $request, $id)
    {
        $articulo = Articulo::findOrFail($id);
        $articulo->idCategoria=$request->get('idCategoria');
        $articulo->codigo=$request->get('codigo');
        $articulo->nombre=$request->get('nombre');
        $articulo->stock=$request->get('stock');
        $articulo->descripcion=$request->get('descripcion');
        // $articulo->estado='Activo';
        $esImagen = $request->hasFile('imagen');
        // $file = Input::file('imagen');
        // if (Input::hasFile('imagen')) {
        if ($esImagen){
            $file=$request->file('imagen');
            $file->move(public_path().'/imagenes/articulos/', $file->getClientOriginalName());
            $articulo->imagen=$file->getClientOriginalName();
        }

        $articulo->update();
        return redirect('almacen/articulo');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $articulo = Articulo::findOrFail($id);
        $articulo->estado='Inactivo';
        $articulo->update();
        return redirect('almacen/articulo');
    }
}
