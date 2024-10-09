@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="d-flex align-items-center user-member__title mb-30 mt-30">
        <h4 class="text-capitalize">{{ trans('expense.update-expense') }}</h4>
      </div>
    </div>
  </div>
  <div class="card mb-50">
    <div class="row ms-5">
      <div class="col-sm-5 col-10">
        <div class="mt-40 mb-50">
          <form action="{{ route('expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="edit-profile__body">

              <div class="form-group mb-25">
                <label for="currency" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('expense.type') }}
                </label>
                <select class="form-select ih-medium ip-gray radius-xs b-light px-15" name="type" id="type">
                  <option value="">---</option>
                  @foreach ($financialTypes as $financialType)
                  <option value="{{$financialType}}">{{__('expense.'.$financialType)}}</option>
                  @endforeach
                </select>
                @if ($errors->has('type'))
                <p class="text-danger">{{ $errors->first('type') }}</p>
                @endif
              </div>
              <div class="form-group mb-25">
                <label for="description" class="color-dark fs-14 fw-500 align-center">{{ trans('expense.description') }}
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="description"
                  value="{{ $expense->description }}" id="description" placeholder="Description">
                @if ($errors->has('description'))
                <p class="text-danger">{{ $errors->first('description') }}</p>
                @endif
              </div>
              <div class="form-group mb-25">
                <label for="currency" class="color-dark fs-14 fw-500 align-center">{{ trans('expense.currency') }} <span
                    class="text-danger">*</span></label>
                <select class="form-select ih-medium ip-gray radius-xs b-light px-15" name="currency" id="currency">
                  <option value="rmb" {{ $expense->currency == 'rmb' ? 'selected' : '' }}>RMB</option>
                  <option value="usd" {{ $expense->currency == 'usd' ? 'selected' : '' }}>USD</option>
                </select>
                @if ($errors->has('currency'))
                <p class="text-danger">{{ $errors->first('currency') }}</p>
                @endif
              </div>
              <div class="form-group mb-25">
                <label for="amount" class="color-dark fs-14 fw-500 align-center">{{ trans('expense.amount') }} <span
                    class="text-danger">*</span></label>
                <input type="number" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="amount"
                  id="amount" value="{{ $expense->amount }}" step="0.01">
                @if ($errors->has('amount'))
                <p class="text-danger">{{ $errors->first('code') }}</p>
                @endif
              </div>
              @php

              if ($expense->rate == 0) {
              $rate = 1;
              }else {
              $rate = $expense->rate;
              }
              @endphp
              <div class="form-group mb-25">
                <label for="rate" class="color-dark fs-14 fw-500 align-center">{{ trans('expense.rate') }}
                  <span class="text-danger"></span>
                </label>
                <input type="number" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="rate"
                  id="rate" value="{{ $rate }}" step="0.01">
                @if ($errors->has('rate'))
                <p class="text-danger">{{ $errors->first('rate') }}</p>
                @endif
              </div>
              <div class="form-group mb-25">
                <label for="otherCurrency" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('expense.amount') }}
                </label>

                <input type="number" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="otherCurrency"
                  id="otherCurrency" step="0.01" readonly
                  value="{{$expense->currency == 'usd' ? $expense->amount * $expense->rate : $expense->amount / $rate }}">
              </div>

              <div class="form-group mb-25">
                <label for="date" class="color-dark fs-14 fw-500 align-center">
                  <span class="text-danger">*</span>
                  {{ trans('date') }}
                </label>
                <input type="date" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="date" id="date"
                  value="{{ $expense->date }}">
                @if ($errors->has('date'))
                <p class="text-danger">{{ $errors->first('date') }}</p>
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
@section('scripts')
<script>
  $(document).ready(function() {
      $('#client_id').select2();
      // Function to update otherCurrency based on currency selection
      function updateOtherCurrency() {
        // Get selected currency value
        var currency = $('#currency').val();

        // Get amount value
        var amount = parseFloat($('#amount').val());

        // Get rate value
        var rate = parseFloat($('#rate').val());

        // Calculate otherCurrency based on currency selection
        var otherCurrency = (currency === 'usd') ? amount * rate : (currency === 'rmb') ? amount / rate : '';

        // Update the otherCurrency input field
        $('#otherCurrency').val(otherCurrency.toFixed(2));
      }

      // Attach the updateOtherCurrency function to currency, amount, and rate change events
      $('#currency, #amount, #rate').on('change input', updateOtherCurrency);

      // Trigger the updateOtherCurrency function initially
      updateOtherCurrency();
    });
</script>
@endsection