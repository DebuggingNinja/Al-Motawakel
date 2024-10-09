@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex align-items-center user-member__title mb-30 mt-30">
                    <h4 class="text-capitalize">{{ trans('client.update') }}</h4>
                </div>
            </div>
        </div>
        <div class="card mb-50">
            <div class="row ms-5">
                <div class="col-sm-5 col-10">
                    <div class="mt-40 mb-50">
                        <form action="{{ route('clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="edit-profile__body">

                                <div class="form-group mb-25">
                                    <label for="name" class="color-dark fs-14 fw-500 align-center">{{trans('client.name')}} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="name" value="{{ $client->name }}" id="name" placeholder="Name">
                                    @if ($errors->has('name'))
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="code" class="color-dark fs-14 fw-500 align-center">{{trans('client.code')}} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="code" id="code"
                                        value="{{$client->code}}"
                                        placeholder="Client Code">
                                    @if ($errors->has('code'))
                                        <p class="text-danger">{{ $errors->first('code') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="mark" class="color-dark fs-14 fw-500 align-center">{{trans('client.mark')}} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="mark" id="mark"
                                        value="{{$client->mark}}"
                                        placeholder="Client mark">
                                    @if ($errors->has('mark'))
                                        <p class="text-danger">{{ $errors->first('mark') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="email" class="color-dark fs-14 fw-500 align-center">{{ trans('client.email') }}
                                    </label>
                                    <input type="email" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="email" id="email" value="{{ $client->email }}"
                                        placeholder="Email Address">
                                    @if ($errors->has('email'))
                                        <p class="text-danger">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="phone" class="color-dark fs-14 fw-500 align-center">
                                        {{trans('client.phone')}}
                                    </label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="phone" id="phone" value="{{ $client->phone }}"
                                        placeholder="Client Phone">
                                    @if ($errors->has('phone'))
                                        <p class="text-danger">{{ $errors->first('phone') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="phone2" class="color-dark fs-14 fw-500 align-center">
                                        {{ trans('client.phone2')}}
                                    </label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="phone2" id="phone2"
                                        value="{{$client->phone2}}"
                                        placeholder="Client Phone 2">
                                    @if ($errors->has('phone2'))
                                        <p class="text-danger">{{ $errors->first('phone2') }}</p>
                                    @endif
                                </div>

                                <div class="form-group mb-25">
                                    <label for="address" class="color-dark fs-14 fw-500 align-center">
                                        {{trans('client.address')}}
                                    </label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="address" id="address"
                                        value="{{$client->address}}"
                                        placeholder="Client Address">
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
