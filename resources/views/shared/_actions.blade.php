
@can('edit_'.$entity)
    <a href="{{ route($entity.'.edit', [Str::singular($entity) => $id])  }}" class="btn btn-sm btn-info">
        <i class="fa fa-edit"></i> Sửa
    </a>
@endcan

@can('delete_'.$entity)
    <form action="{{ route($entity.'.destroy', ['user' => $id]) }}" method="POST" onsubmit="return confirm('Are yous sure wanted to delete it?')" style="display:inline;">
        <button type="submit" class="btn-delete btn btn-sm btn-danger">
            <i class="glyphicon glyphicon-trash"></i> Xoá
        </button>
    </form>
@endcan