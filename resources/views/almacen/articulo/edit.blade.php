@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Editar Artículo: {{ $articulo->nombre }}</h3>
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
    {!! Form::model($articulo, ['method' => 'PATCH', 'route' => ['articulo.update', $articulo->idArticulo], 'files' => 'true']) !!}
    {!! Form::token() !!}
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" name="nombre" required value="{{ $articulo->nombre }}">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label>Categoria</label>
                <select name="idCategoria" class="form-control">
                    @foreach ($categorias as $categoria)
                        @if ($categoria->idCategoria == $articulo->idCategoria)
                            <option value="{{ $categoria->idCategoria }}" selected>{{ $categoria->nombre }}</option>
                        @else
                            <option value="{{ $categoria->idCategoria }}">{{ $categoria->nombre }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" class="form-control" name="codigo" required value="{{ $articulo->codigo }}">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="text" class="form-control" name="stock" required value="{{ $articulo->stock }}">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" class="form-control" name="descripcion" value="{{ $articulo->descripcion }}">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="imagen">Imagen</label>
                <input type="file" class="form-control" name="imagen">
                @if ($articulo->imagen != '')
                    <img src="{{ asset('imagenes/articulos/' . $articulo->imagen) }}" height="300px" width="300px"
                        class="img-thumbnail">
                @endif
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
