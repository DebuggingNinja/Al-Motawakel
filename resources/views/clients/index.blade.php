@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="contact-breadcrumb">
        <div class="breadcrumb-main add-contact justify-content-sm-between ">
          <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
            <div class="d-flex align-items-center add-contact__title justify-content-center me-sm-25">
              <h4 class="text-capitalize fw-500 breadcrumb-title">{{ trans('client.clients') }}
              </h4>
              <span class="sub-title ms-sm-25 ps-sm-25"></span>
            </div>
            <div class="action-btn mt-sm-0 mt-15">
              @can('add clients')
              <a href="{{ route('clients.create') }}" class="btn px-20 btn-primary ">
                <i class="las la-plus fs-16"></i>{{ trans('client.add-client') }}
              </a>
              @endcan
            </div>
          </div>
          <div class="breadcrumb-main__wrapper">

            <form action="{{ route('clients.index') }}" method="GET"
              class="d-flex align-items-center add-contact__form my-sm-0 my-2">
              <img src="{{ asset('assets/img/svg/search.svg') }}" alt="search" class="svg">
              <input name="search" class="form-control me-sm-2 border-0 box-shadow-none" type="search"
                placeholder="Search by Name" aria-label="Search">
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 mb-30">
      <div class="card">
        <div class="card-header color-dark fw-500">
          {{ trans('client.clients') }}
        </div>
        <div class="card-body">
          <div class="userDatatable global-shadow border-light-0 w-100">
            <div class="table-responsive">
              <table class="table mb-0 table-borderless">
                <thead>
                  <tr class="userDatatable-header">
                    <th>
                      <span class="userDatatable-title">{{ trans('client.name') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('client.code') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('client.email') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('client.phone') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('client.phone2') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('client.mark') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('client.address') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{trans('client.balance') . " " . trans('statement.dollar')}}</span>
                    </th>
{{--                    <th>--}}
{{--                      <span class="userDatatable-title">{{trans('client.balance') . " " . trans('statement.rmb')}}</span>--}}
{{--                    </th>--}}
                    <th>
                      <span class="userDatatable-title float-end">Actions</span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @if (count($clients) == 0)
                  <tr>
                    <td colspan="7">
                      <p class="text-center">No Client Found !</p>
                    </td>
                  </tr>
                  @else
                  @foreach ($clients as $client)
                  <tr>
                    <td>
                      <div class="d-flex">
                        <div class="userDatatable__imgWrapper d-flex align-items-center">

                        </div>
                        <div class="userDatatable-inline-title">
                          <a href="#" class="text-dark fw-500">
                            <h6>{{ $client->name }}</h6>
                          </a>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $client->code }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $client->email }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $client->phone }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $client->phone2 }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $client->mark }}
                      </div>
                    </td>

                    <td>
                      <div class="userDatatable-content">
                        {{ $client->address }}
                      </div>
                    </td>

                    <td>
                      <div class="userDatatable-content">
                        @if($client->activeAccount?->total_dollar < 0)
                          <span class="text-success fw-bold">
                            {{ __('client.The Client owe us :amount dollar',['amount'=>number_format($client->activeAccount?->total_dollar,
                            2, '.', ',')]) }}
                          </span>
                        @elseif($client->activeAccount?->total_dollar == 0)
                          0.00
                        @else
                          <span class="text-danger fw-bold">
                            {{ __('client.We owe the Client :amount dollar',['amount'=>number_format($client->activeAccount?->total_dollar
                            , 2, '.', ',')]) }}
                          </span>
                        @endif

                      </div>
                    </td>
{{--                    <td>--}}

{{--                      <div class="userDatatable-content">--}}
{{--                        @if($client->activeAccount?->total_rmb < 0)--}}
{{--                          <span class="text-success fw-bold">--}}
{{--                              {{ __('client.The Client owe us :amount RMB',['amount'=>number_format($client->activeAccount?->total_rmb,--}}
{{--                              2, '.', ',')]) }}--}}
{{--                          </span>--}}
{{--                        @elseif($client->activeAccount?->total_rmb == 0)--}}
{{--                          0.00--}}
{{--                        @else--}}
{{--                          <span class="text-danger fw-bold">--}}
{{--                            {{ __('client.We owe the Client :amount RMB',['amount'=>number_format($client->activeAccount?->total_rmb--}}
{{--                            , 2, '.', ',')]) }}--}}
{{--                          </span>--}}
{{--                        @endif--}}

{{--                      </div>--}}
{{--                    </td>--}}

                    <td>
                      <ul class="orderDatatable_actions mb-0 d-flex flex-wrap">
                        @can('view ledgers')
                        <li>
                          <a href="{{route('clients.statement',$client->id)}}" class="edit download-ledger">
                            <i class="uil uil-download-alt"></i>
                          </a>
                        </li>
                        @endcan
{{--                        @can('view clients')--}}
{{--                        <li>--}}
{{--                          <a href="{{route('clients.show',$client)}}" class="edit">--}}
{{--                            <i class="uil uil-eye"></i>--}}
{{--                          </a>--}}
{{--                        </li>--}}
{{--                        @endcan--}}
                        @can('update clients')
                        <li>
                          <a href="{{ route('clients.edit', [$client]) }}" class="edit">
                            <i class="uil uil-edit"></i>
                          </a>
                        </li>
                        @endcan
                        @can('delete clients')
                        <li>
                          <a href="#" class="remove" onclick="deleteItem('delete-{{ $client->id }}')">
                            <i class="uil uil-trash-alt"></i>
                          </a>

                          <form style="display:none;" id="delete-{{ $client->id }}"
                            action="{{ route('clients.destroy', [$client]) }}" method="POST">
                            @csrf
                            @method('delete')
                          </form>
                        </li>
                        @endcan
                      </ul>
                    </td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>

          <div class="pagination-container d-flex justify-content-end pt-25">
            {{ $clients->links('pagination::bootstrap-5') }}

            <ul class="dm-pagination d-flex">
              <li class="dm-pagination__item">
                <div class="paging-option">
                  <select name="page-number" class="page-selection" onchange="updatePagination( event )">
                    <option value="20" {{ 20==$clients->perPage() ? 'selected' : '' }}>20/page</option>
                    <option value="40" {{ 40==$clients->perPage() ? 'selected' : '' }}>40/page</option>
                    <option value="60" {{ 60==$clients->perPage() ? 'selected' : '' }}>60/page</option>
                  </select>
                  <a href="#" class="d-none per-page-pagination"></a>
                </div>
              </li>
            </ul>

            <script>
              function updatePagination(event) {
                  var per_page = event.target.value;
                  const per_page_link = document.querySelector('.per-page-pagination');
                  per_page_link.setAttribute('href', '/pagination-per-page/' + per_page);

                  per_page_link.click();
                }
            </script>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
  function deleteItem(form) {
      Swal.fire({
        title: "{{ app()->getLocale() == 'ar' ? 'هل انت متأكد من حذف هذا البند؟' : 'Do you want to delete this item?' }}",
        showCancelButton: true,
        confirmButtonText: "Delete",
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          event.preventDefault();
          document.getElementById(form).submit();
        }
      });
    }
</script>
@endsection
