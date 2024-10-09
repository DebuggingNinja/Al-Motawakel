@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 mb-30">
        <div class="card">
          <div class="card-header color-dark fw-500">
            {{ $transfer->id }}
            <button id="print" class="btn btn-primary">{{trans('print')}}</button>
          </div>
          <div class="card-body" id="card-body">
              <div class="row">
                <div class="col-12 col-md-6 col-lg-4">
                  <div class="form-group">
                    <label for="from">{{ trans('transfer.from') }}</label>
                    <input type="text" class="form-control" value="{{$transfer->from}}" readonly>
                  </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                  <div class="form-group">
                    <label for="date">{{ trans('transfer.date') }}:</label>
                    <input type="date" class="form-control" value="{{$transfer->date}}" readonly>
                  </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                  <div class="form-group">
                    <label for="number">{{ trans('transfer.number') }}:</label>
                    <input type="number" class="form-control" value="{{$transfer->number}}" readonly>
                  </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                  <div class="form-group">
                    <label for="transfer_usd">USD:</label>
                    <input type="number" class="form-control" id="transfer_usd" value="{{$transfer->amount_usd}}" readonly>
                  </div>
                </div>

              </div>
              <div class="userDatatable global-shadow border-light-0 w-100">
                <table class="table">
                  <thead>
                    <tr>
                      <th>{{ trans('client.clients') }}</th>
                      <th>{{ trans('client.code') }}</th>
                      <th>USD</th>
                    </tr>
                  </thead>
                  <tbody id="table-body">
                    @foreach ($transfer->ledgers as $ledger)
                      <tr>
                        <td>{{$ledger->client->name}}</td>
                        <td>{{$ledger->client->code}}</td>
                        <td>{{$ledger->credit}}</td>
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
@endsection
@section('scripts')
<script>
   $("#print").on('click',function(){
        printJS({
          printable: 'card-body',
          type:'html',
          style:`
            *{width:98%}
            table{margin: 0 auto}
            input, textarea{border:none}
            input{border-bottom: 1px solid black}
          `});
      });
</script>
@endsection
