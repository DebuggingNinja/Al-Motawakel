@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('style')
  <style>
    .hide{
      display: none;
    }
    .editable-col{
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .order-commission{
      width: 60px;
    }
  </style>
@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="contact-breadcrumb">
        <div class="breadcrumb-main add-contact justify-content-sm-between ">
          <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
            <div class="d-flex align-items-center add-contact__title justify-content-center me-sm-25">
              <h4 class="text-capitalize fw-500 breadcrumb-title">{{ trans('order.buy-orders') }}
              </h4>
              <span class="sub-title ms-sm-25 ps-sm-25"></span>
            </div>
            <div class="action-btn mt-sm-0 mt-15">
              @can('add buy ship orders')
              <a href="{{ route('buy_ship_orders.create') }}" class="btn px-20 btn-primary ">
                <i class="las la-plus fs-16"></i>{{trans('order.add-order')}}
              </a>
              @endcan
            </div>
          </div>
          <div class="breadcrumb-main__wrapper">

          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">

      <form action="{{route('buy_ship_orders.index')}}" method="GET">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="search">
                {{trans('repo.search')}}
              </label>
              <input name="search" class="form-control me-sm-2 border-0 box-shadow-none" type="search"
                     placeholder="Search by Name" value="{{request()?->search ?? ""}}" aria-label="Search">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="client">
                {{trans('client.clients')}}
              </label>
              <select class="form-select me-sm-2 border-0 box-shadow-none" name="client" id="client">
                <option value="">{{trans('client.clients')}}</option>
                @foreach ($clients as $client)
                  <option value="{{$client->id}}">{{$client->name}} - {{$client->code}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="status">
                {{trans('order.status')}}
              </label>
              <select class="form-select me-sm-2 border-0 box-shadow-none" name="status" id="status">
                <option value="">{{trans('order.status')}}</option>
                <option value="requested" {{ request()?->status == 'requested' ? 'selected' : '' }}>requested</option>
                <option value="checked" {{ request()?->status == 'checked' ? 'selected' : '' }}>checked</option>
                <option value="waiting" {{ request()?->status == 'waiting' ? 'selected' : '' }}>waiting</option>
                <option value="received" {{ request()?->status == 'received' ? 'selected' : '' }}>received</option>
                <option value="cancelled" {{ request()?->status == 'cancelled' ? 'selected' : '' }}>cancelled</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="start_date">
                {{trans('repo.start_date')}}
              </label>
              <input class="form-control me-sm-2 border-0 box-shadow-none" type="date" name="start_date" id="start_date"
                     value="{{request()?->start_date ?? date('Y-m-d', strtotime("- 1 month"))}}">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="end_date">
                {{trans('repo.end_date')}}
              </label>
              <input class="form-control me-sm-2 border-0 box-shadow-none" type="date" name="end_date" id="end_date"
                     value="{{request()?->end_date ?? date('Y-m-d')}}">
            </div>
          </div>
          <div class="col-md-4">
            <button class="btn btn-primary" type="submit">{{trans('repo.search')}}</button>
          </div>
        </div>
      </form>

    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 mb-30">
      <div class="card">
        <div class="card-header color-dark fw-500">
          {{trans('order.orders')}}
          <div class="d-flex gap-2">
            <div>
              <span>{{trans('requested')}}</span>
              <span class="badge-dot" style="background-color: yellow"></span>
            </div>
            <div>
              <span>{{trans('checked')}}</span>
              <span class="badge-dot" style="background-color: blue"></span>
            </div>
            <div>
              <span>{{ trans('waiting') }}</span>
              <span class="badge-dot" style="background-color: red"></span>
            </div>
            <div>
              <span>{{ trans('received') }}</span>
              <span class="badge-dot" style="background-color: green"></span>
            </div>
            <div>
              <span>{{ trans('cancelled') }}</span>
              <span class="badge-dot" style="background-color: purple"></span>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="userDatatable global-shadow border-light-0 w-100">
            <div class="table-responsive">
              <table class="table mb-0 table-borderless">
                <thead>
                  <tr class="userDatatable-header">
                    <th>
                      <span class="userDatatable-title">id</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">code</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('repo.repos') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('client.clients') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('supplier.suppliers') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('created_at') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">
                        {{ trans('order.status') }}
                      </span>
                    </th>
                    <th>
                      <span class="userDatatable-title float-end">Actions</span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @if (count($orders) == 0)
                  <tr>
                    <td colspan="7">
                      <p class="text-center">No Orders Found !</p>
                    </td>
                  </tr>
                  @else
                  @php
                  $count = 1;
                  @endphp
                  @foreach ($orders as $order)
                  <tr>
                    <td>
                      <div class="d-flex">
                        <div class="userDatatable__imgWrapper d-flex align-items-center">

                        </div>
                        <div class="userDatatable-inline-title">
                          <a href="#" class="text-dark fw-500">
                            <h6>{{ $order->id }}</h6>
                          </a>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $order->code }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $order->repo?->name .' - '. $order->repo?->code }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $order->client?->name .' - '. $order->client?->code }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $order->supplier?->name .' - '. $order->supplier?->code }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ date('d-m-Y', strtotime($order->created_at)) }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content d-flex gap-2">
                        @foreach ($order->items as $item)
                          @if ($item->status == 'requested')
                            <span class="badge-dot" style="background-color: yellow"></span>
                          @endif
                          @if ($item->status == 'checked')
                            <span class="badge-dot" style="background-color: blue"></span>
                          @endif
                          @if ($item->status == 'waiting')
                            <span class="badge-dot" style="background-color: red"></span>
                          @endif
                          @if (in_array($item->status, ['shipped', 'received']))
                            <span class="badge-dot" style="background-color: green"></span>
                          @endif
                          @if ($item->status == 'cancelled')
                            <span class="badge-dot" style="background-color: purple"></span>
                          @endif
                        @endforeach
                      </div>
                    </td>

                    <td>
                      <ul class="orderDatatable_actions mb-0 d-flex flex-wrap">
                        @can('update buy ship orders')
                        <li>
                          <a href="{{ route('buy_ship_orders.edit', [$order]) }}" class="edit">
                            <i class="uil uil-edit"></i>
                          </a>
                        </li>
                        @endcan
                        @can('delete buy ship orders')
                        <li>
                          <a href="#" class="remove" onclick="
                              event.preventDefault();
                              if ( confirm('Are you sure you want to delete ?') ) {
                                  document.getElementById( 'delete-{{ $order->id }}' ).submit();
                              }
                          ">
                            <i class="uil uil-trash-alt"></i>
                          </a>

                          <form style="display:none;" id="delete-{{ $order->id }}"
                            action="{{ route('buy_ship_orders.destroy', [$order]) }}" method="POST">
                            @csrf
                            @method('delete')
                          </form>
                        </li>
                        @endcan
                      </ul>
                    </td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>

          <div class="pagination-container d-flex justify-content-end pt-25">
            {{ $orders->links( 'pagination::bootstrap-5' ) }}

            <ul class="dm-pagination d-flex">
              <li class="dm-pagination__item">
                <div class="paging-option">
                  <select name="page-number" class="page-selection" onchange="updatePagination( event )">
                    <option value="20" {{ 20==$orders->perPage() ? 'selected' : '' }}>20/page</option>
                    <option value="40" {{ 40==$orders->perPage() ? 'selected' : '' }}>40/page</option>
                    <option value="60" {{ 60==$orders->perPage() ? 'selected' : '' }}>60/page</option>
                  </select>
                  <a href="#" class="d-none per-page-pagination"></a>
                </div>
              </li>
            </ul>

            <script>
              function updatePagination( event ) {
                  var per_page = event.target.value;
                  const per_page_link = document.querySelector( '.per-page-pagination' );
                  per_page_link.setAttribute( 'href', '/pagination-per-page/' + per_page  );

                  per_page_link.click();
              }

              document.addEventListener('DOMContentLoaded', () => {
                $(".editable-col").click(function () {
                  let input = $(this).find(".order-commission");
                  let value = $(this).find('.value');
                  if(input.hasClass('hide')){
                    value.addClass('hide');
                    input.off('keydown');
                    input.removeClass('hide');
                    input.on('keydown',function(e) {
                      if(e.which === 13) {
                        collect();
                      }
                    }).focusout(() => {
                      let e = $.Event("keydown");
                      e.which = 13;
                      input.trigger(e)
                    }).focus();
                    function collect() {
                      if(input.attr('dispatched') === "dispatched") return;
                      if(isNaN(input.val()) || input.val() > 100 || input.val() < 0){
                        swal.fire({
                          type: 'error',
                          title: 'invalid percentage value'
                        })
                        return;
                      }
                      input.attr('dispatched', "dispatched")
                      value.html(input.val() + "%");
                      input.attr('disabled', true)
                      $.ajax({
                        url: '{{route("order.set_commission")}}',
                        type: 'POST',
                        data: {
                          _token: '{{ csrf_token() }}',
                          order: input.attr('data-id'),
                          commission: input.val()
                        },
                        dataType: 'json',
                        success: function(response) {
                          if(response.error){
                            Swal.fire({
                              icon: "error",
                              title: "Error",
                              text: response.message,
                            });
                          }

                          input.attr('disabled', false)
                          input.removeAttr('dispatched')
                          input.addClass('hide');
                          value.removeClass('hide');
                        },
                        error: function(xhr) {

                          if (xhr.status == 422) {
                            const keys = Object.keys(xhr.responseJSON.errors);
                            keys.forEach((key, index) => {
                              toastr["error"](`${xhr.responseJSON.errors[key]}`);
                            });
                          }

                          input.attr('disabled', false)
                          input.removeAttr('dispatched')
                          input.addClass('hide');
                          value.removeClass('hide');
                        }
                      });
                    }
                  }
                });
              })

            </script>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
      $('#client').select2();
    });
  </script>
@endsection
