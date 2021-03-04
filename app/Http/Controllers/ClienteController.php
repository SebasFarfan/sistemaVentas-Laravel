<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonaFormResquest;
use App\Models\Persona;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {
        if ($request) {
            $query=trim($request->get('searchText'));
            $personas=DB::table('persona')
            ->where('nombre','LIKE', '%'.$query.'%')
            ->where('tipo_persona','=','Cliente')
            ->orwhere('nro_documento','LIKE','%'.$query.'%')
            ->where('tipo_persona','=','Cliente')
            ->orderBy('idPersona','desc')
            ->paginate(7);
            return view('ventas.cliente.index',["personas"=>$personas, "searchText"=>$query]);

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ventas.cliente.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\PersonaFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersonaFormResquest $request)
    {
        $persona = new Persona();
        $persona->tipo_persona='Cliente';
        $persona->nombre=$request->get('nombre');
        $persona->tipo_documento=$request->get('tipo_documento');
        $persona->nro_documento=$request->get('nro_documento');
        $persona->direccion=$request->get('direccion');
        $persona->telefono=$request->get('telefono');
        $persona->email=$request->get('email');
        $persona->save();
        return redirect('ventas/cliente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('ventas.cliente.show', ["persona"=>Persona::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('ventas.cliente.edit', ["persona"=>Persona::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\PersonaFormRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PersonaFormResquest $request, $id)
    {
        $persona = Persona::findOrFail($id);
        $persona->nombre=$request->get('nombre');
        $persona->tipo_documento=$request->get('tipo_documento');
        $persona->nro_documento=$request->get('nro_documento');
        $persona->direccion=$request->get('direccion');
        $persona->telefono=$request->get('telefono');
        $persona->email=$request->get('email');
        $persona->update();
        return redirect('ventas/cliente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $persona=Persona::findOrFail($id);
        $persona->tipo_persona='Inactivo';
        $persona->update();
        return redirect('ventas/cliente');
    }
}
