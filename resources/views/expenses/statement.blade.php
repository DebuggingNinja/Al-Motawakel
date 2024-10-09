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
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 mb-30">
      <div class="card">
        <div class="card-header color-dark fw-500">
          {{ __('statement.company') }}
        </div>
        <div class="card-body">
          <div class="userDatatable global-shadow border-light-0 w-100" id="pdf-content">
            <div class="my-3">
              <form id="form_select_warehouse" action="{{route('income.outcome')}}" method="GET">
                <div class="row search-dates">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="start_date">
                        {{trans('repo.start_date')}}
                      </label>
                      <input class="form-control" type="date" name="start_date" id="start_date"
                             value="{{request()?->start_date ?? date('Y-m-d', strtotime("- 1 month"))}}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="end_date">
                        {{trans('repo.end_date')}}
                      </label>
                      <input class="form-control" type="date" name="end_date" id="end_date"
                             value="{{request()?->end_date ?? date('Y-m-d')}}">
                    </div>
                  </div>
                </div>
                <button class="btn btn-primary" type="submit">{{trans('repo.search')}}</button>
              </form>
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
                      <span class="userDatatable-title">{{ __('expense.outcome') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ __('expense.income') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ __('statement.currency') }}</span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                @foreach($ledgers as $date => $ledger_items)
                  @foreach ($ledger_items as $ledger)
                    <tr>
                      <td>
                        <div class="userDatatable-inline-title" style=" text-wrap: balance; max-width:200px">
                          <h6>{{ $ledger['reason'] }}</h6>
                        </div>
                      </td>
                      <td>
                        <div class="userDatatable-content {{ (app()->getLocale() == 'ar')?'text-start':'' }}" dir="ltr">
                          {{$date}}
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
                          <span class="text-success">{{ $ledger['currency'] ?? '--' }}</span>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                @endforeach
                <tr>
                  <td>
                    <div class="userDatatable-inline-title" style=" text-wrap: balance; max-width:200px">
                      <h6>{{ trans('total') }}</h6>
                    </div>
                  </td>
                  <td>
                  </td>
                  <td>
                    <div class="userDatatable-content">
                      <span class="text-danger">{{ $outcome ?? 0}}</span>
                    </div>
                  </td>
                  <td>
                    <div class="userDatatable-content">
                      <span class="text-success">{{ $income ?? 0 }}</span>
                    </div>
                  </td>
                  <td>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  function printTable() {
    window.print();
  }
</script>

@endsection
