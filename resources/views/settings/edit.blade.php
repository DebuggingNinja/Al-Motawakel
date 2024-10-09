@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="d-flex align-items-center user-member__title mb-30 mt-30">
        <h4 class="text-capitalize">{{ trans('edit settings') }}</h4>
      </div>
    </div>
  </div>
  <div class="card mb-50">
    <div class="row ms-5 p-5">
      <div class="col-12">
        <form action="{{ route('settings.updateLogo') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
          <div class="d-flex">
            <div class="form-group">
              <label for="logo">{{ trans('logo') }}</label>
              <input class="form-control" type="file" name="logo" id="logo">
              @if ($errors->has('logo'))
              <p class="text-danger">{{ $errors->first('logo') }}</p>
              @endif
            </div>
            <button type="submit"
              class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
          </div>
        </form>
      </div>
      <div class="col-12 col-md-6">

      </div>
    </div>
    <div class="row ms-5 p-5">
      <div class="col-12">
        <form action="{{ route('settings.updateFileBanner') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
          <div class="d-flex">
            <div class="form-group">
              <label for="file_banner">{{ trans('File Banner') }}</label>
              <input class="form-control" type="file" name="file_banner" id="file_banner">
              @if ($errors->has('file_banner'))
              <p class="text-danger">{{ $errors->first('file_banner') }}</p>
              @endif
            </div>
            <button type="submit"
              class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
          </div>
        </form>
      </div>
      <div class="col-12 col-md-6">

      </div>
    </div>
    <div class="row ms-5 p-5">
      <div class="col-12">
        <form action="{{ route('settings.updatePrintBanner') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
          <div class="d-flex">
            <div class="form-group">
              <label for="print_banner">{{ trans('Print Banner') }}</label>
              <input class="form-control" type="file" name="print_banner" id="v">
              @if ($errors->has('file_banner'))
              <p class="text-danger">{{ $errors->first('print_banner') }}</p>
              @endif
            </div>
            <button type="submit"
              class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
          </div>
        </form>
      </div>
      <div class="col-12 col-md-6">

      </div>
    </div>
    <div class="row ms-5 p-5">
      <div class="col-12">
        <form action="{{ route('settings.updateYear') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
          <div class="d-flex">
            <div class="form-group">
              <label for="current_year">{{ trans('Current Year') }}</label>
              <input class="form-control" type="text" name="current_year" value="{{ $settings['year']??'' }}"
                id="current_year">
              @if ($errors->has('current_year'))
              <p class="text-danger">{{ $errors->first('current_year') }}</p>
              @endif
            </div>
            <button type="submit"
              class="mx-2 mt-2 btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
          </div>
        </form>
      </div>
      <div class="col-12 col-md-6">

      </div>
    </div>
  </div>
</div>
@endsection
