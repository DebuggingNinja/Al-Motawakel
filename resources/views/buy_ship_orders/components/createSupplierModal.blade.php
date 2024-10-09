<div class="modal fade" id="createSupplierModal" tabindex="-1" role="dialog" aria-labelledby="createSupplierModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createSupplierModalLabel">{{ trans('supplier.add-supplier') }}</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Include the createForm blade file -->
        <div class="card p-3">
          <div class="row ps-3 pe-3">
            <div class="col-12">
              <div class="">
                <form action="{{ route('api.suppliers.store') }}" method="POST" enctype="multipart/form-data"
                  id="createSupplierForm">

                  @csrf

                  <div class="edit-profile__body">
                    <div class="form-group mb-25">
                      <label for="name" class="color-dark fs-14 fw-500 align-center">{{trans('supplier.name')}} <span
                          class="text-danger">*</span></label>
                      <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="name"
                        value="{{ old('name') }}" id="name" placeholder="Name">
                      @if ($errors->has('name'))
                      <p class="text-danger">{{ $errors->first('name') }}</p>
                      @endif
                    </div>
                    <div class="form-group mb-25">
                      <label for="code" class="color-dark fs-14 fw-500 align-center">{{trans('supplier.code')}} <span
                          class="text-danger">*</span></label>
                      <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="code"
                        id="code" value="{{old('code')}}" placeholder="Broker Code">
                      @if ($errors->has('code'))
                      <p class="text-danger">{{ $errors->first('code') }}</p>
                      @endif
                    </div>
                    <div class="form-group mb-25">
                      <label for="store_number" class="color-dark fs-14 fw-500 align-center">
                        {{trans('supplier.store_number')}}
                      </label>
                      <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                        name="store_number" id="store_number" value="{{ old('store_number') }}"
                        placeholder="Store Number">
                      @if ($errors->has('store_number'))
                      <p class="text-danger">{{ $errors->first('store_number') }}</p>
                      @endif
                    </div>

                    <div class="form-group mb-25">
                      <label for="phone" class="color-dark fs-14 fw-500 align-center">
                        {{trans('broker.phone')}}
                        <span class="text-danger">*</span>
                      </label>
                      <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="phone"
                        id="phone" value="{{ old('phone') }}" placeholder="Broker Phone">
                      @if ($errors->has('phone'))
                      <p class="text-danger">{{ $errors->first('phone') }}</p>
                      @endif
                    </div>
                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                      <button type="submit" id="submitCreateSupplierForm"
                        class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>

                    </div>

                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
