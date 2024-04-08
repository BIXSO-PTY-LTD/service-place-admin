@extends('adminmodule::layouts.master')

@section('title',translate('business_setup'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/select.dataTables.min.css"/>
    <script src="{{asset('public/assets/admin-module/js/intlTelInput.js')}}"></script>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module/css/intlTelInput.css')}}/">
@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <div class="page-title-wrap mb-3">
                            <h2 class="page-title">{{translate('business_setup')}}</h2>
                        </div>
                        <div>
                            <i class="material-icons" data-bs-toggle="tooltip" data-bs-placement="top"
                               title="{{translate('Please click update for making the changes')}}"
                            >info</i>
                        </div>
                    </div>

                    <div class="mb-3">
                        <ul class="nav nav--tabs nav--tabs__style2">
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=business_setup"
                                   class="nav-link {{$webPage=='business_setup'?'active':''}}">
                                    {{translate('Business info')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=service_setup"
                                   class="nav-link {{$webPage=='service_setup'?'active':''}}">
                                    {{translate('General_Setup')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=promotional_setup"
                                   class="nav-link {{$webPage=='promotional_setup'?'active':''}}">
                                    {{translate('Promotions')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=bookings"
                                   class="nav-link {{$webPage=='bookings'?'active':''}}">
                                    {{translate('bookings')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=customers"
                                   class="nav-link {{$webPage=='customers'?'active':''}}">
                                    {{translate('customers')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=providers"
                                   class="nav-link {{$webPage=='providers'?'active':''}}">
                                    {{translate('providers')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=servicemen"
                                   class="nav-link {{$webPage=='servicemen'?'active':''}}">
                                    {{translate('servicemen')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=otp_login_setup"
                                   class="nav-link {{$webPage=='otp_login_setup'?'active':''}}">
                                    {{translate('otp_&_login_setup')}}
                                </a>
                            </li>
                        </ul>
                    </div>

                    @if($webPage=='business_setup')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$webPage=='business_setup'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form action="javascript:void(0)" method="POST" id="business-info-update-form">
                                            @csrf
                                            @method('PUT')
                                            <div class="discount-type">
                                                <div class="row align-items-end">
                                                    <div class="col-md-6">
                                                        <div class="mb-30">
                                                            <div class="form-floating form-floating__icon">
                                                                <input type="text" class="form-control"
                                                                       name="business_name"
                                                                       placeholder="{{translate('business_name')}} *"
                                                                       required=""
                                                                       value="{{$dataValues->where('key_name','business_name')->first()->live_values}}">
                                                                <label>{{translate('business_name')}} *</label>
                                                                <span class="material-icons">subtitles</span>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <label for="business_phone">{{translate('business_phone')}} *</label>
                                                                <input type="text" class="form-control"
                                                                       name="business_phone"
                                                                       placeholder="{{translate('business_phone')}} *"
                                                                       required="" id="business_phone"
                                                                       oninput="this.value = this.value.replace(/[^+\d]+$/g, '').replace(/(\..*)\./g, '$1');"
                                                                       value="{{$dataValues->where('key_name','business_phone')->first()->live_values}}">
                                                            </div>
                                                        </div>
                                                        <div class="mb-30">
                                                            <div class="form-floating form-floating__icon">
                                                                <input type="email" class="form-control"
                                                                       name="business_email"
                                                                       placeholder="{{translate('email')}} *"
                                                                       required=""
                                                                       value="{{$dataValues->where('key_name','business_email')->first()->live_values}}">
                                                                <label>{{translate('email')}} *</label>
                                                                <span class="material-icons">mail</span>
                                                            </div>
                                                        </div>
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                            <textarea class="form-control" name="business_address"
                                                                      placeholder="{{translate('address')}} *"
                                                                      required="">{{$dataValues->where('key_name','business_address')->first()->live_values}}</textarea>
                                                                <label>{{translate('address')}} *</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row align-items-end">
                                                            <div class="col-md-6">
                                                                <div
                                                                    class="mb-30 d-flex flex-column align-items-center gap-2">
                                                                    <p class="title-color">{{translate('favicon')}}</p>
                                                                    <div class="upload-file mb-30">
                                                                        <input type="file" class="upload-file__input"
                                                                               name="business_favicon" accept=".{{ implode(',.', array_column(IMAGEEXTENSION, 'key')) }}, |image/*">
                                                                        <div class="upload-file__img">
                                                                            <img src="{{onErrorImage(
                                                                                    $dataValues->where('key_name','business_favicon')->first()->live_values,
                                                                                    asset('storage/app/public/business').'/' . $dataValues->where('key_name','business_favicon')->first()->live_values,
                                                                                    asset('public/assets/admin-module/img/media/upload-file.png') ,
                                                                                    'business/')}}"
                                                                                alt="{{ translate('favicon') }}">
                                                                        </div>
                                                                        <span class="upload-file__edit">
                                                                            <span class="material-icons">edit</span>
                                                                        </span>
                                                                    </div>
                                                                    <p class="opacity-75 max-w220">{{translate('Image format - jpg, png,
                                                                    jpeg, gif Image Size - maximum size 2 MB Image Ratio -
                                                                    1:1')}}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div
                                                                    class="mb-30 d-flex flex-column align-items-center gap-2">
                                                                    <p class="title-color">{{translate('logo')}}</p>
                                                                    <div class="upload-file mb-30 max-w-100">
                                                                        <input type="file"
                                                                               class="upload-file__input"
                                                                               name="business_logo" accept=".{{ implode(',.', array_column(IMAGEEXTENSION, 'key')) }}, |image/*">
                                                                        <div
                                                                            class="upload-file__img upload-file__img_banner ratio-none">
                                                                            <img
                                                                                src="{{onErrorImage(
                                                                                        $dataValues->where('key_name','business_logo')->first()->live_values,
                                                                                        asset('storage/app/public/business').'/' . $dataValues->where('key_name','business_logo')->first()->live_values,
                                                                                        asset('public/assets/admin-module/img/media/banner-upload-file.png') ,
                                                                                        'business/')}}"
                                                                                alt="{{ translate('logo') }}">
                                                                        </div>
                                                                        <span class="upload-file__edit">
                                                                            <span class="material-icons">edit</span>
                                                                        </span>
                                                                    </div>
                                                                    <p class="opacity-75 max-w220">{{translate('Image format - jpg, png, jpeg, gif Image Size - maximum size 2 MB Image Ratio - 3:1')}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-end">
                                                    <div class="col-md-6 col-12 mb-30">
                                                        <div class="mb-2">{{translate('Country')}}</div>
                                                        @php($countryCode=$dataValues->where('key_name','country_code')->first()->live_values)
                                                        <select class="js-select theme-input-style w-100"
                                                                name="country_code">
                                                            <option value="0" selected
                                                                    disabled>{{translate('---Select_Country---')}}</option>
                                                            @foreach(COUNTRIES as $country)
                                                                <option
                                                                    value="{{$country['code']}}" {{$countryCode==$country['code']?'selected':''}}>
                                                                    {{$country['name']}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 col-12 mb-30">
                                                        <div class="mb-2">{{translate('Currency Code')}}</div>
                                                        @php($currencyCode=$dataValues->where('key_name','currency_code')->first()->live_values)
                                                        <select class="js-select theme-input-style w-100"
                                                                name="currency_code">
                                                            <option value="0" selected
                                                                    disabled>{{translate('---Select_Currency---')}}</option>
                                                            @foreach(CURRENCIES as $currency)
                                                                <option
                                                                    value="{{$currency['code']}}" {{$currencyCode==$currency['code']?'selected':''}}>
                                                                    {{$currency['name']}} ( {{$currency['symbol']}} )
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 col-12 mb-30">
                                                        <div class="mb-2">{{translate('Currency Symbol Position')}}</div>
                                                        @php($position=$dataValues->where('key_name','currency_symbol_position')->first()->live_values)
                                                        <select class="js-select theme-input-style w-100"
                                                                name="currency_symbol_position">
                                                            <option value="0" selected
                                                                    disabled>{{translate('---Select_Corrency_Symbol_Position---')}}</option>
                                                            <option value="right" {{$position=='right'?'selected':''}}>
                                                                {{translate('right')}}
                                                            </option>
                                                            <option value="left" {{$position=='left'?'selected':''}}>
                                                                {{translate('left')}}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 col-12 mb-30">
                                                        <div class="form-floating">
                                                            <input type="number" class="form-control"
                                                                   name="currency_decimal_point"
                                                                   min="0"
                                                                   max="10"
                                                                   placeholder="{{translate('ex: 2')}} *"
                                                                   required=""
                                                                   value="{{$dataValues->where('key_name','currency_decimal_point')->first()->live_values}}">
                                                            <label>{{translate('decimal_point_after_currency')}}*</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12 mb-30">
                                                        <div class="form-floating form-floating__icon">
                                                            <input type="number" class="form-control"
                                                                   name="default_commission"
                                                                   min="0"
                                                                   max="100"
                                                                   step="any"
                                                                   placeholder="{{translate('ex: 2')}} *"
                                                                   required=""
                                                                   value="{{$dataValues->where('key_name','default_commission')->first()->live_values}}">
                                                            <label>{{translate('default_commission_for_admin')}} ( % ) *</label>
                                                            <span class="material-icons">percent</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12 mb-30">
                                                        <div class="form-floating">
                                                            <input type="number" class="form-control"
                                                                   name="pagination_limit"
                                                                   placeholder="{{translate('ex: 2')}} *"
                                                                   min="1"
                                                                   required=""
                                                                   value="{{$dataValues->where('key_name','pagination_limit')->first()->live_values}}">
                                                            <label>{{translate('pagination_limit')}} *</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12 mb-30">
                                                        <div class="form-floating form-floating__icon">
                                                            <input type="number" class="form-control"
                                                                   name="minimum_withdraw_amount"
                                                                   placeholder="{{translate('ex: 100')}} *"
                                                                   min="1"
                                                                   step="any"
                                                                   required
                                                                   value="{{$dataValues->where('key_name','minimum_withdraw_amount')->first()->live_values??''}}">
                                                            <label>{{translate('minimum_withdraw_amount')}} *</label>
                                                            <span class="material-icons">local_atm</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12 mb-30">
                                                        <div class="form-floating form-floating__icon">
                                                            <input type="number" class="form-control"
                                                                   name="maximum_withdraw_amount"
                                                                   placeholder="{{translate('ex: 2000')}} *"
                                                                   min="1"
                                                                   step="any"
                                                                   required
                                                                   value="{{$dataValues->where('key_name','maximum_withdraw_amount')->first()->live_values??''}}">
                                                            <label>{{translate('maximum_withdraw_amount')}} *</label>
                                                            <span class="material-icons">local_atm</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12 mb-30 mt-4">
                                                        <div class="mb-2">{{translate('Time Zone')}}</div>
                                                        @php($timeZone=$dataValues->where('key_name','time_zone')->first()->live_values)
                                                        <select class="js-select theme-input-style w-100"
                                                                name="time_zone">
                                                            <option value="0" selected disabled>{{translate('---Select_Time_Zone---')}}</option>
                                                            @foreach(TIME_ZONES as $time)
                                                                <option value="{{$time['tzCode']}}" {{$timeZone==$time['tzCode']?'selected':''}}>{{$time['tzCode']}} UTC {{$time['utc']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 col-12 mb-30 mt-4">
                                                        <div class="mb-2">{{translate('Time Format')}}</div>
                                                        @php($timeFormat=$dataValues->where('key_name','time_format')->first()->live_values ?? '24h')
                                                        <select class="js-select theme-input-style w-100" name="time_format">
                                                            <option value="12" {{$timeFormat=='12'?'selected':''}}>{{translate('12_hour')}}</option>
                                                            <option value="24" {{$timeFormat=='24'?'selected':''}}>{{translate('24_hour')}}</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 col-12 mb-30">
                                                        <div class="mb-2">{{translate('Forgot Password Verification Method')}}</div>
                                                        @php($method = $dataValues->where('key_name','forget_password_verification_method')->first()?->live_values)
                                                        <select class="js-select theme-input-style w-100"
                                                                name="forget_password_verification_method">
                                                            <option value="" selected disabled>{{translate('---Select_Method---')}}</option>
                                                            <option value="email" {{$method=='email'?'selected':''}}>{{translate('email')}}</option>
                                                            <option value="phone" {{$method=='phone'?'selected':''}}>{{translate('phone')}}</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 col-12 mb-30 gap-3">
                                                        @php($value=$dataValues->where('key_name','phone_number_visibility_for_chatting')->first()->live_values??null)
                                                        <div class="border p-3 rounded d-flex justify-content-between">
                                                            <div
                                                                class="d-flex align-items-center gap-2">{{translate('Phone number visibility for chatting')}}
                                                                <i class="material-icons" data-bs-toggle="tooltip"
                                                                   data-bs-placement="top"
                                                                   title="{{translate('Customers or providers can not see each other phone numbers during chatting')}}"
                                                                >info</i>
                                                            </div>
                                                            <label class="switcher">
                                                                <input class="switcher_input" type="checkbox"
                                                                       name="phone_number_visibility_for_chatting"
                                                                       value="1"
                                                                    {{isset($value) && $value == '1' ? 'checked' : ''}}>
                                                                <span class="switcher_control"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12 mb-30 gap-3">
                                                        @php($value=$dataValues->where('key_name','direct_provider_booking')->first()->live_values??null)
                                                        <div class="border p-3 rounded d-flex justify-content-between">
                                                            <div
                                                                class="d-flex align-items-center gap-2">{{translate('Direct Provider Booking')}}
                                                                <i class="material-icons" data-bs-toggle="tooltip"
                                                                   data-bs-placement="top"
                                                                   title="{{translate('Customers can directly book any provider')}}"
                                                                >info</i>
                                                            </div>
                                                            <label class="switcher">
                                                                <input class="switcher_input" type="checkbox"
                                                                       name="direct_provider_booking" value="1"
                                                                    {{isset($value) && $value == '1' ? 'checked' : ''}}>
                                                                <span class="switcher_control"></span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-30">
                                                        <div class="form-floating form-floating__icon">
                                                            <input type="text" class="form-control" name="footer_text"
                                                                   placeholder="{{translate('ex:_all_right_reserved')}} *"
                                                                   required=""
                                                                   value="{{$dataValues->where('key_name','footer_text')->first()->live_values}}">
                                                            <label>{{translate('footer_text')}} *</label>
                                                            <span class="material-icons">description</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-30">
                                                        <div class="form-floating">
                                                            <textarea type="text" class="form-control"
                                                                      name="cookies_text"
                                                                      placeholder="{{translate('ex:_al_right_reserved')}} *"
                                                                      required>{{$dataValues->where('key_name','cookies_text')->first()->live_values??null}}</textarea>
                                                            <label>{{translate('cookies_text')}} *</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="reset" class="btn btn-secondary">
                                                    {{translate('reset')}}
                                                </button>
                                                <button type="submit" class="btn btn--primary">
                                                    {{translate('update')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($webPage=='service_setup')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$webPage=='service_setup'?'active show':''}}"
                                 id="business-info">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form action="{{route('admin.business-settings.set-service-setup')}}"
                                              method="POST">
                                            @csrf
                                            @method('put')
                                            <div class="row">
                                                    <?php
                                                    $emailVerification = collect([['key' => 'email_verification', 'info_message' => 'During registration & Login Customers have to verify via email', 'title' => 'Email Verification']]);
                                                    $phoneVerification = collect([['key' => 'phone_verification', 'title' => 'Phone Verifiaction', 'info_message' => 'During registration & Login Customers have to verify via phone']]);
                                                    $cashAfterService = collect([['key' => 'cash_after_service', 'info_message' => 'Customer can pay with cash after receiving the service', 'title' => 'Cash After Service']]);
                                                    $digitalPayment = collect([['key' => 'digital_payment', 'info_message' => 'Customer can pay with digital payments', 'title' => 'Digital Payment']]);
                                                    $partialPayment = collect([['key' => 'partial_payment', 'title' => 'Partial Payment', 'info_message' => 'Customer can pay partially with their wallet balance']]);
                                                    $partialPaymentCombinator = collect([['key' => 'partial_payment_combinator', 'title' => 'Can Combine Payment', 'info_message' => 'Admin can set how customers will make the partial payment by clicking on the preferred radio button. This section will be hidden if Partial Payment feature is disabled']]);
                                                    $offlinePayment = collect([['key' => 'offline_payment', 'title' => 'Offline Payment', 'info_message' => 'Offline Payment allows customers to use external payment methods. After payment, they need to use the transaction details while placing bookings. Admin can set if customers can make offline payments or not by enabling/disabling this button']]);
                                                    $guestCheckout = collect([['key' => 'guest_checkout', 'title' => 'Guest Checkout', 'info_message' => 'Admin can off guest checkout']]);
                                                    ?>
                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span
                                                                class="text-capitalize">{{translate($emailVerification[0]['title'])}}</span>
                                                            <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="{{translate($emailVerification[0]['info_message'] ?? '')}}"
                                                            >info</i>
                                                        </div>
                                                        <label class="switcher">
                                                            @php($value = $dataValues->where('key_name', $emailVerification[0]['key'])?->first()?->live_values ?? null)
                                                            <input class="switcher_input switch_alert"
                                                                   id="{{$emailVerification[0]['key']}}"
                                                                   type="checkbox"
                                                                   name="{{$emailVerification[0]['key']}}"
                                                                   value="1" {{$value ? 'checked' : ''}}
                                                                   data-id="{{$emailVerification[0]['key']}}"
                                                                   data-message="{{ucfirst(translate($emailVerification[0]['key']))}}"
                                                            >
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span
                                                                class="text-capitalize">{{translate($phoneVerification[0]['title'])}}</span>
                                                            <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="{{translate($phoneVerification[0]['info_message'] ?? '')}}"
                                                            >info</i>
                                                        </div>
                                                        <label class="switcher">
                                                            @php($value = $dataValues->where('key_name', $phoneVerification[0]['key'])?->first()?->live_values ?? null)
                                                            <input class="switcher_input switch_alert"
                                                                   id="{{$phoneVerification[0]['key']}}"
                                                                   type="checkbox"
                                                                   name="{{$phoneVerification[0]['key']}}"
                                                                   value="1" {{$value ? 'checked' : ''}}
                                                                   data-id="{{$phoneVerification[0]['key']}}"
                                                                   data-message="{{ucfirst(translate($phoneVerification[0]['key']))}}">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span
                                                                class="text-capitalize">{{translate($cashAfterService[0]['title'])}}</span>
                                                            <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="{{translate($cashAfterService[0]['info_message'] ?? '')}}"
                                                            >info</i>
                                                        </div>
                                                        <label class="switcher">
                                                            @php($value = $dataValues->where('key_name', $cashAfterService[0]['key'])?->first()?->live_values ?? null)
                                                            <input class="switcher_input switch_alert"
                                                                   id="{{$cashAfterService[0]['key']}}"
                                                                   type="checkbox"
                                                                   name="{{$cashAfterService[0]['key']}}"
                                                                   value="1" {{$value ? 'checked' : ''}}
                                                                   data-id="{{$cashAfterService[0]['key']}}"
                                                                   data-message="{{ucfirst(translate($cashAfterService[0]['key']))}}">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span
                                                                class="text-capitalize">{{translate($digitalPayment[0]['title'])}}</span>
                                                            <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="{{translate($digitalPayment[0]['info_message'] ?? '')}}"
                                                            >info</i>
                                                        </div>
                                                        <label class="switcher">
                                                            @php($value = $dataValues->where('key_name', $digitalPayment[0]['key'])?->first()?->live_values ?? null)
                                                            <input class="switcher_input switch_alert"
                                                                   id="{{$digitalPayment[0]['key']}}" type="checkbox"
                                                                   name="{{$digitalPayment[0]['key']}}"
                                                                   value="1" {{$value ? 'checked' : ''}}
                                                                   data-id="{{$digitalPayment[0]['key']}}"
                                                                   data-message="{{ucfirst(translate($digitalPayment[0]['key']))}}"
                                                            >
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span
                                                                class="text-capitalize">{{translate($partialPayment[0]['title'])}}</span>
                                                            <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="{{translate($partialPayment[0]['info_message'] ?? '')}}"
                                                            >info</i>
                                                        </div>
                                                        <label class="switcher">
                                                            @php($value = $dataValues->where('key_name', $partialPayment[0]['key'])?->first()?->live_values ?? null)
                                                            <input class="switcher_input switch_alert"
                                                                   id="{{$partialPayment[0]['key']}}" type="checkbox"
                                                                   name="{{$partialPayment[0]['key']}}"
                                                                   value="1" {{$value ? 'checked' : ''}}
                                                                   data-id="{{$partialPayment[0]['key']}}"
                                                                   data-message="{{ucfirst(translate($partialPayment[0]['key']))}}"
                                                            >
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12 mb-30">
                                                    <label for="partials_payment_combinator"
                                                           class="mb-2">{{translate($partialPaymentCombinator[0]['title'])}}</label>
                                                    <div
                                                        class="border p-3 rounded d-flex justify-content-between gap-2">
                                                        <div class="d-flex align-items-start gap-3 gap-xl-4">
                                                            <div class="custom-radio">
                                                                <input type="radio" id="cash_after_service_combinator"
                                                                       name="partial_payment_combinator"
                                                                       value="cash_after_service" {{$dataValues->where('key_name', $partialPaymentCombinator[0]['key'])->first()->live_values == 'cash_after_service' ? 'checked' : ''}}>
                                                                <label
                                                                    for="cash_after_service_combinator">{{translate('Cash After Service')}}</label>
                                                            </div>
                                                            <div class="custom-radio">
                                                                <input type="radio" id="digital_payment_combinator"
                                                                       name="partial_payment_combinator"
                                                                       value="digital_payment" {{$dataValues->where('key_name', $partialPaymentCombinator[0]['key'])->first()->live_values == 'digital_payment' ? 'checked' : ''}}>
                                                                <label
                                                                    for="digital_payment_combinator">{{translate('Digital Payment')}}</label>
                                                            </div>
                                                            <div class="custom-radio">
                                                                <input type="radio" id="offline_payment_combinator"
                                                                       name="partial_payment_combinator"
                                                                       value="offline_payment" {{$dataValues->where('key_name', $partialPaymentCombinator[0]['key'])->first()->live_values == 'offline_payment' ? 'checked' : ''}}>
                                                                <label
                                                                    for="offline_payment_combinator">{{translate('Offline Payment')}}</label>
                                                            </div>
                                                            <div class="custom-radio">
                                                                <input type="radio" id="all_combinator"
                                                                       name="partial_payment_combinator"
                                                                       value="all" {{$dataValues->where('key_name', $partialPaymentCombinator[0]['key'])->first()->live_values == 'all' ? 'checked' : ''}}>
                                                                <label for="all_combinator">{{translate('All')}}</label>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <i class="material-icons cursor-pointer"
                                                               data-bs-toggle="tooltip"
                                                               title="{{$partialPaymentCombinator[0]['info_message']}}">info</i>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span
                                                                class="text-capitalize">{{translate($offlinePayment[0]['title'])}}</span>
                                                            <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="{{translate($offlinePayment[0]['info_message'] ?? '')}}"
                                                            >info</i>
                                                        </div>
                                                        <label class="switcher">
                                                            @php($value = $dataValues->where('key_name', $offlinePayment[0]['key'])?->first()?->live_values ?? null)
                                                            <input class="switcher_input switch_alert"
                                                                   id="{{$offlinePayment[0]['key']}}" type="checkbox"
                                                                   name="{{$offlinePayment[0]['key']}}"
                                                                   value="1" {{$value ? 'checked' : ''}}
                                                                   data-id="{{$offlinePayment[0]['key']}}"
                                                                   data-message="{{ucfirst(translate($offlinePayment[0]['key']))}}"
                                                            >
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span
                                                                class="text-capitalize">{{translate($guestCheckout[0]['title'])}}</span>
                                                            <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="{{translate($guestCheckout[0]['info_message'] ?? '')}}"
                                                            >info</i>
                                                        </div>
                                                        <label class="switcher">
                                                            @php($value = $dataValues->where('key_name', $guestCheckout[0]['key'])?->first()?->live_values ?? null)
                                                            <input class="switcher_input switch_alert"
                                                                   id="{{$guestCheckout[0]['key']}}" type="checkbox"
                                                                   name="{{$guestCheckout[0]['key']}}"
                                                                   value="1" {{$value ? 'checked' : ''}}
                                                                   data-id="{{$guestCheckout[0]['key']}}"
                                                                   data-message="{{ucfirst(translate($guestCheckout[0]['key']))}}"
                                                            >
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="reset" class="btn btn-secondary">
                                                    {{translate('reset')}}
                                                </button>
                                                <button type="submit" class="btn btn--primary">
                                                    {{translate('update')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($webPage=='bookings')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$webPage=='bookings'?'active show':''}}"
                                 id="business-info">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form action="javascript:void(0)" method="POST" id="booking-system-update-form">
                                            @csrf
                                            @method('PUT')
                                            <div class="row g-4">
                                                <div class="col-md-6 col-12">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span>{{translate('bidding_System_For_Customer_Booking')}}</span>
                                                            <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="{{translate('User  can use the bid feature to create post for customize service requests while the option is enabled')}}"
                                                            >info</i>
                                                        </div>
                                                        <label class="switcher">
                                                            <input class="switcher_input switch_alert" type="checkbox"
                                                                   name="bidding_status" value="1" id="bidding_status"
                                                                   {{$dataValues->where('key_name', 'bidding_status')->first()?->live_values ?'checked':''}}
                                                                   data-id="bidding_status"
                                                                   data-message="Want to change the status of bidding system">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <div class="form-floating form-floating__icon">
                                                        <input class="form-control" name="bidding_post_validity"
                                                               placeholder="{{translate('Post Validation (days)')}} *"
                                                               type="number" required
                                                               value="{{$dataValues->where('key_name', 'bidding_post_validity')->first()->live_values ?? ''}}">
                                                        <label>{{translate('Post Validation (days)')}} *</label>
                                                        <span class="material-icons">calendar_month</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span>{{translate('See Other Providers Offers')}}</span>
                                                            <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="{{translate('Provider can see the bid offers of other provider')}}"
                                                            >info</i>
                                                        </div>
                                                        <label class="switcher">
                                                            <input class="switcher_input switch_alert" type="checkbox"
                                                                   name="bid_offers_visibility_for_providers" value="1"
                                                                   id="bid_offer_visibility"
                                                                   data-id="bid_offer_visibility"
                                                                   data-message="Want to change the status of provider offers"

                                                            {{$dataValues->where('key_name', 'bid_offers_visibility_for_providers')->first()?->live_values ?'checked':''}}>
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span>{{translate('confirmation_OTP_for_Complete_Service')}}</span>
                                                            <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="{{translate('confirmation_OTP_for_Complete_Service')}}"
                                                            >info</i>
                                                        </div>
                                                        <label class="switcher">
                                                            <input class="switcher_input switch_alert" type="checkbox"
                                                                   name="booking_otp" value="1" id="booking_otp"
                                                                   data-id="booking_otp"
                                                                   data-message="Want to change the status of confirmation otp for complete service"
                                                                {{$dataValues->where('key_name', 'booking_otp')->first()?->live_values ?'checked':''}}>
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span>{{translate('Additional_Charge_on_Booking')}}</span>
                                                            <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="{{translate('Admin can charge additional serivce charge')}}"
                                                            >info</i>
                                                        </div>
                                                        <label class="switcher">
                                                            <input class="switcher_input switch_alert" type="checkbox"
                                                                   name="booking_additional_charge" value="1"
                                                                   id="booking_additional_charge"
                                                                   {{$dataValues->where('key_name', 'booking_additional_charge')->first()?->live_values ?'checked':''}}
                                                                   data-id="booking_additional_charge"
                                                                   data-message="Want to change the status Additional Charge on Booking">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-12">
                                                    <div class="form-floating form-floating__icon">
                                                        <input class="form-control remove-spin"
                                                               name="additional_charge_label_name"
                                                               placeholder="{{translate('Additional Charge Label')}} *"
                                                               type="text" required
                                                               value="{{$dataValues->where('key_name', 'additional_charge_label_name')->first()->live_values ?? ''}}"
                                                        >
                                                        <label>{{translate('Additional Charge Label')}}* </label>

                                                        <span class="material-icons" data-bs-toggle="tooltip"
                                                              title="{{ translate('additional_charge_label_name') }}">info</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-12">
                                                    <div class="form-floating">
                                                        <input class="form-control remove-spin"
                                                               name="additional_charge_fee_amount"
                                                               placeholder="{{translate('Additional charge fee')}} *"
                                                               type="number" min="0" step="any" required
                                                               value="{{$dataValues->where('key_name', 'additional_charge_fee_amount')->first()->live_values ?? ''}}"
                                                        >
                                                        <label>{{translate('Additional charge fee')}}* </label>
                                                        <span class="material-icons" data-bs-toggle="tooltip"
                                                              title="{{ translate('Additional charge fee') }}">info</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <div class="form-floating form-floating__icon">
                                                        <input class="form-control" name="min_booking_amount"
                                                               placeholder="{{translate('Post Validation (days)')}} *"
                                                               type="number" required step="any"
                                                               value="{{$dataValues->where('key_name', 'min_booking_amount')->first()->live_values ?? ''}}">
                                                        <label>{{translate('min_booking_amount')}} *</label>
                                                        <span class="material-icons">local_atm</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <div class="form-floating form-floating__icon">
                                                        <input class="form-control" name="max_booking_amount"
                                                               placeholder="{{translate('Post Validation (days)')}} *"
                                                               type="number" required step="any"
                                                               value="{{$dataValues->where('key_name', 'max_booking_amount')->first()->live_values ?? ''}}">
                                                        <label>{{translate('max_booking_amount')}} *</label>
                                                        <span class="material-icons">local_atm</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span>{{translate('Service_complete_Photo_Evidence')}}</span>
                                                            <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="{{translate('Upload photo evidence before concluding the service process.')}}"
                                                            >info</i>
                                                        </div>
                                                        <label class="switcher">
                                                            <input class="switcher_input switch_alert" type="checkbox"
                                                                   name="service_complete_photo_evidence" value="1"
                                                                   id="photo_evidence"
                                                                   {{$dataValues->where('key_name', 'service_complete_photo_evidence')->first()?->live_values ?'checked':''}}
                                                                   data-id="photo_evidence"
                                                                   data-message="Want to change the status Take Picture Before Complete">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="d-flex gap-2 justify-content-end mt-3">
                                                <button type="reset" class="btn btn-secondary">
                                                    {{translate('reset')}}
                                                </button>
                                                <button type="submit" class="btn btn--primary">
                                                    {{translate('update')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($webPage=='promotional_setup')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$webPage=='promotional_setup'?'active show':''}}">
                                <div class="row">
                                    @php($data = $dataValues->where('key_name', 'discount_cost_bearer')->first()->live_values ?? null)
                                    <div class="col-lg-6 col-12 mb-30">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="page-title d-flex align-items-center gap-2">
                                                    <i class="material-icons">redeem</i>
                                                    {{translate('Normal_Discount')}}
                                                </h4>
                                            </div>
                                            <div class="card-body p-30">
                                                <h5 class="pb-4">{{translate('Discount_Cost_Bearer')}}</h5>
                                                <form action="{{route('admin.business-settings.set-promotion-setup')}}"
                                                      method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="d-flex flex-column flex-sm-row flex-wrap gap-3">
                                                        <div
                                                            class="d-flex align-items-start flex-column gap-3 gap-xl-4 mb-30 flex-grow-1">
                                                            <div class="custom-radio">
                                                                <input type="radio" id="admin-select__discount"
                                                                       name="bearer"
                                                                       value="admin" {{isset($data) && $data['bearer'] == 'admin' ? 'checked' : ''}}>
                                                                <label
                                                                    for="admin-select__discount">{{translate('Admin')}}</label>
                                                            </div>
                                                            <div class="custom-radio">
                                                                <input type="radio" id="provider-select__discount"
                                                                       name="bearer"
                                                                       value="provider" {{isset($data) && $data['bearer'] == 'provider' ? 'checked' : ''}}>
                                                                <label
                                                                    for="provider-select__discount">{{translate('Provider')}}</label>
                                                            </div>
                                                            <div class="custom-radio">
                                                                <input type="radio" id="both-select__discount"
                                                                       name="bearer"
                                                                       value="both" {{isset($data) && $data['bearer'] == 'both' ? 'checked' : ''}}>
                                                                <label
                                                                    for="both-select__discount">{{translate('Both')}}</label>
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="flex-grow-1 {{isset($data) && ($data['bearer'] != 'admin' && $data['bearer'] != 'provider') ? '' : 'd-none'}}"
                                                            id="bearer-section__discount">
                                                            <div class="mb-30">
                                                                <div class="form-floating">
                                                                    <input type="number" class="form-control"
                                                                           name="admin_percentage"
                                                                           id="admin_percentage__discount"
                                                                           placeholder="{{translate('Admin_Percentage')}} (%)"
                                                                           value="{{!is_null($data) ? $data['admin_percentage'] : ''}}"
                                                                           min="0" max="100" step="any">
                                                                    <label>{{translate('Admin_Percentage')}} (%)</label>
                                                                </div>
                                                            </div>
                                                            <div class="mb-30">
                                                                <div class="form-floating">
                                                                    <input type="number" class="form-control"
                                                                           name="provider_percentage"
                                                                           id="provider_percentage__discount"
                                                                           placeholder="{{translate('Provider_Percentage')}} (%)"
                                                                           value="{{!is_null($data) ? $data['provider_percentage'] : ''}}"
                                                                           min="0" max="100" step="any">
                                                                    <label>{{translate('Provider_Percentage')}}
                                                                        (%)</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="type" value="discount" class="d-none">

                                                    <div class="d-flex justify-content-end gap-20">
                                                        <button type="submit" class="btn btn--primary demo_check">
                                                            {{translate('update')}}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    @php($data = $dataValues->where('key_name', 'campaign_cost_bearer')->first()->live_values ?? null)
                                    <div class="col-lg-6 col-12 mb-30">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="page-title d-flex align-items-center gap-2">
                                                    <i class="material-icons">campaign</i>
                                                    {{translate('Campaign_Discount')}}
                                                </h4>
                                            </div>
                                            <div class="card-body p-30">
                                                <h5 class="pb-4">{{translate('Campaign_Cost_Bearer')}}</h5>
                                                <form action="{{route('admin.business-settings.set-promotion-setup')}}"
                                                      method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="d-flex flex-column flex-sm-row flex-wrap gap-3">
                                                        <div
                                                            class="d-flex align-items-start flex-column gap-3 gap-xl-4 mb-30 flex-grow-1">
                                                            <div class="custom-radio">
                                                                <input type="radio" id="admin-select__campaign"
                                                                       name="bearer"
                                                                       value="admin" {{isset($data) && $data['bearer'] == 'admin' ? 'checked' : ''}}>
                                                                <label
                                                                    for="admin-select__campaign">{{translate('Admin')}}</label>
                                                            </div>
                                                            <div class="custom-radio">
                                                                <input type="radio" id="provider-select__campaign"
                                                                       name="bearer"
                                                                       value="provider" {{isset($data) && $data['bearer'] == 'provider' ? 'checked' : ''}}>
                                                                <label
                                                                    for="provider-select__campaign">{{translate('Provider')}}</label>
                                                            </div>
                                                            <div class="custom-radio">
                                                                <input type="radio" id="both-select__campaign"
                                                                       name="bearer"
                                                                       value="both" {{isset($data) && $data['bearer'] == 'both' ? 'checked' : ''}}>
                                                                <label
                                                                    for="both-select__campaign">{{translate('Both')}}</label>
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="flex-grow-1 {{isset($data) && ($data['bearer'] != 'admin' && $data['bearer'] != 'provider') ? '' : 'd-none'}}"
                                                            id="bearer-section__campaign">
                                                            <div class="mb-30">
                                                                <div class="form-floating">
                                                                    <input type="number" class="form-control"
                                                                           name="admin_percentage"
                                                                           id="admin_percentage__campaign"
                                                                           placeholder="{{translate('Admin_Percentage')}} (%)"
                                                                           value="{{!is_null($data) ? $data['admin_percentage'] : ''}}"
                                                                           min="0" max="100" step="any">
                                                                    <label>{{translate('Admin_Percentage')}} (%)</label>
                                                                </div>
                                                            </div>
                                                            <div class="mb-30">
                                                                <div class="form-floating">
                                                                    <input type="number" class="form-control"
                                                                           name="provider_percentage"
                                                                           id="provider_percentage__campaign"
                                                                           placeholder="{{translate('Provider_Percentage')}} (%)"
                                                                           value="{{!is_null($data) ? $data['provider_percentage'] : ''}}"
                                                                           min="0" max="100" step="any">
                                                                    <label>{{translate('Provider_Percentage')}}
                                                                        (%)</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="type" value="campaign" class="d-none">

                                                    <div class="d-flex justify-content-end gap-20">
                                                        <button type="submit" class="btn btn--primary demo_check">
                                                            {{translate('update')}}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    @php($data = $dataValues->where('key_name', 'coupon_cost_bearer')->first()->live_values ?? null)
                                    <div class="col-lg-6 col-12 mb-30">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="page-title d-flex align-items-center gap-2">
                                                    <i class="material-icons">sell</i>
                                                    {{translate('Coupon_Discount')}}
                                                </h4>
                                            </div>
                                            <div class="card-body p-30">
                                                <h5 class="pb-4">{{translate('Coupon_Cost_Bearer')}}</h5>
                                                <form action="{{route('admin.business-settings.set-promotion-setup')}}"
                                                      method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="d-flex flex-column flex-sm-row flex-wrap gap-3">
                                                        <div
                                                            class="d-flex align-items-start flex-column gap-3 gap-xl-4 mb-30 flex-grow-1">
                                                            <div class="custom-radio">
                                                                <input type="radio" id="admin-select__coupon"
                                                                       name="bearer"
                                                                       value="admin" {{isset($data) && $data['bearer'] == 'admin' ? 'checked' : ''}}>
                                                                <label
                                                                    for="admin-select__coupon">{{translate('Admin')}}</label>
                                                            </div>
                                                            <div class="custom-radio">
                                                                <input type="radio" id="provider-select__coupon"
                                                                       name="bearer"
                                                                       value="provider" {{isset($data) && $data['bearer'] == 'provider' ? 'checked' : ''}}>
                                                                <label
                                                                    for="provider-select__coupon">{{translate('Provider')}}</label>
                                                            </div>
                                                            <div class="custom-radio">
                                                                <input type="radio" id="both-select__coupon"
                                                                       name="bearer"
                                                                       value="both" {{isset($data) && $data['bearer'] == 'both' ? 'checked' : ''}}>
                                                                <label
                                                                    for="both-select__coupon">{{translate('Both')}}</label>
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="flex-grow-1 {{isset($data) && ($data['bearer'] != 'admin' && $data['bearer'] != 'provider') ? '' : 'd-none'}}"
                                                            id="bearer-section__coupon">
                                                            <div class="mb-30">
                                                                <div class="form-floating">
                                                                    <input type="number" class="form-control"
                                                                           name="admin_percentage"
                                                                           id="admin_percentage__coupon"
                                                                           placeholder="{{translate('Admin_Percentage')}} (%)"
                                                                           value="{{!is_null($data) ? $data['admin_percentage'] : ''}}"
                                                                           min="0" max="100" step="any">
                                                                    <label>{{translate('Admin_Percentage')}} (%)</label>
                                                                </div>
                                                            </div>
                                                            <div class="mb-30">
                                                                <div class="form-floating">
                                                                    <input type="number" class="form-control"
                                                                           name="provider_percentage"
                                                                           id="provider_percentage__coupon"
                                                                           placeholder="{{translate('Provider_Percentage')}} (%)"
                                                                           value="{{!is_null($data) ? $data['provider_percentage'] : ''}}"
                                                                           min="0" max="100" step="any">
                                                                    <label>{{translate('Provider_Percentage')}}
                                                                        (%)</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="type" value="coupon" class="d-none">

                                                    <div class="d-flex justify-content-end gap-20">
                                                        <button type="submit" class="btn btn--primary demo_check">
                                                            {{translate('update')}}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($webPage=='servicemen')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$webPage=='servicemen'?'active show':''}}"
                                 id="business-info">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form action="{{route('admin.business-settings.set-servicemen')}}"
                                              method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row g-4">
                                                @php($service_man_booking_cancel = collect([ ['key' => 'serviceman_can_cancel_booking','info_message' => 'Service Men Can Cancel Booking', 'title' => 'Cancel Booking Req'] ]))
                                                @php($service_man_booking_edit = collect([ ['key' => 'serviceman_can_edit_booking','info_message' => 'Service Men Can Edit Booking', 'title' => 'Edit Booking Req'] ]))

                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span
                                                                class="text-capitalize">{{translate($service_man_booking_cancel[0]['title'])}}</span>
                                                            <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="{{translate($service_man_booking_cancel[0]['info_message'] ?? '')}}"
                                                            >info</i>
                                                        </div>
                                                        <label class="switcher">
                                                            @php($value = $dataValues->where('key_name', $service_man_booking_cancel[0]['key'])?->first()?->live_values ?? null)
                                                            <input class="switcher_input switch_alert"
                                                                   id="{{$service_man_booking_cancel[0]['key']}}"
                                                                   type="checkbox"
                                                                   name="{{$service_man_booking_cancel[0]['key']}}"
                                                                   value="1" {{$value ? 'checked' : ''}}
                                                                   data-id="{{$service_man_booking_cancel[0]['key']}}"
                                                                   data-message="Want to change the status of {{ucfirst(translate($service_man_booking_cancel[0]['key']))}}"
                                                            >
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span
                                                                class="text-capitalize">{{translate($service_man_booking_edit[0]['title'])}}</span>
                                                            <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="{{translate($service_man_booking_edit[0]['info_message'] ?? '')}}"
                                                            >info</i>
                                                        </div>
                                                        <label class="switcher">
                                                            @php($value = $dataValues->where('key_name', $service_man_booking_edit[0]['key'])?->first()?->live_values ?? null)
                                                            <input class="switcher_input switch_alert"
                                                                   id="{{$service_man_booking_edit[0]['key']}}"
                                                                   type="checkbox"
                                                                   name="{{$service_man_booking_edit[0]['key']}}"
                                                                   value="1" {{$value ? 'checked' : ''}}
                                                                   data-id="{{$service_man_booking_edit[0]['key']}}"
                                                                   data-message="Want to change the status of {{ucfirst(translate($service_man_booking_edit[0]['key']))}}"
                                                            >
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="d-flex gap-2 justify-content-end mt-4">
                                                <button type="reset" class="btn btn-secondary">{{translate('reset')}}
                                                </button>
                                                <button type="submit" class="btn btn--primary">{{translate('update')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($webPage=='customers')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$webPage=='customers'?'active show':''}}"
                                 id="business-info">
                                <form action="{{route('admin.business-settings.set-customer-setup')}}"
                                      method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="page-title d-flex align-items-center gap-2">
                                                <i class="material-icons">redeem</i>
                                                {{translate('Normal_Discount')}}
                                            </h4>
                                        </div>
                                        <div class="card-body p-30">
                                            <div class="row g-4 mb-30">
                                                @php($customerWallet = collect([ ['key' => 'customer_wallet','info_message' => 'Customer Wallet Status', 'title' => 'Customer Wallet'] ]))
                                                @php($addFundToWallet = collect([ ['key' => 'add_to_fund_wallet','info_message' => 'Customer Can Add Fund to Wallet', 'title' => 'Add Fund to Wallet'] ]))
                                                @php($customerLoyaltyPoint = collect([ ['key' => 'customer_loyalty_point','info_message' => 'Customer Loyalty Point', 'title' => 'Customer Loyalty Point'] ]))
                                                @php($customerReferralEarning = collect([ ['key' => 'customer_referral_earning','info_message' => 'Customer Referral Earning', 'title' => 'Customer Referral Earning'] ]))

                                                <div class="col-md-6 col-lg-4 col-12">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span
                                                                class="text-capitalize">{{translate($customerWallet[0]['title'])}}</span>
                                                        </div>
                                                        <label class="switcher">
                                                            @php($value = $dataValues->where('key_name', $customerWallet[0]['key'])?->first()?->live_values ?? null)
                                                            <input class="switcher_input switch_alert"
                                                                   id="{{$customerWallet[0]['key']}}"
                                                                   type="checkbox"
                                                                   name="{{$customerWallet[0]['key']}}"
                                                                   value="1" {{$value ? 'checked' : ''}}
                                                                   data-id="{{$customerWallet[0]['key']}}"
                                                                   data-message="Want to change the status of {{ucfirst(translate($customerWallet[0]['key']))}}"
                                                            >
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-lg-4 col-12">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span
                                                                class="text-capitalize">{{translate($addFundToWallet[0]['title'])}}</span>
                                                        </div>
                                                        <label class="switcher">
                                                            @php($value = $dataValues->where('key_name', $addFundToWallet[0]['key'])?->first()?->live_values ?? null)
                                                            <input class="switcher_input switch_alert"
                                                                   id="{{$addFundToWallet[0]['key']}}"
                                                                   type="checkbox"
                                                                   name="{{$addFundToWallet[0]['key']}}"
                                                                   value="1" {{$value ? 'checked' : ''}}
                                                                   data-id="{{$addFundToWallet[0]['key']}}"
                                                                   data-message="Want to change the status of {{ucfirst(translate($addFundToWallet[0]['key']))}}"
                                                            >
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-lg-4 col-12">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span
                                                                class="text-capitalize">{{translate($customerLoyaltyPoint[0]['title'])}}</span>
                                                        </div>
                                                        <label class="switcher">
                                                            @php($value = $dataValues->where('key_name', $customerLoyaltyPoint[0]['key'])?->first()?->live_values ?? null)
                                                            <input class="switcher_input switch_alert"
                                                                   id="{{$customerLoyaltyPoint[0]['key']}}"
                                                                   type="checkbox"
                                                                   name="{{$customerLoyaltyPoint[0]['key']}}"
                                                                   value="1" {{$value ? 'checked' : ''}}
                                                                   data-id="{{$customerLoyaltyPoint[0]['key']}}"
                                                                   data-message="Want to change the status of {{ucfirst(translate($customerLoyaltyPoint[0]['key']))}}"
                                                            >
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-lg-4 col-12">
                                                    <div class="border p-3 rounded d-flex justify-content-between">
                                                        <div>
                                                            <span
                                                                class="text-capitalize">{{translate($customerReferralEarning[0]['title'])}}</span>
                                                        </div>
                                                        <label class="switcher">
                                                            @php($value = $dataValues->where('key_name', $customerReferralEarning[0]['key'])?->first()?->live_values ?? null)
                                                            <input class="switcher_input switch_alert"
                                                                   id="{{$customerReferralEarning[0]['key']}}"
                                                                   type="checkbox"
                                                                   name="{{$customerReferralEarning[0]['key']}}"
                                                                   value="1" {{$value ? 'checked' : ''}}
                                                                   data-id="{{$customerReferralEarning[0]['key']}}"
                                                                   data-message="Want to change the status of {{ucfirst(translate($customerReferralEarning[0]['key']))}}"
                                                            >
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h4 class="page-title d-flex align-items-center gap-2">
                                                <span class="material-symbols-outlined">
                                                    settings
                                                </span>
                                                {{translate('Customer Loyalty Point Settings')}}
                                            </h4>
                                        </div>
                                        <div class="card-body p-30">
                                            <div class="row g-4">
                                                <div class="col-md-6 col-lg-4">
                                                    @php($value=$dataValues->where('key_name','loyalty_point_value_per_currency_unit')->first())
                                                    <div class="form-floating form-floating__icon">
                                                        <input type="number" class="form-control"
                                                               name="loyalty_point_value_per_currency_unit" step="any"
                                                               min="0" value="{{$value->live_values??''}}">
                                                        <label class="mb-1">1 {{currency_code()}} {{translate('Equivalent Point to 1 Unit')}}?</label>
                                                        <span class="material-icons">attach_money</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-4">
                                                    @php($value=$dataValues->where('key_name','loyalty_point_percentage_per_booking')->first())
                                                    <div class="form-floating form-floating__icon">
                                                        <input type="number" class="form-control"
                                                               name="loyalty_point_percentage_per_booking"
                                                               min="0" max="100" step="any"
                                                               value="{{$value->live_values??''}}">
                                                        <label class="mb-1">
                                                            <span>{{translate('Point Earn on Each Booking')}}</span>
                                                        </label>
                                                        <i class="material-symbols-outlined info-transform-fix" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{translate('On every booking this percent of amount will be added as loyalty point on customer account')}}"
                                                        >info</i>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-4">
                                                    @php($value=$dataValues->where('key_name','min_loyalty_point_to_transfer')->first())
                                                    <div class="form-floating form-floating__icon">
                                                        <input type="number" class="form-control"
                                                               name="min_loyalty_point_to_transfer" step="any"
                                                               min="0" value="{{$value->live_values??''}}">
                                                        <label class="mb-1">{{translate('Minimum Point Required To Convert')}}</label>
                                                        <span class="material-icons">loyalty</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h4 class="page-title d-flex align-items-center gap-2">
                                                <span class="material-symbols-outlined">
                                                    settings
                                                </span>
                                                {{ translate('Customer_Referrer_Settings') }} ({{ currency_code() }})
                                            </h4>
                                        </div>
                                        <div class="card-body p-30">

                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    @php($value=$dataValues->where('key_name','referral_value_per_currency_unit')->first())
                                                    <div class="form-floating form-floating__icon">
                                                        <input type="number" class="form-control"
                                                               name="referral_value_per_currency_unit" step="any"
                                                               min="0" value="{{$value->live_values??''}}">
                                                        <label class="mb-1">{{translate('Earnings To Each Referral') . ' ' . currency_code() . '?'}}</label>
                                                        <span class="material-icons">attach_money</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 justify-content-end mt-4">
                                        <button type="reset" class="btn btn-secondary">{{translate('reset')}}
                                        </button>
                                        <button type="submit" class="btn btn--primary">{{translate('save')}}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                </div>
            </div>
            @endif

            @if($webPage=='providers')
                <div class="tab-content">
                    <div class="tab-pane fade {{$webPage=='providers'?'active show':''}}"
                         id="business-info">
                        <div class="card">
                            <div class="card-body p-30">
                                <form action="{{route('admin.business-settings.set-provider-setup')}}"
                                      method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-4">
                                        @php($providerCanCancelBooking = collect([ ['key' => 'provider_can_cancel_booking','info_message' => 'If enabled, providers can cancel a booking even after it has been placed.', 'title' => 'Can Cancel Booking'] ]))
                                        @php($providerCanEditBooking = collect([ ['key' => 'provider_can_edit_booking','info_message' => 'If enabled, providers can edit a booking request after it has been placed', 'title' => 'Can Edit Booking'] ]))
                                        @php($providerSelfRegistration = collect([ ['key' => 'provider_self_registration','info_message' => 'If enabled, providers can do self-registration from the admin landing page, provider panel & app, and customer website & app.', 'title' => 'Provider Self Registration'] ]))
                                        @php($providerSelfDelete = collect([ ['key' => 'provider_self_delete','info_message' => 'If enabled, provider can delete account', 'title' => 'Provider Self Delete'] ]))
                                        @php($suspendOnExceedCashLimit = collect([ ['key' => 'suspend_on_exceed_cash_limit_provider','info_message' => 'If enabled, the provider will be automatically suspended by the system when their ‘Cash in Hand’ limit is exceeded.', 'title' => 'Suspend on Exceed Cash Limit'] ]))

                                        <div class="col-md-6 col-12 mb-30">
                                            <div class="border p-3 rounded d-flex justify-content-between">
                                                <div>
                                                            <span
                                                                class="text-capitalize">{{translate($providerCanCancelBooking[0]['title'])}}</span>
                                                    <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                       data-bs-placement="top"
                                                       title="{{translate($providerCanCancelBooking[0]['info_message'] ?? '')}}"
                                                    >info</i>
                                                </div>
                                                <label class="switcher">
                                                    @php($value = $dataValues->where('key_name', $providerCanCancelBooking[0]['key'])?->first()?->live_values ?? null)
                                                    <input class="switcher_input switch_alert"
                                                           id="{{$providerCanCancelBooking[0]['key']}}"
                                                           type="checkbox"
                                                           name="{{$providerCanCancelBooking[0]['key']}}"
                                                           value="1" {{$value ? 'checked' : ''}}
                                                           data-id="{{$providerCanCancelBooking[0]['key']}}"
                                                           data-message="Want to change the status of {{ucfirst(translate($providerCanCancelBooking[0]['key']))}}"
                                                    >
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mb-30">
                                            <div class="border p-3 rounded d-flex justify-content-between">
                                                <div>
                                                            <span
                                                                class="text-capitalize">{{translate($providerCanEditBooking[0]['title'])}}</span>
                                                    <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                       data-bs-placement="top"
                                                       title="{{translate($providerCanEditBooking[0]['info_message'] ?? '')}}"
                                                    >info</i>
                                                </div>
                                                <label class="switcher">
                                                    @php($value = $dataValues->where('key_name', $providerCanEditBooking[0]['key'])?->first()?->live_values ?? null)
                                                    <input class="switcher_input switch_alert"
                                                           id="{{$providerCanEditBooking[0]['key']}}"
                                                           type="checkbox"
                                                           name="{{$providerCanEditBooking[0]['key']}}"
                                                           value="1" {{$value ? 'checked' : ''}}
                                                           data-id="{{$providerCanEditBooking[0]['key']}}"
                                                           data-message="Want to change the status of {{ucfirst(translate($providerCanEditBooking[0]['key']))}}"
                                                    >
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mb-30">
                                            <div class="border p-3 rounded d-flex justify-content-between">
                                                <div>
                                                            <span
                                                                class="text-capitalize">{{translate($suspendOnExceedCashLimit[0]['title'])}}</span>
                                                    <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                       data-bs-placement="top"
                                                       title="{{translate($suspendOnExceedCashLimit[0]['info_message'] ?? '')}}"
                                                    >info</i>
                                                </div>
                                                <label class="switcher">
                                                    @php($value = $dataValues->where('key_name', $suspendOnExceedCashLimit[0]['key'])?->first()?->live_values ?? null)
                                                    <input class="switcher_input switch_alert"
                                                           id="{{$suspendOnExceedCashLimit[0]['key']}}"
                                                           type="checkbox"
                                                           name="{{$suspendOnExceedCashLimit[0]['key']}}"
                                                           value="1" {{$value ? 'checked' : ''}}
                                                           data-id="{{$suspendOnExceedCashLimit[0]['key']}}"
                                                           data-message="Want to change the status of {{ucfirst(translate($suspendOnExceedCashLimit[0]['key']))}}"
                                                    >
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mb-30">
                                            @php($value=$dataValues->where('key_name','max_cash_in_hand_limit_provider')->first())
                                            <div class="form-floating">
                                                <input type="number" class="form-control"
                                                       name="max_cash_in_hand_limit_provider"
                                                       min="0" step="any" value="{{$value->live_values??''}}">
                                                <label class="mb-1">{{translate('Maximum Cash in Hand Limit')}}
                                                    ({{currency_symbol()}})</label>
                                                <i class="material-symbols-outlined" data-bs-toggle="tooltip"
                                                   data-bs-placement="top"
                                                   title="{{translate('Define the maximum amount of ‘Cash in Hand’ a provider is allowed to keep. If the maximum limit is exceeded, the provider will be suspended and will not receive any service requests. ')}}"
                                                >info</i>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mb-30">
                                            <div class="border p-3 rounded d-flex justify-content-between">
                                                <div>
                                                            <span
                                                                class="text-capitalize">{{translate($providerSelfRegistration[0]['title'])}}</span>
                                                    <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                       data-bs-placement="top"
                                                       title="{{translate($providerSelfRegistration[0]['info_message'] ?? '')}}"
                                                    >info</i>
                                                </div>
                                                <label class="switcher">
                                                    @php($value = $dataValues->where('key_name', $providerSelfRegistration[0]['key'])?->first()?->live_values ?? null)
                                                    <input class="switcher_input switch_alert"
                                                           id="{{$providerSelfRegistration[0]['key']}}"
                                                           type="checkbox"
                                                           name="{{$providerSelfRegistration[0]['key']}}"
                                                           value="1" {{$value ? 'checked' : ''}}
                                                           data-id="{{$providerSelfRegistration[0]['key']}}"
                                                           data-message="Want to change the status of {{ucfirst(translate($providerSelfRegistration[0]['key']))}}"
                                                    >
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mb-30">
                                            <div class="border p-3 rounded d-flex justify-content-between">
                                                <div>
                                                            <span
                                                                class="text-capitalize">{{translate($providerSelfDelete[0]['title'])}}</span>
                                                    <i class="material-icons px-1" data-bs-toggle="tooltip"
                                                       data-bs-placement="top"
                                                       title="{{translate($providerSelfDelete[0]['info_message'] ?? '')}}"
                                                    >info</i>
                                                </div>
                                                <label class="switcher">
                                                    @php($value = $dataValues->where('key_name', $providerSelfDelete[0]['key'])?->first()?->live_values ?? null)
                                                    <input class="switcher_input switch_alert"
                                                           id="{{$providerSelfDelete[0]['key']}}"
                                                           type="checkbox"
                                                           name="{{$providerSelfDelete[0]['key']}}"
                                                           value="1" {{$value ? 'checked' : ''}}
                                                           data-id="{{$providerSelfDelete[0]['key']}}"
                                                           data-message="Want to change the status of {{ucfirst(translate($providerSelfDelete[0]['key']))}}"
                                                    >
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="d-flex gap-2 justify-content-end mt-4">
                                        <button type="reset" class="btn btn-secondary">{{translate('reset')}}
                                        </button>
                                        <button type="submit" class="btn btn--primary">{{translate('update')}}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($webPage=='otp_login_setup')
                <div class="tab-content">
                    <div class="tab-pane fade {{$webPage=='otp_login_setup'?'active show':''}}"
                         id="business-info">
                        <div class="card">
                            <div class="card-body p-30">
                                <form action="{{route('admin.business-settings.set-otp-login-information')}}"
                                      method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-4">
                                        <div class="col-md-6 col-12">
                                            <div class="form-floating">
                                                <input class="form-control remove-spin"
                                                       name="temporary_login_block_time"
                                                       placeholder="{{translate('Temporary Login Block Time')}} *"
                                                       type="number" min="0" required
                                                       value="{{$dataValues->where('key_name', 'temporary_login_block_time')->first()->live_values ?? ''}}"
                                                >
                                                <label>{{translate('Temporary Login Block Time')}}* <small
                                                        class="text-danger">({{translate('In Second')}})</small>
                                                </label>

                                                <span class="material-icons" data-bs-toggle="tooltip"
                                                      title="{{ translate('Temporary login block time refers to a security measure implemented by systems to restrict access for a specified period of time for wrong Password submission.') }}">info</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-floating">
                                                <input class="form-control remove-spin" name="maximum_login_hit"
                                                       placeholder="{{translate('Maximum Login Hit')}} *"
                                                       type="number" min="0" required
                                                       value="{{$dataValues->where('key_name', 'maximum_login_hit')->first()->live_values ?? ''}}"
                                                >
                                                <label>{{translate('Maximum Login Hit')}}* </label>

                                                <span class="material-icons" data-bs-toggle="tooltip"
                                                      title="{{ translate('The maximum login hit is a measure of how many times a user can submit password within a time.') }}">info</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-floating">
                                                <input class="form-control remove-spin"
                                                       name="temporary_otp_block_time"
                                                       placeholder="{{translate('Temporary OTP Block Time')}} *"
                                                       type="number" min="0" required
                                                       value="{{$dataValues->where('key_name', 'temporary_otp_block_time')->first()->live_values ?? ''}}"
                                                >
                                                <label>{{translate('Temporary OTP Block Time')}}* <small
                                                        class="text-danger">({{translate('In Second')}})</small></label>

                                                <span class="material-icons" data-bs-toggle="tooltip"
                                                      title="{{ translate('Temporary OTP block time refers to a security measure implemented by systems to restrict access to OTP service for a specified period of time for wrong OTP submission.') }}">info</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-floating">
                                                <input class="form-control remove-spin" name="maximum_otp_hit"
                                                       placeholder="{{translate('Maximum OTP Hit')}} *"
                                                       type="number" min="0" required
                                                       value="{{$dataValues->where('key_name', 'maximum_otp_hit')->first()->live_values ?? ''}}">
                                                <label>{{translate('Maximum OTP Hit')}} *</label>

                                                <span class="material-icons" data-bs-toggle="tooltip"
                                                      title="{{ translate('The maximum OTP hit is a measure of how many times a specific one-time password has been generated and used within a time.') }}">info</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-floating">
                                                <input class="form-control remove-spin" name="otp_resend_time"
                                                       placeholder="{{translate('OTP Resend Time')}} *"
                                                       type="number" min="0" required
                                                       value="{{$dataValues->where('key_name', 'otp_resend_time')->first()->live_values ?? ''}}"
                                                >

                                                <label>{{translate('OTP Resend Time')}}* <small
                                                        class="text-danger">({{translate('In Second')}})</small></label>

                                                <span class="material-icons" data-bs-toggle="tooltip"
                                                      title="{{ translate('If the user fails to get the OTP within a certain time, user can request a resend.') }}">info</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 justify-content-end mt-4">
                                        <button type="reset" class="btn btn-secondary">{{translate('reset')}}
                                        </button>
                                        <button type="submit" class="btn btn--primary">{{translate('update')}}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    </div>
    </div>
@endsection

@push('script')
    <script src="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.js"></script>
    <script src="{{asset('public/assets/admin-module')}}/plugins/dataTables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/admin-module')}}/plugins/dataTables/dataTables.select.min.js"></script>

    <script>
        "use strict";

        $('.switch_alert').on('click', function () {
            let id = $(this).data('id');
            let status = $(this).is(':checked') === true ? 1 : 0;
            let message = $(this).data('message');
            switch_alert(id, status, message)
        });

        @if($webPage == 'business_setup')
        let flag = "{{business_config('country_code', 'business_information')->live_values ?? 'bd'}}"
        const business_phone = window.intlTelInput(document.querySelector("#business_phone"), {
            utilsScript: "{{asset('public/assets/admin-module/js/utils.js')}}",
            autoHideDialCode: false,
            autoPlaceholder: "ON",
            dropdownContainer: document.body,
            formatOnDisplay: true,
            hiddenInput: "business_phone",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
            initialCountry: flag,
        });
        @endif

        $('#business-info-update-form').on('submit', function (event) {
            event.preventDefault();

            let form = $('#business-info-update-form')[0];
            let formData = new FormData(form);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.set-business-information')}}",
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (response) {
                    toastr.success('{{translate('successfully_updated')}}');
                },
                error: function (jqXHR, exception) {
                    if (jqXHR.responseJSON && jqXHR.responseJSON.errors && jqXHR.responseJSON.errors.length > 0) {
                        var errorMessages = jqXHR.responseJSON.errors.map(function (error) {
                            return error.message;
                        });

                        errorMessages.forEach(function (errorMessage) {
                            toastr.error(errorMessage);
                        });
                    } else {
                        toastr.error("An error occurred.");
                    }
                }
            });
        });

        $('#bidding-system-update-form').on('submit', function (event) {
            event.preventDefault();

            let form = $('#bidding-system-update-form')[0];
            let formData = new FormData(form);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.set-bidding-system')}}",
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (response) {
                    toastr.success('{{translate('successfully_updated')}}');
                },
                error: function (jqXHR, exception) {
                    if (jqXHR.responseJSON && jqXHR.responseJSON.errors && jqXHR.responseJSON.errors.length > 0) {
                        var errorMessages = jqXHR.responseJSON.errors.map(function (error) {
                            return error.message;
                        });

                        errorMessages.forEach(function (errorMessage) {
                            toastr.error(errorMessage);
                        });
                    } else {
                        toastr.error("An error occurred.");
                    }
                }
            });
        });

        $('#booking-system-update-form').on('submit', function (event) {
            event.preventDefault();

            let form = $('#booking-system-update-form')[0];
            let formData = new FormData(form);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.set-booking-setup')}}",
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (response) {
                    toastr.success('{{translate('successfully_updated')}}');
                },
                error: function (jqXHR, exception) {
                    if (jqXHR.responseJSON && jqXHR.responseJSON.errors && jqXHR.responseJSON.errors.length > 0) {
                        var errorMessages = jqXHR.responseJSON.errors.map(function (error) {
                            return error.message;
                        });

                        errorMessages.forEach(function (errorMessage) {
                            toastr.error(errorMessage);
                        });
                    } else {
                        toastr.error("An error occurred.");
                    }
                }
            });
        });

        function update_action_status(key_name, value, settings_type, will_reload = false) {
            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: '{{translate('want_to_update_status')}}',
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonColor: 'var(--c2)',
                confirmButtonColor: 'var(--c1)',
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.business-settings.update-action-status')}}",
                        data: {
                            key: key_name,
                            value: value,
                            settings_type: settings_type,
                        },
                        type: 'put',
                        success: function (response) {
                            toastr.success('{{translate('successfully_updated')}}');

                            if (will_reload) {
                                setTimeout(() => {
                                    document.location.reload();
                                }, 3000);
                            }
                        },
                        error: function () {

                        }
                    });
                }
            })
        }

        $(document).ready(function () {
            $('.js-select').select2();
        });

        $(window).on('load', function () {
            $("#admin-select__discount, #provider-select__discount").on('click', function (e) {
                $("#bearer-section__discount").addClass('d-none');
            })

            $("#both-select__discount").on('click', function (e) {
                $("#bearer-section__discount").removeClass('d-none');
            })

            $("#admin_percentage__discount").keyup(function (e) {
                if (this.value >= 0 && this.value <= 100) {
                    $("#provider_percentage__discount").val((100 - this.value));
                }
            });

            $("#provider_percentage__discount").keyup(function (e) {
                if (this.value >= 0 && this.value <= 100) {
                    $("#admin_percentage__discount").val((100 - this.value));
                }
            });

            $("#admin-select__campaign, #provider-select__campaign").on('click', function (e) {
                $("#bearer-section__campaign").addClass('d-none');
            })

            $("#both-select__campaign").on('click', function (e) {
                $("#bearer-section__campaign").removeClass('d-none');
            })

            $("#admin_percentage__campaign").keyup(function (e) {
                if (this.value >= 0 && this.value <= 100) {
                    $("#provider_percentage__campaign").val((100 - this.value));
                }
            });

            $("#provider_percentage__campaign").keyup(function (e) {
                if (this.value >= 0 && this.value <= 100) {
                    $("#admin_percentage__campaign").val((100 - this.value));
                }
            });

            $("#admin-select__coupon, #provider-select__coupon").on('click', function (e) {
                $("#bearer-section__coupon").addClass('d-none');
            })

            $("#both-select__coupon").on('click', function (e) {
                $("#bearer-section__coupon").removeClass('d-none');
            })

            $("#admin_percentage__coupon").keyup(function (e) {
                if (this.value >= 0 && this.value <= 100) {
                    $("#provider_percentage__coupon").val((100 - this.value));
                }
            });

            $("#provider_percentage__coupon").keyup(function (e) {
                if (this.value >= 0 && this.value <= 100) {
                    $("#admin_percentage__coupon").val((100 - this.value));
                }
            });
        })

        function switch_alert(id, status, message) {
            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: message,
                type: 'warning',
                showDenyButton: true,
                showCancelButton: true,
                denyButtonColor: 'var(--c2)',
                confirmButtonColor: 'var(--c1)',
                confirmButtonText: 'Save',
                denyButtonText: `Don't save`,
            }).then((result) => {
                if (result.value) {
                } else {
                    if (status === 1) $(`#${id}`).prop('checked', false);
                    if (status === 0) $(`#${id}`).prop('checked', true);
                    Swal.fire('{{translate('Changes are not saved')}}', '', 'info')
                }
            })
        }

        $(document).ready(function ($) {
            $("#phone_verification").on('change', function () {
                const phoneVerification = $(this).is(':checked') === true ? 1 : 0;

                if (phoneVerification === 1) {
                    $("#email_verification").prop('checked', false);
                }
            });

            $("#email_verification").on('change', function () {
                const emailVerification = $(this).is(':checked') === true ? 1 : 0;

                if (emailVerification === 1) {
                    $("#phone_verification").prop('checked', false);
                }
            });
        });

    </script>
@endpush
