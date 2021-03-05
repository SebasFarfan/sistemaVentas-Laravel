@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Editar Cliente: {{ $persona->nombre }}</h3>
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
    {!! Form::model($persona, ['method' => 'PATCH', 'route' => ['cliente.update', $persona->idPersona]]) !!}
    {!! Form::token() !!}
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" name="nombre" required value="{{ $persona->nombre }}"
                    placeholder="Nombre...">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" class="form-control" name="direccion" value="{{ $persona->direccion }}"
                    placeholder="Dirección...">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label>Documento</label>
                <select name="tipo_documento" class="form-control">
                    @if ($persona->tipo_documento == 'DNI')
                        <option value="DNI" selected>DNI</option>
                        <option value="LC">LC</option>
                        <option value="CI">CI</option>
                    @elseif ($persona->tipo_documento=='LC')
                        <option value="DNI">DNI</option>
                        <option value="LC" selected>LC</option>
                        <option value="CI">CI</option>
                    @else
                        <option value="DNI">DNI</option>
                        <option value="LC">LC</option>
                        <option value="CI" selected>CI</option>
                    @endif

                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="nro_documento">Número de Documento</label>
                <input type="text" class="form-control" name="nro_documento" value="{{ $persona->nro_documento }}"
                    placeholder="Número de Documento...">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" class="form-control" name="telefono" value="{{ $persona->telefono }}"
                    placeholder="Teléfono...">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" value="{{ $persona->email }}" placeholder="Email...">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
        </div>
    </div>

    {!! Form::close() !!}
@endsection
