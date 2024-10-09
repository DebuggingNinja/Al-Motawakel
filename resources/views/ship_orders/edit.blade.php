@section('title', $title)
@section('description', $description)
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
        <form action="{{ route('ship_orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('put')
          <div class="row">
            <div class="col-12 col-md-3">
              <h6 class="mt-3">{{ trans('id') }} : <span dir="ltr">{{ $order->code }}</span></h6>
            </div>
            <div class="col-12 col-md-3">
              <h6 class="mt-3">{{ trans('created_at') }} : {{ date('d-m-Y', strtotime($order->created_at)) }}</h6>
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
                  <option value="{{ $client->id }}" {{ $order->client->id == $client->id ? 'selected' : '' }}>
                    {{ $client->name }} - {{$client->code}}</option>
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
                <select class="form-select " name="repo" id="repo">
                  <option value="">---</option>
                  @foreach ($repos as $repo)
                  <option value="{{ $repo->id }}" {{ $order->repo->id == $repo->id ? 'selected' : '' }}>
                    {{ $repo->name.' - '.$repo->code }}</option>
                  @endforeach
                </select>
                @if ($errors->has('repo'))
                <p class="text-danger">{{ $errors->first('repo') }}</p>
                @endif
              </div>
            </div>
{{--            <div class="col-12 col-md-3">--}}
{{--              <div class="form-group mb-25">--}}
{{--                <label for="registery" class="color-dark fs-14 fw-500 align-center">{{ trans('order.registery') }}--}}
{{--                  <span class="text-danger">*</span></label>--}}
{{--                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="registery"--}}
{{--                  id="registery" value="{{ $order->registery }}">--}}
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
{{--                  id="paper" value="{{ $order->paper }}">--}}
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


  @can('view ship items')
  <div class="row">
    <div class="col-lg-12">
      <div class="d-flex align-items-center justify-content-between user-member__title mb-30 mt-30">
        <h4 class="text-capitalize">{{ trans('order.items') }}</h4>
        <div class="d-flex align-items-center justify-content-end gap-2">
          @can('add ship items')
          <button id="add-item" class="btn btn-primary" style="position: fixed; bottom:20px; z-index:12">{{
            trans('order.add-item') }}</button>
          @endcan
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
    @can('update ship items')
    @foreach ($order->items as $item)
    <div class="card mb-50">

      <div class="row p-5">
        <form id="create-item-form-{{ $item->id }}">
          <div class="d-flex flex-column flex-md-row justify-content-start align-items-center gap-1 overflow-auto">
            <input name="id" type="hidden" value="{{ $item->id }}">
            <input name="order_id" type="hidden" value="{{ $order->id }}">
            <div>
              <label>{{ trans('order.carton_quantity') }}</label>
              <input name="carton_quantity" type="number" style="width:100px" value="{{ $item->carton_quantity }}">
            </div>
            <div>
              <label>{{ trans('order.cbm') }}</label>
              <input name="cbm" type="number" style="width:100px" value="{{ $item->cbm }}">
            </div>
            <div>
              <label>{{ trans('order.weight') }}</label>
              <input name="weight" type="number" style="width:100px" value="{{ $item->weight }}">
            </div>
            <div>
              <label>{{ trans('order.status') }}</label>
              <select data-item-id="{{ $item->id }}" class="status" name="status" id="item-status-{{ $item->id }}"
                style="width:100px">
                <option value="received" {{ $item->status == 'received' ? 'selected' : '' }}>received</option>
                <option value="shipped" {{ $item->status == 'shipped' ? 'selected' : '' }}>shipped</option>
              </select>
            </div>
            <div>
              <label>{{ trans('order.container_number') }}</label>
              <input data-item-id="{{ $item->id }}" id="container_number_{{ $item->id }}" class="container_number"
                name="container_number" type="text" {{ $item->status != 'shipped' ? 'disabled' : '' }} value="{{
              $item->container_number }}">
            </div>
            <div>
              <label>{{ trans('order.notes') }}</label>
              <textarea data-item-id="{{ $item->id }}" id="notes_{{ $item->id }}" class="notes" name="notes" cols="20"
                style="min-width:100px">{{ $item->notes }}</textarea>
            </div>
          </div>
          <div class="position-absolute bottom-0 end-0 d-flex gap-2">
            @can('delete ship items')
            <button class="delete-item btn btn-sm btn-danger me-2 mb-2" data-item-id="{{ $item->id }}">
              <i class="la la-window-close fs-4 me-0"></i>
            </button>
            @endcan
            @can('update ship items')
            <button class="save-item btn btn-sm btn-success me-2 mb-2" data-item-id="{{ $item->id }}">
              <i class="la la-upload fs-4 me-0"></i>
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
@endsection
@section('scripts')
<script>
  $(document).ready(function() {
      $('#client').select2();
      $('#repo').select2();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

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
                <div class="row p-5">
                    <form id="create-item-form-${randomCode}">
                        <input name="order_id" type="hidden" value="{{ $order->id }}">
                        <div class="d-flex flex-column flex-md-row justify-content-start align-items-center gap-1 overflow-auto">
                            <div>
                                <label>{{ trans('order.carton_quantity') }}</label>
                                <input name="carton_quantity" type="number" style="width:100px">
                            </div>
                            <div>
                                <label>{{ trans('order.cbm') }}</label>
                                <input name="cbm" type="number" style="width:100px">
                            </div>
                            <div>
                                <label>{{ trans('order.weight') }}</label>
                                <input name="weight" type="number" style="width:100px">
                            </div>
                            <div>
                                <label>{{ trans('order.status') }}</label>
                                <select name="status" style="width:100px">
                                    <option value="received">received</option>
                                    <option value="shipped" disabled>shipped</option>
                                </select>
                            </div>
                            <div>
                                <label>{{ trans('order.container_number') }}</label>
                                <input name="container_number" type="text" disabled>
                            </div>
                            <div>
                                <label>{{ trans('order.notes') }}</label>
                                <textarea name="notes"  cols="20" style="min-width:100px"></textarea>
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
      });
      $('body').on('click', '.save-item', function(e) {
        e.preventDefault();
        var form_id = $(this).data('randcode') || $(this).data('item-id');
        $(this).html('<i class="la la-sync fs-4 me-0"></i>');
        $.ajax({
          data: $(`#create-item-form-${form_id}`).serialize(),
          url: "{{ route('ship_items.store') }}",
          type: "POST",
          dataType: 'json',
          success: function(data) {
            console.log('success');
            $('.save-item').html('<i class="la la-upload fs-4 me-0"></i>');
            $('#items').load(" #items");
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
              url: "{{ URL::to('ship_items') }}" + '/' + form_id,
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
      $('body').on('change','.status',function(e){
            var itemId = $(this).data('item-id');
            if($(this).val() === 'shipped') {
                $(`#container_number_${itemId}`).prop('disabled',false)
            }else{
                $(`#container_number_${itemId}`).prop('disabled',true)
            }
        });
    });
</script>

@endsection
