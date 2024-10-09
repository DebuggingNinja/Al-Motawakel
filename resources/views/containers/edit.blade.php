@section('title', $title)
@section('description', $description)
@extends('layout.app')
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
@section('content')
  <div class="container-fluid" id="items-data">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex align-items-center user-member__title mb-30 mt-30">
          <h4 class="text-capitalize">{{ trans('container.edit-container') }}</h4>
        </div>
      </div>
    </div>
    <div class="card mb-50">
      <div class="row p-5">
        <div class="col-12">
          <form action="{{ route('containers.update', $container->id) }}" method="POST" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div class="row">
              <div class="col-12 col-md-3">
                <div class="form-group mb-25">
                  <label for="broker" class="color-dark fs-14 fw-500 align-center">
                    {{ trans('broker.brokers') }}
                    <span class="text-danger">*</span>
                  </label>
                  <select class="form-select" name="broker" id="broker">
                    <option value="">---</option>
                    @foreach ($brokers as $broker)
                      <option value="{{ $broker->id }}" {{ $container->broker_id == $broker->id ? 'selected' : '' }}>
                        {{ $broker->name }} - {{$broker->code}}</option>
                    @endforeach
                  </select>
                  @if ($errors->has('broker'))
                    <p class="text-danger">{{ $errors->first('broker') }}</p>
                  @endif
                </div>
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
                      <option value="{{ $client->id }}" {{ $container->client_id == $client->id ? 'selected' : '' }}>
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
                      <option value="{{ $repo->id }}" {{ $container->repository_id == $repo->id ? 'selected' : '' }}>
                        {{$repo->name . ' - ' . $repo->code}}</option>
                    @endforeach
                  </select>
                  @if ($errors->has('repo'))
                    <p class="text-danger">{{ $errors->first('repo') }}</p>
                  @endif
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="form-group mb-25">
                  <label for="company" class="color-dark fs-14 fw-500 align-center">
                    {{ trans('company.companies') }}
                    <span class="text-danger">*</span>
                  </label>
                  <select class="form-select " name="company" id="company">
                    <option value="">---</option>
                    @foreach ($companies as $company)
                      <option value="{{ $company->id }}" {{ $container->shipping_company_id == $company->id ? 'selected' :
                    '' }}>{{ $company->name }}
                      </option>
                    @endforeach
                  </select>
                  @if ($errors->has('company'))
                    <p class="text-danger">{{ $errors->first('company') }}</p>
                  @endif
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="form-group mb-25">
                  <label for="company" class="color-dark fs-14 fw-500 align-center">
                    {{ trans('container.shipping_type') }}
                    <span class="text-danger">*</span>
                  </label>
                  <select class="form-select " name="shipping_type" id="shipping_type">
                    <option value="">---</option>
                    <option value="complete" {{ $container->shipping_type == 'complete' ? 'selected' : '' }}>complete
                    </option>
                    <option value="partial" {{ $container->shipping_type == 'partial' ? 'selected' : '' }}>partial
                    </option>
                  </select>
                  @if ($errors->has('shipping_type'))
                    <p class="text-danger">{{ $errors->first('shipping_type') }}</p>
                  @endif
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="form-group mb-25">
                  <label for="number" class="color-dark fs-14 fw-500 align-center">{{ trans('container.number') }}
                    <span class="text-danger">*</span></label>
                  <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="number"
                         id="number" value="{{ $container->number }}">
                  @if ($errors->has('number'))
                    <p class="text-danger">{{ $errors->first('number') }}</p>
                  @endif
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="form-group mb-25">
                  <label for="lock_number" class="color-dark fs-14 fw-500 align-center">{{ trans('container.lock_number')
                  }}
                    <span class="text-danger">*</span></label>
                  <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="lock_number"
                         id="lock_number" value="{{ $container->lock_number }}">
                  @if ($errors->has('lock_number'))
                    <p class="text-danger">{{ $errors->first('lock_number') }}</p>
                  @endif
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="form-group mb-25">
                  <label for="destination" class="color-dark fs-14 fw-500 align-center">{{ trans('container.destination')
                  }}
                    <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="destination"
                         id="destination" value="{{ $container->destination }}">
                  @if ($errors->has('destination'))
                    <p class="text-danger">{{ $errors->first('destination') }}</p>
                  @endif
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="form-group mb-0 form-group-calender">
                  <label for="est_arrive_date" class="color-dark fs-14 fw-500 align-center">{{
                  trans('container.est_arrive_date') }}
                    <span class="text-danger">*</span>
                  </label>
                  <input type="date" class="form-control form-control-lg" id="est_arrive_date" name="est_arrive_date"
                         value="{{ $container->est_arrive_date }}">
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="form-group mb-0 form-group-calender">
                  <label for="cost_rmb" class="color-dark fs-14 fw-500 align-center">{{
                  trans('container.cost_rmb') }}
                  </label>
                  <input type="text" class="form-control form-control-lg" id="cost_rmb" name="cost_rmb"
                         value="{{ $container->cost_rmb }}">
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="form-group mb-0 form-group-calender">
                  <label for="cost_dollar" class="color-dark fs-14 fw-500 align-center">{{
                  trans('container.cost_dollar') }}
                  </label>
                  <input type="text" class="form-control form-control-lg" id="cost_dollar" name="cost_dollar"
                         value="{{ $container->cost_dollar }}">
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="form-group mb-0 form-group-calender">
                  <label for="back_rmb" class="color-dark fs-14 fw-500 align-center">
                    {{ trans('Back RMB') }}
                  </label>
                  <input type="text" class="form-control form-control-lg" id="back_rmb" name="back_rmb"
                         value="{{ $container->back_rmb }}">
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="form-group mb-0 form-group-calender">
                  <label for="back_rmb" class="color-dark fs-14 fw-500 align-center">
                    {{ trans('statement.dollar_rate') }}
                  </label>
                  <input type="text" class="form-control form-control-lg" id="dollar_price" name="dollar_price"
                         value="{{ $container->dollar_price }}">
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="form-group mb-0 form-group-calender">
                  <label for="commission" class="color-dark fs-14 fw-500 align-center">{{
                  trans('container.commission') }}
                  </label>
                  <input type="number" class="form-control form-control-lg" id="commission" name="commission"
                         value="{{ $container->commission }}">
                </div>
              </div>
              <div class="col-12">
                <p>{{trans('order.save_to_effect_price')}}</p>
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
    <div id="ajaxPart" class="row">
      <div class="col-12">
        <div class="d-flex gap-2 flex-wrap">
          <div class="form-check">
            <input id="c-orders" type="checkbox" class="form-check-input select-effector" data-effect=".orders" checked>
            <label for="c-orders" class="form-check-label">{{ trans('order.orders') }}</label>
          </div>
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
            <input id="c-cbm" type="checkbox" class="form-check-input select-effector" data-effect=".cbm" checked>
            <label for="c-cbm" class="form-check-label">{{ trans('order.cbm') }}</label>
          </div>
          <div class="form-check">
            <input id="c-dozen_quantity" type="checkbox" class="form-check-input select-effector" data-effect=".dozen_quantity" checked>
            <label for="c-dozen_quantity" class="form-check-label">{{ trans('order.dozen_quantity') }}</label>
          </div>
          <div class="form-check">
            <input id="c-dozen_price" type="checkbox" class="form-check-input select-effector" data-effect=".dozen_price" checked>
            <label for="c-dozen_price" class="form-check-label">{{ trans('order.buy_price_dozen') }}</label>
          </div>
          <div class="form-check">
            <input id="c-cost_dollar" type="checkbox" class="form-check-input select-effector" data-effect=".cost_dollar" checked>
            <label for="c-cost_dollar" class="form-check-label">{{ trans('order.buy_price') }}</label>
          </div>
          <div class="form-check">
            <input id="c-sale_price_dozen" type="checkbox" class="form-check-input select-effector" data-effect=".sale_price_dozen" checked>
            <label for="c-sale_price_dozen" class="form-check-label">{{ trans('order.sale_price_dozen') }}</label>
          </div>
          <div class="form-check">
            <input id="c-sale_price" type="checkbox" class="form-check-input select-effector" data-effect=".sale_price" checked>
            <label for="c-sale_price" class="form-check-label">{{ trans('order.sale_price') }}</label>
          </div>
          <div class="form-check">
            <input id="c-image" type="checkbox" class="form-check-input select-effector" data-effect=".image" checked>
            <label for="c-image" class="form-check-label">{{ trans('order.image') }}</label>
          </div>
          <div class="form-check">
            <input id="c-notes" type="checkbox" class="form-check-input select-effector" data-effect=".notes" checked>
            <label for="c-notes" class="form-check-label">{{ trans('order.notes') }}</label>
          </div>
        </div>
      </div>
      <div class="col-lg-12 mb-30 print-content">
        <div class="card">
          <div class="card-header color-dark fw-500">
            <div>
              {{ trans('container.items') }}
            </div>
            <div class="d-flex gap-1 hide-on-print">
              <button class="btn btn-primary" id="print">{{ trans('print') }}</button>
              <button class="btn btn-primary save-items" id="print">{{ trans('save') }}</button>
            </div>
          </div>
          <div class="card-body">
            <div class="userDatatable global-shadow border-light-0 w-100" dir="auto">
              <div class="table-responsive">
                <table class="table mb-0 table-borderless table-striped" id="items-table" cellpadding="1">
                  <thead>
                  <tr class="userDatatable-header">
                    <th class="no-print">
                      <div class="custom-checkbox">
                        <input type="checkbox" id="check-all-items">
                        <label for="check-all-items">
                        </label>
                      </div>
                    </th>
                    <th class="orders">
                      <span class="userDatatable-title">{{ trans('order.orders') }}</span>
                    </th>
                    <th class="item">
                      <span class="userDatatable-title">{{ trans('order.item') }}</span>
                    </th>

                    <th class="carton_quantity">
                      <span class="userDatatable-title">{{ trans('order.carton_quantity') }}</span>
                    </th>
                    <th class="pieces_number">
                      <span class="userDatatable-title">{{ trans('order.pieces_number') }}</span>
                    </th>
                    <th class="cbm">
                      <span class="userDatatable-title">{{ trans('order.cbm') }}</span>
                    </th>
                    <th class="dozen_quantity">
                      <span class="userDatatable-title">{{ trans('order.dozen_quantity') }}</span>
                    </th>
                    <th class="dozen_price">
                      <span class="userDatatable-title">{{ trans('order.buy_price_dozen') }}</span>
                    </th>
                    <th class="cost_dollar">
                      <span class="userDatatable-title">{{ trans('order.buy_price') }}</span>
                    </th>
                    <th class="sale_price_dozen">
                      <span class="userDatatable-title">{{ trans('order.sale_price_dozen') }}</span>
                    </th>
                    <th class="sale_price">
                      <span class="userDatatable-title">{{ trans('order.sale_price') }}</span>
                    </th>
                    <th class="image">
                      <span class="userDatatable-title">{{ trans('order.image') }}</span>
                    </th>
                    <th class="notes">
                      <span class="userDatatable-title">{{ trans('notes') }}</span>
                    </th>
                    <th class="hide-on-print">
                      <span class="userDatatable-title">{{ trans('actions') }}</span>
                    </th>
                  </tr>
                  </thead>
                  <tbody>
                  @if (!count($items))
                    <tr>
                      <td colspan="7">
                        <p class="text-center">No Items Found !</p>
                      </td>
                    </tr>
                  @else
                    @php
                      $counter = 1;
                    @endphp
                    @foreach ($items as $item)
                      <tr>
                        <form class="item-form" id="item-{{ $item->item->id }}" data-item-id="{{ $item->item->id }}" method="POST">
                          @csrf
                          <input type="hidden" class="container_id" name="container_id" value="{{ $container->id }}">
                          <input type="hidden" class="item_id" name="item_id" value="{{ $item->id }}">
                          <td class="no-print">
                            <div class="custom-checkbox">
                              <input class="check-item" type="checkbox" id="check-{{$item->id}}" data-id="{{$item->id}}">
                              <label for="check-{{$item->id}}">
                              </label>
                            </div>
                          </td>
                          <td class="orders">
                            <div class="userDatatable-content">
                              <a href="{{route('buy_ship_orders.edit', $item->order->id)}}" target="_blank">
                                {{ $item->order->code }}
                              </a>
                            </div>
                          </td>
                          <td class="item">
                            <div class="userDatatable-content">
                              {{ $item->product->product->name }} - {{ $item->product->product->code }}
                            </div>
                          </td>
                          <td class="carton_quantity">
                            <div class="userDatatable-content collect_carton_quantity" data-value="{{ $item->quantity }}">
                              {{ $item->quantity }}
                            </div>
                          </td>
                          <td class="pieces_number">
                            <div class="userDatatable-content collect_pieces_number"
                                 data-value="{{ $item->product->product->pieces_number }}">
                              {{ $item->product->product->pieces_number }}
                            </div>
                            <input type="hidden" name="pieces_number" value="{{ $item->product->product->pieces_number }}">
                          </td>
                          <td class="cbm">
                            <div class="userDatatable-content cbm collect_cbm" data-item-id="{{ $item->item->id }}"
                                 data-value="{{ $item->cbm }}">
                              {{ $item->cbm }}
                            </div>
                          </td>
                          <td class="dozen_quantity">
                            @php
                              $dozenQuantity = (float) number_format(($item->product->carton_quantity * $item->product->pieces_number) / 12, 3, '.', '');
                              $dozenSinglePrice = (float) number_format(($item->product->single_price * 12) / ($item->order->dollar_price ?? 1), 3, '.', '');
                              $dollarPrice = (float) number_format($dozenSinglePrice * $dozenQuantity, 3, '.', '');
                              $commission = $container->commission && $dozenSinglePrice ? (($dozenSinglePrice / 100) * $container->commission) : 0;
                              $singlePlusCommission = (float) number_format($item->item->total ? ($item->item->total / $dozenQuantity) : $dozenSinglePrice + $commission, 3, '.', '');
                              $totalPlusCommission = (float) number_format($singlePlusCommission * $dozenQuantity, 3, '.', '');
                            @endphp
                            <div class="userDatatable-content collect_dozen_quantity" data-value="{{ $dozenQuantity }}">
                              {{ $dozenQuantity }}
                            </div>
                            <input type="hidden" name="dozen_quantity" value="{{ $dozenQuantity }}">
                          </td>
                          <td class="dozen_price">
                            <div class="userDatatable-content collect_dozen_price" data-value="{{$dozenSinglePrice}}">
                              {{ $dozenSinglePrice }}
                            </div>
                          </td>
                          <td class="cost_dollar">
                            <div class="userDatatable-content  collect_cost_dollar" data-value="{{$dollarPrice}}">
                              {{ $dollarPrice }}
                            </div>
                          </td>
                          <td class="sale_price_dozen">
                            <div class="userDatatable-content  collect_sale_price_dozen" data-value="{{$singlePlusCommission}}">
                              <input class="total_{{ $item->item->id }}" type="text" name="item_dollar_price"
                                     value="{{$singlePlusCommission}}">
                            </div>
                          </td>
                          <td class="sale_price price_live_change">
                            <div class="userDatatable-content collect_sale_price"
                                 data-value="{{ $totalPlusCommission }}">
                              {{ $totalPlusCommission }}
                            </div>
                          </td>
                          <td class="image">
                            @if ($item->product->product->image)
                              <a href="{{url('uploads/product/' . $item->product->product->image)}}" target="_blank">
                                <img src="{{url('uploads/product/' . $item->product->product->image)}}" height="100px" alt="{{$item->product->product->name}}">
                              </a>
                            @endif
                          </td>
                          <td class="notes">
                            <div class="userDatatable-content">
                                <textarea class="notes" name="notes" rows="1" data-item="notes"
                                    data-item-notes="{{ $item->item->notes ?? '' }}">{{ $item->item->notes ?? '' }}</textarea>
                            </div>
                          </td>
                          <td>
                            <button class="btn btn-success save-item" type="submit" data-item-id="{{ $item->item->id }}">
                              <i class="la la-upload fs-4 me-0"></i>
                            </button>
                          </td>
                        </form>
                      </tr>
                    @endforeach
                  @endif
                  </tbody>
                </table>
              </div>
              <hr>
              <table class="table table-striped">
                <tr>
                  <th class="carton_quantity">
                      {{trans('order.carton_quantity')}}
                  </th>
                  <th class="pieces_number">
                    {{trans('order.pieces_number2')}}
                  </th>
                  <th class="cbm">
                    {{trans('order.cbm')}}
                  </th>
                  <th class="cost_dollar">
                    {{trans('container.cost_dollar')}}
                  </th>
                  <th class="sale_price">
                    {{trans('order.sale_price')}}
                  </th>
                </tr>
                <tr>
                  <th class="carton_quantity total_carton_quantity">
                  </th>
                  <th class="pieces_number total_pieces_number">
                  </th>
                  <th class="cbm total_cbm">
                  </th>
                  <th class="cost_dollar total_cost_dollar">
                  </th>
                  <th class="sale_price total_sale_price">
                  </th>
                </tr>
              </table>
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

      function collectItems() {
        let carton_quantity = 0, pieces_number = 0, cbm = 0, dozen_quantity = 0, dozen_price = 0,
          cost_dollar = 0, sale_price_dozen = 0, sale_price = 0;
        $('.check-item:checked').each(function () {
          carton_quantity += parseFloat($(this).closest('tr').find('.collect_carton_quantity').data("value"));
          pieces_number += parseFloat($(this).closest('tr').find('.collect_pieces_number').data("value"))
            * parseFloat($(this).closest('tr').find('.collect_carton_quantity').data("value"));
          cbm += parseFloat($(this).closest('tr').find('.collect_cbm').data("value"));
          dozen_quantity += parseFloat($(this).closest('tr').find('.collect_dozen_quantity').data("value"));
          dozen_price += parseFloat($(this).closest('tr').find('.collect_dozen_price').data("value"));
          cost_dollar += parseFloat($(this).closest('tr').find('.collect_cost_dollar').data("value"));
          sale_price_dozen += parseFloat($(this).closest('tr').find('.collect_sale_price_dozen').data("value"));
          sale_price += parseFloat($(this).closest('tr').find('.collect_sale_price').data("value"));
        })
        $('.total_carton_quantity').html(carton_quantity);
        $('.total_pieces_number').html(pieces_number);
        $('.total_cbm').html(cbm.toFixed(2));
        $('.total_dozen_quantity').html(dozen_quantity.toFixed(2));
        $('.total_dozen_price').html(dozen_price.toFixed(2));
        $('.total_cost_dollar').html(cost_dollar.toFixed(2));
        $('.total_sale_price_dozen').html(sale_price_dozen.toFixed(2));
        $('.total_sale_price').html(sale_price.toFixed(2));
      }

      let checkLength = () => {
        if($('.check-item:checked').length){
          $(".update-item").attr('disabled', false);
          $(".delete-item-bulk").attr('disabled', false);
        }else{
          $(".update-item").attr('disabled', true);
          $(".delete-item-bulk").attr('disabled', true);
        }
      }

      checkLength();

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

      $(".select-effector").each(function () {
        if($(this).is(':checked'))
          $($(this).attr('data-effect')).removeClass('hide').removeClass('hide-on-print');
        else
          $($(this).attr('data-effect')).addClass('hide').addClass('hide-on-print');
      }).change(function(){
        if($(this).is(':checked'))
          $($(this).attr('data-effect')).removeClass('hide').removeClass('hide-on-print');
        else
          $($(this).attr('data-effect')).addClass('hide').addClass('hide-on-print');
      });

      $('#repo').select2();
      $('#broker').select2();
      $('#client').select2();
      $('#company').select2();
      $('#shipping_type').select2();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $('#print').click(function() {

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
          '<img src="{{ url('uploads/' . $fileBanner) }}" class="head-img"> '  +  $(".print-content").html() + '</body></html>';

        printWindow.document.write(content);

        printWindow.document.close();

        // Wait for content to load before printing
        printWindow.addEventListener('DOMContentLoaded', () => {
          printWindow.print();
          printWindow.close();
        })
      });

      $('input[name="item_dollar_price"]').on('input', function (){
        let effect = $(this).closest('tr').find('.price_live_change').find('.collect_sale_price');
        let value = parseFloat($(this).val());
        let quantity = parseInt($(this).closest('tr').find('input[name="dozen_quantity"]').val());
        let total = (value * quantity).toFixed(2);
        effect.data('value', total).text(total);
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
