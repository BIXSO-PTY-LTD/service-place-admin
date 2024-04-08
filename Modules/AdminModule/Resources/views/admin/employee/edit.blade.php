@extends('adminmodule::layouts.master')

@section('title', translate('employee_update'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.css"/>
    <script src="{{asset('public/assets/admin-module/js/intlTelInput.js')}}"></script>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module/css/intlTelInput.css')}}/">
@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('employee_update')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('admin.employee.update',[$employee->id])}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <h4 class="c1 mb-20">{{translate('General_Information')}}</h4>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating__icon mb-30">
                                            <input type="text" class="form-control" name="first_name"
                                                   placeholder="{{translate('First_name')}}"
                                                   value="{{$employee['first_name']}}" required>
                                            <label>{{translate('First_name')}}</label>
                                            <span class="material-icons">account_circle</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating__icon mb-30">
                                            <input type="text" class="form-control" name="last_name"
                                                   placeholder="{{translate('Last_name')}}"
                                                   value="{{$employee['last_name']}}" required>
                                            <label>{{translate('Last_name')}}</label>
                                            <span class="material-icons">account_circle</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <label for="phone">{{translate('Phone_number')}}</label>
                                            <input type="tel" class="form-control" id="phone" name="phone"
                                                   placeholder="{{translate('Phone_number')}}"
                                                   value="{{$employee['phone']}}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating form-floating__icon mb-30">
                                            <input type="text" class="form-control" id="address" name="address"
                                                   placeholder="{{translate('address')}}"
                                                   value="{{$employee->addresses->first()->address}}" required>
                                            <label>{{translate('Address')}}</label>
                                            <span class="material-icons">home</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <select class="select-identity theme-input-style w-100" name="role_id"
                                                required>
                                            <option value="0" selected disabled>{{translate('Select_role')}}</option>
                                            @foreach($roles as $role)
                                                <option
                                                    value="{{$role->id}}" {{$employee->roles->where('id',$role->id)->first()?'selected':''}}>
                                                    {{$role->role_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <select class="zone-select select-identity theme-input-style w-100"
                                                name="zone_ids[]" required id="zone_selector__select" multiple>
                                            <option value="all">{{translate('Select All')}}</option>
                                            @foreach($zones as $zone)
                                                <option
                                                    value="{{$zone->id}}" {{in_array($zone->id,$employee->zones->pluck('id')->toArray())?'selected':''}}>{{$zone->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mt-30">
                                        @php($id_types=['passport','driving_license','nid','trade_license'])
                                        <select class="select-identity theme-input-style w-100"
                                                name="identity_type" required>
                                            <option selected disabled>{{translate('Select_Identity_Type')}}</option>
                                            @foreach($id_types as $type)
                                                <option
                                                    value="{{$type}}" {{$type==$employee->identification_type?'selected':''}}>{{translate($type)}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-6 mt-30">
                                        <div class="form-floating form-floating__icon mb-30">
                                            <input type="text" class="form-control" name="identity_number"
                                                   placeholder="{{translate('Identity Number')}}"
                                                   value="{{$employee->identification_number}}" required>
                                            <label>{{translate('Identity_Number')}}</label>
                                            <span class="material-icons">badge</span>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="row gx-2">
                                            <div class="col-md-6 mb-30 mb-md-0">
                                                <div class="d-flex flex-column align-items-center gap-3">
                                                    <h3 class="mb-0">{{translate('Employee_Image')}}</h3>
                                                    <div>
                                                        <div class="upload-file">
                                                            <input type="file" class="upload-file__input"
                                                                   name="profile_image" accept=".{{ implode(',.', array_column(IMAGEEXTENSION, 'key')) }}, |image/*">
                                                            <div class="upload-file__img">
                                                                <img class="onerror-image"
                                                                     src="{{onErrorImage($employee->profile_image, asset('storage/app/public/employee/profile').'/' . $employee->profile_image,
                                                                    asset('public/assets/admin-module/img/media/upload-file.png') ,'employee/profile/')}}"
                                                                     alt="{{ translate('profile_image') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="opacity-75 max-w220 mx-auto">{{translate('Image format - jpg, png,jpeg,gif Image Size - maximum size 2 MB Image Ratio - 1:1')}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex flex-column align-items-center gap-3">
                                                    <h3 class="mb-0">{{translate('Identification_Image')}}</h3>
                                                    <div id="multi_image_picker" class="w-100">
                                                        @foreach($employee->identification_image as $img)
                                                            <img class="p-1" height="150"
                                                                 src="{{onErrorImage($img, asset('storage/app/public/employee/identity').'/' . $img,
                                                                    asset('public/assets/admin-module/img/media/provider-id.png') ,'employee/identity/')}}"
                                                                 alt="{{ translate('identity-image') }}">
                                                        @endforeach
                                                    </div>
                                                    <p class="opacity-75 max-w220 mx-auto">{{translate('Image format - jpg png jpeg gif Image Size - maximum size 2 MB')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h4 class="c1 mb-20 mt-30">{{translate('Account_Information')}}</h4>
                                    <div class="col-12">
                                        <div class="form-floating form-floating__icon mb-30">
                                            <input type="email" class="form-control" name="email"
                                                   placeholder="{{translate('Email_*')}}"
                                                   value="{{$employee['email']}}" required>
                                            <label>{{translate('Email_*')}}</label>
                                            <span class="material-icons">mail</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating__icon mb-30">
                                            <input type="password" class="form-control" name="password"
                                                   value=""
                                                   placeholder="{{translate('Password')}}">
                                            <label>{{translate('Password')}}</label>
                                            <span class="material-icons togglePassword">visibility_off</span>
                                            <span class="material-icons">lock</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating__icon mb-30">
                                            <input type="password" class="form-control" name="confirm_password"
                                                   value=""
                                                   placeholder="{{translate('Confirm_Password')}}">
                                            <label>{{translate('Confirm_Password')}}</label>
                                            <span class="material-icons togglePassword">visibility_off</span>
                                            <span class="material-icons">lock</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-4 flex-wrap justify-content-end">
                                    <button type="reset" class="btn btn--secondary">{{translate('Reset')}}</button>
                                    <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
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
    <script src="{{asset('public/assets/admin-module')}}/js/spartan-multi-image-picker.js"></script>
    <script>
        "use strict";

        $('#zone_selector__select').on('change', function() {
            var selectedValues = $(this).val();
            if (selectedValues !== null && selectedValues.includes('all')) {
                $(this).find('option').not(':disabled').prop('selected', 'selected');
                $(this).find('option[value="all"]').prop('selected', false);
            }
        });

        $("#multi_image_picker").spartanMultiImagePicker({
                fieldName: 'identity_images[]',
                maxCount: 2,
                rowHeight: 'auto',
                groupClassName: 'item',
                dropFileLabel: "{{translate('Drop_here')}}",
                placeholderImage: {
                    image: '{{asset('public/assets/admin-module')}}/img/media/banner-upload-file.png',
                    width: '100%',
                },

                onRenderedPreview: function (index) {
                    toastr.success('{{translate('Image_added')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onRemoveRow: function (index) {
                    console.log(index);
                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{translate('Please_only_input_png_or_jpg_type_file')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{translate('File_size_too_big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }
        );
    </script>

    <script>
        let flag = "{{business_config('country_code', 'business_information')->live_values ?? 'bd'}}"
        const phone = window.intlTelInput(document.querySelector("#phone"), {
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

    <script src="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.js"></script>
    <script src="{{asset('public/assets/admin-module')}}/js/section/employee/custom.js"></script>

@endpush
