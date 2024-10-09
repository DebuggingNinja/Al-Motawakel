@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="contact-breadcrumb">
        <div class="breadcrumb-main add-contact justify-content-sm-between ">
          <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
            <div class="d-flex align-items-center add-contact__title justify-content-center me-sm-25">
              <h4 class="text-capitalize fw-500 breadcrumb-title">{{ trans('order.ship-orders') }}
              </h4>
              <span class="sub-title ms-sm-25 ps-sm-25"></span>
            </div>
            <div class="action-btn mt-sm-0 mt-15">
              @can('add ship orders')
              <a href="{{ route('ship_orders.create') }}" class="btn px-20 btn-primary ">
                <i class="las la-plus fs-16"></i>{{trans('order.add-order')}}
              </a>
              @endcan
            </div>
          </div>
          <div class="breadcrumb-main__wrapper">

            <form action="{{route('ship_orders.index')}}" method="GET"
              class="d-flex align-items-center add-contact__form my-sm-0 my-2">
              <img src="{{ asset('assets/img/svg/search.svg') }}" alt="search" class="svg">
              <input name="search" class="form-control me-sm-2 border-0 box-shadow-none" type="search"
                placeholder="Search by Name" aria-label="Search">
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 mb-30">
      <div class="card">
        <div class="card-header color-dark fw-500">
          {{trans('order.orders')}}
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
                      <span class="userDatatable-title">{{ trans('created_at') }}</span>
                    </th>
{{--                    <th>--}}
{{--                      <span class="userDatatable-title">{{ trans('order.registery') }}</span>--}}
{{--                    </th>--}}
{{--                    <th>--}}
{{--                      <span class="userDatatable-title">{{ trans('order.paper') }}</span>--}}
{{--                    </th>--}}
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
                            <h6>{{ $count++ }}</h6>
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
                        {{ $order->repo->name .' - '. $order->repo->code }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $order->client->name .' - '. $order->client->code }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ date('d-m-Y', strtotime($order->created_at)) }}
                      </div>
                    </td>
{{--                    <td>--}}
{{--                      <div class="userDatatable-content">--}}
{{--                        {{ $order->registery }}--}}
{{--                      </div>--}}
{{--                    </td>--}}

{{--                    <td>--}}
{{--                      <div class="userDatatable-content">--}}
{{--                        {{ $order->paper }}--}}
{{--                      </div>--}}
{{--                    </td>--}}


                    <td>
                      <ul class="orderDatatable_actions mb-0 d-flex flex-wrap">
                        @can('update ship orders')
                        <li>
                          <a href="{{ route('ship_orders.edit', [$order]) }}" class="edit">
                            <i class="uil uil-edit"></i>
                          </a>
                        </li>
                        @endcan
                        @can('delete ship orders')
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
                            action="{{ route('ship_orders.destroy', [$order]) }}" method="POST">
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
            </script>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
