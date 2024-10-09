@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
<div class="crm mb-25">
  <div class="container-fluid">
    <div class="row ">
      <div class="col-lg-12">
        <div class="breadcrumb-main">
          <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.dashboard-menu-title') }}</h4>
          <div class="breadcrumb-action justify-content-center flex-wrap">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>{{ trans('page_title.dashboard')
                    }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.dashboard-index') }}</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>

      <div class="col-xxl-6 col-lg-6 mb-25">
        <div class="card border-0 px-25">
          <div class="card-header px-0 border-0">
            <h6>{{trans('order.buy-orders')}}</h6>
            <div class="card-extra">
              <ul class="card-tab-links nav-tabs nav" role="tablist">
                <li>
                  <a class="active" href="#t_selling-today" data-bs-toggle="tab" id="t_selling-today-tab" role="tab"
                    aria-selected="true">{{trans('requested')}}</a>
                </li>
                <li>
                  <a href="#t_selling-week" data-bs-toggle="tab" id="t_selling-week-tab" role="tab"
                    aria-selected="true">{{trans('waiting')}}</a>
                </li>

              </ul>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="tab-content">
              <div class="tab-pane fade active show" id="t_selling-today" role="tabpanel"
                aria-labelledby="t_selling-today-tab">
                <div class="selling-table-wrap">
                  <div class="table-responsive">
                    <table class="table table--default table-borderless">
                      <thead>
                        <tr>
                          <th>{{ trans('order.code') }}</th>
                          <th>{{trans('order.check_date')}}</th>
                          <th>{{trans('client.clients')}}</th>
                          <th>{{trans('order.supplier')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($requestedItems as $requestedItem)
                        <tr>
                          <td>
                            <a href="{{route('buy_ship_orders.edit',$requestedItem->id)}}" target="__blank">
                              {{$requestedItem?->code}}
                            </a>
                          </td>
                          <td>{{$requestedItem?->check_date}}</td>
                          <td>
                            <a href="{{route('clients.show',$requestedItem?->client?->id)}}">
                              {{$requestedItem?->client?->name}} - {{ $requestedItem?->client?->code }}
                            </a>
                          </td>
                          <td>{{$requestedItem?->supplier?->name}} - {{$requestedItem?->supplier?->code}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="t_selling-week" role="tabpanel" aria-labelledby="t_selling-week-tab">
                <div class="selling-table-wrap">
                  <div class="table-responsive">
                    <table class="table table--default table-borderless">
                      <thead>
                        <tr>
                          <th>{{ trans('order.items') }}</th>
                          <th>{{trans('order.receive_date')}}</th>
                          <th>{{trans('client.clients')}}</th>
                          <th>{{trans('order.supplier')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($waitingItems as $waitingItem)
                        <tr>
                          <td>
                            <a href="{{route('buy_ship_orders.edit',$waitingItem?->order?->id)}}" target="__blank">
                              {{$waitingItem?->product?->name}}
                            </a>
                          </td>
                          <td>{{$waitingItem?->receive_date}}</td>
                          <td>
                            <a href="{{route('clients.show',$waitingItem?->order?->client?->id)}}">
                              {{$waitingItem?->order?->client?->name}} - {{ $waitingItem?->order?->client?->code }}
                            </a>
                          </td>
                          <td>{{$waitingItem?->order?->supplier?->name}} - {{$waitingItem?->order?->supplier?->code}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <div class="col-xxl-6 col-lg-6 mb-25">
        <div class="card border-0 px-25">
          <div class="card-header px-0 border-0">
            <h6>{{trans('order.cbm_counter')}}</h6>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table--default table-borderless">
                <thead>
                <tr>
                  <th>{{trans('client.clients')}}</th>
                  <th>{{trans('client.code')}}</th>
                  <th>{{trans('repo.repos')}}</th>
                  <th>{{trans('order.cbm')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($clients_cbm as $requestedItem)
                  <tr>
                    <td>
                      {{$requestedItem->client_name}}
                    </td>
                    <td>
                      {{$requestedItem->client_code}}
                    </td>
                    <td>
                      {{$requestedItem->repo_name}}
                    </td>
                    <td>
                      {{$requestedItem->total_cbm}}
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="row">
          @foreach ($repos as $repo)
          <div class="col-lg-4 col-sm-6 mb-25">
            <!-- Card 1  -->
            <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
              <div class="overview-content w-100">
                <div class=" ap-po-details-content d-flex flex-wrap justify-content-between">
                  <div class="ap-po-details__titlebar">
                    @php
                    $cbm_sum = $repo?->items?->sum('cbm');
                    @endphp
                    <h1 class="{{ $cbm_sum < 68 ? 'color-success' : 'color-danger' }}">{{ $cbm_sum }}</h1>
                    <p>{{ $repo->name .' - '. $repo->code }}</p>
                  </div>
                </div>
              </div>

            </div>
            <!-- Card 1 End  -->
          </div>
          @endforeach
        </div>
      </div>
      <div class="col-md-12 mb-25">
        <div class="card border-0 px-25">
          <div class="card-header color-dark fw-500">
            {{trans('supplier.Suppliers\' Payments Reminders')}}
          </div>
          <div class="card-body">
            <div class="userDatatable global-shadow border-light-0 w-100">
              <div class="table-responsive">
                <table class="table mb-0 table-borderless">
                  <thead>
                    <tr class="userDatatable-header">
                      <th>
                        <span class="userDatatable-title">{{ trans('supplier.name') }}</span>
                      </th>
                      <th>
                        <span class="userDatatable-title">{{ trans('supplier.code') }}</span>
                      </th>
                      <th>
                        <span class="userDatatable-title">{{trans('supplier.balance') . " " . trans('statement.rmb')}}</span>
                      </th>
                      <th>
                        <span class="userDatatable-title">{{trans('supplier.payment_date')}}</span>
                      </th>
                      <th>
                        <span class="userDatatable-title">{{trans('supplier.paymenr_status')}}</span>
                      </th>

                    </tr>
                  </thead>
                  <tbody>
                    @if (count($SupplierPaymentReminders) == 0)
                    <tr>
                      <td colspan="7">
                        <p class="text-center">No Data Found !</p>
                      </td>
                    </tr>
                    @else
                    @foreach ($SupplierPaymentReminders as $paymentReminder)
                    <tr>
                      <td>
                        <div class="d-flex">
                          <div class="userDatatable__imgWrapper d-flex align-items-center">

                          </div>
                          <div class="userDatatable-inline-title">
                            <a href="{{route('suppliers.statement', $paymentReminder['id'])}}" class="text-dark fw-500">
                              <h6>{{ $paymentReminder['name']??'--' }}</h6>
                            </a>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="userDatatable-content">
                          {{ $paymentReminder['code']??'--' }}
                        </div>
                      </td>
                      <td>
                        <div class="userDatatable-content">
                          @if(($paymentReminder['balance_rmb']??0) < 0)
                          <span class="text-success fw-bold">
                            {{ __(
                            'supplier.The Supplier owe us :amount RMB'
                            ,['amount'=>number_format(($paymentReminder['balance_rmb'] ?? 0),
                            2, '.', ',')]) }}
                          </span>
                          @elseif(($paymentReminder['balance_rmb']??0) == 0)

                          0.00
                          @else
                          <span class="text-danger fw-bold">
                            {{
                            __(
                            'supplier.We owe the supplier :amount RMB'
                            ,['amount'=>number_format(($paymentReminder['balance_rmb'] ?? 0)
                            *-1, 2, '.', ',')]) }}
                          </span>
                          @endif
                        </div>
                      </td>
                      <td>
                        <div class="userDatatable-content">
                          {{ $paymentReminder['payment_date']??'--' }}
                        </div>
                      </td>
                      <td>

                        <div class="text-center radius-md
                          @if($paymentReminder['status'] == 'pending')
                          badge-warning
                          @elseif($paymentReminder['status'] == 'paid')
                          badge-success
                          @elseif($paymentReminder['status'] == 'overdue')
                          badge-danger
                          @elseif($paymentReminder['status'] == 'extended')
                          badge-dark
                          @endif
                           d-inline-block w-auto px-2">
                          {{
                          $paymentReminder['status']!='--'?__('supplier.'.$paymentReminder['status']):'--' }}
                        </div>
                      </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                  <tfoot>
                    <tr>
                      <td style="background: #f8f9fb;border-radius: 15px;" colspan="5" class="text-center"><a
                          style="font-size: 14px" href="{{ route('suppliers.payments.reminder') }}">{{
                          __('supplier.view all reminders') }}</a></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 mb-25">
        <div class="card border-0 px-25">
          <div class="card-header color-dark fw-500">
            {{trans('company.Company\' Payments Reminders')}}
          </div>
          <div class="card-body">
            <div class="userDatatable global-shadow border-light-0 w-100">
              <div class="table-responsive">
                <table class="table mb-0 table-borderless">
                  <thead>
                    <tr class="userDatatable-header">
                      <th>
                        <span class="userDatatable-title">{{ trans('company.name') }}</span>
                      </th>
                      <th>
                        <span class="userDatatable-title">{{ trans('company.code') }}</span>
                      </th>
                      <th>
                        <span class="userDatatable-title">{{trans('company.balance') . " " . trans('statement.dollar')}}</span>
                      </th>
                      <th>
                        <span class="userDatatable-title">{{trans('company.balance') . " " . trans('statement.rmb')}}</span>
                      </th>
                      <th>
                        <span class="userDatatable-title">{{trans('company.payment_date')}}</span>
                      </th>
                      <th>
                        <span class="userDatatable-title">{{trans('company.paymenr_status')}}</span>
                      </th>

                    </tr>
                  </thead>
                  <tbody>
                    @if (count($CompanyPaymentReminders) == 0)
                    <tr>
                      <td colspan="7">
                        <p class="text-center">No Data Found !</p>
                      </td>
                    </tr>
                    @else
                    @foreach ($CompanyPaymentReminders as $paymentReminder)
                    <tr>
                      <td>
                        <div class="d-flex">
                          <div class="userDatatable__imgWrapper d-flex align-items-center">

                          </div>
                          <div class="userDatatable-inline-title">
                            <a href="{{route('shipping_companies.statement', $paymentReminder['id'])}}" class="text-dark fw-500">
                              <h6>{{ $paymentReminder['name']??'--' }}</h6>
                            </a>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="userDatatable-content">
                          {{ $paymentReminder['code']??'--' }}
                        </div>
                      </td>
                      <td>
                        <div class="userDatatable-content">
                          @if(($paymentReminder['balance_dollar']??0) < 0)
                          <span class="text-success fw-bold">
                            {{ __(
                            'company.The company owe us :amount dollar'
                            ,['amount'=>number_format(($paymentReminder['balance_dollar'] ?? 0),
                            2, '.', ',')]) }}
                          </span>
                          @elseif(($paymentReminder['balance_dollar']??0) == 0)

                          0.00
                          @else
                          <span class="text-danger fw-bold">
                            {{
                            __(
                            'company.We owe the company :amount dollar'
                            ,['amount'=>number_format(($paymentReminder['balance_dollar'] ?? 0)
                            *-1, 2, '.', ',')]) }}
                          </span>
                          @endif
                        </div>
                      </td>
                      <td>
                        <div class="userDatatable-content">
                          @if(($paymentReminder['balance_rmb']??0) < 0)
                          <span class="text-success fw-bold">
                            {{ __(
                            'company.The company owe us :amount RMB'
                            ,['amount'=>number_format(($paymentReminder['balance_rmb'] ?? 0),
                            2, '.', ',')]) }}
                          </span>
                          @elseif(($paymentReminder['balance_rmb']??0) == 0)

                          0.00
                          @else
                          <span class="text-danger fw-bold">
                            {{
                            __(
                            'company.We owe the company :amount RMB'
                            ,['amount'=>number_format(($paymentReminder['balance_rmb'] ?? 0)
                            *-1, 2, '.', ',')]) }}
                          </span>
                          @endif
                        </div>
                      </td>
                      <td>
                        <div class="userDatatable-content">
                          {{ $paymentReminder['payment_date']??'--' }}
                        </div>
                      </td>
                      <td>

                        <div class="text-center radius-md
                          @if($paymentReminder['status'] == 'pending')
                          badge-warning
                          @elseif($paymentReminder['status'] == 'paid')
                          badge-success
                          @elseif($paymentReminder['status'] == 'overdue')
                          badge-danger
                          @elseif($paymentReminder['status'] == 'extended')
                          badge-dark
                          @endif
                           d-inline-block w-auto px-2">
                          {{
                          $paymentReminder['status']!='--'?__('supplier.'.$paymentReminder['status']):'--' }}
                        </div>
                      </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                  <tfoot>
                    <tr>
                      <td style="background: #f8f9fb;border-radius: 15px;" colspan="5" class="text-center"><a
                          style="font-size: 14px" href="{{ route('companies.payments.reminder') }}">{{
                          __('supplier.view all reminders') }}</a></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 mb-25">
        <div class="card border-0 px-25">
          <div class="card-header color-dark fw-500">
            {{trans('client.Clients\' Claim Reminders')}}
          </div>
          <div class="card-body">
            <div class="userDatatable global-shadow border-light-0 w-100">
              <div class="table-responsive">
                <table class="table mb-0 table-borderless">
                  <thead>
                    <tr class="userDatatable-header">
                      <th>
                        <span class="userDatatable-title">{{ trans('supplier.name') }}</span>
                      </th>
                      <th>
                        <span class="userDatatable-title">{{ trans('supplier.code') }}</span>
                      </th>
                      <th>
                        <span class="userDatatable-title">{{trans('supplier.balance') . " " . trans('statement.dollar')}}</span>
                      </th>
{{--                      <th>--}}
{{--                        <span class="userDatatable-title">{{trans('supplier.balance') . " " . trans('statement.rmb')}}</span>--}}
{{--                      </th>--}}
                      <th>
                        <span class="userDatatable-title">{{trans('supplier.payment_date')}}</span>
                      </th>
                      <th>
                        <span class="userDatatable-title">{{trans('supplier.paymenr_status')}}</span>
                      </th>

                    </tr>
                  </thead>
                  <tbody>
                    @if (count($ClientClaimReminders) == 0)
                    <tr>
                      <td colspan="7">
                        <p class="text-center">No Data Found !</p>
                      </td>
                    </tr>
                    @else
                    @foreach ($ClientClaimReminders as $paymentReminder)
                    <tr>
                      <td>
                        <div class="d-flex">
                          <div class="userDatatable__imgWrapper d-flex align-items-center">

                          </div>
                          <div class="userDatatable-inline-title">
                            <a href="{{route('clients.statement', $paymentReminder['id'])}}" class="text-dark fw-500">
                              <h6>{{ $paymentReminder['name']??'--' }}</h6>
                            </a>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="userDatatable-content">
                          {{ $paymentReminder['code']??'--' }}
                        </div>
                      </td>
                      <td>
                        <div class="userDatatable-content">
                          @if(($paymentReminder['balance_dollar']??0) < 0)
                          <span class="text-success fw-bold">
                            {{ __(
                            'client.The Client owe us :amount dollar'
                            ,['amount'=>number_format(($paymentReminder['balance_dollar'] ?? 0),
                            2, '.', ',')]) }}
                          </span>
                          @elseif(($paymentReminder['balance_dollar']??0) == 0)

                          0.00
                          @else
                          <span class="text-danger fw-bold">
                            {{
                            __(
                            'client.We owe the client :amount dollar'
                            ,['amount'=>number_format(($paymentReminder['balance_dollar'] ?? 0)
                            *-1, 2, '.', ',')]) }}
                          </span>
                          @endif
                        </div>
                      </td>
{{--                      <td>--}}
{{--                        <div class="userDatatable-content">--}}
{{--                          @if(($paymentReminder['balance_rmb']??0) < 0)--}}
{{--                          <span class="text-success fw-bold">--}}
{{--                            {{ __(--}}
{{--                            'client.The Client owe us :amount RMB'--}}
{{--                            ,['amount'=>number_format(($paymentReminder['balance_rmb'] ?? 0),--}}
{{--                            2, '.', ',')]) }}--}}
{{--                          </span>--}}
{{--                          @elseif(($paymentReminder['balance_rmb']??0) == 0)--}}

{{--                          0.00--}}
{{--                          @else--}}
{{--                          <span class="text-danger fw-bold">--}}
{{--                            {{--}}
{{--                            __(--}}
{{--                            'client.We owe the client :amount RMB'--}}
{{--                            ,['amount'=>number_format(($paymentReminder['balance_rmb'] ?? 0)--}}
{{--                            *-1, 2, '.', ',')]) }}--}}
{{--                          </span>--}}
{{--                          @endif--}}
{{--                        </div>--}}
{{--                      </td>--}}
                      <td>
                        <div class="userDatatable-content">
                          {{ $paymentReminder['payment_date']??'--' }}
                        </div>
                      </td>
                      <td>

                        <div class="text-center radius-md
                          @if($paymentReminder['status'] == 'pending')
                          badge-warning
                          @elseif($paymentReminder['status'] == 'paid')
                          badge-success
                          @elseif($paymentReminder['status'] == 'overdue')
                          badge-danger
                          @elseif($paymentReminder['status'] == 'extended')
                          badge-dark
                          @endif
                           d-inline-block w-auto px-2">
                          {{
                          $paymentReminder['status']!='--'?__('supplier.'.$paymentReminder['status']):'--' }}
                        </div>
                      </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                  <tfoot>
                    <tr>
                      <td style="background: #f8f9fb;border-radius: 15px;" colspan="5" class="text-center"><a
                          style="font-size: 14px" href="{{ route('clients.claims.reminder') }}">{{
                          __('supplier.view all reminders') }}</a></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 mb-25">
        <div class="card border-0 px-25">
          <div class="card-header color-dark fw-500">
            {{trans('client.global_reminders')}}
          </div>
          <div class="card-body">
            <div class="userDatatable global-shadow border-light-0 w-100">
              <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                {{trans('client.add_reminder')}}
              </button>
              <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">{{trans('client.add_reminder')}}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form action="{{ route('reminders.store') }}" method="POST">
                        @csrf

                        <div class="edit-profile__body">
                          <div class="form-group mb-25">
                            <label for="description" class="color-dark fs-14 fw-500 align-center">
                              {{trans('client.reminder_desc')}}
                            </label>
                            <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                   name="description" id="description" value="{{ old('description') }}">
                            @if ($errors->has('description'))
                              <p class="text-danger">{{ $errors->first('description') }}</p>
                            @endif
                          </div>
                          <div class="form-group mb-25">
                            <label for="date" class="color-dark fs-14 fw-500 align-center">
                              {{ trans('client.reminder_date')}}
                            </label>
                            <input type="date" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                   name="date" id="date"
                                   value="{{old('date')}}">
                            @if ($errors->has('date'))
                              <p class="text-danger">{{ $errors->first('date') }}</p>
                            @endif
                          </div>

                          <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                            <button type="submit"
                                    class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                          </div>

                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <div class="table-responsive">
                <table class="table mb-0 table-borderless">
                  <thead>
                    <tr class="userDatatable-header">
                      <th>
                        <span class="userDatatable-title">{{ trans('client.reminder_desc') }}</span>
                      </th>
                      <th>
                        <span class="userDatatable-title">{{ trans('client.reminder_date') }}</span>
                      </th>

                      <th>
                        <span class="userDatatable-title">{{ trans('order.delete') }}</span>
                      </th>

                    </tr>
                  </thead>
                  <tbody>
                    @if (!$globalReminders->count())
                    <tr>
                      <td colspan="2">
                        <p class="text-center">No Data Found !</p>
                      </td>
                    </tr>
                    @else
                    @foreach ($globalReminders as $paymentReminder)
                      @if(strtotime($paymentReminder->date) < strtotime(date('Y-m-d')))
                        <tr class="table-danger">
                      @elseif(strtotime($paymentReminder->date) == strtotime(date('Y-m-d')))
                        <tr class="table-info">
                      @else
                        <tr class="">
                      @endif
                      <td>
                        <div class="d-flex">
                          <div class="userDatatable__imgWrapper d-flex align-items-center">

                          </div>
                          <div class="userDatatable-inline-title">
                            <a href="#" class="text-dark fw-500">
                              <h6>{{ $paymentReminder->description ?? '--' }}</h6>
                            </a>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="userDatatable-content">
                          {{ $paymentReminder->date ??'--' }}
                        </div>
                      </td>
                      <td>
                        <button class="delete-item btn btn-sm btn-danger me-2 mb-2" data-item-id="{{ $paymentReminder->id }}">
                          <i class="la la-window-close fs-4 me-0"></i>
                        </button>
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
      {{-- @include('components.dashboard.demo_one.overview_cards')
      @include('components.dashboard.demo_one.sales_report')
      @include('components.dashboard.demo_one.sales_growth')
      @include('components.dashboard.demo_one.sales_location')
      @include('components.dashboard.demo_one.top_sale_products')
      @include('components.dashboard.demo_one.browser_state') --}}

    </div>
  </div>
</div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function () {
      $(".delete-item").click(function () {
        let createBtn = $(this);
        let item = createBtn.attr('data-item-id');
        Swal.fire({
          title: "{{trans('sales.cancel')}}",
          showCancelButton: true,
          confirmButtonText: "{{trans('delete')}}",
        }).then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              url: '{{route("dashboard.delete_reminder")}}/'+item,
              type: 'DELETE',
              data: {
                "_token": '{{ csrf_token() }}',
              },
              dataType: 'json',
              success: function(response) {
                if(response.error){
                  Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: response.message,
                  });
                  return false;
                }
                createBtn.removeAttr('disabled');
                window.location = '{{route('dashboard.index')}}';
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
    })
  </script>
@endsection
