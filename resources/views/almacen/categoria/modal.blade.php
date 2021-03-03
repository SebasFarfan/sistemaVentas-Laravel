<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-{{$categoria->idCategoria}}">
    {!! Form::open(array('action'=>array('CategoriaController@destroy', $categoria->idCategoria))) !!}
    {!! Form::hidden('_method', 'delete') !!} {{--linea agregada  --}}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Eliminar Categoria</h4>
            </div>
            <div class="modal-body">
                <p>Confirme si desea eliminar categoría</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary" type="submit">Confirmar</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
