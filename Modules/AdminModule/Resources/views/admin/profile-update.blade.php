@extends('adminmodule::layouts.master')

@section('title',translate('profile_update'))
<link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/css/intlTelInput.css">
@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Update_Profile')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.profile_update') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row gx-2 mt-2">
                                    <div class="col-md-6">
                                        <div class="radius-10 h-100">
                                            <div class="card-body">
                                                <h4 class="c1 mb-20">{{translate('Information')}}</h4>
                                                <div class="row gx-2">
                                                    <div class="col-lg-6">
                                                        <div class="form-floating form-floating__icon mb-30">
                                                            <input type="text" class="form-control" name="first_name"
                                                                   value="{{ auth()->user()->first_name }}"
                                                                   placeholder="{{translate('First_Name')}}">
                                                            <label>{{translate('First_Name')}}</label>
                                                            <span class="material-icons">account_circle</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-floating form-floating__icon mb-30">
                                                            <input type="text" class="form-control" name="last_name"
                                                                   value="{{ auth()->user()->last_name }}"
                                                                   placeholder="{{translate('Last_Name')}}">
                                                            <label>{{translate('Last_Name')}}</label>
                                                            <span class="material-icons">account_circle</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-floating form-floating__icon mb-30">
                                                    <input type="email" class="form-control" name="email"
                                                           value="{{ auth()->user()->email }}"
                                                           placeholder="{{translate('Email')}}">
                                                    <label>{{translate('Email')}}</label>
                                                    <span class="material-icons">mail</span>
                                                </div>
                                                <div class="form-floating mb-30">
                                                    <label for="phone_number">{{translate('phone')}}</label>
                                                    <input id="phone_number"
                                                           type="tel" class="form-control" name="phone"
                                                           value="{{ auth()->user()->phone }}"
                                                           placeholder="{{translate('Phone')}}" required>
                                                </div>
                                                <div class="row gx-2">
                                                    <div class="col-lg-6">
                                                        <div class="form-floating form-floating__icon mb-30">
                                                            <input type="password" class="form-control" name="password"
                                                                   placeholder="{{translate('Password')}}">
                                                            <label>{{translate('Password')}}</label>
                                                            <span class="material-icons togglePassword">visibility_off</span>
                                                            <span class="material-icons">lock</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-floating form-floating__icon mb-30">
                                                            <input type="password" class="form-control"
                                                                   name="confirm_password"
                                                                   placeholder="{{translate('Confirm_Password')}}">
                                                            <label>{{translate('Confirm_Password')}}</label>
                                                            <span class="material-icons togglePassword">visibility_off</span>
                                                            <span class="material-icons">lock</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column align-items-center gap-3">
                                            <h3 class="mb-0">{{translate('profile_image')}}</h3>
                                            <div class="d-flex flex-column align-items-center">
                                                <div class="upload-file">
                                                    <input type="file" class="upload-file__input" name="profile_image" accept=".{{ implode(',.', array_column(IMAGEEXTENSION, 'key')) }}, |image/*">
                                                    <div class="upload-file__img">
                                                        <img
                                                             src="{{onErrorImage(
                                                                auth()->user()->profile_image,
                                                                auth()->user()->user_type == 'admin-employee' ? asset('storage/app/public/employee/profile').'/' . auth()->user()->profile_image : asset('storage/app/public/user/profile_image').'/' . auth()->user()->profile_image,
                                                                asset('public/assets/provider-module/img/user2x.png') ,
                                                                auth()->user()->user_type == 'admin-employee' ? 'employee/profile/' :'user/profile_image/')}}"

                                                            alt="{{ translate('profile_image') }}">
                                                    </div>
                                                    <span class="upload-file__edit">
                                                        <span class="material-icons">edit</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="opacity-75 max-w220 mx-auto">
                                                {{translate('Image format - jpg, png,jpeg, gif Image Size -maximum size 2 MB Image Ratio - 1:1')}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-4 flex-wrap justify-content-end mt-20">
                                    <button type="reset" class="btn btn--secondary">{{translate('Reset')}}</button>
                                    <button type="submit"
                                            class="btn btn--primary demo_check">{{translate('update')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{asset('public/assets/admin-module/js/intlTelInput.js')}}"></script>

    <script>
        let flag = "{{business_config('country_code', 'business_information')->live_values ?? 'bd'}}"
        var phone_number = window.intlTelInput(document.querySelector("#phone_number"), {
            utilsScript: "{{asset('public/assets/admin-module/js/utils.js')}}",
            autoHideDialCode: false,
            autoPlaceholder: "ON",
            dropdownContainer: document.body,
            formatOnDisplay: true,
            hiddenInput: "phone",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
            initialCountry: flag,
        });
    </script>
@endpush
