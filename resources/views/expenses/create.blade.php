@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="d-flex align-items-center user-member__title mb-30 mt-30">
        <h4 class="text-capitalize">{{ trans('expense.add-expense') }}</h4>
      </div>
    </div>
  </div>
  <div class="card mb-50">
    <div class="row mx-5">
      <div class="col-12">
        <div class="mt-40 mb-50">
          <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="edit-profile__body">

              <div class="form-group mb-25">
                <label for="type" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('expense.type') }}
                </label>
                <select class="form-select ih-medium ip-gray radius-xs b-light px-15" name="type" id="type">
                  <option value="">---</option>
                  @foreach ($financialTypes as $financialType)
                  <option value="{{$financialType}}" {{ (old('type')==$financialType || request()?->o_type == $financialType) ? 'selected' : '' }}>
                    {{__('expense.'.$financialType)}}</option>
                  @endforeach
                </select>
                @if ($errors->has('type'))
                <p class="text-danger">{{ $errors->first('type') }}</p>
                @endif
              </div>



              <div class="form-group mb-25" style="display: none">
                <label for="client_id" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('client.clients') }}
                </label>
                <select class="form-select ih-medium ip-gray radius-xs b-light px-15" disabled name="client_id"
                  id="client_id">
                  <option value="">---</option>
                  @foreach ($clients as $client)
                  <option value="{{$client->id}}" {{ (old('client_id')==$client->id || request()?->client == $client->id) ? 'selected' : '' }}
                    >{{$client->name}} - {{$client->code}}</option>
                  @endforeach
                </select>
                @if ($errors->has('client_id'))
                <p class="text-danger">{{ $errors->first('client_id') }}</p>
                @endif
              </div>


              <div class="form-group mb-25" style="display: none">
                <label for="supplier_id" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('supplier.suppliers') }}
                </label>
                <select class="form-select ih-medium ip-gray radius-xs b-light px-15" disabled name="supplier_id"
                  id="supplier_id">
                  <option value="">---</option>
                  @foreach ($suppliers as $supplier)
                  <option value="{{$supplier->id}}" {{ (old('supplier_id')==$supplier->id || request()?->supplier == $supplier->id) ? 'selected' : ''
                    }}>{{$supplier->name}} - {{$supplier->code}}</option>
                  @endforeach
                </select>
                @if ($errors->has('supplier_id'))
                <p class="text-danger">{{ $errors->first('supplier_id') }}</p>
                @endif
              </div>

              <div class="form-group mb-25" style="display: none">
                <label for="shipping_company_id" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('company.companies') }}
                </label>
                <select class="form-select ih-medium ip-gray radius-xs b-light px-15" disabled name="shipping_company_id"
                  id="shipping_company_id">
                  <option value="">---</option>
                  @foreach ($companies as $company)
                  <option value="{{$company->id}}" {{ (old('shipping_company_id') == $company->id || request()?->company == $company->id) ? 'selected' : ''
                    }}>{{$company->name}} - {{$company->code}}</option>
                  @endforeach
                </select>
                @if ($errors->has('shipping_company_id'))
                <p class="text-danger">{{ $errors->first('shipping_company_id') }}</p>
                @endif
              </div>

              <div class="form-group mb-25">
                <label for="description" class="color-dark fs-14 fw-500 align-center">{{ trans('expense.description') }}
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="description"
                  value="{{ old('description') }}" id="description" placeholder="Description">
                @if ($errors->has('description'))
                <p class="text-danger">{{ $errors->first('description') }}</p>
                @endif
              </div>
              <div class="form-group mb-25">
                <label for="currency" class="color-dark fs-14 fw-500 align-center">{{ trans('expense.currency') }} <span
                    class="text-danger">*</span></label>
                <select class="form-select ih-medium ip-gray radius-xs b-light px-15" name="currency" id="currency">
                  <option value="usd" {{ (old('currency')=='usd' ) ? 'selected' : '' }}>USD</option>
                  <option value="rmb" {{ (old('currency')=='rmb' ) ? 'selected' : '' }}>RMB</option>
                </select>
                @if ($errors->has('currency'))
                <p class="text-danger">{{ $errors->first('currency') }}</p>
                @endif
              </div>
              <div class="form-group mb-25">
                <label for="amount" class="color-dark fs-14 fw-500 align-center">{{ trans('expense.amount') }} <span
                    class="text-danger">*</span></label>
                <input type="number" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="amount"
                  id="amount" value="{{ old('amount') }}" step="0.01">
                @if ($errors->has('amount'))
                <p class="text-danger">{{ $errors->first('code') }}</p>
                @endif
              </div>
              <div class="form-group mb-25" style="display: none">
                <label for="rate" class="color-dark fs-14 fw-500 align-center">{{ trans('expense.rate') }}
                </label>
                <input type="number" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="rate"
                  id="rate" value="{{ old('rate') }}" step="0.01">
                @if ($errors->has('rate'))
                <p class="text-danger">{{ $errors->first('rate') }}</p>
                @endif
              </div>
              <div class="form-group mb-25">
                <label for="date" class="color-dark fs-14 fw-500 align-center">
                  <span class="text-danger">*</span>
                  {{ trans('date') }}
                </label>
                <input type="date" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="date" id="date"
                  value="{{ old('date') }}">
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
    $('#shipping_company_id').select2();
    $('#supplier_id').select2();
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


  let triggerDollarRate = () => {

    let selectedCase = $('#type').val(), selectedValue = $('#currency').val();

    if(selectedCase === 'Payment to Supplier'){

      if (selectedValue === 'rmb') {
        $('#rate').attr('disabled', true).parent().hide(0);
        $('#otherCurrency').attr('disabled', true).parent().hide(0);
      } else {
        $('#rate').attr('disabled', false).parent().show(0);
        $('#otherCurrency').attr('disabled', false).parent().show(0);
      }
    }else if(selectedCase === 'Payment from Client' || selectedCase === 'Paid for Client'){
      if (selectedValue === 'usd') {
        $('#rate').attr('disabled', true).parent().hide(0);
        $('#otherCurrency').attr('disabled', true).parent().hide(0);
      } else {
        $('#rate').attr('disabled', false).parent().show(0);
        $('#otherCurrency').attr('disabled', false).parent().show(0);
      }
    }else{
      $('#rate').attr('disabled', true).parent().hide(0);
      $('#otherCurrency').attr('disabled', true).parent().hide(0);
    }

  }
      // Change event handler for the select dropdown
  $('#type').change(function () {
    // Get the selected value
    var selectedValue = $(this).val();

    // Check the selected value and enable/disable fields accordingly
    if (selectedValue === 'Rent' || selectedValue === 'Salary' || selectedValue === 'Commissions' || selectedValue === 'Other') {
      $('#client_id').attr('disabled', true).parent().hide(0);
      $('#supplier_id').attr('disabled', true).parent().hide(0)
      $('#shipping_company_id').attr('disabled', true).parent().hide(0)
      ;
    } else if(selectedValue === 'Payment from Client' || selectedValue === 'Paid for Client') {
      $('#client_id').attr('disabled', false).parent().show(0);
      $('#supplier_id').attr('disabled', true).parent().hide(0);
      $('#shipping_company_id').attr('disabled', true).parent().hide(0);
    }else if(selectedValue === 'Payment to Supplier') {
      $('#client_id').attr('disabled', true).parent().hide(0);
      $('#supplier_id').attr('disabled', false).parent().show(0);
      $('#shipping_company_id').attr('disabled', true).parent().hide(0);
    }else if(selectedValue === 'Payment to Shipping') {
      $('#client_id').attr('disabled', true).parent().hide(0);
      $('#supplier_id').attr('disabled', true).parent().hide(0);
      $('#shipping_company_id').attr('disabled', false).parent().show(0);
    }

    triggerDollarRate();
  }).trigger('change');
  $('#currency').change(function () {
    triggerDollarRate();
  }).trigger('change');

</script>
@endsection
