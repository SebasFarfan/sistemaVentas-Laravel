@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <h3>Listado de Proveedores <a href="proveedor/create"><button class="btn btn-success">Nuevo</button></a></h3>
            @include('compras.proveedor.search')
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Tipo Doc.</th>
                        <th>Número Doc.</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Opciones</th>
                    </thead>
                    @foreach ($personas as $persona)
                        <tr>
                            <td>{{ $persona->idPersona }}</td>
                            <td>{{ $persona->nombre }}</td>
                            <td>{{ $persona->tipo_documento }}</td>
                            <td>{{ $persona->nro_documento }}</td>
                            <td>{{ $persona->telefono }}</td>
                            <td>{{ $persona->email }}</td>
                            <td>
                                <a href="{{ URL::action('ProveedorController@edit', $persona->idPersona) }}"><button
                                        class="btn btn-info">Editar</button></a>
                                <a href="" data-target="#modal-delete-{{ $persona->idPersona }}" data-toggle="modal"><button
                                        class="btn btn-danger">Eliminar</button></a>
                            </td>
                        </tr>
                        @include('compras.proveedor.modal')
                    @endforeach
                </table>
            </div>
            {{-- paginacion --}}
            {!! $personas->links() !!}
        </div>
    </div>
@endsection
