<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <title>{{ $client->name }} - {{ now() }}</title>
  <style>
    table,
    td,
    th {
      border: 1px solid black;
    }

    table {
      border-collapse: collapse;
      width: 98%;
    }
  </style>
</head>

<body>
  <img src="{{public_path("storage/{$fileBannerPath}")}}" alt="" width="100%" height="10%">
  <table>

    <tr>
      <td>
        {{ trans('client.name') }}
      </td>
      <td>{{ $client->name }}</td>
      <td>{{trans('client.code')}}</td>
      <td>{{$client->code}}</td>
    </tr>

    <tr>
      <td>{{ trans('date') }}</td>
      <td colspan="3">{{ $date }}</td>
    </tr>
  </table>
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
              {{ $ledger->description }}
            </td>
            <td>
              {{ $ledger->date }}
            </td>
            <td>
              {{ $ledger->debit }}
            </td>
            <td>
              {{ $ledger->credit }}
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>
</body>

</html>
