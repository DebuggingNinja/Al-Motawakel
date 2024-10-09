@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('style')
  <style>
    .form-control[readonly] {
      background-color: white;
      border: none
    }
  </style>
@endsection
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex align-items-center user-member__title mb-30 mt-30">
          <h4 class="text-capitalize">{{ $client->name }}</h4>
        </div>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-12 col-md-4 balance-card">
        <div class="card p-5">
          <div class="card-header">
            <h5>{{ trans('client.balance') }}</h5>
          </div>
          <div class="card-body">
            <div class="d-flex">
              <input class="form-control" type="text" id="rate" placeholder="Rate">
              <select class="form-select" id="currency">
                <option value="rmb" selected>RMB</option>
                <option value="usd">USD</option>
              </select>
            </div>
            @php
              $balance = $client
                  ->ledgers()
                  ->latest('date')
                  ->first()->balance;
            @endphp
            <input id="balance" class="form-control fs-3 {{ $balance >= 0 ? 'text-success' : 'text-danger' }}"
              value="{{ $balance }}" readonly />
          </div>
        </div>
      </div>
      @can('add ledgers')
        <div class="col-12 col-md-8">
          <div class="card p-5">
            <ul class="nav nav-pills" id="myTabs">
              <li class="nav-item">
                <a class="nav-link active" id="credit-tab" data-toggle="tab" href="#tab1">Credit</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="debit-tab" data-toggle="tab" href="#tab2">Debit</a>
              </li>

            </ul>
            <div class="tab-content mt-3">
              <div class="tab-pane fade show active" id="tab1">
                <form id="credit-form" method="POST">
                  @csrf
                  <input type="hidden" name="client_id" value="{{ $client->id }}">
                  <input class="form-control mb-3" type="text" name="credit" placeholder="100.00">
                  <input class="form-control" type="text" name="description" placeholder="Client Pay">
                  <button class="btn btn-primary send mt-2" data-form="credit-form">Submit</button>
                </form>
              </div>
              <div class="tab-pane fade" id="tab2">
                <form id="debit-form" method="POST">
                  @csrf
                  <input type="hidden" name="client_id" value="{{ $client->id }}">
                  <input class="form-control mb-3" type="text" name="debit" placeholder="100.00">
                  <input class="form-control" type="text" name="description" placeholder="Expenses">
                  <button class="btn btn-primary send mt-2" data-form="debit-form">Submit</button>
                </form>
              </div>

            </div>

          </div>
        </div>
      @endcan
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card p-3">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="form-group mb-25">
                <label for="name" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('client.name') }}
                </label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                  value="{{ $client->name }}" id="name" readonly>

              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group mb-25">
                <label for="code" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('client.code') }}
                </label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" id="code"
                  value="{{ $client->code }}" readonly>

              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group mb-25">
                <label for="mark" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('client.mark') }}
                </label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" id="mark"
                  value="{{ $client->mark }}" readonly>

              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group mb-25">
                <label for="email" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('client.email') }}
                </label>
                <input type="email" class="form-control ih-medium ip-gray radius-xs b-light px-15" id="email"
                  value="{{ $client->email }}" readonly>

              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group mb-25">
                <label for="phone" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('client.phone') }}
                </label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" id="phone"
                  value="{{ $client->phone }}" readonly>

              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group mb-25">
                <label for="phone2" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('client.phone2') }}
                </label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" id="phone2"
                  value="{{ $client->phone2 }}" readonly>

              </div>

            </div>
            <div class="col-12 col-md-6">
              <div class="form-group mb-25">
                <label for="address" class="color-dark fs-14 fw-500 align-center">
                  {{ trans('client.address') }}
                </label>
                <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" id="address"
                  value="{{ $client->address }}" readonly>
              </div>
            </div>

          </div>

        </div>
      </div>
    </div>
    @can('view ledgers')
      <div class="row mb-3 mt-3">
        <div class="col-12">
          <div class="card p-5 ledgers">
            <div class="table-responsive">
              <table class="table mb-0 table-borderless">
                <thead>
                  <tr class="userDatatable-header">
                    <th>
                      <span class="userDatatable-title">{{ trans('description') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('time') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('debit') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('credit') }}</span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @if (count($ledgers) == 0)
                    <tr>
                      <td colspan="7">
                        <p class="text-center">No Data Available !</p>
                      </td>
                    </tr>
                  @else
                    @foreach ($ledgers as $ledger)
                      <tr>
                        <td>
                          <div class="d-flex">
                            <div class="userDatatable__imgWrapper d-flex align-items-center">
                            </div>
                            <div class="userDatatable-inline-title">
                              <a href="#" class="text-dark fw-500">
                                <h6>{{ $ledger->description }}</h6>
                              </a>
                            </div>
                          </div>
                        </td>
                        <td>
                          <div class="userDatatable-content">
                            {{ $ledger->date }}
                          </div>
                        </td>
                        <td>
                          <div class="userDatatable-content">
                            <input class="ledger-debit form-control" value="{{ $ledger->debit }}" readonly />
                          </div>
                        </td>
                        <td>
                          <div class="userDatatable-content">
                            <input class="ledger-credit form-control" value="{{ $ledger->credit }}" readonly />
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

    @endcan
  </div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
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
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $('#myTabs a').on('click', function(e) {
        e.preventDefault();
        $(this).tab('show');
      });
      $('#currency').on('change', function(e) {
        var currency = $(this).val();
        var rate = parseFloat($('#rate').val());
        var balance = parseFloat($('#balance').val());
        if (currency == 'usd') {
          $('#balance').val(balance * rate);
        }
        if (currency == 'rmb') {
          $('#balance').val(balance / rate);
        }
      });
      $('.send').on('click', function(e) {
        e.preventDefault();
        var formId = $(this).data('form');
        var form = $(`#${formId}`);
        Swal.fire({
          title: "{{ app()->getLocale() == 'ar' ? 'هل انت متأكد ؟' : 'Are you sure ?' }}",
          showCancelButton: true,
          confirmButtonText: "Yes",
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            if (formId == 'credit-form') {
              $.ajax({
                type: "POST",
                url: "{{ route('ledgers.credit') }}",
                data: form.serialize(),
                success: function(xhr) {
                  console.log(xhr);
                  toastr["success"](xhr.success);
                },
                error: function(error) {
                  toastr["warning"]("Something Went Wrong");
                }
              });

            } else {
              $.ajax({
                type: "POST",
                url: "{{ route('ledgers.debit') }}",
                data: form.serialize(),
                success: function(xhr) {
                  toastr["success"](xhr.success);
                },
                error: function(error) {
                  toastr["warning"]("Something Went Wrong");
                }
              });
            }
          } else {

          }
        });
      });

    });
  </script>
@endsection
