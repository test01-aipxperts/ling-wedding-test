@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Pick Product <a href="{{route('home')}}" class="btn btn-primary align-items-right">< Back</a></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('product.pick',\Crypt::encryptString($product->id)) }}" class="row" enctype="multipart/form-data"> 
                        @csrf
                        <div class="col-md-4 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-img-actions"> <img src="{{ $product->image }}" class="card-img img-fluid" width="96" height="350" alt=""> </div>
                                </div>
                                <div class="card-body bg-light text-center">
                                    <div class="mb-2">
                                        <h6 class="font-weight-semibold mb-2"> {{ $product->title }} </h6> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">

                            <div class="form-group row">
                                <label for="qty" class="col-md-4 col-form-label text-md-right">{{ __('Quatity') }}</label>

                                <div class="col-md-6">
                                    <input id="qty" type="number" class="form-control @error('qty') is-invalid @enderror" name="qty" value="{{ ($product->pickedup) ? $product->pickedup->qty : '' }}" required autocomplete="qty" autofocus min="1">

                                    @error('qty')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Price') }} /item ($)</label>

                                <div class="col-md-6">
                                    <input id="price" type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ ($product->pickedup) ? $product->pickedup->price : '' }}" required autocomplete="price" autofocus min="0.1" step="0.01">

                                    @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Pick') }}
                                </button>
                            </div>
                        </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
