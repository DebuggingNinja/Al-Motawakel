@section('title', $title)
@section('description', $description)
@section('style')
<style>
  tr.selected {
    background-color: var(--bs-green);
  }
  .confirm-item, .cancel-item{
     padding: 10px !important;
    font-size: 14px;
    height: auto;
    width: fit-content;
    line-height: 14px;
    display: inline-block;
  }
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
  .shipping_note_editor{
    width: 100%;
    height: auto;
    min-height: 40px;
    white-space: pre;
    position: relative;
  }
  .inline-editor{
    background: #f7f7f7;
    border: 0;
    max-width: 150px;
  }
  .save-link{
    position: absolute;
    right: 0;
    top: 0;
    display: block;
    background: #e7e7e7;
    color: #333333;
    font-size: 14px;
    padding: 10px;
    cursor: pointer;
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
              <h4 class="text-capitalize fw-500 breadcrumb-title">{{$repo->name . ' - ' . $repo->code}}
              </h4>
              <span class="sub-title ms-sm-25 ps-sm-25"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card p-5">
        <form id="form_select_warehouse" action="{{route('repository.current_items')}}" method="POST">
          @csrf
          @method('POST')
          <div id="select-repo" class="form-group">
            <label for="repo_id">
              {{trans('repo.name')}}
            </label>
            <select class="form-select" name="repo_id" id="repo_id">
              <option value="">Select a Warehouse</option>
              @if (count($repos) == 0)
              <option value="">NO</option>
              @endif
              @foreach ( $repos as $repo)
              <option value="{{$repo->id}}" @if(request()?->repo_id == $repo->id) selected @endif>{{$repo->name.' - '.$repo->code}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="search">
              {{trans('repo.search')}}
            </label>
            <input class="form-control" name="search" id="search" value="{{request()?->search}}">
          </div>
          <div class="form-group">
            <label for="showShipped">
              {{trans('repo.show_shipped')}}
            </label>
            <input class="" type="checkbox" name="showShipped" id="showShipped"
              @if(request()?->showShipped) checked @endif>
          </div>
          <div class="row search-dates">
            <div class="col-md-6">
              <div class="form-group">
                <label for="start_date">
                  {{trans('repo.start_date')}}
                </label>
                <input class="form-control" type="date" name="start_date" id="start_date"
                       value="{{request()?->start_date ?? date('Y-m-d')}}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="end_date">
                  {{trans('repo.end_date')}}
                </label>
                <input class="form-control" type="date" name="end_date" id="end_date"
                       value="{{request()?->end_date ?? date('Y-m-d', strtotime("+ 1 day"))}}">
              </div>
            </div>
          </div>
          <button class="btn btn-primary" type="submit">{{trans('repo.search')}}</button>
        </form>
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
            <button class="btn btn-primary" id="print-items">{{trans('print')}}</button>
            @if(!request()?->showShipped)
            <button class="btn btn-primary ship-orders" data-bs-target="#load-containers"
                    data-bs-toggle="modal">{{trans('ship')}}</button>
              <button class="btn btn-primary sale-items">{{trans('sales.sales')}}</button>
              <button class="btn btn-primary move-items">{{trans('repo.move')}}</button>
            @endif
          </div>
        </div>
        <div class="col-12">
          <div class="row mb-3" id="check-columns">
            <div class="col-12">
              <div class="d-flex gap-2 flex-wrap">
                <div class="form-check">
                  <input id="c-name" type="checkbox" class="form-check-input select-effector" data-effect=".name" checked>
                  <label for="c-name" class="form-check-label">{{ trans('client.name') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-code" type="checkbox" class="form-check-input select-effector" data-effect=".code" checked>
                  <label for="c-code" class="form-check-label">{{ trans('client.code') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-pieces_number" type="checkbox" class="form-check-input select-effector" data-effect=".pieces_number" checked>
                  <label for="c-pieces_number" class="form-check-label">{{ trans('order.pieces_number') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-item" type="checkbox" class="form-check-input select-effector" data-effect=".item" checked>
                  <label for="c-item" class="form-check-label">{{ trans('order.item') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-mark" type="checkbox" class="form-check-input select-effector" data-effect=".mark" checked>
                  <label for="c-mark" class="form-check-label">{{ trans('client.mark') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-carton_quantity" type="checkbox" class="form-check-input select-effector" data-effect=".carton_quantity" checked>
                  <label for="c-carton_quantity" class="form-check-label">{{ trans('order.carton_quantity') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-cbm" type="checkbox" class="form-check-input select-effector" data-effect=".cbm" checked>
                  <label for="c-cbm" class="form-check-label">{{ trans('order.cbm') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-weight" type="checkbox" class="form-check-input select-effector" data-effect=".weight" checked>
                  <label for="c-weight" class="form-check-label">{{ trans('order.weight') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-receive_date" type="checkbox" class="form-check-input select-effector" data-effect=".receive_date" checked>
                  <label for="c-receive_date" class="form-check-label">{{ trans('order.receive_date') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-receive_notes" type="checkbox" class="form-check-input select-effector" data-effect=".receive_notes" checked>
                  <label for="c-receive_notes" class="form-check-label">{{ trans('order.receive_notes') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-image" type="checkbox" class="form-check-input select-effector" data-effect=".image" checked>
                  <label for="c-image" class="form-check-label">{{ trans('order.image') }}</label>
                </div>
                @if(request()?->showShipped)
                <div class="form-check">
                  <input id="c-shipping_date" type="checkbox" class="form-check-input select-effector" data-effect=".shipping_date" checked>
                  <label for="c-shipping_date" class="form-check-label">{{ trans('repo.shipping_date') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-number" type="checkbox" class="form-check-input select-effector" data-effect=".number" checked>
                  <label for="c-number" class="form-check-label">{{ trans('container.number') }}</label>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="card-body print-content">
          <div id="items-data" class="userDatatable global-shadow border-light-0 w-100">
            <div class="table-responsive">
              <table class="table mb-0 table-borderless" id="items-table">
                <thead>
                  <tr class="userDatatable-header">
                    @if(!request()?->showShipped)
                    <th class=" hide-on-print">
                      <div class="custom-checkbox">
                        <input type="checkbox" id="check-all-items">
                        <label for="check-all-items">
                        </label>
                      </div>
                    </th>
                    @endif
                    <th class="name">
                      <span class="userDatatable-title ">{{ trans('client.name') }}</span>
                    </th>

                    <th class="item">
                      <span class="userDatatable-title">{{ trans('order.item') }}</span>
                    </th>
                    <th class="mark">
                      <span class="userDatatable-title">{{ trans('client.mark') }}</span>
                    </th>
                    <th class="carton_quantity">
                      <span class="userDatatable-title">{{ trans('order.carton_quantity') }}</span>
                    </th>
                    <th class="pieces_number">
                      <span class="userDatatable-title ">{{ trans('order.pieces_number') }}</span>
                    </th>
                    <th class="cbm">
                      <span class="userDatatable-title">{{ trans('order.cbm') }}</span>
                    </th>
                    <th class="weight">
                      <span class="userDatatable-title">{{ trans('order.weight') }}</span>
                    </th>
                    <th class="receive_date">
                      <span class="userDatatable-title">{{ trans('order.receive_date') }}</span>
                    </th>
                    <th class="receive_notes">
                      <span class="userDatatable-title">{{ trans('order.shipping_notes') }}</span>
                    </th>
                    <th class="image">
                      <span class="userDatatable-title">{{ trans('order.image') }}</span>
                    </th>
                    @if(request()?->showShipped)
                      <th class="shipping_date">
                        <span class="userDatatable-title">{{ trans('repo.shipping_date') }}</span>
                      </th>
                      <th class="number">
                        <span class="userDatatable-title">{{ trans('container.number') }}</span>
                      </th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @if (count($items) == 0)
                  <tr>
                    <td colspan="7">
                      <p class="text-center">No Items Found !</p>
                    </td>
                  </tr>
                  @else
                  @foreach ($items as $item)
                  <tr>
                    @if(!request()?->showShipped)
                    <td class="hide-on-print">
                      <div class="custom-checkbox">
                        <input class="check-item" type="checkbox" id="check-{{$item->id}}" data-id="{{$item->id}}">
                        <label for="check-{{$item->id}}">
                        </label>
                      </div>
                    </td>
                    @endif
                    <td class="name">
                      <div class="userDatatable-content">
                        {{ $item->order->client->code }} - {{ $item->order->client->name }}
                      </div>
                    </td>
                    <td class="item">
                      <div class="userDatatable-content item-text">
                        {{ $item->product->code }} - {{ $item->product->name }}
                      </div>
                    </td>
                    <td class="mark">
                      <div class="userDatatable-content">
                        {{ $item->order->client->mark }}
                      </div>
                    </td>
                    <td class="carton_quantity">
                      <div class="userDatatable-content quantity-in dozen_quantity" data-dozen-quantity="{{$item->carton_quantity}}"
                           data-dozen-price="{{$item->dozen_price}}" data-remaining="{{$item->carton_quantity - ((int) $item->sold)}}"
                           data-item='carton' data-item-carton-qty="{{$item->carton_quantity}}" data-item-dozen-price="{{$item->single_price * 12}}">
                        {{ $item->carton_quantity }}
                      </div>
                    </td>
                    <td class="pieces_number">
                      <div class="userDatatable-content">
                        {{ $item->pieces_number }}
                      </div>
                    </td>
                    <td class="cbm">
                      <div class="userDatatable-content cbm-in" data-item='cbm' data-item-cbm="{{$item->cbm}}">
                        {{ $item->cbm }}
                      </div>
                    </td>
                    <td class="weight">
                      <div class="userDatatable-content weight-in" data-item='weight' data-item-weight="{{$item->weight}}">
                        {{ $item->weight }}
                      </div>
                    </td>
                    <td class="receive_date">
                      <div class="userDatatable-content">
                        {{ $item->receive_date }}
                      </div>
                    </td>
                    <td class="receive_notes">
                      <div class="shipping_note_editor" data-id="{{$item->id}}">{{ $item->shipping_notes }}</div>
                    </td>
                    <td class="image">
                      <div class="userDatatable-content">
                        @if($item->product?->image)
                          <a href="{{url('uploads/product/' . $item->product?->image)}}" target="_blank">
                            <img src="{{url('uploads/product/' . $item->product?->image)}}" height="100px" alt="{{$item->product?->name}}">
                          </a>
                        @endif
                      </div>
                    </td>
                    @if(request()?->showShipped)
                      <td class="shipping_date">
                        <div class="userDatatable-content">
                          {{ $item->shipping_date?->format('Y-m-d') }}
                        </div>
                      </td>
                      <td class="number">
                        <div class="userDatatable-content">
                          {{ $item->container_number }}
                        </div>
                      </td>
                    @endif
                  </tr>
                  @endforeach
                  @endif
                </tbody>
                <tfoot>
                  <tr>
                    <td class="hide-on-print"></td>
                    <td class="name">{{ trans('order.total') }}</td>
                    <td class="item"></td>
                    <td class="mark"></td>
                    <td class="carton_quantity" id="carton_quantity_total">0</td>
                    <td class="pieces_number" id="pieces_number"></td>
                    <td class="cbm" id="cbm_total">0</td>
                    <td class="weight" id="weight_total">0</td>
                    <td class="receive_date"></td>
                    <td class="receive_notes"></td>
                    <td class="shipping_date"></td>
                    <td class="number"></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <div class="pagination-container d-flex justify-content-end pt-25">
            {{ $items->links( 'pagination::bootstrap-5' ) }}

            <ul class="dm-pagination d-flex">
              <li class="dm-pagination__item">
                <div class="paging-option">
                  <select name="page-number" class="page-selection" onchange="updatePagination( event )">
                    <option value="20" {{ 20==$items->perPage() ? 'selected' : '' }}>20/page</option>
                    <option value="40" {{ 40==$items->perPage() ? 'selected' : '' }}>40/page</option>
                    <option value="60" {{ 60==$items->perPage() ? 'selected' : '' }}>60/page</option>
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
  <div class="row">
    <div class="col-lg-12 mb-30">
      <div class="card">
        <div class="card-header color-dark fw-500">
          <div>
            {{trans('sales.sales')}}
          </div>
          <div style="display: flex; gap: 10px">
            <button class="btn btn-sm btn-primary confirm-item">
              {{trans('sales.confirm')}}
            </button>
            <button class="btn btn-sm btn-danger cancel-item">
              {{trans('sales.cancel')}}
            </button>
          </div>
        </div>
        <div class="card-body">
          <div id="items-data" class="userDatatable global-shadow border-light-0 w-100">
            <div class="table-responsive">
              <table class="table mb-0 table-borderless" id="items-table">
                <thead>
                  <tr class="userDatatable-header">
                    @if(!request()?->showShipped)
                      <th>
                        <div class="custom-checkbox">
                          <input type="checkbox" id="check-all-selects">
                          <label for="check-all-selects">
                          </label>
                        </div>
                      </th>
                    @endif
                    <th>
                      <span class="userDatatable-title">{{ trans('client.name') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('order.item') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('order.cbm') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('order.carton_quantity') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('order.price') }}</span>
                    </th>
                    <th>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @if (count($items) == 0)
                  <tr>
                    <td colspan="7">
                      <p class="text-center">No Items Found !</p>
                    </td>
                  </tr>
                  @else
                  @foreach ($sales as $item)
                  <tr>
                    @if(!request()?->showShipped)
                      <td>
                        <div class="custom-checkbox">
                          <input class="check-selected" type="checkbox" id="check-{{$item->id}}" data-id="{{$item->id}}">
                          <label for="check-{{$item->id}}">
                          </label>
                        </div>
                      </td>
                    @endif
                    <td>
                      <div class="userDatatable-content">
                        {{ $item->client->code }} - {{ $item->client->name }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $item->item->product->code }} - {{ $item->item->product->name }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content item-cbm" data-cbm="{{ $item->cbm }}">
                        {{ $item->cbm }}
                      </div>
                    </td>
                      <td class="dozen_quantity">
                        <div class="userDatatable-content value">
                          {{$item->quantity}}
                        </div>
                      <div class="item-qty" data-qty="{{ $item->quantity }}">
                        <input type="number" min="1" autocomplete="off" class="hide" data-id="{{$item->id}}"
                               value="{{$item->quantity}}">
                      </div>
                    </td>
                      <td class="dozen_price">
                        <div class="userDatatable-content value">
                          {{$item->price}}
                        </div>
                      <div class="item-prc" data-prc="{{ $item->price }}">
                        <input type="number" autocomplete="off" min="1" class="hide " data-id="{{$item->id}}"
                               value="{{$item->price}}">
                      </div>
                    </td>
                    <td>
                      <button class="btn btn-warning editable-col" data-id="{{$item->id}}">
                        <i class="fa fa-pencil-alt"></i> Edit
                      </button>
                    </td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
                <tfoot>
                <tr>
                  <td class="hide-on-print"></td>
                  <td>{{ trans('order.total') }}</td>
                  <td></td>
                  <td></td>
                  <td id="cbm_total">0</td>
                  <td id="qty_total">0</td>
                  <td id="prc_total">0</td>
                </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <div class="pagination-container d-flex justify-content-end pt-25">
            {{ $items->links( 'pagination::bootstrap-5' ) }}

            <ul class="dm-pagination d-flex">
              <li class="dm-pagination__item">
                <div class="paging-option">
                  <select name="page-number" class="page-selection" onchange="updatePagination( event )">
                    <option value="20" {{ 20==$items->perPage() ? 'selected' : '' }}>20/page</option>
                    <option value="40" {{ 40==$items->perPage() ? 'selected' : '' }}>40/page</option>
                    <option value="60" {{ 60==$items->perPage() ? 'selected' : '' }}>60/page</option>
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
<div class="modal fade" id="load-containers">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select class="form-select" name="container" id="container">
          <option value="">Select a Container</option>
          @foreach ( $containers as $container)
            <option value="{{$container->id}}">{{$container->number.'(' . $container->serial_number . ')'}}</option>
          @endforeach
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary move-to-shipping">Ship items</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="load-clients">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{route('repository.current_items')}}">
          @csrf
          <select class="form-select" name="client" id="client">
            <option value="">{{trans('client.clients')}}</option>
            @foreach ( $clients as $client)
              <option value="{{$client->id}}">{{$client->code.' - '.$client->name}}</option>
            @endforeach
          </select>
          <input type="hidden" name="repo_id" value="{{request()?->repo_id}}">
          <div>
            <table class="table">
              <thead>
              <tr>
                <th>{{ trans('order.item') }}</th>
                <th>{{ trans('order.carton_quantity') }}</th>
              </tr>
              </thead>
              <tbody class="items-in"></tbody>
            </table>
          </div>
          <button type="submit" class="btn btn-primary" name="distribute_items">{{trans('sales.sales')}}</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="load-items">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{route('repository.current_items')}}">
          @csrf
          <select class="form-select" name="repo" id="repo">
            <option value="">{{trans('repo.repos')}}</option>
            @foreach ($repos as $repo)
              @if(request()?->repo_id == $repo->id) @continue @endif
              <option value="{{$repo->id}}">{{$repo->code.' - '.$repo->name}}</option>
            @endforeach
          </select>
          <input type="hidden" name="repo_id" value="{{request()?->repo_id}}">
          <div>
            <table class="table">
              <thead>
              <tr>
                <th>{{ trans('order.item') }}</th>
                <th>{{ trans('order.carton_quantity') }}</th>
              </tr>
              </thead>
              <tbody class="items-in"></tbody>
            </table>
          </div>
          <button type="submit" class="btn btn-primary" name="move_items">{{trans('repo.move')}}</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    $(".select-effector").each(function () {
      if($(this).is(':checked'))
        $($(this).attr('data-effect')).removeClass('hide').removeClass('hide-on-print');
      else
        $($(this).attr('data-effect')).addClass('hide').addClass('hide-on-print');
    });
    $(".select-effector").change(function(){
      if($(this).is(':checked'))
        $($(this).attr('data-effect')).removeClass('hide').removeClass('hide-on-print');
      else
        $($(this).attr('data-effect')).addClass('hide').addClass('hide-on-print');
    });

    let oldQuantity = 0, oldPrice = 0;
    $(".editable-col").click(function () {
      let input = $(this);
      let row = $(this).closest('tr');
      let quantity = row.find('td.dozen_quantity'), price = row.find('td.dozen_price');
      if(!input.hasClass('activated')){
        oldQuantity = quantity.find('input[type="number"]').val()
        oldPrice = price.find('input[type="number"]').val();
        input.addClass('activated').html('<i class="fa fa-save"></i> Save');
        quantity.find('.value').addClass('hide');
        quantity.find('input[type="number"]').removeClass('hide');
        price.find('.value').addClass('hide');
        price.find('input[type="number"]').removeClass('hide');
      }else{
        input.attr('disabled', true);
        let quantity_value = quantity.find('input[type="number"]').val(),
          price_value = price.find('input[type="number"]').val();
        $.ajax({
          url: '{{route("repository.update_quantity")}}',
          type: 'PUT',
          data: {
            _token: '{{ csrf_token() }}',
            item: input.attr('data-id'),
            quantity: quantity_value,
            price: price_value
          },
          dataType: 'json',
          success: function(response) {
            if(response.error){
              Swal.fire({
                icon: "error",
                title: "Error",
                text: response.message,
              });
              input.removeClass('activated').html('<i class="fa fa-pencil-alt"></i> Edit').removeAttr('disabled');
              quantity.find('.value').removeClass('hide');
              quantity.find('input[type="number"]').val(oldQuantity).addClass('hide');
              price.find('.value').removeClass('hide');
              price.find('input[type="number"]').val(oldPrice).addClass('hide');
              return;
            }

            input.removeClass('activated').html('<i class="fa fa-pencil-alt"></i> Edit').removeAttr('disabled');
            quantity.find('.value').text(quantity_value).removeClass('hide');
            quantity.find('input[type="number"]').addClass('hide');
            price.find('.value').text(price_value).removeClass('hide');
            price.find('input[type="number"]').addClass('hide');
          },
          error: function(xhr) {

            if (xhr.status == 422) {
              const keys = Object.keys(xhr.responseJSON.errors);
              keys.forEach((key, index) => {
                toastr["error"](`${xhr.responseJSON.errors[key]}`);
              });
            }else{
              toastr["error"](`error updating item`);
            }
            input.removeClass('activated').html('<i class="fa fa-pencil-alt"></i> Edit').removeAttr('disabled');
            quantity.find('.value').removeClass('hide');
            quantity.find('input[type="number"]').val(oldQuantity).addClass('hide');
            price.find('.value').removeClass('hide');
            price.find('input[type="number"]').val(oldPrice).addClass('hide');
          }
        });

      }
    });
  })

  $(document).ready(function() {

    $('.shipping_note_editor').click(function () {
      let card = $(this);
      if(!card.hasClass('opened')){
        let text = card.text();
        card.addClass('opened').html(`<a class="save-link"><i class="fa fa-save"></i></a><textarea class="inline-editor">${text}</textarea>`);
        let textarea = card.find('textarea');
        textarea.focus();
        card.focusout(function () {
          let newText = textarea.val();
          textarea.attr('disabled', true);
          if(newText != text){
            $.ajax({
              url: '{{route("repository.update_notes")}}',
              type: 'put',
              data: {
                '_token': '{{ csrf_token() }}',
                'item': card.data('id'),
                'note': newText
              },
              dataType: 'json',
              success: function(response) {
                if(response.error){
                  card.removeClass('opened').html(text);
                  toastr["error"](`${response.message}`);
                  return false;
                }
                card.removeClass('opened').html(newText);
              },
              error: function(xhr) {
                card.removeClass('opened').html(text);
                toastr["error"](`failed to update`);
              }
            });
          }else{
            card.removeClass('opened').html(text);
          }
        });
      }
    });

    $(".confirm-item").click(function () {
      let createBtn = $(this);
      let item = createBtn.attr('data-id');
      Swal.fire({
        title: "{{trans('sales.confirm')}}",
        showCancelButton: true,
        confirmButtonText: "{{trans('save')}}",
      }).then((result) => {
        if (result.isConfirmed) {

          let formData = new FormData();
          formData.append('_token', '{{ csrf_token() }}');

          if(!$(".check-selected:checked").length){
            toastr["error"]("please select at least one item");
            createBtn.removeAttr('disabled');
            return;
          }

          $(".check-selected:checked").each(function () {
            formData.append("items[]", $(this).attr('data-id'));
          });

          $.ajax({
            url: '{{route("repository.confirmSale")}}',
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
              createBtn.removeAttr('disabled');
                window.location = '{{route('repository.index', request()?->repo_id)}}';
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
        }
      });
    });

    $(".cancel-item").click(function () {
      let createBtn = $(this);
      let item = createBtn.attr('data-id');
      Swal.fire({
        title: "{{trans('sales.cancel')}}",
        showCancelButton: true,
        confirmButtonText: "{{trans('delete')}}",
      }).then((result) => {
        if (result.isConfirmed) {

          let formData = new FormData();
          formData.append('_token', '{{ csrf_token() }}');

          if(!$(".check-selected:checked").length){
            toastr["error"]("please select at least one item");
            createBtn.removeAttr('disabled');
            return;
          }

          $(".check-selected:checked").each(function () {
            formData.append("items[]", $(this).attr('data-id'));
          });

          $.ajax({
            url: '{{route("repository.declineSale")}}',
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
              createBtn.removeAttr('disabled');
              window.location = '{{route('repository.index', request()?->repo_id)}}';
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
        }
      });
    });

    $(".sale-items").click(function () {
      let content = "";
      $(".check-item:checked").each(function () {
        let row = $(this).closest('tr');
        let remaining = parseInt(row.find('.dozen_quantity').attr('data-remaining'));
        content += `<tr>
                <th><input type="hidden" name="items[]" value="${$(this).attr('data-id')}">
                        ${row.find('.item-text').text()}</th>
                <th><input type="number" class="form-control" name="quantity_${$(this).attr('data-id')}"
                    value="${remaining}" min="1" max="${remaining}}"></th>
              </tr>`
      });
      $("#load-clients").find('.items-in').html(content);
      $("#load-clients").modal('show');
    });

    $(".move-items").click(function () {
      let content = "";
      $(".check-item:checked").each(function () {
        let row = $(this).closest('tr');
        let remaining = parseInt(row.find('.dozen_quantity').attr('data-remaining'));
        content += `<tr>
                <th><input type="hidden" name="items[]" value="${$(this).attr('data-id')}">
                        ${row.find('.item-text').text()}</th>
                <th><input type="number" class="form-control" name="quantity_${$(this).attr('data-id')}"
                    value="${remaining}" min="1" max="${remaining}}"></th>
              </tr>`
      });
      $("#load-items").find('.items-in').html(content);
      $("#load-items").modal('show');
    });

    @if($error)
      @if($error === true)
      toastr.success('Distributed successfully');
      @else
      toastr.error('{{$error}}');
      @endif
    @endif

    $('#repo_id').select2();
    $('#container').select2();
    $('#repo').select2();
    $('#client').select2();
    let checkLength = () => {
      if($('.check-item:checked').length){
        $(".ship-orders").attr('disabled', false);
        $(".sale-items").attr('disabled', false);
        $(".move-items").attr('disabled', false);
      }else{
        $(".ship-orders").attr('disabled', true);
        $(".sale-items").attr('disabled', true);
        $(".move-items").attr('disabled', true);
      }
      if($('.check-selected:checked').length){
        $(".confirm-item").attr('disabled', false);
        $(".cancel-item").attr('disabled', false);
      }else{
        $(".confirm-item").attr('disabled', true);
        $(".cancel-item").attr('disabled', true);
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

      let totalCbm = 0;

      $(".check-item:checked").each(function () {
        formData.append("items[]", $(this).attr('data-id'));
        totalCbm += parseFloat($(this).closest('tr').find('.cbm-in').data('item-cbm'));
      });

      if(totalCbm > 68){
        if(!confirm('{{trans('order.cbm_warning')}}')) {
          createBtn.removeAttr('disabled');
          return;
        }
      }

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
            .hide-on-print{display:none !important}
            tr{display:table-row;}
            tbody tr:not(.selected){display:none!important;}
          `});
    });

    $("#showShipped").change(function () {
      $(".search-dates").toggle($(this).is(":checked"));
    }).trigger('change');

    $('#check-all-items').on('change', function() {
      $('.check-item').prop('checked', $(this).prop('checked'));
      if($(this).prop('checked')){
        $('.print-content').find('tr').each(function(){
          $(this).removeClass('hide-on-print')
        });
      }else{
        $('.print-content').find('tr').each(function(){
          $(this).addClass('hide-on-print')
        });
      }
      collectItems();
      checkLength();
    });

    $('.check-item').on('change', function() {
      var allChecked = $('.check-item:checked').length === $('.check-item').length;
      $('#check-all-items').prop('checked', allChecked);
      if($(this).is(":checked")){
        $(this).closest('tr').removeClass('hide-on-print');
      }else{
        $(this).closest('tr').addClass('hide-on-print');
      }
      collectItems();
      checkLength();
    }).trigger('change');

    $('#print-items').click(function() {

      let printWindow = window.open('', '_blank');
      printWindow.document.open();
      let content = '<html><head><title>Print</title>';

      $("head").find("link").each(function () {
        content += '<link href="' + $(this).attr('href') + '">';
      });
      content += '<style>.head-img{width: 100%;} .no-print{display: none !important;}' +
        '.hide-on-print{display: none !important;} .table{width: 100%} '+
        `body {
              font-family: Arial, sans-serif;
              margin: 0;
              padding: 20px;
              background-color: #f8f9fa;
          }

          table {
              width: 100%;
              border-collapse: collapse;
              margin: 20px 0;
              font-size: 18px;
              text-align: left;
              border: 1px solid #ddd;
              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
          }

          thead tr {
              background-color: #007bff;
              color: #ffffff;
              text-align: left;
          }

          th, td {
              padding: 12px 15px;
              border: 1px solid #ddd;
          }

          table tr:nth-child(odd) {
              background-color: #f3f3f3;
          }

          table tr:nth-child(even) {
              background-color: #ffffff;
          }

          table tr:hover {
              background-color: #f1f1f1;
              cursor: pointer;
          }

          tbody tr {
              border-bottom: 1px solid #ddd;
          }` +
        ' </style></head><body>' +
        '<img src="{{ url('uploads/' . $fileBanner) }}" class="head-img"> '  + $(".print-content").html() + '</body></html>';

      printWindow.document.write(content);

      printWindow.document.close();

      // Wait for content to load before printing
      printWindow.addEventListener('DOMContentLoaded', () => {
        printWindow.print();
        printWindow.close();
      })
    });

    function collectItems() {
      let carton_sum = 0, cbm_sum = 0, weight_sum = 0;
      $('.check-item:checked').each(function () {
        carton_sum += parseFloat($(this).closest('tr').find('.quantity-in').data('item-carton-qty'));
        cbm_sum += parseFloat($(this).closest('tr').find('.cbm-in').data('item-cbm'));
        weight_sum += parseFloat($(this).closest('tr').find('.weight-in').data('item-weight'));
      })
      $('#carton_quantity_total').text(carton_sum);
      $('#cbm_total').text(cbm_sum);
      $('#weight_total').text(weight_sum);
    }

    $('#check-all-selects').on('change', function() {
      $('.check-selected').prop('checked', $(this).prop('checked'));
      collectSelects();
      checkLength();
    });

    $('.check-selected').on('change', function() {
      var allChecked = $('.check-selected:checked').length === $('.check-selected').length;
      $('#check-all-selects').prop('checked', allChecked);
      collectSelects();
      checkLength();
    });
    function collectSelects() {
      let carton_sum = 0, cbm_sum = 0, cbm_cbm_sum = 0;
      $('.check-selected:checked').each(function () {
        carton_sum += parseFloat($(this).closest('tr').find('.item-prc').data('prc'));
        cbm_cbm_sum += parseFloat($(this).closest('tr').find('.item-cbm').data('cbm'));
      })
      $('#prc_total').text(carton_sum);
      $('#qty_total').text(cbm_sum);
      $('#cbm_total').text(cbm_cbm_sum);
    }
  });
</script>
@endsection
