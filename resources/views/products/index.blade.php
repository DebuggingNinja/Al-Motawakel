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
              <h4 class="text-capitalize fw-500 breadcrumb-title">{{ trans('products.products') }}
              </h4>
              <span class="sub-title ms-sm-25 ps-sm-25"></span>
            </div>
            <div class="action-btn mt-sm-0 mt-15">
              @can('add containers')
              <a href="{{ route('products.create') }}" class="btn px-20 btn-primary ">
                <i class="las la-plus fs-16"></i>{{ trans('products.add') }}
              </a>
              @endcan
            </div>
          </div>
          <div class="breadcrumb-main__wrapper">
            <form action="{{ route('products.index') }}" method="GET"
              class="d-flex align-items-center add-contact__form my-sm-0 my-2">
              <img src="{{ asset('assets/img/svg/search.svg') }}" alt="search" class="svg">
              <input name="search" class="form-control me-sm-2 border-0 box-shadow-none" type="search"
                placeholder="Search" aria-label="Search">
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
          {{ trans('products.products') }}
        </div>
        <div class="card-body">
          <div class="userDatatable global-shadow border-light-0 w-100">
            <div class="table-responsive">
              <table class="table mb-0 table-borderless">
                <thead>
                  <tr class="userDatatable-header">
                    <th>
                      <span class="userDatatable-title">{{ trans('order.carton_code') }}</span>
                    </th>

                    <th>
                      <span class="userDatatable-title">{{ trans('order.item') }}</span>
                    </th>

                    <th>
                      <span class="userDatatable-title">{{ trans('order.measuring') }}</span>
                    </th>

                    <th>
                      <span class="userDatatable-title">{{ trans('order.pieces_number') }}</span>
                    </th>

                    <th>
                      <span class="userDatatable-title">{{ trans('order.cbm') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('order.weight') }}</span>
                    </th>
                    <th>
                      <span class="userDatatable-title">{{ trans('order.image') }}</span>
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
                      <p class="text-center">No Products Found !</p>
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
                            <h6>{{ $container->code }}</h6>
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
                            <h6>{{ $container->name }}</h6>
                          </a>
                        </div>
                      </div>
                    </td>

                    <td>
                      <div class="userDatatable-content">
                        {{ $container->measuring }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $container->pieces_number }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $container->cbm }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        {{ $container->weight }}
                      </div>
                    </td>
                    <td>
                      <div class="userDatatable-content">
                        @if($container->image)
                          <a href="{{url('uploads/product/' . $container->image)}}" target="_blank">
                            <img src="{{url('uploads/product/' . $container->image)}}" height="100px" alt="{{$container->name}}">
                          </a>
                        @endif
                      </div>
                    </td>

                    <td>
                      <ul class="orderDatatable_actions mb-0 d-flex flex-wrap">
                        @can('update products')
                        <li>
                          <a href="{{ route('products.edit', [$container]) }}" class="edit">
                            <i class="uil uil-edit"></i>
                          </a>
                        </li>
                        @endcan
                        @can('delete products')
                        <li>
                          <a href="#" class="remove" onclick="deleteItem('delete-{{ $container->id }}')">
                            <i class="uil uil-trash-alt"></i>
                          </a>

                          <form style="display:none;" id="delete-{{ $container->id }}"
                            action="{{ route('products.destroy', [$container]) }}" method="POST">
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
