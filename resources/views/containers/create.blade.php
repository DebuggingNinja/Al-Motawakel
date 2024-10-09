@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="d-flex align-items-center user-member__title mb-30 mt-30">
        <h4 class="text-capitalize">{{ trans('container.add-container') }}</h4>
      </div>
    </div>
  </div>
  <div class="card mb-50">
    <div class="row p-5">
      <div class="col-12">
        <form action="{{ route('containers.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-12 col-md-3">
              <div class="form-group mb-25">
                <label for="broker" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('broker.brokers') }}
                  <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="broker" id="broker">
                  <option value="">---</option>
                  @foreach ($brokers as $broker)
                  <option value="{{ $broker->id }}">{{ $broker->name }} - {{$broker->code}}</option>
                  @endforeach
                </select>
                @if ($errors->has('broker'))
                <p class="text-danger">{{ $errors->first('broker') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group mb-25">
                <label for="client" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('client.clients') }}
                  <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="client" id="client">
                  <option value="">---</option>
                  @foreach ($clients as $client)
                  <option value="{{ $client->id }}">{{ $client->name }} - {{$client->code}}</option>
                  @endforeach
                </select>
                @if ($errors->has('client'))
                <p class="text-danger">{{ $errors->first('client') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group mb-25">
                <label for="repo" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('repo.repos') }}
                  <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="repo" id="repo">
                  <option value="">---</option>
                  @foreach ($repos as $repo)
                  <option value="{{ $repo->id }}">{{$repo->name . ' - ' . $repo->code}}</option>
                  @endforeach
                </select>
                @if ($errors->has('repo'))
                <p class="text-danger">{{ $errors->first('repo') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group mb-25">
                <label for="company" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('company.companies') }}
                  <span class="text-danger">*</span>
                </label>
                <select class="form-select " name="company" id="company">
                  <option value="">---</option>
                  @foreach ($companies as $company)
                  <option value="{{ $company->id }}">{{ $company->name }}</option>
                  @endforeach
                </select>
                @if ($errors->has('company'))
                <p class="text-danger">{{ $errors->first('company') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group mb-25">
                <label for="company" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('container.shipping_type') }}
                  <span class="text-danger">*</span>
                </label>
                <select class="form-select " name="shipping_type" id="shipping_type">
                  <option value="">---</option>
                  <option value="complete">complete</option>
                  <option value="partial">partial</option>
                </select>
                @if ($errors->has('shipping_type'))
                <p class="text-danger">{{ $errors->first('shipping_type') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group mb-25">
                <label for="number" class="color-dark fs-14 fw-500 align-center">{{ trans('container.number') }}
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="number"
                  id="number" value="{{ old('number') }}">
                @if ($errors->has('number'))
                <p class="text-danger">{{ $errors->first('number') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group mb-25">
                <label for="lock_number" class="color-dark fs-14 fw-500 align-center">{{ trans('container.lock_number')
                  }}
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="lock_number"
                  id="lock_number" value="{{ old('lock_number') }}">
                @if ($errors->has('lock_number'))
                <p class="text-danger">{{ $errors->first('lock_number') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group mb-25">
                <label for="destination" class="color-dark fs-14 fw-500 align-center">{{ trans('container.destination')
                  }}
                  <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="destination"
                  id="destination" value="{{ old('destination') }}">
                @if ($errors->has('destination'))
                <p class="text-danger">{{ $errors->first('destination') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group mb-0 form-group-calender">
                <label for="est_arrive_date" class="color-dark fs-14 fw-500 align-center">{{
                  trans('container.est_arrive_date') }}
                  <span class="text-danger">*</span>
                </label>
                <input type="date" class="form-control form-control-lg" id="est_arrive_date" name="est_arrive_date">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group mb-0 form-group-calender">
                <label for="cost_rmb" class="color-dark fs-14 fw-500 align-center">{{
                  trans('container.cost_rmb') }}
                </label>
                <input type="text" class="form-control form-control-lg" id="cost_rmb" name="cost_rmb">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group mb-0 form-group-calender">
                <label for="cost_dollar" class="color-dark fs-14 fw-500 align-center">{{
                  trans('container.cost_dollar') }}
                </label>
                <input type="text" class="form-control form-control-lg" id="cost_dollar" name="cost_dollar">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group mb-0 form-group-calender">
                <label for="back_rmb" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('Back RMB') }}
                </label>
                <input type="text" class="form-control form-control-lg" id="back_rmb" name="back_rmb">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-group mb-0 form-group-calender">
                <label for="back_rmb" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('statement.dollar_rate') }}
                </label>
                <input type="text" class="form-control form-control-lg" id="dollar_price" name="dollar_price">
              </div>
            </div>
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
  $('#repo').select2();
  $('#broker').select2();
  $('#client').select2();
  $('#company').select2();
  $('#shipping_type').select2();
  });
</script>
@endsection
