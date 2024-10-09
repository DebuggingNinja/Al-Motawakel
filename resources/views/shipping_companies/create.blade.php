@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex align-items-center user-member__title mb-30 mt-30">
                    <h4 class="text-capitalize">{{ trans('company.add-company') }}</h4>
                </div>
            </div>
        </div>
        <div class="card mb-50">
            <div class="row ms-5">
                <div class="col-sm-5 col-10">
                    <div class="mt-40 mb-50">
                        <form action="{{ route('shipping_companies.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="edit-profile__body">
                                <div class="form-group mb-25">
                                    <label for="name" class="color-dark fs-14 fw-500 align-center">{{trans('company.name')}} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="name" value="{{ old('name') }}" id="name" placeholder="Name">
                                    @if ($errors->has('name'))
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="code" class="color-dark fs-14 fw-500 align-center">{{trans('company.code')}} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="code" id="code"
                                        value="{{old('code')}}"
                                        placeholder="Client Code">
                                    @if ($errors->has('code'))
                                        <p class="text-danger">{{ $errors->first('code') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="email" class="color-dark fs-14 fw-500 align-center">{{ trans('company.email') }}
                                    </label>
                                    <input type="email" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="email" id="email" value="{{ old('email') }}"
                                        placeholder="Email Address">
                                    @if ($errors->has('email'))
                                        <p class="text-danger">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="phone" class="color-dark fs-14 fw-500 align-center">
                                        {{trans('company.phone')}}
                                    </label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="phone" id="phone" value="{{ old('phone') }}"
                                        placeholder="Company Phone">
                                    @if ($errors->has('phone'))
                                        <p class="text-danger">{{ $errors->first('phone') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="phone2" class="color-dark fs-14 fw-500 align-center">
                                        {{ trans('company.phone2')}}
                                    </label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="phone2" id="phone2"
                                        value="{{old('phone2')}}"
                                        placeholder="Company Phone 2">
                                    @if ($errors->has('phone2'))
                                        <p class="text-danger">{{ $errors->first('phone2') }}</p>
                                    @endif
                                </div>

                                <div class="form-group mb-25">
                                    <label for="address" class="color-dark fs-14 fw-500 align-center">
                                        {{trans('company.address')}}
                                    </label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="address" id="address"
                                        value="{{old('address')}}"
                                        placeholder="Company Address">
                                    @if ($errors->has('address'))
                                        <p class="text-danger">{{ $errors->first('address') }}</p>
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
