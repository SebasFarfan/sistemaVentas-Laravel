@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <h3>Listado de Ingresos <a href="ingreso/create"><button class="btn btn-success">Nuevo</button></a></h3>
            @include('compras.ingreso.search')
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Comprobante</th>
                        <th>Impuesto</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </thead>
                    @foreach ($ingresos as $ingreso)
                        <tr>
                            <td>{{ $ingreso->fecha_hora }}</td>
                            <td>{{ $ingreso->nombre }}</td>
                            <td>{{ $ingreso->tipo_comprobante .': '.$ingreso->serie_comprobante.'-'.$ingreso->num_comprobante }}</td>
                            <td>{{ $ingreso->impuesto }}</td>
                            <td>{{ $ingreso->total}}</td>
                            <td>{{ $ingreso->estado }}</td>
                            <td>
                                <a href="{{ URL::action('IngresoController@show', $ingreso->idIngreso) }}"><button
                                        class="btn btn-primary">Detalles</button></a>
                                <a href="" data-target="#modal-delete-{{ $ingreso->idIngreso }}" data-toggle="modal"><button
                                        class="btn btn-danger">Anular</button></a>
                            </td>
                        </tr>
                        @include('compras.ingreso.modal')
                    @endforeach
                </table>
            </div>
            {{-- paginacion --}}
            {!! $ingresos->links() !!}
        </div>
    </div>
@endsection
