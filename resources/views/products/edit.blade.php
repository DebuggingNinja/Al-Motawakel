@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
<div class="container-fluid" id="items-data">
  <div class="row">
    <div class="col-lg-12">
      <div class="d-flex align-items-center user-member__title mb-30 mt-30">
        <h4 class="text-capitalize">{{ trans('products.update') }}</h4>
      </div>
    </div>
  </div>
  <div class="card mb-50">
    <div class="row p-5">
      <div class="col-12">
        <form action="{{ route('products.update', $container->id) }}" method="POST" enctype="multipart/form-data">
          @method('put')
          @csrf
          <div class="row">
            <div class="col-12 col-md-4">
              <div class="form-group mb-25">
                <label for="code" class="color-dark fs-14 fw-500 align-center">{{ trans('order.carton_code') }}
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="code"
                       id="code" value="{{ $container->code }}">
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
                       id="name" value="{{ $container->name }}">
                @if ($errors->has('name'))
                  <p class="text-danger">{{ $errors->first('name') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group mb-25">
                <label for="measuring" class="color-dark fs-14 fw-500 align-center">{{ trans('order.measuring') }}
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="measuring"
                       id="measuring" value="{{ $container->measuring }}">
                @if ($errors->has('measuring'))
                  <p class="text-danger">{{ $errors->first('measuring') }}</p>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group mb-25">
                <label for="pieces_number" class="color-dark fs-14 fw-500 align-center">{{ trans('order.pieces_number') }}
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="pieces_number"
                       id="pieces_number" value="{{ $container->pieces_number }}">
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
                       id="cbm" value="{{ $container->cbm }}">
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
                       id="weight" value="{{ $container->weight }}">
                @if ($errors->has('weight'))
                  <p class="text-danger">{{ $errors->first('weight') }}</p>
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
      $('#company').select2();
      $('#shipping_type').select2();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $("#print").on('click', function() {
        printJS({
          printable: 'items-data',
          type: 'html',
          style: `
            table {border-collapse: collapse;width: 98%;font-size:10px;}
            th, td {border: 1px solid #ddd;text-align: center;width:8%}
            tr{display:table-row;}
            button, .text-danger {display:none!important}
            textarea, input, select {border:none!important;width:50px!important}
            select {appearance:none}
            textarea {resize: none;min-height:100px}
            th:last-of-type, td:last-of-type{display:none}

          `
        });
      });
      $('body').on('click', '.save-item', function(e) {
        e.preventDefault();
        var form_id = $(this).data('item-id');
        $(this).html('<i class="la la-sync fs-4 me-0"></i>');
        $.ajax({
          data: $(`#item-${form_id}`).serialize(),
          url: "{{ route('containers.items') }}",
          type: "POST",
          dataType: 'json',
          success: function(data) {
            console.log('success');
            $('.save-item').html('<i class="la la-upload fs-4 me-0"></i>');
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
            location.reload();
          },
          error: function(xhr) {
            if(xhr.status = 422){
              const keys = Object.keys(xhr.responseJSON.errors);
              keys.forEach((key,index) => {
                toastr["error"](`${xhr.responseJSON.errors[key]}`);
              });
            }
            $('.save-item').html('<i class="la la-upload fs-4 me-0"></i>');
          }
        });
      });
      $('.save-items').click(function(e) {
        e.preventDefault();
        var form_id = $(this).data('item-id');
        $(this).html('<i class="la la-sync fs-4 me-0"></i>');
        let data = new FormData;
        $(".item-form").each(function () {
          let item = {};
          $(this).serializeArray().forEach(function (it){
            item[it.name] = it.value;
          });
          data.append("items[]", JSON.stringify(item));
        });
        $.ajax({
          data: data,
          url: "{{ route('containers.items_all') }}",
          type: "POST",
          processData: false,
          contentType: false,
          dataType: 'json',
          success: function(data) {
            console.log('success');
            $('.save-item').html('<i class="la la-upload fs-4 me-0"></i>');
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
            location.reload();
          },
          error: function(xhr) {
            if(xhr.status = 422){
              const keys = Object.keys(xhr.responseJSON.errors);
              keys.forEach((key,index) => {
                toastr["error"](`${xhr.responseJSON.errors[key]}`);
              });
            }
            $('.save-item').html('<i class="la la-upload fs-4 me-0"></i>');
          }
        });
      });
      $('.meter_price').on('input', function(e) {
        var itemId = $(this).data('item-id');
        var meterPrice = $(this).val();
        collectTotal($(this), itemId, meterPrice);
      });
      $('.collect_by').click(function(e) {
        var itemId = $(this).data('item-id');
        var meterPrice = $(".meter_price[data-item-id='" + itemId + "']").val();
        collectTotal($(this), itemId, meterPrice);
      });
      function collectTotal(elem, itemId, meterPrice){
        let collectByCbm = $(".collect_by[data-item-id='" + itemId + "']:checked").val() !== "weight";
        var cbmValue;
        if(collectByCbm){
          cbmValue = parseFloat($('.cbm[data-item-id="' + itemId + '"]').data('item-cbm')) || 0;
        }else{
          cbmValue = parseFloat($('.weight[data-item-id="' + itemId + '"]').data('item-weight')) || 0;
        }
        var totalValue = parseFloat(meterPrice) * cbmValue || 0;
        $('.total[data-item-id="' + itemId + '"]').val(totalValue.toFixed(1));
      }
    });
</script>
@endsection
