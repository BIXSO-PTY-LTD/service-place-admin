@extends('providermanagement::layouts.master')

@section('title',translate('Add_Serviceman'))

@push('css_or_js')
    <script src="{{asset('public/assets/admin-module/js/intlTelInput.js')}}"></script>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module/css/intlTelInput.css')}}">
@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Add_New_Serviceman')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('provider.serviceman.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <h4 class="c1 mb-20">{{translate('General_Information')}}</h4>
                                <div class="row gx-xl-5">
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating__icon mb-30">
                                            <input type="text" class="form-control" name="first_name"
                                                    placeholder="{{translate('First_name')}}"
                                                    value="{{old('first_name')}}" required>
                                            <label>{{translate('First_name')}}</label>
                                            <span class="material-icons">account_circle</span>
                                        </div>
                                        <div class="form-floating mb-30">
                                            <label for="phone_number">{{translate('phone')}}</label>
                                            <input type="tel" class="form-control" id="phone_number" name="phone"
                                                   placeholder="{{translate('Phone_number')}}"
                                                   value="{{old('phone')}}" required>
                                        </div>
                                        <div class="form-floating form-floating__icon mb-30">
                                            <input type="text" class="form-control" name="identity_number"
                                                    placeholder="{{translate('Identity Number')}}"
                                                    value="{{old('identity_number')}}" required>
                                            <label>{{translate('Identity_Number')}}</label>
                                            <span class="material-icons">badge</span>
                                        </div>
                                        <div class="d-flex flex-column align-items-center gap-3 mb-30">
                                            <h3 class="mb-0">{{translate('Serviceman_image')}}</h3>
                                            <div>
                                                <div class="upload-file">
                                                    <input type="file" class="upload-file__input"
                                                            name="profile_image" required accept=".{{ implode(',.', array_column(IMAGEEXTENSION, 'key')) }}, |image/*">
                                                    <div class="upload-file__img">
                                                        <img src="{{asset('public/assets/provider-module/img/media/upload-file.png')}}" alt="{{ translate('serviceman') }}">
                                                    </div>
                                                    <span class="upload-file__edit">
                                                        <span class="material-icons">edit</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="opacity-75 max-w220 mx-auto">{{translate('Image format - jpg, png, jpeg, gif Image Size - maximum size 2 MB Image Ratio - 1:1')}}</p>
                                        </div>

                                        <h4 class="c1 mb-30">{{translate('Account_Information')}}</h4>
                                        <div class="form-floating form-floating__icon mb-30">
                                            <input type="email" class="form-control" name="email"
                                                    placeholder="{{translate('Email_*')}}"
                                                    value="{{old('email')}}" required>
                                            <label>{{translate('Email_*')}}</label>
                                            <span class="material-icons">mail</span>
                                        </div>
                                        <div class="form-floating form-floating__icon mb-30">
                                            <input type="password" class="form-control" name="password"
                                                    placeholder="{{translate('Password')}}" required>
                                            <label>{{translate('Password')}}</label>
                                            <span class="material-icons">lock</span>
                                            <span class="material-icons togglePassword">visibility_off</span>
                                        </div>
                                        <div class="form-floating form-floating__icon mb-30">
                                            <input type="password" class="form-control" name="confirm_password"
                                                    placeholder="{{translate('Confirm_Password')}}" required>
                                            <label>{{translate('Confirm_Password')}}</label>
                                            <span class="material-icons">lock</span>
                                            <span class="material-icons togglePassword">visibility_off</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating__icon mb-30">
                                            <input type="text" class="form-control" name="last_name"
                                                    placeholder="{{translate('Last_name')}}"
                                                    value="{{old('last_name')}}" required>
                                            <label>{{translate('Last_name')}}</label>
                                            <span class="material-icons">account_circle</span>
                                        </div>
                                        <select class="select-identity theme-input-style mb-30" name="identity_type" required>
                                            <option disabled selected>{{translate('Select_Identity_Type')}}</option>
                                            <option value="passport">{{translate('Passport')}}</option>
                                            <option value="driving_license">{{translate('Driving_License')}}</option>
                                            <option value="nid">{{translate('nid')}}</option>
                                            <option value="trade_license">{{translate('Trade_License')}}</option>
                                        </select>

                                        <div class="d-flex flex-column align-items-start gap-3">
                                            <h3 class="mb-0">{{translate('Identification_Image')}}</h3>
                                            <div id="multi_image_picker"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-4 flex-wrap justify-content-end">
                                    <button type="reset" class="btn btn--secondary">{{translate('Reset')}}</button>
                                    <button type="submit" class="btn btn--primary">{{translate('Submit')}}</button>
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
    <script src="{{asset('public/assets/provider-module/js/spartan-multi-image-picker.js')}}"></script>
    <script>
        "use strict";

        let flag = "{{business_config('country_code', 'business_information')->live_values ?? 'bd'}}"
        const phone_number = window.intlTelInput(document.querySelector("#phone_number"), {
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

        $("#multi_image_picker").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 2,
                rowHeight: 'auto',
                groupClassName: 'multi_image_picker_item',
                //maxFileSize: '',
                dropFileLabel : "{{translate('Drop_here')}}",
                placeholderImage: {
                    image: '{{asset('public/assets/provider-module')}}/img/media/identity-img.png',
                    width: '100%',
                },

                onRenderedPreview : function(index){
                },
                onExtensionErr : function(index, file){
                    toastr.error('{{translate('Please_only_input_png_or_jpg_type_file')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr : function(index, file){
                    toastr.error('{{translate('File_size_too_big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }
        );
    </script>
@endpush
