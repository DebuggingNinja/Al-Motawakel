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
              <h4 class="text-capitalize fw-500 breadcrumb-title">{{$order->code . ' - ' . $order->client->name}}
              </h4>
              <span class="sub-title ms-sm-25 ps-sm-25"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 mb-30">
      <div class="card">
        <div class="card-header color-dark fw-500">
          <div>
            {{trans('order.items')}}
          </div>
          <div style="display: flex; gap: 10px">
            <button class="btn btn-primary ship-orders">{{trans('sales.sales')}}</button>
          </div>
        </div>
        <div class="card-body">
          <div id="items-data" class="userDatatable global-shadow border-light-0 w-100">
            <div class="table-responsive">
              <table class="table mb-0 table-borderless" id="items-table">
                <thead>
                  <tr class="userDatatable-header">
                    <th>
                      <div class="custom-checkbox">
                        <input type="checkbox" id="check-all-items">
                        <label for="check-all-items">
                        </label>
                      </div>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('order.carton_code') }}</span>
                    </th>

                    <th>
                      <span class="userDatatable-title">{{ trans('order.item') }}</span>
                    </th>

                    <th>
                      <span class="userDatatable-title">{{ trans('order.dozen_quantity') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('order.sold_quantity') }}</span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @if (count($order->items) == 0)
                  <tr>
                    <td colspan="7">
                      <p class="text-center">No Items Found !</p>
                    </td>
                  </tr>
                  @else
                  @foreach ($order->items as $item)
                  <tr>
                    <td>
                      <div class="custom-checkbox">
                        <input class="check-item" type="checkbox" id="check-{{$item->id}}" data-id="{{$item->id}}">
                        <label for="check-{{$item->id}}">
                        </label>
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content carton_code">
                        {{ $item->carton_code }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content item">
                        {{ $item->item }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content dozen_quantity" data-dozen-quantity="{{$item->dozen_quantity}}"
                           data-dozen-price="{{$item->dozen_price}}" data-remaining="{{$item->dozen_quantity - ((int) $item->sold)}}">
                        {{ $item->dozen_quantity }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content sold_quantity" data-sold-quantity="{{$item->sold}}">
                        {{ $item->sold }}
                      </div>
                    </td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="load-containers"> <div class="modal-dialog" role="document">
    <div class="modal-content modal-lg">
      <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{route('sales.create')}}">
          @csrf
        <select class="form-select" name="client" id="container">
          <option value="">{{trans('client.clients')}}</option>
          @foreach ( $clients as $client)
            <option value="{{$client->id}}">{{$client->code.' - '.$client->name}}</option>
          @endforeach
        </select>
        <div>
            <table class="table">
              <thead>
              <tr>
                <th>{{ trans('order.carton_code') }}</th>
                <th>{{ trans('order.item') }}</th>
                <th>{{ trans('order.dozen_price') }}</th>
                <th>{{ trans('order.quantity') }}</th>
              </tr>
              </thead>
              <tbody class="items-in"></tbody>
            </table>
        </div>
          <button type="submit" class="btn btn-primary">{{trans('sales.sales')}}</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
  $(document).ready(function() {

    $(".ship-orders").click(function () {
      let content = "";
        $(".check-item:checked").each(function () {
          let row = $(this).closest('tr');
          let remaining = parseInt(row.find('.dozen_quantity').attr('data-remaining'));
          content += `<tr>
                <th><input type="hidden" name="items[]" value="${$(this).attr('data-id')}">
                        ${row.find('.carton_code').text()}</th>
                <th>${row.find('.item').text()}</th>
                <th><input type="number" name="price_${$(this).attr('data-id')}"
                    value="${row.find('.dozen_quantity').attr('data-dozen-price')}"></th>
                <th><input type="number" name="quantity_${$(this).attr('data-id')}"
                    value="${remaining}" min="1" max="${remaining}}"></th>
              </tr>`
        });
        $("#load-containers").find('.items-in').html(content);
        $("#load-containers").modal('show');
    });

    $('#repo_id').select2();
    $('#container').select2();
    let checkLength = () => {
      if($('.check-item:checked').length){
        $(".ship-orders").attr('disabled', false);
      }else{
        $(".ship-orders").attr('disabled', true);
      }
    }

    $(".move-to-shipping").click(function () {

      let createBtn = $(this);

      createBtn.attr('disabled', 'disabled');
      let container = $('#container').val();

      if(container === ""){
        toastr["error"]("please select a valid container");
        createBtn.removeAttr('disabled');
        return;
      }

      let formData = new FormData();
      formData.append('_token', '{{ csrf_token() }}');
      formData.append("container", container);

      if(!$(".check-item:checked").length){
        toastr["error"]("please select at least one item");
        createBtn.removeAttr('disabled');
        return;
      }

      $(".check-item:checked").each(function () {
        formData.append("items[]", $(this).attr('data-id'));
      });

      $.ajax({
        url: '{{route("repository.ship")}}',
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
            createBtn.removeAttr('disabled');
            return false;
          }
          $(".check-item:checked").each(function () {
            $(this).closest('tr').remove();
          });

          $("#load-containers").modal('hide');
          createBtn.removeAttr('disabled');
          Swal.fire({
            icon: "success",
            title: response.message,
          });
          checkLength();
        },
        error: function(xhr) {

          if (xhr.status == 422) {
            const keys = Object.keys(xhr.responseJSON.errors);
            keys.forEach((key, index) => {
              createBtn.removeAttr('disabled');
              toastr["error"](`${xhr.responseJSON.errors[key]}`);
            });
          }

        }
      });
    });
    $('.check-item').on('change', () => {
      checkLength();
    });
    checkLength();
      $('#repo_id').on('change', function() {
        // Trigger form submission when the value changes
        $('#form_select_warehouse').submit();
      });
      $("#print").on('click',function(){
        printJS({
          printable: 'items-data',
          type:'html',
          style:`
            table {border-collapse: collapse;width: 100%;}
            th, td {border: 1px solid #ddd;padding: 8px;text-align: center;}
            td:first-child,th:first-child{display:none !important;}
            .custom-checkbox {display: none !important;}
            tr{display:table-row;}
            tbody tr:not(.selected){display:none!important;}
          `});
      });

      $("#showShipped").change(function () {
        $(".search-dates").toggle($(this).is(":checked"));
      }).trigger('change');

            // Select all rows when the "Select All" checkbox is clicked
            $('#check-all-items').on('change', function() {
                $('.check-item').prop('checked', $(this).prop('checked'));
                if($(this).prop('checked')){
                  $('.check-item').parent().parent().parent().addClass('selected')
                  var carton_sum = 0;
                  var cbm_sum = 0;
                  var weight_sum = 0;
                  $('#items-table tbody tr').each(function(){
                    var carton_cell_value = $(this).find('td div[data-item="carton"]').data('item-carton-qty');
                    var cbm_cell_value = $(this).find('td div[data-item="cbm"]').data('item-cbm');
                    var weight_cell_value = $(this).find('td div[data-item="weight"]').data('item-weight');
                    carton_sum += parseFloat(carton_cell_value);
                    cbm_sum += parseFloat(cbm_cell_value);
                    weight_sum += parseFloat(weight_cell_value);
                  });
                  $('#carton_quantity_total').text(carton_sum);
                  $('#cbm_total').text(cbm_sum);
                  $('#weight_total').text(weight_sum);
                }else{
                  $('.check-item').parent().parent().parent().removeClass('selected')
                  $('#carton_quantity_total').text(0);
                  $('#cbm_total').text(0);
                  $('#weight_total').text(0);
                }
            });

            // Select only the clicked row when a row checkbox is clicked
            $('.check-item').on('change', function() {
              var allChecked = $('.check-item:checked').length === $('.check-item').length;
              $('#check-all-items').prop('checked', allChecked);
              if($(this).prop('checked')){
                  $(this).parent().parent().parent().addClass('selected');

                    var carton_cell_value = $(this).parent().parent().parent().find('td div[data-item="carton"]').data('item-carton-qty');
                    var cbm_cell_value = $(this).parent().parent().parent().find('td div[data-item="cbm"]').data('item-cbm');
                    var weight_cell_value = $(this).parent().parent().parent().find('td div[data-item="weight"]').data('item-weight');
                    var carton_sum = parseFloat($('#carton_quantity_total').text());
                    var cbm_sum = parseFloat($('#cbm_total').text());
                    var weight_sum = parseFloat($('#weight_total').text());
                    carton_sum += parseFloat(carton_cell_value);
                    cbm_sum += parseFloat(cbm_cell_value);
                    weight_sum += parseFloat(weight_cell_value);
                    $('#carton_quantity_total').text(carton_sum);
                    $('#cbm_total').text(cbm_sum);
                    $('#weight_total').text(weight_sum);

              }else{
                  $(this).parent().parent().parent().removeClass('selected');
                    var carton_cell_value = $(this).parent().parent().parent().find('td div[data-item="carton"]').data('item-carton-qty');
                    var cbm_cell_value = $(this).parent().parent().parent().find('td div[data-item="cbm"]').data('item-cbm');
                    var weight_cell_value = $(this).parent().parent().parent().find('td div[data-item="weight"]').data('item-weight');
                    var carton_sum = parseFloat($('#carton_quantity_total').text());
                    var cbm_sum = parseFloat($('#cbm_total').text());
                    var weight_sum = parseFloat($('#weight_total').text());
                    carton_sum -= parseFloat(carton_cell_value);
                    cbm_sum -= parseFloat(cbm_cell_value);
                    weight_sum -= parseFloat(weight_cell_value);
                    $('#carton_quantity_total').text(carton_sum);
                    $('#cbm_total').text(cbm_sum);
                    $('#weight_total').text(weight_sum);

              }
            });
        });
</script>
@endsection
