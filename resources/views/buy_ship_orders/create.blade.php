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
        <form action="{{ route('buy_ship_orders.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-12 col-md-4">
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
            <div class="col-12 col-md-4">
              <div class="form-group mb-25">
                <label for="supplier" class="color-dark fs-14 fw-500 align-center">
                  {{trans('supplier.suppliers')}}
                  <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="supplier" id="supplier">
                  <option value="">---</option>
                  @foreach ($suppliers as $supplier)
                  <option value="{{$supplier->id}}">{{$supplier->name}} - {{$supplier->code}}</option>
                  @endforeach
                </select>
                @if ($errors->has('supplier'))
                <p class="text-danger">{{ $errors->first('supplier') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group mb-25">
                <label for="repo" class="color-dark fs-14 fw-500 align-center">
                  {{trans('repo.repos')}}
                  <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="repo" id="repo">
                  <option value="">---</option>
                  @foreach ($repos as $repo)
                  <option value="{{$repo->id}}">{{$repo->name . ' - ' . $repo->code}} </option>
                  @endforeach
                </select>
                @if ($errors->has('repo'))
                <p class="text-danger">{{ $errors->first('repo') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group mb-25">
                <label for="check_date" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('order.check_date') }}
                  <span class="text-danger">*</span>
                </label>
                <input class="form-control " type="date" name="check_date" id="check_date">
                @if ($errors->has('check_date'))
                  <p class="text-danger">{{ $errors->first('check_date') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group mb-25">
                <label for="dollar_price" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('statement.enter_dollar_rate') }}
                  <span class="text-danger">*</span>
                </label>
                <input class="form-control " type="text" name="dollar_price" id="dollar_price">
                @if ($errors->has('dollar_price'))
                  <p class="text-danger">{{ $errors->first('dollar_price') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-8">
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
@include('buy_ship_orders.components.createSupplierModal')
@endsection
@section('scripts')
<script>
  $(document).ready(function() {
    $('#client').select2();
    $('#repo').select2();

    $('#supplier').select2(
      {
        allowClear: true,
        placeholder: 'This is my placeholder',
        language: {
          noResults: function() {
            return `<button style="width: 100%;font-size:12px" type="button"
             class="btn btn-primary"
             onClick='createSupplier()'>Add Supplier</button>
             </li>`;
          }
        },

        escapeMarkup: function (markup) {
          return markup;
        }
      }
    );

  $('#submitCreateSupplierForm').on('click', function (e) {
    e.preventDefault(); // Prevent the default form submission
    storeSupplierAndUpdateSelects(); // Call the function to handle form submission
  });
});
  function createSupplier(){
    $('#createSupplierModal').modal('show');
  }
  function updateSelect2Options(data) {
    // Assuming 'mySelect2' is the ID of your Select2 element
    var select2 = $('#supplier');

    // Clear existing options
    select2.empty();

    // Add new options based on the response data
    $.each(data.suppliers, function(index, supplier) {
      var option = new Option(supplier.name + ' - ' + supplier.code, supplier.id);
      // Add additional attributes to the option
      $(option).attr({
        'data-store-number': supplier.store_number,
        'data-phone': supplier.phone
        // Add more attributes as needed
      });

      select2.append(option);
    });

    // Trigger change event to refresh Select2
    select2.trigger('change');
  }
  function storeSupplierAndUpdateSelects(){
    $.ajax({
      type: 'POST',
      url: $('#createSupplierForm').attr('action'),
      data: $('#createSupplierForm').serialize(),
      success: function(response) {
        if (response.success) {
          toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "swing",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
          };
          toastr["success"](response.message);
          console.log(response);
          updateSelect2Options(response);
          $('#createSupplierModal').modal('hide');
        } else {
          alert('Failed to create supplier.'); // Display error message
        }
      },
      error: function(xhr, status, error) {
        console.error('AJAX request failed:', status, error);
        alert('Failed to create supplier.'); // Display error message
      }
    });
  }
</script>

@endsection
