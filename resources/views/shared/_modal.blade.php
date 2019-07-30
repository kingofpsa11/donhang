<form action="{{ route($routeName. '.destroy', $model) }}" method="POST">
    @csrf()
    @method('DELETE')
    <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="custom-width-modalLabel">{{ ucfirst($modelName) }}</h4>
                </div>
                <div class="modal-body">
                    <h5>Chắc chắn xóa {{ $modelName }} số {{ $modelInformation}} ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-dismiss="modal">Hủy</button>
                    <input type="submit" class="btn btn-danger waves-effect waves-light" value="Xóa">
                </div>
            </div>
        </div>
    </div>
</form>