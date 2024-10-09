<!-- resources/views/statement.blade.php -->

@extends('layout.app')

@section('title', $title)
@section('description', $description)

@section('style')
  <style>
    /* Add your custom styles here */
    .horizontal-table table {
      width: 100%;
      border-collapse: collapse;
    }

    .horizontal-table table tbody tr:nth-child(even) {
      background-color: #f8f9fb;
    }

    .horizontal-table table th,
    .horizontal-table table td {
      padding: 8px;
    }

    @media print {
      body * {
        visibility: hidden;
      }

      body {
        background: white;
      }

      #pdf-content,
      #pdf-content * {
        visibility: visible;
      }

      #pdf-content {
        position: absolute !important;
        top: 0px;
        left: 0px
      }

      .contents {
        padding: 0px
      }
    }
  </style>
@endsection

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="mb-3">
        <button class="btn btn-primary d-inline" onclick="printTable()">{{ __('statement.print') }}</button>
        <button class="btn btn-secondary d-inline" onclick="downloadPDF()">{{ __('statement.download_pdf') }}</button>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 mb-30">
        <div class="card">
          <div class="card-header color-dark fw-500">
            {{ __('statement.client_statement') }}
          </div>
          <div class="card-body">
            <div class="userDatatable global-shadow border-light-0 w-100" id="pdf-content">
              <div class="my-3">
                <table class="table mb-0 table-borderless horizontal-table ">
                  <thead>
                  <tr>
                    <th colspan="2">{{ __('statement.client_data') }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td><strong>{{ __('statement.name') }}</strong> &nbsp; {{ $client->name }}</td>
                    <td><strong>{{ __('statement.code') }}</strong> &nbsp; {{ $client->code }}</td>
                  </tr>
                  <tr>
                    <td><strong>{{ __('statement.print_date') }}</strong> &nbsp; <span dir="ltr">{{
                        now()->format('d/m/Y h:i A') }}</span>
                    </td>
                    <td>
                      <div class="d-flex gap-2 align-content-center">
                        <strong>{{ __('statement.selected_account') }}</strong>
                        <select class="form-control" name="account" id="account" aria-label="account">
                          <option value="">{{ __('statement.choose_account') }}</option>
                          @foreach($accounts as $acc)
                            <option value="{{$acc->id}}" @if($account->id == $acc->id) selected @endif>
                              {{$acc->start_date . ' - ' . $acc->end_date}}
                            </option>
                          @endforeach
                        </select>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <form method="post" action="{{route('clients.statement.update_dollar_rate')}}">
                        @csrf
                        <div class="d-flex gap-5" style="align-items: center">
                            <label for="dollar_rate">{{ __('statement.dollar_rate') }}</label>
                            <input type="text" class="form-control" name="dollar_rate" id="dollar_rate" value="{{$account->dollar_rate}}">
                            <input type="hidden" name="account" value="{{$account->id}}">
                            <button type="submit" class="btn btn-primary">
                              {{__('update')}}
                            </button>
                        </div>
                      </form>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <div class="my-3">
                <table class="table mb-0 table-borderless horizontal-table ">
                  <thead>
                  <tr>
                    <th colspan="2">{{ __('statement.account') }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td><strong>{{ __('statement.start_date') }}</strong> &nbsp; {{ $account->start_date }}</td>
                    <td>
                      @if(!$account->end_date)
                        <strong><span class="text-success">{{ __('statement.account_active') }}</span></strong>
                        <button class="btn btn-primary btn-xs close-account">
                          {{ __('statement.close_account') }}
                        </button>
                      @else
                        <strong>{{ __('statement.end_date') }}</strong> &nbsp;
                        <span class="text-primary">{{ $account->end_date }}</span>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <strong>{{ __('statement.start_balance') . " " . __('statement.dollar') }}</strong> &nbsp;
                      @if($account->starting_balance_dollar >= 0)
                        <strong><span class="text-success">{{ $account->starting_balance_dollar }}</span></strong>
                      @else
                        <strong><span class="text-danger">{{ $account->starting_balance_dollar }}</span></strong>
                      @endif
                    </td>
                  </tr>
                  @if($account->end_date)
                    <tr>
                      <td colspan="2"><strong>{{__('statement.current_balance') . " " . __('statement.dollar') }}</strong> &nbsp;
                        <strong><span class="text-success">0</span></strong>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2"><strong>{{__('statement.close_balance') . " " . __('statement.dollar') }}</strong> &nbsp;
                        @if($account->total_dollar >= 0)
                          <strong><span class="text-success">{{ $account->total_dollar }}</span></strong>
                        @else
                          <strong><span class="text-danger">{{ $account->total_dollar }}</span></strong>
                        @endif
                      </td>
                    </tr>
                  @else
                    <tr>
                      <td colspan="2"><strong>{{__('statement.current_balance') . " " . __('statement.dollar') }}</strong> &nbsp;
                        @if($account->total_dollar >= 0)
                          <strong><span class="text-success">{{ $account->total_dollar }}</span></strong>
                        @else
                          <strong><span class="text-danger">{{ $account->total_dollar }}</span></strong>
                        @endif
                      </td>
                    </tr>
                    @if($account->dollar_rate)
                    <tr>
                      <td><strong>{{ __('statement.total_rmb') }}</strong> &nbsp;
                        @php($rmb_total = float_format($account->total_rmb + ($account->total_dollar ? ($account->total_dollar * $account->dollar_rate) : 0)))
                        @if($rmb_total >= 0)
                          <strong><span class="text-success">{{ $rmb_total }}</span></strong>
                        @else
                          <strong><span class="text-danger">{{ $rmb_total }}</span></strong>
                        @endif
                      </td>
                      <td><strong>{{ __('statement.total_dollar') }}</strong> &nbsp;
                        @php($dollar_total = float_format($account->total_dollar + ($account->total_rmb / $account->dollar_rate)))
                        @if($dollar_total >= 0)
                          <strong><span class="text-success">{{ $dollar_total }}</span></strong>
                        @else
                          <strong><span class="text-danger">{{ $dollar_total }}</span></strong>
                        @endif
                      </td>
                    </tr>
                    @endif
                  @endif
                  </tbody>
                </table>
              </div>
              <div class="table-responsive">
                <table class="table mb-0 table-borderless horizontal-table">
                  <thead>
                  <tr class="userDatatable-header">
                    <th>
                      <span class="userDatatable-title">{{ __('expense.description') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ __('statement.operation_date') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ __('statement.client_debt') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ __('statement.client_payment') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ __('client.balance_dollar') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ __('statement.currency') }}</span>
                    </th>
                  </tr>
                  </thead>
                  <tbody>
                  @forelse ($ledgers as $date => $ledger_items)
                    @foreach ($ledger_items as $ledger)
                      <tr>
                        <td>
                          <div class="userDatatable-inline-title" style=" text-wrap: balance; max-width:200px">
                            <h6>{{ $ledger['reason'] }}</h6>
                          </div>
                        </td>
                        <td>
                          <div class="userDatatable-content {{ (app()->getLocale() == 'ar')?'text-start':'' }}" dir="ltr">
                            {{date("Y-m-d", strtotime($date))}}
                          </div>
                        </td>
                        <td>
                          <div class="userDatatable-content">
                            <span class="text-danger">{{ $ledger['debit'] ?? 0}}</span>
                          </div>
                        </td>
                        <td>
                          <div class="userDatatable-content">
                            <span class="text-success">{{ $ledger['credit'] ?? 0 }}</span>
                          </div>
                        </td>
                        <td>
                          <div class="userDatatable-content">
                            @if(($ledger['due'] ?? 0) > -1)
                              <span class="text-success">{{ $ledger['due'] ?? "-" }}</span>
                            @else
                              <span class="text-danger">{{ $ledger['due'] ?? "-"}}</span>
                            @endif
                          </div>
                        </td>
                        <td>
                          <div class="userDatatable-content">
                            {{ $ledger['currency']}}
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  @empty
                    <tr>
                      <td colspan="7">

                        <p class="text-center">{{ __('statement.no_data_found') }}</p>
                      </td>
                    </tr>
                  @endforelse
                  </tbody>
                  <tfoot>
                  <tr>
                    <td colspan="6">
                      <hr class="p-0 m-0">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="4">
                      <div class="userDatatable-content">
                        <span>{{ __('statement.client_balance_usd') }} :</span>
                        <span class="mx-1 {{ $balance < 0 ?'text-danger':'text-success' }}" data-origin="{{ $balance }}" id="usdBalance">
                          {{$balance}}
                        </span>{{ __('statement.dollar') }}
                      </div>
                    </td>
                    <td colspan="2">
                    </td>
                  </tr>
                  </tfoot>
                </table>
              </div>
              @if($balance < 0 || $balance_rmb < 0)
              <div class="d-flex gap-5">
                <button class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#schedulePaymentDateModal">
                    {{__('client.schedule_payment')}}
                </button>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="schedulePaymentDateModal" tabindex="-1" aria-labelledby="schedulePaymentDateModalLabel"
       aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="schedulePaymentDateModalLabel">{{ __('supplier.Create Payment Date') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form inside the modal -->
          <form action="{{ route('clients.claims.reminder.create.date') }}" method="POST">
            @csrf
            <!-- Date input for the new payment date -->
            <div class="mb-3">
              <label for="new_payment_date" class="form-label">{{ __('client.Payment Date') }}</label>
              <input type="date" class="form-control" id="new_payment_date" name="new_payment_date" required>
              <input type="hidden" name="client_id" value="{{$client->id}}">
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary">{{ __('client.Save Changes') }}</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $(document).ready(function(){
      let account = $("#account");
      account.select2();
      account.change(function () {
        if(!$(this).val()) return;
        window.location = "{{route('clients.statement', request()->id)}}?account=" + $(this).val();
      });
      $(".close-account").click(function () {
        Swal.fire({
          title: "{{__('statement.close_account_warning')}}",
          showCancelButton: true,
          confirmButtonText: "{{__('statement.close_account')}}",
        }).then((result) => {
          if (result.isConfirmed) {
            window.location = "{{route('clients.statement.restart_account', $client->id)}}";
          }
        });
      });
    });
    function printTable() {
      window.print();
    }
    // Function to handle downloading PDF
    function downloadPDF() {
      var options = {
        margin: 10,
        filename: '{{ $client->name .'-'. date('Y-m-d') }}.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        exclude: ['#conversion_rate'],
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
      };
      html2pdf($('#pdf-content')[0],options);
    }
  </script>
  <script>
    // Function to update USD balance based on conversion rate
    function updateUSDBalance() {
      // Get RMB balance and conversion rate elements
      var rmbBalanceElement = $('#rmbBalance');
      var usdBalanceElement = $('#usdBalance');
      var conversionRateElement = $('#conversion_rate');

      // Get values from elements
      var rmbBalance = parseFloat(rmbBalanceElement.text());
      var conversionRate = parseFloat(conversionRateElement.val());

      if(!conversionRate || !rmbBalance){
        usdBalanceElement.text(parseFloat(usdBalanceElement.data('origin')).toFixed(2));
        return;
      }

      // Check if the values are valid
      if (!isNaN(rmbBalance) && !isNaN(conversionRate)) {
        // Calculate USD balance
        var usdBalance = rmbBalance / conversionRate;

        // Update USD balance element
        usdBalanceElement.text((parseFloat(usdBalanceElement.data('origin')) + usdBalance).toFixed(2));
      }
    }

    // Attach the update function to the conversion rate input's change event
    $('#conversion_rate').on('input', updateUSDBalance);

    // Initial update when the page loads
    updateUSDBalance();
  </script>
@endsection
