@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex align-items-center user-member__title mb-30 mt-30">
                    <h4 class="text-capitalize">{{ trans('supplier.update-supplier') }}</h4>
                </div>
            </div>
        </div>
        <div class="card mb-50">
            <div class="row ms-5">
                <div class="col-sm-5 col-10">
                    <div class="mt-40 mb-50">
                        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="edit-profile__body">

                                <div class="form-group mb-25">
                                    <label for="name" class="color-dark fs-14 fw-500 align-center">{{trans('supplier.name')}} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="name" value="{{ $supplier->name }}" id="name" placeholder="Name">
                                    @if ($errors->has('name'))
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="code" class="color-dark fs-14 fw-500 align-center">{{trans('supplier.code')}} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="code" id="code"
                                        value="{{$supplier->code}}"
                                        placeholder="Supplier Code">
                                    @if ($errors->has('code'))
                                        <p class="text-danger">{{ $errors->first('code') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="store_number" class="color-dark fs-14 fw-500 align-center">
                                        {{trans('supplier.store_number')}}
                                    </label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="store_number" id="store_number" value="{{ $supplier->store_number }}"
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
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="phone" id="phone" value="{{ $supplier->phone }}"
                                        placeholder="Supplier Phone">
                                    @if ($errors->has('phone'))
                                        <p class="text-danger">{{ $errors->first('phone') }}</p>
                                    @endif
                                </div>
                                <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                    <button type="submit"
                                        class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
