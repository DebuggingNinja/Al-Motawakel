@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex align-items-center user-member__title mb-30 mt-30">
          <h4 class="text-capitalize">{{ trans('transfer.add-transfer') }}</h4>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 mb-30">
        <div class="card">
          <div class="card-header color-dark fw-500">
            {{ trans('transfer.transfers') }}
          </div>
          <div class="card-body">
            <form action="{{ route('transfers.store') }}" method="POST">
              @method('POST')
              @csrf
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="from">{{ trans('transfer.from') }}</label>
                    <input type="text" class="form-control" value="{{ old('from') }}" id="from" name="from">
                    @if ($errors->has('from'))
                      <p class="text-danger">{{ $errors->first('from') }}</p>
                    @endif
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="date">{{ trans('transfer.date') }}:</label>
                    <input type="date" class="form-control" value="{{ old('date') }}" id="date" name="date">
                    @if ($errors->has('date'))
                      <p class="text-danger">{{ $errors->first('date') }}</p>
                    @endif
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="number">{{ trans('transfer.number') }}:</label>
                    <input type="text" class="form-control" value="{{ old('number') }}" id="number" name="number">
                    @if ($errors->has('number'))
                      <p class="text-danger">{{ $errors->first('number') }}</p>
                    @endif
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="transfer_usd">USD:</label>
                    <input type="number" class="form-control" value="{{ old('transfer_usd') }}" id="transfer_usd" name="transfer_usd" step="0.01">
                    @if ($errors->has('transfer_usd'))
                      <p class="text-danger">{{ $errors->first('transfer_usd') }}</p>
                    @endif
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="input1">{{ trans('client.clients') }}</label>
                    <select name="client_id" class="form-control select2 w-100">
                      <option>Choose A Client Or Search</option>
                      @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }} - {{$client->code}}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('client_id'))
                      <p class="text-danger">{{ $errors->first('client_id') }}</p>
                    @endif
                  </div>
                </div>

              </div>
              <button class="btn btn-success">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
      $('.select2').select2();

      var updatingAmounts = false;

      function updateAmounts(row) {
        if (!updatingAmounts) {
          var exchangeRate = parseFloat($(".exchange-rate").val()) || 0;

          var amountUSD = parseFloat(row.find(".amount-usd").val()) || 0;
          var amountRMB = parseFloat(row.find(".amount-rmb").val()) || 0;

          if (!isNaN(amountUSD) && !isNaN(amountRMB)) {
            updatingAmounts = true;

            if ($(event.target).hasClass('amount-usd')) {
              amountRMB = amountUSD * exchangeRate;
              row.find(".amount-rmb").val(amountRMB.toFixed(2));
            } else if ($(event.target).hasClass('amount-rmb')) {
              amountUSD = amountRMB / exchangeRate;
              row.find(".amount-usd").val(amountUSD.toFixed(2));
            }

            updatingAmounts = false;
          }
        }

        updateTotals();
      }

      $(".exchange-rate").on("input", function() {
        $("#table-body tr").each(function() {
          updateAmounts($(this));
        });
      });

      $("#add-row").click(function(e) {
        e.preventDefault();
        var rowCount = $("#table-body tr").length;
        var newRow = '<tr>' +
          `<td><select name="transfers[${rowCount}][client_id]" class="form-control select2" id="select-tag">
                          <option>Choose A Client Or Search</option>
                          @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                          @endforeach
                        </select></td>
          {{-- <td><input type="text" class="form-control" name="transfers[${rowCount}][client_code]"></td> --}}
          <td><input type="number" class="form-control amount-usd" name="transfers[${rowCount}][amount_usd]" step="0.01"></td>
          <td><input type="number" class="form-control amount-rmb" name="transfers[${rowCount}][amount_rmb]" step="0.01"></td>
          <td><textarea type="text" class="form-control" name="transfers[${rowCount}][description]"></textarea></td>
          <td><button class="btn btn-danger remove-btn ms-auto">-</button></td>
          </tr>`;

        var $newRow = $(newRow);
        $("#table-body").append($newRow);
        $('.select2').select2();
        updateAmounts($newRow);
      });

      $("#table-body").on("input", ".amount-usd, .amount-rmb", function(event) {
        updateAmounts($(event.target).closest("tr"));
      });

      $("#table-body").on("click", ".remove-btn", function(e) {
        e.preventDefault();
        $(this).closest("tr").remove();
        updateTotals();
        updateRowKeys();

      });
      $("#transfer_usd").on("input", function() {
        var exchangeRate = parseFloat($(".exchange-rate").val()) || 0;
        var transferUSD = parseFloat($(this).val()) || 0;
        var transferRMB = transferUSD * exchangeRate;
        $("#transfer_rmb").val(transferRMB.toFixed(2));
        updateTotals();
      });

      $("#transfer_rmb").on("input", function() {
        var exchangeRate = parseFloat($(".exchange-rate").val()) || 0;
        var transferRMB = parseFloat($(this).val()) || 0;
        var transferUSD = transferRMB / exchangeRate;
        $("#transfer_usd").val(transferUSD.toFixed(2));
        updateTotals();
      });

      function updateTotals() {
        var totalUSD = 0;
        var totalRMB = 0;

        $(".amount-usd").each(function() {
          totalUSD += parseFloat($(this).val()) || 0;
        });

        $(".amount-rmb").each(function() {
          totalRMB += parseFloat($(this).val()) || 0;
        });

        $("#total-usd").text(totalUSD.toFixed(2));
        $("#total-rmb").text(totalRMB.toFixed(2));
      }

      function updateRowKeys() {
        $("#table-body tr").each(function(index) {
          $(this).find("select[name^='transfers']").attr('name', `transfers[${index}][client_id]`);
          $(this).find("textarea[name^='transfers']").attr('name', `transfers[${index}][description]`);
          $(this).find("input[name^='transfers']").each(function() {
            var fieldName = $(this).attr('name').replace(/\[\d+\]/, `[${index}]`);
            $(this).attr('name', fieldName);
          });
        });
      }
    });
  </script>


@endsection
