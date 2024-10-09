@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="d-flex align-items-center user-member__title mb-30 mt-30">
        <h4 class="text-capitalize">{{ trans('products.add') }}</h4>
      </div>
    </div>
  </div>
  <div class="card mb-50">
    <div class="row p-5">
      <div class="col-12">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-12 col-md-4">
              <div class="form-group mb-25">
                <label for="code" class="color-dark fs-14 fw-500 align-center">{{ trans('order.carton_code') }}
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="code"
                  id="code" value="{{ old('code') }}">
                @if ($errors->has('code'))
                <p class="text-danger">{{ $errors->first('code') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group mb-25">
                <label for="name" class="color-dark fs-14 fw-500 align-center">{{ trans('order.item') }}
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="name"
                  id="name" value="{{ old('name') }}">
                @if ($errors->has('name'))
                <p class="text-danger">{{ $errors->first('name') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group mb-25">
                <label for="pieces_number" class="color-dark fs-14 fw-500 align-center">{{ trans('order.pieces_number') }}
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="pieces_number"
                  id="pieces_number" value="{{ old('pieces_number') }}">
                @if ($errors->has('pieces_number'))
                <p class="text-danger">{{ $errors->first('pieces_number') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group mb-25">
                <label for="cbm" class="color-dark fs-14 fw-500 align-center">{{ trans('order.cbm') }}
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="cbm"
                  id="cbm" value="{{ old('cbm') }}">
                @if ($errors->has('cbm'))
                <p class="text-danger">{{ $errors->first('cbm') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group mb-25">
                <label for="weight" class="color-dark fs-14 fw-500 align-center">{{ trans('order.weight') }}
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="weight"
                  id="weight" value="{{ old('weight') }}">
                @if ($errors->has('weight'))
                <p class="text-danger">{{ $errors->first('weight') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group mb-25">
                <label for="measuring" class="color-dark fs-14 fw-500 align-center">{{ trans('order.measuring') }}
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="measuring"
                  id="measuring" value="{{ old('measuring') }}">
                @if ($errors->has('measuring'))
                <p class="text-danger">{{ $errors->first('measuring') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group mb-25">
                <label for="image" class="color-dark fs-14 fw-500 align-center">{{ trans('order.image') }}</label>
                <input type="file" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="image"
                  id="image" value="{{ old('image') }}">
                @if ($errors->has('image'))
                <p class="text-danger">{{ $errors->first('image') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="button-group d-flex pt-25 justify-content-md-end justify-content-stretch">
                <button type="submit"
                        class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>

</div>

@endsection
@section('scripts')
<script>
  $(document).ready(function() {
  // $('#repo').select2();
  // $('#broker').select2();
  // $('#company').select2();
  // $('#shipping_type').select2();
  });
</script>
@endsection
