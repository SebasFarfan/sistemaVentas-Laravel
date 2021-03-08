@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Nueva Venta</h3>
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
    {!! Form::open(['url' => 'ventas/venta', 'method' => 'POST', 'autocomplete' => 'off']) !!}
    {!! Form::token() !!}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="cliente">Cliente</label>
                <select name="idCliente" id="idCliente" class="form-control selectpicker" data-Live-search="true">
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
                <label for="num_comprobante">Número Comprobante</label>
                <input type="text" class="form-control" name="num_comprobante" value="{{ old('num_comprobante') }}"
                    placeholder="Nro. de comprobante...">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Artículo</label>
                    <select name="pidArticulo" id="pidArticulo" class="form-control selectpicker" data-Live-search="true">
                        @foreach ($articulos as $articulo)
                            <option
                                value="{{ $articulo->idArticulo }}_{{ $articulo->stock }}_{{ $articulo->precio_promedio }}">
                                {{ $articulo->articulo }}</option>
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
                        <label for="pstock">Stock</label>
                        <input type="number" disabled name="pstock" id="pstock" class="form-control" placeholder="stock...">
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <div class="form-group">
                        <label for="pprecio_venta">Precio Venta</label>
                        <input type="number" disabled name="pprecio_venta" id="pprecio_venta" class="form-control"
                            placeholder="precio de venta...">
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <div class="form-group">
                        <label for="pdescuento">Descuento</label>
                        <input type="number" name="pdescuento" id="pdescuento" class="form-control"
                            placeholder="descuento...">
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
                                <th>Artículo</th>
                                <th>Cantidad</th>
                                <th>Precio Venta</th>
                                <th>Descuento</th>
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
                                    <input type="hidden" name="total_venta" id="total_venta">
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
            $("#pidArticulo").change(mostrarValores);

            function mostrarValores() {
                datosArticulo = document.getElementById('pidArticulo').value.split('_');
                $('#pprecio_venta').val(datosArticulo[2]);
                $('#pstock').val(datosArticulo[1]);

            }

            function agregar() {
                datosArticulo = document.getElementById('pidArticulo').value.split('_');

                idArticulo = datosArticulo[0];
                articulo = $("#pidArticulo option:selected").text();
                cantidad = $("#pcantidad").val();

                descuento = $("#pdescuento").val();
                precio_venta = $("#pprecio_venta").val();
                stock = $("#pstock").val();


                console.log(idArticulo + "-" + articulo);
                console.log(idArticulo+"-"+articulo+"-"+cantidad+"-"+descuento+"-"+precio_venta);

                if (idArticulo != "" && articulo != "" && cantidad != 0 && cantidad > 0 && descuento != "" && precio_venta !=
                    0) {
                    if (stock >= cantidad) {
                        subtotal[cont] = (cantidad * precio_venta-descuento);
                        total = total + subtotal[cont];

                        var fila = '<tr class="selected" id="fila' + cont +
                            '"><td><button type="button" class="btn btn-warning" onclick="eliminar(' + cont +
                            ')">x</button></td><td><input type="hidden" name="idArticulo[]"value="' + idArticulo + '">' +
                            articulo +
                            '</td><td><input type="number" size="10" name="cantidad[]" value="' + cantidad +
                            '"></td><td><input type="number" size="10" name="precio_venta[]" value="' + precio_venta +
                            '"></td><td><input type="number" size="10" name="descuento[]" value="' + descuento +
                            '"></td>' +
                            '<td>' + subtotal[cont] + '</td></tr>'
                        cont++;
                        limpiar();
                        $("#total").html("$/. " + total);
                        $("#total_venta").val(total);
                        evaluar();
                        $("#detalles").append(fila);

                    }else{
                        alert("la cantidad a vender supera el stock");
                    }
                } else {
                    alert('Error al ingresar el detalle de la venta, revise los datos del artículo');
                }

            }

            function limpiar() {
                $("#pcantidad").val("");
                $("#pdescuento").val("");
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
                $("#total_venta").val(total);
                $("#fila" + index).remove();
                evaluar();
            }

        </script>
    @endpush
@endsection
