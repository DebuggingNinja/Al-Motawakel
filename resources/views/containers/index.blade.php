@section('title', $title)
@section('description', $description)
@section('style')
<style>
  tr.selected {
    background-color: var(--bs-green);
  }
</style>
@endsection
@extends('layout.app')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="contact-breadcrumb">
        <div class="breadcrumb-main add-contact justify-content-sm-between ">
          <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
            <div class="d-flex align-items-center add-contact__title justify-content-center me-sm-25">
              <h4 class="text-capitalize fw-500 breadcrumb-title">{{ trans('container.containers') }}
              </h4>
              <span class="sub-title ms-sm-25 ps-sm-25"></span>
            </div>
            <div class="action-btn mt-sm-0 mt-15">
              @can('add containers')
              <a href="{{ route('containers.create') }}" class="btn px-20 btn-primary ">
                <i class="las la-plus fs-16"></i>{{ trans('container.add-container') }}
              </a>
              @endcan
            </div>
          </div>
          <div class="breadcrumb-main__wrapper">
            <form action="{{ route('containers.index') }}" method="GET"
              class="d-flex align-items-center add-contact__form my-sm-0 my-2">
              <img src="{{ asset('assets/img/svg/search.svg') }}" alt="search" class="svg">
              <input name="search" class="form-control me-sm-2 border-0 box-shadow-none" type="search"
                placeholder="Search by Container or Lock Number" aria-label="Search">
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
          {{ trans('container.containers') }}
        </div>
        <div class="card-body">
          <div class="userDatatable global-shadow border-light-0 w-100">
            <div class="table-responsive">
              <table class="table mb-0 table-borderless">
                <thead>
                  <tr class="userDatatable-header">
                    <th>
                      <span class="userDatatable-title">{{ trans('container.serial_number') }}</span>
                    </th>

                    <th>
                      <span class="userDatatable-title">{{ trans('container.number') }}</span>
                    </th>

                    <th>
                      <span class="userDatatable-title">{{ trans('container.lock_number') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('container.shipping_date') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('container.est_arrive_date') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('container.destination') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('container.shipping_type') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('company.companies') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('broker.brokers') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('repo.repos') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('client.clients') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title float-end">Actions</span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @if (count($containers) == 0)
                  <tr>
                    <td colspan="7">
                      <p class="text-center">No Containers Found !</p>
                    </td>
                  </tr>
                  @else
                  @foreach ($containers as $container)
                  <tr>
                    <td>
                      <div class="d-flex">
                        <div class="userDatatable__imgWrapper d-flex align-items-center">

                        </div>
                        <div class="userDatatable-inline-title">
                          <a href="#" class="text-dark fw-500">
                            <h6>{{ $container->serial_number }}</h6>
                          </a>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="d-flex">
                        <div class="userDatatable__imgWrapper d-flex align-items-center">

                        </div>
                        <div class="userDatatable-inline-title">
                          <a href="#" class="text-dark fw-500">
                            <h6>{{ $container->number }}</h6>
                          </a>
                        </div>
                      </div>
                    </td>

                    <td>
                      <div class="userDatatable-content">
                        {{ $container->lock_number }}
                      </div>
                    </td>

                    <td>
                      <div class="userDatatable-content">
                        {{ $container->shipping_date }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $container->est_arrive_date }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $container->destination }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $container->shipping_type }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $container->company->name }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $container->broker == null ? '-': $container->broker->name}}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $container->repo->name .' - '. $container->repo->code }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $container->client?->name .' - '. $container->client?->code }}
                      </div>
                    </td>

                    <td>
                      <ul class="orderDatatable_actions mb-0 d-flex flex-wrap">
                        @can('update containers')
                        <li>
                          <a href="{{ route('containers.edit', [$container]) }}" class="edit">
                            <i class="uil uil-edit"></i>
                          </a>
                        </li>
                        @endcan
                        @can('delete containers')
                        <li>
                          <a href="#" class="remove" onclick="deleteItem('delete-{{ $container->id }}')">
                            <i class="uil uil-trash-alt"></i>
                          </a>

                          <form style="display:none;" id="delete-{{ $container->id }}"
                            action="{{ route('containers.destroy', [$container]) }}" method="POST">
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
            {{ $containers->links('pagination::bootstrap-5') }}

            <ul class="dm-pagination d-flex">
              <li class="dm-pagination__item">
                <div class="paging-option">
                  <select name="page-number" class="page-selection" onchange="updatePagination( event )">
                    <option value="20" {{ 20==$containers->perPage() ? 'selected' : '' }}>20/page</option>
                    <option value="40" {{ 40==$containers->perPage() ? 'selected' : '' }}>40/page</option>
                    <option value="60" {{ 60==$containers->perPage() ? 'selected' : '' }}>60/page</option>
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
  function deleteItem(form){
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
