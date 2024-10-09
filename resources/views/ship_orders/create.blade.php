@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="d-flex align-items-center user-member__title mb-30 mt-30">
        <h4 class="text-capitalize">{{ trans('order.add-order') }}</h4>
      </div>
    </div>
  </div>
  <div class="card mb-50">
    <div class="row p-5">
      <div class="col-12">
        <form action="{{ route('ship_orders.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="form-group mb-25">
                <label for="client" class="color-dark fs-14 fw-500 align-center">
                  {{trans('client.clients')}}
                  <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="client" id="client">
                  <option value="">---</option>
                  @foreach ($clients as $client)
                  <option value="{{$client->id}}">{{$client->name}} - {{$client->code}}</option>
                  @endforeach
                </select>
                @if ($errors->has('client'))
                <p class="text-danger">{{ $errors->first('client') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group mb-25">
                <label for="repo" class="color-dark fs-14 fw-500 align-center">
                  {{trans('repo.repos')}}
                  <span class="text-danger">*</span>
                </label>
                <select class="form-select " name="repo" id="repo">
                  <option value="">---</option>
                  @foreach ($repos as $repo)
                  <option value="{{$repo->id}}">{{$repo->name.' - '.$repo->code}}</option>
                  @endforeach
                </select>
                @if ($errors->has('repo'))
                <p class="text-danger">{{ $errors->first('repo') }}</p>
                @endif
              </div>
            </div>
{{--            <div class="col-12 col-md-3">--}}
{{--              <div class="form-group mb-25">--}}
{{--                <label for="registery" class="color-dark fs-14 fw-500 align-center">{{trans('order.registery')}} <span--}}
{{--                    class="text-danger">*</span></label>--}}
{{--                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="registery"--}}
{{--                  id="registery" value="{{old('registery')}}">--}}
{{--                @if ($errors->has('registery'))--}}
{{--                <p class="text-danger">{{ $errors->first('registery') }}</p>--}}
{{--                @endif--}}
{{--              </div>--}}
{{--            </div>--}}
{{--            <div class="col-12 col-md-3">--}}
{{--              <div class="form-group mb-25">--}}
{{--                <label for="paper" class="color-dark fs-14 fw-500 align-center">{{ trans('order.paper') }}--}}
{{--                  <span class="text-danger">*</span>--}}
{{--                </label>--}}
{{--                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="paper"--}}
{{--                  id="paper" value="{{ old('paper') }}">--}}
{{--                @if ($errors->has('paper'))--}}
{{--                <p class="text-danger">{{ $errors->first('paper') }}</p>--}}
{{--                @endif--}}
{{--              </div>--}}
{{--            </div>--}}
            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-stretch">
              <button type="submit"
                class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
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
    $('#client').select2();
    $('#repo').select2();
});
</script>

@endsection
