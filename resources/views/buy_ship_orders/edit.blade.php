@section('title', $title)
@section('description', $description)
@section('style')
  <style>
    .orderDatatable_actions{
      min-width: fit-content !important;
    }
    .hide{
      display: none;
    }
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
    .flex-container{
      display: flex;
      justify-content: center;
      align-items: center;
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
          <div class="invoice-data hide">
            <table class="table">
              <tr>
                <th>{{ trans('order.code') }}</th>
                <th>{{$order->code}}</th>
              </tr>
              <tr>
                <th>{{ trans('client.clients') }}</th>
                <th>{{$order->client?->name . " " . $order->client?->code}}</th>
              </tr>
              <tr>
                <th>{{ trans('supplier.suppliers') }}</th>
                <th>{{$order->supplier?->name . " " . $order->supplier?->code}}</th>
              </tr>
              <tr>
                <th>{{ trans('supplier.store_number') }}</th>
                <th>{{$order->supplier?->store_number}}</th>
              </tr>
              <tr>
                <th>{{ trans('repo.repos') }}</th>
                <th>{{$order->repo?->name . " " . $order->repo?->code}}</th>
              </tr>
              <tr>
                <th>{{ trans('order.check_date') }}</th>
                <th>{{$order?->check_date}}</th>
              </tr>
            </table>
          </div>
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
                      <option value="{{ $repo->id }}" {{ $order->repo?->id == $repo->id ? 'selected' : '' }}>
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
              <div class="col-12 col-md-4">
                <div class="form-group mb-25">
                  <label for="dollar_price" class="color-dark fs-14 fw-500 align-center">
                    {{ trans('statement.enter_dollar_rate') }}
                    <span class="text-danger">*</span>
                  </label>
                  <input class="form-control " value="{{$order->dollar_price}}" type="text" name="dollar_price" id="dollar_price">
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


    @can('add buy ship items')
      <div class="card">
        <div class="card-header">
          {{ trans('order.add-item') }}
        </div>
        <div class="card-body">
          <form class="save-item-form" method="post" action="{{ route('buy_ship_items.store') }}"
                enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-4">
                @csrf
                <input type="hidden" name="order" value="{{$order->id}}">
                <label for="product">{{ trans('products.products') }}</label>
                <select class="form-select" name="product" id="product">
                  <option value="">---</option>
                  @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->code }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label for="carton_quantity">{{ trans('order.carton_quantity') }}</label>
                <input id="carton_quantity" name="carton_quantity" type="number" class="form-control">
              </div>
              <div class="col-md-3">
                <label for="single_price">{{ trans('order.single_price') }}</label>
                <input id="single_price" name="single_price" type="text" class="form-control">
              </div>
              <div class="col-md-2 flex-container">
                <button type="submit" class="btn btn-primary">
                  {{ trans('order.add-item') }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    @endcan
    @can('view buy ship items')
      <div class="row">
        <div class="col-lg-12">
          <div class="d-flex align-items-center justify-content-between user-member__title mb-30 mt-30">
            <h4 class="text-capitalize">{{ trans('order.items') }}</h4>
            <div class="d-flex align-items-center justify-content-end gap-2">
              <button id="print-items" class="btn btn-primary">print</button>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="row mb-3" id="check-columns">
            <div class="col-12">
              <div class="d-flex gap-2 flex-wrap">
                <div class="form-check">
                  <input id="c-item" type="checkbox" class="form-check-input select-effector" data-effect=".item" checked>
                  <label for="c-item" class="form-check-label">{{ trans('order.item') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-carton_quantity" type="checkbox" class="form-check-input select-effector" data-effect=".carton_quantity" checked>
                  <label for="c-carton_quantity" class="form-check-label">{{ trans('order.carton_quantity') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-pieces_number" type="checkbox" class="form-check-input select-effector" data-effect=".pieces_number" checked>
                  <label for="c-pieces_number" class="form-check-label">{{ trans('order.pieces_number') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-single_price" type="checkbox" class="form-check-input select-effector" data-effect=".single_price" checked>
                  <label for="c-single_price" class="form-check-label">{{ trans('order.single_price') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-total" type="checkbox" class="form-check-input select-effector" data-effect=".total" checked>
                  <label for="c-total" class="form-check-label">{{ trans('order.total') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-receive_date" type="checkbox" class="form-check-input select-effector" data-effect=".receive_date" checked>
                  <label for="c-receive_date" class="form-check-label">{{ trans('order.receive_date') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-cbm" type="checkbox" class="form-check-input select-effector" data-effect=".cbm" checked>
                  <label for="c-cbm" class="form-check-label">{{ trans('order.cbm') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-check_image" type="checkbox" class="form-check-input select-effector" data-effect=".check_image">
                  <label for="c-check_image" class="form-check-label">{{ trans('order.check_image') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-weight" type="checkbox" class="form-check-input select-effector" data-effect=".weight" checked>
                  <label for="c-weight" class="form-check-label">{{ trans('order.weight') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-status" type="checkbox" class="form-check-input select-effector" data-effect=".status" checked>
                  <label for="c-status" class="form-check-label">{{ trans('order.status') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-measuring" type="checkbox" class="form-check-input select-effector" data-effect=".measuring" checked>
                  <label for="c-measuring" class="form-check-label">{{ trans('order.measuring') }}</label>
                </div>
                <div class="form-check">
                  <input id="c-container_number" type="checkbox" class="form-check-input select-effector" data-effect=".container_number" checked>
                  <label for="c-container_number" class="form-check-label">{{ trans('order.container_number') }}</label>
                </div>
              </div>
            </div>
          </div>
          <div id="items">
            <div class="row">
              <div class="col-lg-12 mb-30">
                <div class="card print-content">
                  <div class="card-header color-dark fw-500">
                    <div>
                      {{trans('order.items')}}
                    </div>
                    <div style="display: flex; gap: 10px" class="no-print">
                      <button class="btn btn-sm btn-primary update-item">
                        {{trans('order.update-item')}}
                      </button>
                      <button class="btn btn-sm btn-danger delete-item-bulk">
                        {{trans('order.delete-item')}}
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="userDatatable global-shadow border-light-0">
                      <div class="table-responsive table-responsive-md">
                        <table class="table table-striped w-100" id="items-table">
                          <thead>
                          <tr class="userDatatable-header">
                            <th class="no-print">
                              <div class="custom-checkbox">
                                <input type="checkbox" id="check-all-items">
                                <label for="check-all-items">
                                </label>
                              </div>
                            </th>
                            <th class="item">
                              <span class="userDatatable-title">{{ trans('order.item') }}</span>
                            </th>
                            <th class="measuring">
                              <span class="userDatatable-title">{{ trans('order.measuring') }}</span>
                            </th>
                            <th class="carton_quantity">
                              <span class="userDatatable-title">{{ trans('order.carton_quantity') }}</span>
                            </th>
                            <th class="pieces_number">
                              <span class="userDatatable-title">{{ trans('order.pieces_number') }}</span>
                            </th>
                            <th class="single_price">
                              <span class="userDatatable-title">{{ trans('order.single_price') }}</span>
                            </th>
                            <th class="total">
                              <span class="userDatatable-title">{{ trans('order.total') }}</span>
                            </th>
                            <th class="receive_date">
                              <span class="userDatatable-title">{{ trans('order.receive_date') }}</span>
                            </th>
                            <th class="cbm">
                              <span class="userDatatable-title">{{ trans('order.cbm') }}</span>
                            </th>
                            <th class="weight">
                              <span class="userDatatable-title">{{ trans('order.weight') }}</span>
                            </th>
                            <th class="status">
                              <span class="userDatatable-title">{{ trans('order.status') }}</span>
                            </th>
                            <th class="container_number">
                              <span class="userDatatable-title">{{ trans('order.container_number') }}</span>
                            </th>
                            <th class="check_image">
                              <span class="userDatatable-title">{{ trans('order.check_image') }}</span>
                            </th>
                          </tr>
                          </thead>
                          <tbody>
                          @php($count = 1)
                          @foreach ($order->items as $item)
                            <tr class="userDatatable-header">
                              <td class="no-print">
                                <div class="custom-checkbox">
                                  <input class="check-item" type="checkbox" id="check-{{$item->id}}" data-id="{{$item->id}}">
                                  <label for="check-{{$item->id}}">
                                  </label>
                                </div>
                              </td>
                              <td class="item">
                                <span class="userDatatable-title">{{ $item->product?->code . " - " . $item->product?->name  }}</span>
                              </td>
                              <td class="measuring">
                                <span class="userDatatable-title">{{ $item->product?->measuring }}</span>
                              </td>
                              <td class="carton_quantity">
                                <span class="userDatatable-title data-quantity" data-quantity="{{ $item->carton_quantity }}"
                                  data-sum="{{$item->carton_quantity * $item->pieces_number}}">
                                  {{ $item->carton_quantity }}
                                </span>
                              </td>
                              <td class="pieces_number">
                                <span class="userDatatable-title data-pieces_number">
                                  {{ $item->pieces_number }}
                                </span>
                              </td>
                              <td class="single_price">
                                <span class="userDatatable-title">{{ $item->single_price }}</span>
                              </td>
                              <td class="total">
                                <span class="userDatatable-title data-total" data-total="{{ $item->total }}">{{ $item->total }}</span>
                              </td>
                              <td class="receive_date">
                                <span class="userDatatable-title">{{ $item->receive_date }}</span>
                              </td>
                              <td class="cbm">
                                <span class="userDatatable-title data-cbm" data-cbm="{{ $item->cbm }}">{{ $item->cbm }}</span>
                              </td>
                              <td class="weight">
                                <span class="userDatatable-title data-weight" data-weight="{{ $item->weight }}">{{ $item->weight }}</span>
                              </td>
                              <td class="status">
                                <div class="ignore badge badge-{{$item->status}}">
                                  {{trans($item->status)}}
                                </div>
                              </td>
                              <td class="container_number">
                                <span class="userDatatable-title">{{ $item->container_number }}</span>
                              </td>
                              <td class="check_image">
                                @if($item->check_image)
                                  <a href="{{ url('uploads/check_images/' . $item->check_image) }}" target="_blank">
                                    <img style="height: 60px; width: auto;"
                                         src="{{ url('uploads/check_images/' . $item->check_image) }}"
                                         class="form-control" />
                                  </a>
                                @endif
                              </td>
                            </tr>
                          @endforeach
                          </tbody>
                          <tfoot>
                          <tr>
                            <td class="hide-on-print"></td>
                            <td class="item">{{ trans('order.total') }}</td>
                            <td id="" class="measuring"></td>
                            <td id="d-quantity" class="carton_quantity">0</td>
                            <td id="" class="pieces_number"></td>
                            <td id="" class="single_price"></td>
                            <td id="d-total" class="total">0</td>
                            <td class="receive_date"></td>
                            <td id="d-cbm" class="cbm">0</td>
                            <td id="d-weight" class="weight">0</td>
                            <td id="" class="status"></td>
                            <td id="" class="container_number"></td>
                            <td id="" class="check_image"></td>
                          </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modals">
              @foreach ($order->items as $item)
                <div class="modal single-item-modal-{{ $item->id }}" tabindex="-1" role="dialog">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form class="items-data-form" id="create-item-form-{{ $item->id }}"
                              enctype="multipart/form-data">
                          <div class="row">
                            <div class="col-md-6">
                              <input name="id" type="hidden" value="{{ $item->id }}">
                              <input name="order_id" type="hidden" value="{{ $order->id }}">
                              <label>{{ trans('products.products') }}</label>
                              <select class="form-select products-list" name="product">
                                <option value="">---</option>
                                @foreach ($products as $product)
                                  <option value="{{ $product->id }}" @if($item->product_id == $product->id) selected @endif>{{ $product->name }} - {{ $product->code }}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-md-3">
                              <label>{{ trans('order.carton_quantity') }}</label>
                              <input name="carton_quantity" type="number" class="form-control" value="{{ $item->carton_quantity }}">
                            </div>

                            <div class="col-md-3">
                              <label>{{ trans('order.single_price') }}</label>
                              <input name="single_price" type="number" class="form-control" value="{{ $item->single_price }}">
                            </div>

                            <div class="col-12">
                              <br>
                            </div>

                            <div class="col-md-3">
                              <label>{{ trans('order.receive_date') }}</label>
                              <input id="receive_date_{{ $item->id }}" name="receive_date" type="date" class="form-control"
                                     value="{{ $item->receive_date }}" {{ $item->status != 'waiting' ? 'readonly' : null }}>
                            </div>

                            <div class="col-md-3">
                              <label>{{ trans('order.cbm') }}</label>
                              <input name="cbm" type="number" class="form-control" value="{{ $item->cbm }}">
                            </div>
                            <div class="col-md-3">
                              <label>{{ trans('order.weight') }}</label>
                              <input name="weight" type="number" class="form-control" value="{{ $item->weight }}">
                            </div>
                            <div class="col-md-3">
                              <label>{{ trans('order.measuring') }}</label>
                              <input name="measuring" type="text" class="form-control" value="{{ $item->measuring }}">
                            </div>
                            <div class="col-12">
                              <br>
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
                            @if ($item->check_image != null)
                              <div class="col-md-4">
                                <input name="check_image" type="file" size="1000000" accept="image/*">
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
                          <div class="col-12">
                            <br>
                          </div>
                          <div class="col-12">
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
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
              <div class="modal bulk-items-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form class="bulk-data-form"
                            enctype="multipart/form-data">
                        <div class="row">
                          <div class="col-md-3">
                            <label>{{ trans('order.receive_date') }}</label>
                            <input id="receive_date_bulk" name="receive_date" type="date"
                                   class="form-control" disabled>
                          </div>

                          <div class="col-md-4">
                            <label>{{ trans('order.status') }}</label>
                            <select class="status_bulk form-control" name="status">
                              <option value="requested" selected>requested</option>
                              <option value="checked">checked</option>
                              <option value="waiting">waiting</option>
                              <option value="received">received</option>
                              <option value="cancelled">cancelled</option>
                            </select>
                          </div>
                          <div class="col-md-4">
                            <label>{{ trans('order.check_image') }}</label>
                            <input name="check_image" type="file" size="1000000" accept="image/*" disabled>
                          </div>
                          <div class="col-12">
                            <br>
                          </div>

                          <div class="col-md-4">
                            <label>{{ trans('order.check_notes') }}</label>
                            <textarea id="check_notes_bulk" class="check_notes form-control"
                                      name="check_notes" cols="20" style="min-width:100px"></textarea>
                          </div>
                          <div class="col-md-4">
                            <label>{{ trans('order.receive_notes') }}</label>
                            <textarea  id="receive_notes_bulk" class="receive_notes form-control"
                                      name="receive_notes" cols="20" style="min-width:100px" disabled></textarea>
                          </div>
                          <div class="col-md-4">
                            <label>{{ trans('order.cancelled_notes') }}</label>
                            <textarea id="cancelled_notes_bulk" class="cancelled_notes form-control"
                                      name="cancelled_notes" cols="20"
                                      style="min-width:100px" disabled></textarea>
                          </div>

                        </div>
                        <div class="col-12">
                          <br>
                        </div>
                        <div class="col-12">
                          <div class="position-absolute bottom-0 end-0 d-flex gap-2">
                            @can('update buy ship items')
                              <button type="submit" class="bulk-items btn btn-sm btn-success me-2 mb-2">
                                <i class="la la-upload fs-4 me-0"></i>
                              </button>
                            @endcan
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endcan
  </div>
  @include('buy_ship_orders.components.createSupplierModal')
@endsection
@section('scripts')
  <script>
    let contNum = {{$count}};
    $(document).ready(function () {
      initJquery();
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
            updateSelect2Options(response);
            $('#createSupplierModal').modal('hide');
          } else {
            alert('Failed to create supplier.'); // Display error message
          }
        },
        error: function(xhr, status, error) {
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
      $('#product').select2();
      $('.products-list').each(function () {
        $(this).select2({
        });
      });
      $('#repo').select2();
    }

    function initJquery(){
      initiateSelect2();

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


      $('#check-all-items').change(function() {
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

      let checkLength = () => {
        if($('.check-item:checked').length){
          $(".update-item").attr('disabled', false);
          $(".delete-item-bulk").attr('disabled', false);
        }else{
          $(".update-item").attr('disabled', true);
          $(".delete-item-bulk").attr('disabled', true);
        }
      }

      $('.check-item').on('change', function() {
        let allChecked = $('.check-item:checked').length === $('.check-item').length;
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
          '.hide-on-print{display: none !important;}.table{width: 100%} '+
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
          '<img src="{{ url('uploads/' . $fileBanner) }}" class="head-img"> '  + $(".invoice-data").html()  + $(".print-content").html() + '</body></html>';

        printWindow.document.write(content);

        printWindow.document.close();

        // Wait for content to load before printing
        printWindow.addEventListener('DOMContentLoaded', () => {
          printWindow.print();
          printWindow.close();
        })
      });

      function collectItems() {
        let quantity_sum = 0, carton_sum = 0, cbm_sum = 0, weight_sum = 0, total_sum = 0;
        $('.check-item:checked').each(function () {
          carton_sum += parseFloat($(this).closest('tr').find('.data-quantity').data("quantity"));
          // quantity_sum += parseFloat($(this).closest('tr').find('.data-quantity').data("sum"));
          cbm_sum += parseFloat($(this).closest('tr').find('.data-cbm').data('cbm'));
          weight_sum += parseFloat($(this).closest('tr').find('.data-weight').data('weight'));
          total_sum += parseFloat($(this).closest('tr').find('.data-total').data('total'));
        })
        $('#d-quantity').html(carton_sum);
        $('#d-cbm').text(cbm_sum);
        $('#d-weight').text(weight_sum);
        $('#d-total').text(total_sum);
      }

      checkLength();

      $(".delete-item-bulk").off('click');
      $(".delete-item-bulk").click(function () {
        let createBtn = $(this);
        Swal.fire({
          title: "{{trans('order.delete-item')}}",
          showCancelButton: true,
          confirmButtonText: "{{trans('delete')}}",
        }).then((result) => {
          if (result.isConfirmed) {
            createBtn.attr('disabled', true);
            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');

            if(!$(".check-item:checked").length){
              toastr["error"]("please select at least one item");
              createBtn.removeAttr('disabled');
              return;
            }

            $(".check-item:checked").each(function () {
              formData.append("items[]", $(this).attr('data-id'));
            });

            $.ajax({
              url: '{{route("buy_ship_items.delete_items")}}',
              type: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              dataType: 'json',
              success: function(response) {
                if(response.error){
                  Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: response.message,
                  });
                  createBtn.removeAttr('disabled');
                  return false;
                }
                $('#items').load(" #items",function(){
                  initJquery();
                });
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

      $(".update-item").off('click');
      $(".update-item").click(function () {
        let checked = $(".check-item:checked");
        if(!checked.length){
          toastr["error"]("please select at least one item");
          return;
        }

        if(checked.length === 1){

          $(".single-item-modal-"+checked.data("id")).modal("show")

        }else{

          $(".bulk-items-modal").modal("show")

        }

      });

      $('.bulk-items').off('click').click(function(e) {
        var createBtn = $(this);
        createBtn.attr('disabled','disabled');
        e.preventDefault();
        // Create a FormData object
        var formData = new FormData(createBtn.closest('.bulk-data-form')[0]);

        // Append the CSRF token to the FormData
        formData.append('_token', '{{ csrf_token() }}');

        $(".check-item:checked").each(function () {
          formData.append("items[]", $(this).attr('data-id'));
        });

        $(this).html('<i class="la la-sync fs-4 me-0"></i>');

        $.ajax({
          data: formData,
          url: "{{ route('buy_ship_items.update_bulk') }}",
          type: "POST",
          contentType: false, // Important to prevent jQuery from automatically setting the content type
          processData: false, // Important to prevent jQuery from processing the data
          dataType: 'json',
          success: function(data) {

            createBtn.html('<i class="la la-upload fs-4 me-0"></i>');
            $(".modal").modal('hide');

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
            $('#items').load(" #items",function(){
              initJquery();
            });
          },
          error: function(xhr) {

            if (xhr.status == 422) {
              const keys = Object.keys(xhr.responseJSON.errors);
              keys.forEach((key, index) => {
                createBtn.removeAttr('disabled');
                toastr["error"](`${xhr.responseJSON.errors[key]}`);
              });
            }

            createBtn.html('<i class="la la-upload fs-4 me-0"></i>');
          }
        });
      });

      $('.save-item').off('click').click(function(e) {
        var createBtn = $(this);
        createBtn.attr('disabled','disabled');
        e.preventDefault();
        var form_id = $(this).data('item-id');

        // Create a FormData object
        var formData = new FormData(createBtn.closest('.items-data-form')[0]);

        // Append the CSRF token to the FormData
        formData.append('_token', '{{ csrf_token() }}');

        $(this).html('<i class="la la-sync fs-4 me-0"></i>');

        $.ajax({
          data: formData,
          url: "{{ route('buy_ship_items.update') }}",
          type: "POST",
          contentType: false, // Important to prevent jQuery from automatically setting the content type
          processData: false, // Important to prevent jQuery from processing the data
          dataType: 'json',
          success: function(data) {

            createBtn.html('<i class="la la-upload fs-4 me-0"></i>');
            $(".modal").modal('hide');
            $('#items').load(" #items",function(){  initJquery();});

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

            createBtn.html('<i class="la la-upload fs-4 me-0"></i>');
          }
        });
      });
      $('.delete-item').off('click').click(function(e) {
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
                $('#items').load(' #items', function () {
                  initJquery();
                });
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
                $('.save-item').html('<i class="la la-window-close fs-4 me-0"></i>');
                toastr["error"]("Something went wrong");
              }
            });
          } else {
            $('.save-item').html('<i class="la la-window-close fs-4 me-0"></i>');
          }
        });


      });
      $('.status').off('change').change(function(e) {
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
      $('.status_bulk').off('change').change(function(e) {
        if ($(this).val() === 'checked') {
          $(`#receive_notes_bulk`).prop('disabled', false);
        } else {
          $(`#receive_notes_bulk`).prop('disabled', true);
        }

        if ($(this).val() === 'requested') {
          $(`#check_notes_bulk`).prop('disabled', false)
        } else {
          $(`#check_notes_bulk`).prop('disabled', true)
        }

        if ($(this).val() === 'cancelled') {
          $(`#cancelled_notes_bulk`).prop('disabled', false)
        } else {
          $(`#cancelled_notes_bulk`).prop('disabled', true)
        }

        if ($(this).val() === 'checked') {
          $(`#check_image_bulk`).prop('disabled', true)
        } else {
          $(`#check_image_bulk`).prop('disabled', false)
        }
        if ($(this).val() === 'waiting') {
          $(`#receive_date_bulk`).prop('disabled', true)
        } else {
          $(`#receive_date_bulk`).prop('disabled', false)
        }
        if ($(this).val() === 'waiting') {
          $(`#receive_date_bulk`).prop('disabled', false)
        } else {
          $(`#receive_date_bulk`).prop('disabled', true)
        }
      });
    }

    $(document).ready(function() {
      initJquery();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $('.save-item-form').submit(function(e) {
        e.preventDefault();
        let form = $(this), createBtn = form.find('button[type="submit"]');
        createBtn.attr('disabled','disabled');

        // Create a FormData object
        let formData = new FormData(form[0]);

        // Append the CSRF token to the FormData
        formData.append('_token', '{{ csrf_token() }}');

        createBtn.html('<i class="la la-sync fs-4 me-0"></i>');

        $.ajax({
          data: formData,
          url: form.attr('action'),
          type: form.attr('method'),
          contentType: false,
          processData: false,
          dataType: 'json',
          success: function(data) {

            $('#items').load(" #items",function(){
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
              createBtn.removeAttr('disabled');
              createBtn.html('{{ trans('order.add-item') }}');
              initJquery();
            });

          },
          error: function(xhr) {

            if (xhr.status == 422) {
              const keys = Object.keys(xhr.responseJSON.errors);
              keys.forEach((key, index) => {
                createBtn.removeAttr('disabled');
                toastr["error"](`${xhr.responseJSON.errors[key]}`);
              });
            }

            createBtn.html('{{ trans('order.add-item') }}');
          }
        });
      });

    });


  </script>

@endsection
