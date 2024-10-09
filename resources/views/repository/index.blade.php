@section('title', $title)
@section('description', $description)
@section('style')
<style>
  tr.selected {
    background-color: var(--bs-green);
  }
</style>
@endsection
@extends('layout.app')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="contact-breadcrumb">
        <div class="breadcrumb-main add-contact justify-content-sm-between ">
          <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
            <div class="d-flex align-items-center add-contact__title justify-content-center me-sm-25">
              <h4 class="text-capitalize fw-500 breadcrumb-title">{{ trans('repo.repos') }}
              </h4>
              <span class="sub-title ms-sm-25 ps-sm-25"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card p-5">
        <form action="{{route('repository.current_items')}}" id="select_repo" method="POST">
          @csrf
          @method('POST')
          <div id="select-repo" class="form-group">
            <label for="repo_id">
              {{trans('repo.name')}}
            </label>
            <select class="form-select" name="repo_id" id="repo_id">
              <option value="">Select a Warehouse</option>
              @foreach ( $repos as $repo)
              <option value="{{$repo->id}}">{{$repo->name.' - '.$repo->code}}</option>
              @endforeach
            </select>
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
      $('#repo_id').select2();
      $('#repo_id').on('change', function() {
        // Trigger form submission when the value changes
        $('#select_repo').submit();
      });
    });
</script>
@endsection
