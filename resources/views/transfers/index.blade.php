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
                <h4 class="text-capitalize fw-500 breadcrumb-title">{{ trans('transfer.transfers') }}
                </h4>
                <span class="sub-title ms-sm-25 ps-sm-25"></span>
              </div>
              <div class="action-btn mt-sm-0 mt-15">
                @can('add transfers')
                  <a href="{{ route('transfers.create') }}" class="btn px-20 btn-primary ">
                    <i class="las la-plus fs-16"></i>{{ trans('transfer.add-transfer') }}
                  </a>
                @endcan
              </div>
            </div>
            <div class="breadcrumb-main__wrapper">

              <form action="{{ route('transfers.index') }}" method="GET"
                class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                <img src="{{ asset('assets/img/svg/search.svg') }}" alt="search" class="svg">
                <input name="search" class="form-control me-sm-2 border-0 box-shadow-none" type="search"
                  placeholder="Search by ID" aria-label="Search">
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
            {{ trans('transfer.transfers') }}
          </div>
          <div class="card-body">
            <div class="userDatatable global-shadow border-light-0 w-100">
              <table class="table">
                <thead>
                  <tr>
                    <th>{{ trans('id') }}</th>
                    <th>{{ trans('client.clients') }}</th>
                    <th>{{ trans('transfer.number') }}</th>
                    <th>{{ trans('transfer.from') }}</th>
                    <th>{{ trans('transfer.date') }}</th>
                    <th>USD</th>
                    <th>
                      {{trans('actions')}}
                    </th>
                  </tr>
                </thead>
                <tbody id="table-body">
                  @if (count($transfers) == 0)
                    <tr>
                      <td colspan="7" style="text-align: center">
                        No Data Found
                      </td>
                    </tr>
                  @endif
                  @foreach ($transfers as $transfer)
                    @php($client = $transfer->ledgers?->first()?->client)
                  <tr>
                    <td>
                      {{$transfer->id}}
                    </td>
                    <td>{{$client?->name . " - " . $client?->code}}</td>
                    <td>{{$transfer->number ?? "---"}}</td>
                    <td>{{$transfer->from}}</td>
                    <td>{{$transfer->date}}</td>
                    <td>{{$transfer->amount_usd}}</td>
                    <td>
                      <a href="{{route('transfers.show',$transfer->id)}}">
                        <i class="la la-eye"></i>
                      </a>
                      <a href="#" class="remove" onclick="
                          event.preventDefault();
                          if ( confirm('Are you sure you want to delete ?') ) {
                              document.getElementById( 'delete-{{ $transfer->id }}' ).submit();
                          }
                      ">
                        <i class="uil uil-trash-alt"></i>
                      </a>

                      <form style="display:none;" id="delete-{{ $transfer->id }}"
                            action="{{ route('transfers.destroy', [$transfer]) }}" method="POST">
                        @csrf
                        @method('delete')
                      </form>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="pagination-container d-flex justify-content-end pt-25">
              {{ $transfers->links('pagination::bootstrap-5') }}

              <ul class="dm-pagination d-flex">
                <li class="dm-pagination__item">
                  <div class="paging-option">
                    <select name="page-number" class="page-selection" onchange="updatePagination( event )">
                      <option value="20" {{ 20 == $transfers->perPage() ? 'selected' : '' }}>20/page</option>
                      <option value="40" {{ 40 == $transfers->perPage() ? 'selected' : '' }}>40/page</option>
                      <option value="60" {{ 60 == $transfers->perPage() ? 'selected' : '' }}>60/page</option>
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
