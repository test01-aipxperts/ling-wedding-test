@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Products <a href="{{route('admin.products.create')}}" class="btn btn-primary align-items-right">Create New</a></div>

                <div class="card-body">
                   @if (session('success'))
                   <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-error" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th class="th-sm">Product</th>
                      <th class="th-sm">Status</th>
                      <th class="th-sm">Action</th>
                  </tr>
              </thead>
              <tbody>
                @foreach($products as $product)
                <tr>
                  <td>                        
                    <div class="d-flex align-items-center"><img class="" src="{{$product->image}}" width="30"><span class="ml-2">{{$product->title}}</span></div>
                </td>
                <td>
                    @if($product->status)
                    <label class="badge badge-success">Active</label>
                    @else
                    <label class="badge badge-danger">In-active</label>
                    @endif
                </td>
                <td>
                    <a href="{{route('admin.products.edit',$product->id)}}" class="btn btn-info">Edit</a> | 
                    <form action="{{route('admin.products.destroy',$product->id)}}" class="inline-frm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button  onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger">Delete</button>
                    </form>
                </td>
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
