@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="proveedor">Proveedor: {{ $ingreso->nombre }}</label>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label>Tipo Comprobante: {{ $ingreso->tipo_comprobante }}</label>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label for="serie_comprobante">Serie de Comprobante: {{ $ingreso->serie_comprobante }}</label>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label for="num_comprobante">Número Comprobante: {{ $ingreso->num_comprobante }}</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="detalles" class="table table-striped table-bordered condensed table-hover">
                            <thead style="background: #A9D0F5">
                                <th>Artículo</th>
                                <th>Cantidad</th>
                                <th>Precio Compra</th>
                                <th>Precio Venta</th>
                                <th>Subtotal</th>
                            </thead>
                            <tfoot>
                                <th colspan="4"></th>
                                {{-- <th></th>
                                <th></th>
                                <th></th>
                                <th></th> --}}
                                <th>
                                    <h4 id="total">{{ $ingreso->total }}</h4>
                                </th>
                            </tfoot>
                            <tbody>
                                @foreach ($detalles as $detalle)
                                    <tr>
                                        <td>{{ $detalle->articulo }}</td>
                                        <td>{{ $detalle->cantidad }}</td>
                                        <td>{{ $detalle->precio_compra }}</td>
                                        <td>{{ $detalle->precio_venta }}</td>
                                        <td>{{ $detalle->cantidad * $detalle->precio_compra }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection
