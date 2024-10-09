@section('title', $title)
@section('description', $description)
@section('style')
  <style>
    .product-row{
      flex-wrap: wrap;
    }
    .check-image{
      margin-top: 20px;
    }
    .image-modal{
      padding: 0;
      position: relative;
    }
    .image-modal .btn-close{
      position: absolute;
      top: 10px;
      right: 10px;
    }
    .int_num{
      width: fit-content;
      display: inline-block;
      position: absolute;
      top: 12px;
      left: 50px;
      font-size: 24px;
      color: #000;
    }
    .badge{
      border-radius: 5px;
      height: 28px;
      line-height: 28px;
      font-size: 14px;
    }
    .badge-requested{
      background-color: var(--bg-warning)
    }
    .badge-checked{
      background-color: var(--bg-info)
    }
    .badge-waiting{
      background-color: var(--bg-danger)
    }
    .badge-received{
      background-color: var(--bg-success)
    }
    .badge-shipped{
      background-color: var(--bg-dark);
    }
    .badge-cancelled{
      background-color: var(--bg-primary)
    }
  </style>
@endsection
@extends('layout.app')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex align-items-center user-member__title mb-30 mt-30">
          <h4 class="text-capitalize">{{ trans('order.update-order') }}</h4>
        </div>
      </div>
    </div>
    <div class="card mb-50">
      <div class="row p-5">
        <div class="col-12">
          <form action="{{ route('buy_ship_orders.update', $order->id) }}"
                method="POST" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="row">
              <div class="col-12 col-md-6">
                <h6 class="mt-3">{{ trans('id') }} : <span dir="ltr">{{ $order->code }}</span></h6>
              </div>
              <div class="col-12 col-md-6">
                <h6 class="mt-3">{{ trans('created_at') }} : {{ date('d-m-Y', strtotime($order->created_at)) }}</h6>
              </div>
              <div class="col-12">
                <br>
              </div>
              <div class="col-12 col-md-4">
                <div class="form-group mb-25">
                  <label for="client" class="color-dark fs-14 fw-500 align-center">
                    {{ trans('client.clients') }}
                    <span class="text-danger">*</span>
                  </label>
                  <select class="form-select" name="client" id="client">
                    <option value="">---</option>
                    @foreach ($clients as $client)
                      <option value="{{ $client->id }}" {{ $order->client_id == $client->id ? 'selected' : '' }}>
                        {{ $client->name }} - {{ $client->code }}</option>
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
                    {{ trans('supplier.suppliers') }}
                    <span class="text-danger">*</span>
                  </label>
                  <select class="form-select" name="supplier" id="supplier">
                    <option value="">---</option>
                    @foreach ($suppliers as $supplier)
                      <option value="{{ $supplier->id }}" {{ $order->supplier_id == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }} - {{ $supplier->code }}</option>
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
                    {{ trans('repo.repos') }}
                    <span class="text-danger">*</span>
                  </label>
                  <select class="form-select " name="repo" id="repo">
                    <option value="">---</option>
                    @foreach ($repos as $repo)
                      <option value="{{ $repo->id }}" {{ $order->repo->id == $repo->id ? 'selected' : '' }}>
                        {{$repo->name . ' - ' . $repo->code}}</option>
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
                  <input class="form-control " value="{{$order->check_date}}" type="date" name="check_date" id="check_date">
                  @if ($errors->has('check_date'))
                    <p class="text-danger">{{ $errors->first('check_date') }}</p>
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


    @can('view buy ship items')
      <div class="row">
        <div class="col-lg-12">
          <div class="d-flex align-items-center justify-content-between user-member__title mb-30 mt-30">
            <h4 class="text-capitalize">{{ trans('order.items') }}</h4>
            <div class="d-flex align-items-center justify-content-end gap-2">
              <button id="print" class="btn btn-primary">print</button>
              @can('add buy ship items')
                <button id="add-item" class="btn btn-primary" style="position: fixed; bottom:20px; z-index:12">{{
            trans('order.add-item') }}</button>
              @endcan
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-12">
          <label for="allTotal">
            <span>{{ trans('total') }}</span>
            <input class="form-control" id="allTotal" type="text" readonly />
          </label>
        </div>
        <div class="col-12 col-md-4">
          <div class="form-group mb-25">
            <label for="item-status-all" class="color-dark fs-14 fw-500 align-center">
              {{ trans('order.status') }}
            </label>
            <select class="form-control" name="status" id="item-status-all">
              <option value="requested">requested</option>
              <option value="checked">checked</option>
              <option value="waiting">waiting</option>
              <option value="received">received</option>
              <option value="cancelled">cancelled</option>
            </select>

          </div>
        </div>
        <div class="col-12 col-md-4">
            <button class="btn btn-primary save-all-items" type="button">
                {{trans('order.save_all')}}
            </button>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-12">
          <div class="form-check">
            <input id="select-all" type="checkbox" class="form-check-input">
            <label for="select-all" class="form-check-label">{{ trans('check all') }}</label>
          </div>
        </div>
      </div>
      <div class="row mb-3" id="check-columns">
        <div class="col-12">
          <div class="d-flex gap-2 flex-wrap">
            <div class="form-check">
              <input id="carton_code" type="checkbox" class="form-check-input">
              <label for="carton_code" class="form-check-label">{{ trans('order.carton_code') }}</label>
            </div>
            <div class="form-check">
              <input id="item" type="checkbox" class="form-check-input">
              <label for="item" class="form-check-label">{{ trans('order.item') }}</label>
            </div>
            <div class="form-check">
              <input id="carton_quantity" type="checkbox" class="form-check-input">
              <label for="carton_quantity" class="form-check-label">{{ trans('order.carton_quantity') }}</label>
            </div>
            <div class="form-check">
              <input id="pieces_number" type="checkbox" class="form-check-input">
              <label for="pieces_number" class="form-check-label">{{ trans('order.pieces_number') }}</label>
            </div>
            <div class="form-check">
              <input id="single_price" type="checkbox" class="form-check-input">
              <label for="single_price" class="form-check-label">{{ trans('order.single_price') }}</label>
            </div>
            <div class="form-check">
              <input id="total" type="checkbox" class="form-check-input">
              <label for="total" class="form-check-label">{{ trans('order.total') }}</label>
            </div>
            <div class="form-check">
              <input id="receive_date" type="checkbox" class="form-check-input">
              <label for="receive_date" class="form-check-label">{{ trans('order.receive_date') }}</label>
            </div>
            <div class="form-check">
              <input id="cbm" type="checkbox" class="form-check-input">
              <label for="cbm" class="form-check-label">{{ trans('order.cbm') }}</label>
            </div>
            <div class="form-check">
              <input id="weight" type="checkbox" class="form-check-input">
              <label for="weight" class="form-check-label">{{ trans('order.weight') }}</label>
            </div>
            <div class="form-check">
              <input id="status" type="checkbox" class="form-check-input">
              <label for="status" class="form-check-label">{{ trans('order.status') }}</label>
            </div>
            <div class="form-check">
              <input id="phone" type="checkbox" class="form-check-input">
              <label for="phone" class="form-check-label">{{ trans('order.phone') }}</label>
            </div>
            <div class="form-check">
              <input id="store_number" type="checkbox" class="form-check-input">
              <label for="store_number" class="form-check-label">{{ trans('order.store_number') }}</label>
            </div>
            <div class="form-check">
              <input id="mark" type="checkbox" class="form-check-input">
              <label for="mark" class="form-check-label">{{ trans('client.mark') }}</label>
            </div>
            <div class="form-check">
              <input id="container_number" type="checkbox" class="form-check-input">
              <label for="container_number" class="form-check-label">{{ trans('order.container_number') }}</label>
            </div>
            <div class="form-check">
              <input id="check_notes" type="checkbox" class="form-check-input">
              <label for="check_notes" class="form-check-label">{{ trans('order.check_notes') }}</label>
            </div>
            <div class="form-check">
              <input id="receive_notes" type="checkbox" class="form-check-input">
              <label for="receive_notes" class="form-check-label">{{ trans('order.receive_notes') }}</label>
            </div>
            <div class="form-check">
              <input id="cancelled_notes" type="checkbox" class="form-check-input">
              <label for="cancelled_notes" class="form-check-label">{{ trans('order.cancelled_notes') }}</label>
            </div>

          </div>
        </div>
      </div>

    @endcan
    <div id="items">

      @if (count($order->items) == 0)
        <div class="card mb-50" data-no-item>
          <div class="row p-5">
            <div class="col-12">
              <div class="d-flex justify-content-center align-items-center">
                <i class="fas fa-meh" style="font-size: 100px"></i>
              </div>
            </div>
          </div>
        </div>
      @endif
      @php($count = 1)
      @can('update buy ship items')
        @foreach ($order->items as $item)
          <div class="card mb-50">
            <input type="checkbox" class="form-check-input ms-3 mt-3 select-card">
            <span class="int_num">#{{$count++}}</span>
            <div class="row p-5">
              <form class="items-data-form" id="create-item-form-{{ $item->id }}" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-3">
                    <input name="id" type="hidden" value="{{ $item->id }}">
                    <input name="order_id" type="hidden" value="{{ $order->id }}">
                    <label>{{ trans('order.carton_code') }}</label>
                    <input name="carton_code" class="carton_code form-control" type="text"
                           value="{{ $item->carton_code }}">
                  </div>
                  <div class="col-md-3">
                    <label>{{ trans('order.item') }}</label>
                    <input name="item" type="text" class="form-control" value="{{ $item->item }}">
                  </div>
                  <div class="col-md-3">
                    <label>{{ trans('order.carton_quantity') }}</label>
                    <input name="carton_quantity" type="number" class="form-control" value="{{ $item->carton_quantity }}">
                  </div>
                  <div class="col-md-3">
                    <label>{{ trans('order.pieces_number') }}</label>
                    <input name="pieces_number" type="number" class="form-control" value="{{ $item->pieces_number }}">
                  </div>

                  <div class="col-12">
                    <br>
                  </div>

                  <div class="col-md-4">
                    <label>{{ trans('order.single_price') }}</label>
                    <input name="single_price" type="number" class="form-control" value="{{ $item->single_price }}">
                  </div>
                  <div class="col-md-4">
                    <label>{{ trans('order.total') }}</label>
                    <input name="total" type="number" class="form-control" data-status="{{$item->status}}" value="{{ $item->total }}">
                  </div>
                  <div class="col-md-4">
                    <label>{{ trans('order.receive_date') }}</label>
                    <input id="receive_date_{{ $item->id }}" name="receive_date" type="date" class="form-control"
                           value="{{ $item->receive_date }}" {{ $item->status != 'waiting' ? 'readonly' : null }}>
                  </div>

                  <div class="col-12">
                    <br>
                  </div>

                  <div class="col-md-4">
                    <label>{{ trans('order.cbm') }}</label>
                    <input name="cbm" type="number" class="form-control" value="{{ $item->cbm }}">
                  </div>
                  <div class="col-md-4">
                    <label>{{ trans('order.weight') }}</label>
                    <input name="weight" type="number" class="form-control" value="{{ $item->weight }}">
                  </div>
                  <div class="col-md-4">
                    <label>{{ trans('order.status') }}</label>
                    <select data-item-id="{{ $item->id }}" class="status form-control" name="status" id="item-status-{{ $item->id }}">
                      <option value="requested" {{ $item->status == 'requested' ? 'selected' : '' }}>requested</option>
                      <option value="checked" {{ $item->status == 'checked' ? 'selected' : '' }}>checked</option>
                      <option value="waiting" {{ $item->status == 'waiting' ? 'selected' : '' }}>waiting</option>
                      <option value="received" {{ $item->status == 'received' ? 'selected' : '' }}>received</option>
                      @if($item->status == 'shipped')
                        <option value="shipped" selected>shipped</option>
                      @endif
                      <option value="cancelled" {{ $item->status == 'cancelled' ? 'selected' : '' }}>cancelled</option>
                    </select>
                  </div>
                  <div class="col-12">
                    <br>
                  </div>

                  <div class="col-md-4">
                    <label>{{ trans('client.mark') }}</label>
                    <input type="text" name="mark" class="form-control" value="{{ $item->mark }}">
                  </div>
                  @if ($item->check_image != null)
                    <div class="col-md-4">
                      <button type="button" class="btn btn-primary btn-sm check-image" data-bs-target="#image-viewer-{{ $item->id }}" data-bs-toggle="modal">
                        {{ trans('order.check_image') }}
                      </button>
                      <div class="modal" id="image-viewer-{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-body image-modal">
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              <img src="{{ url('uploads/check_images/' . $item->check_image) }}" class="form-control" />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @else
                    <div class="col-md-4">
                      <label>{{ trans('order.check_image') }}</label>
                      <input name="check_image" type="file" size="1000000" accept="image/*">
                    </div>
                  @endif
                  <div class="col-md-4">
                    <label>{{ trans('order.container_number') }}</label>
                    <input data-item-id="{{ $item->id }}" id="container_number_{{ $item->id }}" class="container_number form-control"
                           name="container_number" type="text" {{ $item->status != 'shipped' ? 'readonly' : '' }} value="{{
              $item->container_number }}">
                  </div>

                  <div class="col-12">
                    <br>
                  </div>

                  <div class="col-md-4">
                    <label>{{ trans('order.check_notes') }}</label>
                    <textarea data-item-id="{{ $item->id }}" id="check_notes_{{ $item->id }}" class="check_notes form-control"
                              name="check_notes" cols="20" {{
                $item->status != 'requested' ? 'readonly' : '' }} style="min-width:100px">{{ $item->check_notes }}</textarea>
                  </div>
                  <div class="col-md-4">
                    <label>{{ trans('order.receive_notes') }}</label>
                    <textarea data-item-id="{{ $item->id }}" id="receive_notes_{{ $item->id }}" class="receive_notes form-control"
                              name="receive_notes" cols="20" {{
                $item->status != 'checked' ? 'readonly' : '' }} style="min-width:100px">{{ $item->receive_notes }}</textarea>
                  </div>
                  <div class="col-md-4">
                    <label>{{ trans('order.cancelled_notes') }}</label>
                    <textarea data-item-id="{{ $item->id }}" id="cancelled_notes_{{ $item->id }}" class="cancelled_notes form-control"
                              name="cancelled_notes" cols="20" {{ $item->status != 'cancelled' ? 'readonly' : '' }}
                              style="min-width:100px">{{ $item->cancelled_notes }}</textarea>
                  </div>

                </div>
                <div class="position-absolute bottom-0 end-0 d-flex gap-2">
                  <div class="ignore badge badge-{{$item->status}}">
                    {{trans($item->status)}}
                  </div>
                  @can('delete buy ship items')
                    <button class="delete-item btn btn-sm btn-danger me-2 mb-2" data-item-id="{{ $item->id }}">
                      <i class="la la-window-close fs-4 me-0"></i>
                    </button>
                  @endcan
                  @can('update buy ship items')
                    <button type="submit" class="save-item btn btn-sm btn-success me-2 mb-2" data-item-id="{{ $item->id }}">
                      <i class="la la-upload fs-4 me-0"></i>
                    </button>
                  @endcan
                </div>
                <div class="position-absolute bottom-0 start-0 d-flex gap-2">
                  @can('update buy ship items')
                    <button type="button" class="duplicate-item btn btn-sm btn-warning ms-2 mb-2" data-item-id="{{ $item->id }}">
                      <i class="la la-copy fs-4 me-0"></i>
                    </button>
                  @endcan
                </div>
              </form>
            </div>


          </div>
        @endforeach
      @endcan
    </div>

  </div>
  @include('buy_ship_orders.components.createSupplierModal')
@endsection
@section('scripts')
  <script>
    let contNum = {{$count}};
    $(document).ready(function () {

      $(".duplicate-item").click(function () {
        let id = $(this).attr('data-item-id');
        Swal.fire({
          title: 'Enter Number',
          input: 'number',
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          confirmButtonText: 'Submit',
          showLoaderOnConfirm: true,
          preConfirm: (number) => {
            if(number < 1) return false;
            return number;
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {

            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append("item", id);
            formData.append("count", result.value);

            $.ajax({
              url: '{{route("buy_ship_items.copy")}}',
              type: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              dataType: 'json',
              success: function(response) {
                if(response.error){
                  $("#load-containers").modal('hide');
                  Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: response.message,
                  });
                  return false;
                }
                $('#items').load(" #items",function(){  initiateSelect2();});
              },
              error: function(xhr) {

                if (xhr.status == 422) {
                  const keys = Object.keys(xhr.responseJSON.errors);
                  keys.forEach((key, index) => {
                    toastr["error"](`${xhr.responseJSON.errors[key]}`);
                  });
                }

              }
            });
          }
        });
      });

      $('#item-status-all').change(function () {
        $(".status").val($(this).val()).trigger('change');
      });

      $('.dynamic-select').on('change', function () {
        var selectedOption = $(this).find(':selected');

        // Retrieve data attributes from the selected option
        var storeNumber = selectedOption.data('store-number');
        var phone = selectedOption.data('phone');

        var itemId = $(this).data('item-id');

        // Update the phone and store_number inputs directly
        $('#phone_' + itemId).val(phone);
        $('#store_number_' + itemId).val(storeNumber);
      });
    });
    $('#submitCreateSupplierForm').on('click', function (e) {
      e.preventDefault(); // Prevent the default form submission
      storeSupplierAndUpdateSelects(); // Call the function to handle form submission
    });
    function createSupplier(){
      $('#createSupplierModal').modal('show');
    }

    // Function to update Select2 options
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
    function  initiateSelect2(){
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
      $('#client').select2();
      $('#repo').select2();
    }
    $(document).ready(function() {
      updateAllTotal();
      initiateSelect2();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      function updateAllTotal() {
        var sum = 0;
        // Loop through all inputs with name "total"
        $('input[name="total"]').each(function() {
          if($(this).attr("data-status") === "cancelled") return;
          var value = parseFloat($(this).val()) || 0;
          sum += value;
        });
        // Update the value of the input with id "allTotal"
        $('#allTotal').val(sum.toFixed(2));
      }

      function generateRandomString(length) {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let randomString = '';

        for (let i = 0; i < length; i++) {
          const randomIndex = Math.floor(Math.random() * characters.length);
          randomString += characters.charAt(randomIndex);
        }
        const timeStamp = new Date().getTime();
        randomString += timeStamp.toString();
        return randomString;
      }



      $('#add-item').on('click', function() {

        var randomCode = generateRandomString(10);
        var newCard = `
            <div class="card mb-50">
                <input type="checkbox" class="form-check-input ms-3 mt-3 select-card">
                <span class="int_num">#${contNum++}</span>
                <div class="row p-5">
                    <form id="create-item-form-${randomCode}" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-3">
                                <input name="order_id" type="hidden" value="{{ $order->id }}">
                                <label>{{ trans('order.carton_code') }}</label>
                                <input name="carton_code" type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>{{ trans('order.item') }}</label>
                                <input name="item" type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>{{ trans('order.carton_quantity') }}</label>
                                <input name="carton_quantity" type="number" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>{{ trans('order.pieces_number') }}</label>
                                <input name="pieces_number" type="number" class="form-control">
                            </div>

                            <div class="col-12">
                              <br>
                            </div>

                            <div class="col-md-4">
                                <label>{{ trans('order.single_price') }}</label>
                                <input name="single_price" type="number" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>{{ trans('order.total') }}</label>
                                <input name="total" type="number" class="form-control">
                            </div>
                            <div class="col-md-4">
                              <label>{{ trans('order.receive_date') }}</label>
                              <input  name="receive_date" type="date" class="form-control"
                                disabled>
                            </div>

                            <div class="col-12">
                              <br>
                            </div>

                            <div class="col-md-4">
                                <label>{{ trans('order.cbm') }}</label>
                                <input name="cbm" type="number" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>{{ trans('order.weight') }}</label>
                                <input name="weight" type="number" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>{{ trans('order.status') }}</label>
                                <select name="status" class="form-control">
                                    <option value="requested" selected>requested</option>
                                    <option value="checked" disabled>checked</option>
                                    <option value="waiting" disabled>waiting</option>
                                    <option value="received" disabled>received</option>
                                    <option value="shipped" disabled>shipped</option>
                                    <option value="cancelled" disabled>cancelled</option>
                                </select>
                            </div>

                            <div class="col-12">
                              <br>
                            </div>

                            <div class="col-md-4">
                              <label>{{ trans('client.mark') }}</label>
                              <input type="text" name="mark" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>{{ trans('order.check_image') }}</label>
                                <input name="check_image" type="file" size="1000000" accept="image/*">
                            </div>
                            <div class="col-md-4">
                                <label>{{ trans('order.container_number') }}</label>
                                <input name="container_number" type="text" class="form-control" readonly>
                            </div>

                            <div class="col-12">
                              <br>
                            </div>

                            <div class="col-md-4">
                                <label>{{ trans('order.check_notes') }}</label>
                                <textarea name="check_notes"  cols="20" class="form-control" style="min-width:100px"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label>{{ trans('order.receive_notes') }}</label>
                                <textarea name="receive_notes"  cols="20" class="form-control" style="min-width:100px" readonly></textarea>
                            </div>
                            <div class="col-md-4">
                                <label>{{ trans('order.cancelled_notes') }}</label>
                                <textarea name="cancelled_notes"  cols="20" class="form-control" style="min-width:100px" readonly></textarea>
                            </div>
                        </div>
                        <div class="position-absolute bottom-0 end-0 d-flex gap-2">
                            <button class="delete-item btn btn-sm btn-danger me-2 mb-2" data-randCode="${randomCode}">
                                <i class="la la-window-close fs-4 me-0"></i>
                            </button>
                            <button class="save-item btn btn-sm btn-success me-2 mb-2" data-randCode="${randomCode}">
                                <i class="la la-upload fs-4 me-0"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            `;
        $('#items').append(newCard);
        initiateSelect2()
      });

      $('body').on('click', '.save-item', function(e) {
        var createBtn = $(this);
        createBtn.attr('disabled','disabled');
        e.preventDefault();
        var form_id = $(this).data('randcode') || $(this).data('item-id');

        // Create a FormData object
        var formData = new FormData($(`#create-item-form-${form_id}`)[0]);

        // Append the CSRF token to the FormData
        formData.append('_token', '{{ csrf_token() }}');

        $(this).html('<i class="la la-sync fs-4 me-0"></i>');

        $.ajax({
          data: formData,
          url: "{{ route('buy_ship_items.store') }}",
          type: "POST",
          contentType: false, // Important to prevent jQuery from automatically setting the content type
          processData: false, // Important to prevent jQuery from processing the data
          dataType: 'json',
          success: function(data) {

            console.log('success');
            $('.save-item').html('<i class="la la-upload fs-4 me-0"></i>');
            $('#items').load(" #items",function(){  initiateSelect2();});

            createBtn.removeAttr('disabled');
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
            toastr["success"](data.success);
          },
          error: function(xhr) {

            if (xhr.status == 422) {
              const keys = Object.keys(xhr.responseJSON.errors);
              keys.forEach((key, index) => {
                createBtn.removeAttr('disabled');
                toastr["error"](`${xhr.responseJSON.errors[key]}`);
              });
            }

            $('.save-item').html('<i class="la la-upload fs-4 me-0"></i>');
          }
        });
      });

      $('body').on('click', '.delete-item', function(e) {
        e.preventDefault();
        var form_id = $(this).data('randcode') || $(this).data('item-id');
        $(this).html('<i class="la la-sync fs-4 me-0"></i>');
        Swal.fire({
          title: "{{ app()->getLocale() == 'ar' ? 'هل انت متأكد من حذف هذا البند؟' : 'Do you want to delete this item?' }}",
          showCancelButton: true,
          confirmButtonText: "Delete",
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            $.ajax({
              url: "{{ URL::to('buy_ship_items') }}" + '/' + form_id,
              type: "DELETE",
              dataType: 'json',
              success: function(data) {
                console.log('success');
                $('#items').load(' #items');
                $('.delete-item').html('<i class="la la-window-close fs-4 me-0"></i>');
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

                toastr["warning"](data.success);
              },
              error: function(data) {
                console.log(data);
                $('.save-item').html('<i class="la la-window-close fs-4 me-0"></i>');
                toastr["error"]("Something went wrong");
              }
            });
          } else {
            $('.save-item').html('<i class="la la-window-close fs-4 me-0"></i>');
          }
        });


      });
      $('body').on('change', '.status', function(e) {
        console.log($(this).data('item-id'));
        var itemId = $(this).data('item-id');
        if ($(this).val() === 'checked') {
          $(`#receive_notes_${itemId}`).prop('readonly', false);
        } else {
          $(`#receive_notes_${itemId}`).prop('readonly', true);
        }

        if ($(this).val() === 'requested') {
          $(`#check_notes_${itemId}`).prop('readonly', false)
        } else {
          $(`#check_notes_${itemId}`).prop('readonly', true)
        }

        if ($(this).val() === 'cancelled') {
          $(`#cancelled_notes_${itemId}`).prop('readonly', false)
        } else {
          $(`#cancelled_notes_${itemId}`).prop('readonly', true)
        }

        if ($(this).val() === 'shipped') {
          $(`#container_number_${itemId}`).prop('readonly', false)
        } else {
          $(`#container_number_${itemId}`).prop('readonly', true)
        }
        if ($(this).val() === 'waiting') {
          $(`#receive_date_${itemId}`).prop('readonly', false)
        } else {
          $(`#receive_date_${itemId}`).prop('readonly', true)
        }
      });
      $(document).on('input',
        'input[name="single_price"], input[name="carton_quantity"], input[name="pieces_number"], input[name="cbm"], input[name="weight"]',
        function() {
          updateTotal($(this));
          updateAllTotal();
        });
      // Function to update the total based on the input fields
      function updateTotal(currentInput) {
        var container = currentInput.closest('.card');
        var singlePrice = parseFloat(container.find('input[name="single_price"]').val()) || 0;
        // var cartonQuantity = parseFloat(container.find('input[name="carton_quantity"]').val()) || 0;
        var cartonQuantity = parseFloat(container.find('input[name="carton_quantity"]').val()) || 0;
        var piecesNumber = parseFloat(container.find('input[name="pieces_number"]').val()) || 0;

        // Calculate the total and update the total input field
        var total = singlePrice * cartonQuantity * piecesNumber;
        container.find('input[name="total"]').val(total.toFixed(2)); // You can adjust the precision as needed
      }

      $("#print").on('click', function () {
        // Determine the direction based on the locale
        var direction = '{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}';

        // Create a temporary container
        var tempContainerId = 'tempPrintContainer';
        $('<div id="' + tempContainerId + '" style="direction: ' + direction + '"></div>').appendTo('body');

        var selectedItemsHTML = '';
        var headerLabels = [];
        var firstIteration = true;

        $('.card.selected').each(function () {
          var rowData = [];
          $(this).find('input.selected, select.selected, textarea.selected').each(function () {
            var label = $(this).prev('label').text();
            var value = $(this).val();

            if (firstIteration) {
              headerLabels.push(label);
            }

            rowData.push({ label: label, value: value });
          });

          selectedItemsHTML += '<tr>';
          rowData.forEach(function (data) {
            selectedItemsHTML += '<td>' + data.value + '</td>';
          });
          selectedItemsHTML += '</tr>';

          firstIteration = false;
        });

        // Create the table
        var tableHTML = '<table class="table table-bordered" style="direction: ' + direction + '"><thead><tr>';
        headerLabels.forEach(function (label) {
          tableHTML += '<th>' + label + '</th>';
        });
        tableHTML += '</tr></thead><tbody>' + selectedItemsHTML + '</tbody></table>';

        // Append the HTML content to the temporary container
        $('#' + tempContainerId).html('<div id="printable-table">' + tableHTML + '</div>');

        // Print the selected items
        printJS({
          printable: tempContainerId,
          type: 'html',
          style: `
                .table-bordered {
                    border-collapse: collapse;
                    width: 100%;
                }

                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }

                th {
                    background-color: black;
                    color:white;
                }
            `,
          gridStyle: 'table-bordered',
        });

        // Remove the temporary container from the DOM
        $('#' + tempContainerId).remove();
      });

      // Checkbox functionality
      $('#select-all').on('change', function() {
        // Check or uncheck all checkboxes with class 'select-card'
        $('.select-card').prop('checked', $(this).prop('checked'));
        // Add or remove class 'selected' based on the status of the checkbox
        updateCardSelection();
      });

      // Individual checkbox functionality
      $('body').on('change', '.select-card', function() {
        // Uncheck "select-all" checkbox if any individual checkbox is unchecked
        if (!$(this).prop('checked')) {
          $('#select-all').prop('checked', false);
        }
        // Add or remove class 'selected' based on the status of the checkbox
        updateCardSelection();
      });
      // Function to update the card selection based on checkbox status
      function updateCardSelection() {
        $('.select-card').each(function() {
          var card = $(this).closest('.card');
          if ($(this).prop('checked')) {
            card.addClass('selected');
          } else {
            card.removeClass('selected');
          }
        });
      }
      function handleCheckboxChange() {
        // Clear previously selected items
        $('#items input, #items textarea, #items select').removeClass('selected');

        // Loop through checked checkboxes in check-columns
        $('#check-columns input:checked').each(function() {
          // Get the id of the checkbox
          var checkboxId = $(this).attr('id');

          // Add selected class to items with corresponding name attribute for input and textarea
          $('#items input[name="' + checkboxId + '"]').addClass('selected');
          $('#items textarea[name="' + checkboxId + '"]').addClass('selected');

          // Add selected class to items with corresponding name attribute for select elements
          $('#items select[name="' + checkboxId + '"]').addClass('selected');
        });
      }


      // Attach the function to checkbox change event
      $('#check-columns input').change(handleCheckboxChange);
      $(".save-all-items").click(function () {
          $(this).attr('disabled', true);
          let defaultB = $(this);
          let items = $(".items-data-form").toArray();
          for(let i = 0; i < items.length; i++){
            var createBtn = $(items[i]).find('button[type="submit"]');
            createBtn.attr('disabled','disabled');
            var form_id = createBtn.data('randcode') || createBtn.data('item-id');

            // Create a FormData object
            var formData = new FormData($(`#create-item-form-${form_id}`)[0]);

            // Append the CSRF token to the FormData
            formData.append('_token', '{{ csrf_token() }}');

            createBtn.html('<i class="la la-sync fs-4 me-0"></i>');

            $.ajax({
              data: formData,
              url: "{{ route('buy_ship_items.store') }}",
              type: "POST",
              contentType: false, // Important to prevent jQuery from automatically setting the content type
              processData: false, // Important to prevent jQuery from processing the data
              dataType: 'json',
              success: function(data) {

                console.log('success');
                $('.save-item').html('<i class="la la-upload fs-4 me-0"></i>');

                createBtn.removeAttr('disabled');
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
                if(items.length === (i+1)){
                  $('#items').load(" #items",function(){  initiateSelect2();});
                  toastr["success"](data.success);
                  defaultB.removeAttr('disabled');
                }
              },
              error: function(xhr) {

                if (xhr.status == 422) {
                  const keys = Object.keys(xhr.responseJSON.errors);
                  keys.forEach((key, index) => {
                    createBtn.removeAttr('disabled');
                    toastr["error"](`${xhr.responseJSON.errors[key]}`);
                  });
                  defaultB.removeAttr('disabled');
                }

                $('.save-item').html('<i class="la la-upload fs-4 me-0"></i>');
              }
            });
          }
      });
    });
  </script>

@endsection
