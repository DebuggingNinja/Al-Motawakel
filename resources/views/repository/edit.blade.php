@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="d-flex align-items-center user-member__title mb-30 mt-30">
        <h4 class="text-capitalize">{{ trans('repo.update-repo') }}</h4>
      </div>
    </div>
  </div>
  <div class="card mb-50">
    <div class="row ms-5">
      <div class="col-sm-5 col-10">
        <div class="mt-40 mb-50">
          <form action="{{ route('repositories.update', $repo->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="edit-profile__body">

              <div class="form-group mb-25">
                <label for="name" class="color-dark fs-14 fw-500 align-center">{{trans('company.name')}} <span
                    class="text-danger">*</span></label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="name"
                  value="{{$repo->name . ' - ' . $repo->code}}" id="name" placeholder="Name">
                @if ($errors->has('name'))
                <p class="text-danger">{{ $errors->first('name') }}</p>
                @endif
              </div>

              <div class="form-group mb-25">
                <label for="address" class="color-dark fs-14 fw-500 align-center">
                  {{trans('repo.address')}}
                </label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="address"
                  id="address" value="{{$repo->address}}" placeholder="Repository Address">
                @if ($errors->has('address'))
                <p class="text-danger">{{ $errors->first('address') }}</p>
                @endif
              </div>

              <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                <button type="submit"
                  class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
              </div>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection