@extends('product.form')

@section('route')
    {{ route('product.update', [$product]) }}
@endsection

@section('form-content')
    <div class="form-group">
        <label for="" class="col-md-3 control-label">Nhóm</label>
        <select class="form-control category" style="width: 100%;" name="product[category_id]" required>
            <option value="">--Chọn nhóm sản phẩm--</option>
            @foreach($categories as $category)
                @if($category->id === $product->category_id)
                    <option value="{{ $category->id }}" selected="selected">{{ $category->name }}</option>
                @else
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="" class="col-md-3 control-label">Mã sản phẩm</label>
        <input type="text" class="form-control" name="product[code]" value="{{ $product->code }}" required>
    </div>
    <div class="form-group">
        <label for="" class="col-md-3 control-label">Tên sản phẩm</label>
        <input type="text" class="form-control" name="product[name]" value="{{ $product->name }}" required>
    </div>
    <div class="form-group">
        <label for="" class="col-md-3 control-label">Ghi chú</label>
        <textarea id="" class="form-control" name="product[note]"></textarea>
    </div>
@endsection
