@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Nuevo Ingreso</h3>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    {!! Form::open(['url' => 'compras/ingreso', 'method' => 'POST', 'autocomplete' => 'off']) !!}
    {!! Form::token() !!}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="proveedor">Proveedor</label>
                <select name="idProveedor" id="idProveedor" class="form-control selectpicker" data-Live-search="true">
                    @foreach ($personas as $persona)
                        <option value="{{ $persona->idPersona }}">{{ $persona->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label>Tipo Comprobante</label>
                <select name="tipo_comprobante" class="form-control">
                    <option value="Boleta">Boleta</option>
                    <option value="Factura">Factura</option>
                    <option value="Ticket">Ticket</option>
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label for="serie_comprobante">Serie de Comprobante</label>
                <input type="text" class="form-control" name="serie_comprobante" value="{{ old('serie_comprobante') }}"
                    placeholder="Serie de comprobante...">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label for="num_comprobante">N??mero Comprobante</label>
                <input type="text" class="form-control" name="num_comprobante" value="{{ old('num_comprobante') }}"
                    placeholder="Nro. de comprobante...">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Art??culo</label>
                    <select name="pidArticulo" id="pidArticulo" class="form-control selectpicker" data-Live-search="true">
                        @foreach ($articulos as $articulo)
                            <option value="{{ $articulo->idArticulo }}">{{ $articulo->articulo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <div class="form-group">
                        <label for="pcantidad">Cantidad</label>
                        <input type="number" name="pcantidad" id="pcantidad" class="form-control" placeholder="cantidad...">
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <div class="form-group">
                        <label for="pprecio_compra">Precio Compra</label>
                        <input type="number" name="pprecio_compra" id="pprecio_compra" class="form-control"
                            placeholder="precio de compra...">
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <div class="form-group">
                        <label for="pprecio_venta">Precio Venta</label>
                        <input type="number" name="pprecio_venta" id="pprecio_venta" class="form-control"
                            placeholder="precio de venta...">
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="btn_add">agregar</button>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="detalles" class="table table-striped table-bordered condensed table-hover">
                            <thead style="background: #A9D0F5">
                                <th>Opciones</th>
                                <th>Art??culo</th>
                                <th>Cantidad</th>
                                <th>Precio Compra</th>
                                <th>Precio Venta</th>
                                <th>Subtotal</th>
                            </thead>
                            <tfoot>
                                <th colspan="5">Total</th>
                                {{-- <th></th>
                                <th></th>
                                <th></th>
                                <th></th> --}}
                                <th>
                                    <h4 id="total">$/. 0.00</h4>
                                </th>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="guardar">
            <div class="form-group">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    @push('scripts')
        <script>
            $(document).ready(function() {
                $("#btn_add").click(function() {
                    agregar();
                });
            });

            var cont = 0;
            total = 0;
            subtotal = [];
            $("#guardar").hide();

            function agregar() {
                idArticulo = $("#pidArticulo").val();
                articulo = $("#pidArticulo option:selected").text();
                cantidad = $("#pcantidad").val();
                precio_compra = $("#pprecio_compra").val();
                precio_venta = $("#pprecio_venta").val();
                console.log(idArticulo + "-" + articulo);

                if (idArticulo != "" && articulo != "" && cantidad != 0 && precio_compra != 0 && precio_venta != 0) {
                    subtotal[cont] = (cantidad * precio_compra);
                    total = total + subtotal[cont];

                    var fila = '<tr class="selected" id="fila' + cont +
                        '"><td><button type="button" class="btn btn-warning" onclick="eliminar(' + cont +
                        ')">x</button></td><td><input type="hidden" name="idArticulo[]"value="' + idArticulo + '">' + articulo +
                        '</td><td><input type="number" size="10" name="cantidad[]" value="' + cantidad +
                        '"></td><td><input type="number" size="10" name="precio_compra[]" value="' + precio_compra +
                        '"></td><td><input type="number" size="10" name="precio_venta[]" value="' + precio_venta + '"></td>' +
                        '<td>' + subtotal[cont] + '</td></tr>'
                    cont++;
                    limpiar();
                    $("#total").html("$/. " + total);
                    evaluar();
                    $("#detalles").append(fila);
                } else {
                    alert('Error al ingresar el detalle del ingreso, revise los datos del art??culo');
                }

            }

            function limpiar() {
                $("#pcantidad").val("");
                $("#pprecio_compra").val("");
                $("#pprecio_venta").val("");
            }

            function evaluar() {
                if (total > 0) {
                    $("#guardar").show();
                } else {
                    $("#guardar").hide();
                }
            }

            function eliminar(index) {
                total = total - subtotal[index];
                $("#total").html("$/. " + total);
                $("#fila" + index).remove();
                evaluar();
            }

        </script>
    @endpush
@endsection
