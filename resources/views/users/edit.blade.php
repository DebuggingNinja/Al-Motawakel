@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex align-items-center user-member__title mb-30 mt-30">
                    <h4 class="text-capitalize">{{ trans('user.update') }}</h4>
                </div>
            </div>
        </div>
        <div class="card mb-50">
            <div class="row ms-5">
                <div class="col-sm-5 col-10">
                    <div class="mt-40 mb-50">
                        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="edit-profile__body">
                                <div class="form-group mb-25">
                                    <label for="name" class="color-dark fs-14 fw-500 align-center">
                                        {{trans('user.status')}}
                                    </label>
                                    <div class="dm-select">
                                        <select class="form-control form-control-default" name="status" id="status">
                                            <option value="active" {{$user->status == 'active'? 'selected' :''}}>Active</option>
                                            <option value="deactivated" {{$user->status == 'deactivated'? 'selected':''}}>Deactivated</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-25">
                                    <label for="name" class="color-dark fs-14 fw-500 align-center">{{trans('user.name')}} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="name" value="{{ $user->name }}" id="name" placeholder="Name">
                                    @if ($errors->has('name'))
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="email" class="color-dark fs-14 fw-500 align-center">{{ trans('user.email') }}
                                    </label>
                                    <input type="email" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="email" id="email" value="{{ $user->email }}"
                                        placeholder="Email Address">
                                    @if ($errors->has('email'))
                                        <p class="text-danger">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="username" class="color-dark fs-14 fw-500 align-center">{{trans('user.username')}} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="username" id="username" value="{{ $user->username }}"
                                        placeholder="User Name">
                                    @if ($errors->has('username'))
                                        <p class="text-danger">{{ $errors->first('username') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="password" class="color-dark fs-14 fw-500 align-center">{{ trans('user.password')}} <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="password" id="password"
                                        placeholder="Password">
                                    @if ($errors->has('password'))
                                        <p class="text-danger">{{ $errors->first('password') }}</p>
                                    @endif
                                </div>
                                <div class="form-group mb-25">
                                    <label for="password_confirmation" class="color-dark fs-14 fw-500 align-center">{{trans('user.password_confirmation')}} <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                        name="password_confirmation" id="password_confirmation"
                                        placeholder="Password Confirmation">
                                    @if ($errors->has('password_confirmation'))
                                        <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                    @endif
                                </div>
                                <h6 class="color-dark align-center">
                                    {{trans('user.permissions')}}
                                </h6>
                                <div class="d-flex justify-space-between flex-wrap gap-3">

                                    @foreach ($permissions as $permission)
                                        <div class="checkbox-theme-default custom-checkbox d-inline mx-3">
                                            <input type="checkbox" name="permissions[]" id="{{$permission->id}}" value="{{$permission->name}}" {{$user->hasPermissionTo($permission->name)? 'checked': ''}} />
                                            <label for="{{$permission->id}}">{{ $permission->name }}</label>
                                        </div>
                                    @endforeach
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
