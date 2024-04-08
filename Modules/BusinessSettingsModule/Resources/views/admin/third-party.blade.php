@extends('adminmodule::layouts.master')

@section('title',translate('3rd_party'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/swiper/swiper-bundle.min.css"/>
@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('3rd_party')}}</h2>
                    </div>

                    <div class="mb-3">
                        <ul class="nav nav--tabs nav--tabs__style2">
                            @include('businesssettingsmodule::admin.partials.third-party-partial')
                        </ul>
                    </div>

                    @if($webPage == 'google_map')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$webPage == 'google_map' ? 'show active' : ''}}"
                                 id="google-map">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="page-title">{{translate('google_map_api_key_setup')}}</h4>
                                    </div>
                                    <div class="card-body p-30">
                                        <div class="alert alert-danger mb-30">
                                            <p><i class="material-icons">info</i>
                                                {{translate('Client Key Should Have Enable Map Javascript Api And You Can Restrict It With Http Refere. Server Key Should Have Enable Place Api Key And You Can Restrict It With Ip. You Can Use Same Api For Both Field Without Any Restrictions.')}}
                                            </p>
                                        </div>
                                        <form action="{{route('admin.configuration.set-third-party-config')}}"
                                              method="POST"
                                              id="google-map-update-form" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="discount-type">
                                                <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input name="party_name" value="google_map"
                                                                       class="hide-div">
                                                                <input type="text" class="form-control"
                                                                       name="map_api_key_server"
                                                                       placeholder="{{translate('map_api_key_server')}} *"
                                                                       required=""
                                                                       value="{{bs_data($dataValues,'google_map')['map_api_key_server']??''}}">
                                                                <label>{{translate('map_api_key_server')}} *</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="map_api_key_client"
                                                                       placeholder="{{translate('map_api_key_client')}} *"
                                                                       required=""
                                                                       value="{{bs_data($dataValues,'google_map')['map_api_key_client']??''}}">
                                                                <label>{{translate('map_api_key_client')}} *</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn--primary demo_check">
                                                    {{translate('update')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($webPage == 'push_notification')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$webPage == 'push_notification' ? 'show active' : ''}}"
                                 id="firebase-push-notification">
                                <div class="card">
                                    <div class="card-header">

                                        <div class="d-flex justify-content-between mb-5">
                                            <div class="page-header align-items-center">
                                                <h4>{{translate('Firebase_Push_Notification_Setup')}}</h4>
                                            </div>
                                            <div class="d-flex align-items-center gap-3 font-weight-bolder">
                                                {{ translate('Read Instructions') }}
                                                <div class="ripple-animation" data-bs-toggle="modal"
                                                     data-bs-target="#carouselModal" type="button">
                                                    <img src="{{asset('/public/assets/admin-module/img/info.svg')}}"
                                                         class="svg"
                                                         alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-30">
                                        <form action="{{route('admin.configuration.set-third-party-config')}}"
                                              method="POST"
                                              id="firebase-form" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="discount-type">
                                                <div class="row">
                                                    <div class="col-md-12 col-12">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input name="party_name" value="push_notification"
                                                                       class="hide-div">
                                                                <input type="text" class="form-control"
                                                                       name="server_key"
                                                                       placeholder="{{translate('server_key')}} *"
                                                                       required=""
                                                                       value="{{bs_data($dataValues,'push_notification')['server_key']??''}}">
                                                                <label>{{translate('server_key')}} *</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn--primary demo_check">
                                                    {{translate('update')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="modal fade firebase-modal" id="carouselModal" tabindex="-1"
                             aria-labelledby="carouselModal"
                             aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-1">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body px-4 px-sm-5 pt-0">
                                        <div dir="ltr" class="swiper modalSwiper pb-4">
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide">
                                                    <div class="d-flex flex-column align-items-center gap-2 fs-12">
                                                        <img width="80" class="mb-3"
                                                             src="{{asset('public/assets/admin-module/img/media/firebase-console.png')}}"
                                                             alt="">
                                                        <h5 class="modal-title text-center mb-3">Go to Firebase
                                                            Console</h5>

                                                        <ul class="d-flex flex-column gap-2 px-3">
                                                            <li>{{translate('Open your web browser and go to the Firebase Console')}}
                                                                (
                                                                <a
                                                                    href="https://console.firebase.google.com">https://console.firebase.google.com/</a>
                                                                ).
                                                            </li>
                                                            <li>{{translate('Select the project for which you want to configure FCM
                                                                from the Firebase
                                                                Console dashboard')}}
                                                            </li>
                                                            <li>{{translate('If you don’t have any project before. Create one with
                                                                the website name')}}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="d-flex flex-column align-items-center gap-2 fs-12">
                                                        <img width="80" class="mb-3"
                                                             src="{{asset('public/assets/admin-module/img/media/project-settings.png')}}"
                                                             alt="">
                                                        <h5 class="modal-title text-center mb-3">{{translate('Navigate to Project
                                                            Settings')}}</h5>

                                                        <ul class="d-flex flex-column gap-2 px-3">
                                                            <li>{{translate('In the left-hand menu, click on the')}}
                                                                <strong>"Settings"</strong> gear icon,
                                                                {{translate('there you will vae a dropdown. and then select')}}
                                                                <strong>"Project
                                                                    settings"</strong> {{translate('from the dropdown.')}}
                                                            </li>
                                                            <li>{{translate('In the Project settings page, click on the "Cloud
                                                                Messaging" tab from the
                                                                top menu.')}}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="d-flex flex-column align-items-center gap-2 fs-12">
                                                        <img width="80" class="mb-3"
                                                             src="{{asset('public/assets/admin-module/img/media/cloud-message.png')}}"
                                                             alt="">
                                                        <h5 class="modal-title text-center mb-3">{{translate('Cloud Messaging
                                                            API')}}</h5>

                                                        <ul class="d-flex flex-column gap-2 px-3">
                                                            <li>{{translate('From Cloud Messaging Page there will be a section called
                                                                Cloud Messaging
                                                                API.')}}
                                                            </li>
                                                            <li>{{translate('Click on the menu icon and enable the API')}}</li>
                                                            <li>{{translate('Refresh the Cloud Messaging Page - You will have your
                                                                server key. Just copy
                                                                the code and paste here')}}
                                                            </li>
                                                        </ul>

                                                        <div class="d-flex justify-content-center mt-2 w-100">
                                                            <button type="button" class="btn btn-primary w-100 max-w320"
                                                                    data-bs-dismiss="modal">Got It
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="swiper-pagination mb-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($webPage == 'recaptcha')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$webPage == 'recaptcha' ? 'show active' : ''}}" id="recaptcha">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="page-title">{{translate('recaptcha_setup')}}</h4>
                                    </div>
                                    <div class="card-body p-30">
                                        <form action="{{route('admin.configuration.set-third-party-config')}}"
                                              method="POST"
                                              id="recaptcha-form" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="discount-type">
                                                <div class="d-flex align-items-center gap-4 gap-xl-5 mb-30">
                                                    <div class="custom-radio">
                                                        <input type="radio" id="active" name="status"
                                                               value="1" {{$dataValues->where('key_name','recaptcha')->first()->live_values['status']?'checked':''}}>
                                                        <label for="active">{{translate('active')}}</label>
                                                    </div>
                                                    <div class="custom-radio">
                                                        <input type="radio" id="inactive" name="status"
                                                               value="0" {{$dataValues->where('key_name','recaptcha')->first()->live_values['status']?'':'checked'}}>
                                                        <label for="inactive">{{translate('inactive')}}</label>
                                                    </div>
                                                </div>

                                                <br>

                                                <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input name="party_name" value="recaptcha"
                                                                       class="hide-div">
                                                                <input type="text" class="form-control"
                                                                       name="site_key"
                                                                       placeholder="{{translate('site_key')}} *"
                                                                       required=""
                                                                       value="{{bs_data($dataValues,'recaptcha')['site_key']??''}}">
                                                                <label>{{translate('site_key')}} *</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="secret_key"
                                                                       placeholder="{{translate('secret_key')}} *"
                                                                       required=""
                                                                       value="{{bs_data($dataValues,'recaptcha')['secret_key']??''}}">
                                                                <label>{{translate('secret_key')}} *</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn--primary demo_check">
                                                    {{translate('update')}}
                                                </button>
                                            </div>
                                        </form>

                                        <div class="mt-3">
                                            <h4 class="mb-3">{{translate('Instructions')}}</h4>
                                            <ol>
                                                <li>{{translate('To get site key and secret keyGo to the Credentials page')}}
                                                    (<a href="https://developers.google.com/recaptcha/docs/v3"
                                                        class="c1">{{translate('Click
                                                        Here')}}</a>)
                                                </li>
                                                <li>{{translate('Add a Label (Ex: abc company)')}}</li>
                                                <li>{{translate('Select reCAPTCHA v2 as ReCAPTCHA Type')}}</li>
                                                <li>{{translate('Select Sub type: I am not a robot Checkbox')}} </li>
                                                <li>{{translate('Add Domain')}} (For ex: demo.6amtech.com)</li>
                                                <li>{{translate('Check in “Accept the reCAPTCHA Terms of Service”')}} </li>
                                                <li>{{translate('Press Submit')}}</li>
                                                <li>{{translate('Copy Site Key and Secret Key, Paste in the input filed below and
                                                    Save.')}}
                                                </li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @php($appleLogin = (business_config('apple_login', 'third_party'))->live_values)

                    @if($webPage == 'apple_login')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$webPage == 'apple_login' ? 'show active' : ''}}"
                                 id="apple_login">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="page-title">
                                            <img src="{{asset('public/assets/admin-module/img/media/apple.png')}}"
                                                 alt="">
                                            {{translate('Apple_login')}}
                                        </h4>
                                    </div>
                                    <div class="card-body p-30">
                                        <div class="row">
                                            <div class="col-12 col-md-12 mb-30">
                                                <form action="{{route('admin.configuration.set-third-party-config')}}"
                                                      method="POST"
                                                      id="apple-login-form" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="discount-type">
                                                        <div class="d-flex align-items-center gap-4 gap-xl-5 mb-30">
                                                            <input name="party_name" value="apple_login"
                                                                   class="hide-div">
                                                            <div class="custom-radio">
                                                                <input type="radio" id="apple-login-active"
                                                                       name="status"
                                                                       value="1" {{$appleLogin['status']?'checked':''}}>
                                                                <label
                                                                    for="apple-login-active">{{translate('active')}}</label>
                                                            </div>
                                                            <div class="custom-radio">
                                                                <input type="radio" id="apple-login-inactive"
                                                                       name="status"
                                                                       value="0" {{$appleLogin['status']?'':'checked'}}>
                                                                <label
                                                                    for="apple-login-inactive">{{translate('inactive')}}</label>
                                                            </div>
                                                        </div>

                                                        <div class="form-floating mb-30 mt-30">
                                                            <input type="text" class="form-control" name="client_id"
                                                                   value="{{env('APP_ENV')=='demo'?'':$appleLogin['client_id']}}">
                                                            <label>{{translate('client_id')}} *</label>
                                                        </div>

                                                        <div class="form-floating mb-30 mt-30">
                                                            <input type="text" class="form-control" name="team_id"
                                                                   value="{{env('APP_ENV')=='demo'?'':$appleLogin['team_id']}}">
                                                            <label>{{translate('team_id')}} *</label>
                                                        </div>

                                                        <div class="form-floating mb-30 mt-30">
                                                            <input type="text" class="form-control" name="key_id"
                                                                   value="{{env('APP_ENV')=='demo'?'':$appleLogin['key_id']}}">
                                                            <label>{{translate('key_id')}} *</label>
                                                        </div>

                                                        <div class="form-floating mb-30 mt-30">
                                                            <input type="file" accept=".p8" class="form-control"
                                                                   name="service_file"
                                                                   value="{{ 'storage/app/public/apple-login/'.$appleLogin['service_file'] }}">
                                                            <label>{{translate('service_file')}} {{ $appleLogin['service_file']? translate('(Already Exists)'):'*' }}</label>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button type="submit" class="btn btn--primary demo_check">
                                                            {{translate('update')}}
                                                        </button>
                                                    </div>
                                                </form>
                                                <div class="mt-3">
                                                    <h4 class="mb-3">{{translate('Instructions')}}</h4>
                                                    <ol>
                                                        <li>{{translate('Go to Apple Developer page')}} (<a
                                                                href="https://developer.apple.com/account/resources/identifiers/list"
                                                                target="_blank">{{translate('click_here')}}</a>)
                                                        </li>
                                                        <li>{{translate('Here in top left corner you can see the')}}
                                                            <b>{{ translate('Team ID') }}</b> {{ translate('[Apple_Deveveloper_Account_Name - Team_ID]')}}
                                                        </li>
                                                        <li>{{translate('Click Plus icon -> select App IDs -> click on Continue')}}</li>
                                                        <li>{{translate('Put a description and also identifier (identifier that used for app) and this is the')}}
                                                            <b>{{ translate('Client ID') }}</b></li>
                                                        <li>{{translate('Click Continue and Download the file in device named AuthKey_ID.p8 (Store it safely and it is used for push notification)')}} </li>
                                                        <li>{{translate('Again click Plus icon -> select Service IDs -> click on Continue')}} </li>
                                                        <li>{{translate('Push a description and also identifier and Continue')}} </li>
                                                        <li>{{translate('Download the file in device named')}}
                                                            <b>{{ translate('AuthKey_KeyID.p8') }}</b> {{translate('[This is the Service Key ID file and also after AuthKey_ that is the Key ID]')}}
                                                        </li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($webPage == 'email_config')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$webPage == 'email_config' ? 'show active' : ''}}"
                                 id="email_config">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <div
                                            class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-40">
                                            <ul class="nav nav--tabs nav--tabs__style2" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active"
                                                       href="{{url('admin/configuration/get-third-party-config')}}?web_page=email_config">{{translate('Email Config')}}</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link {{$type=='offline_payment'?'active':''}}"
                                                       href="{{url('admin/configuration/get-third-party-config')}}?web_page=test_mail">{{translate('Send Test Mail')}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-12 col-md-12 mb-30">
                                            <form action="{{route('admin.configuration.set-email-config')}}"
                                                  method="POST"
                                                  id="config-form" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="discount-type">
                                                    <div class="row">
                                                        <div class="col-md-6 col-12 mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="mailer_name"
                                                                       placeholder="{{translate('mailer_name')}} *"
                                                                       value="{{bs_data($dataValues,'email_config')['mailer_name']??''}}">
                                                                <label>{{translate('mailer_name')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12 mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="host"
                                                                       placeholder="{{translate('host')}} *"
                                                                       value="{{bs_data($dataValues,'email_config')['host']??''}}">
                                                                <label>{{translate('host')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12 mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="driver"
                                                                       placeholder="{{translate('driver')}} *"
                                                                       value="{{bs_data($dataValues,'email_config')['driver']??''}}">
                                                                <label>{{translate('driver')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12 mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="port"
                                                                       placeholder="{{translate('port')}} *"
                                                                       value="{{bs_data($dataValues,'email_config')['port']??''}}">
                                                                <label>{{translate('port')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12 mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="user_name"
                                                                       placeholder="{{translate('user_name')}} *"
                                                                       value="{{bs_data($dataValues,'email_config')['user_name']??''}}">
                                                                <label>{{translate('user_name')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12 mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="email_id"
                                                                       placeholder="{{translate('email_id')}} *"
                                                                       value="{{bs_data($dataValues,'email_config')['email_id']??''}}">
                                                                <label>{{translate('email_id')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12 mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="encryption"
                                                                       placeholder="{{translate('encryption')}} *"
                                                                       value="{{bs_data($dataValues,'email_config')['encryption']??''}}">
                                                                <label>{{translate('encryption')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12 mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="password"
                                                                       placeholder="{{translate('password')}} *"
                                                                       value="{{bs_data($dataValues,'email_config')['password']??''}}">
                                                                <label>{{translate('password')}} *</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button type="submit" class="btn btn--primary">
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

                @if($webPage == 'test_mail')
                    <div class="tab-content">
                        <div class="tab-pane fade {{$webPage == 'test_mail' ? 'show active' : ''}}"
                             id="email_config">
                            <div class="card">
                                <div class="card-body p-30">
                                    <div class="row">
                                        <div
                                            class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-30">
                                            <ul class="nav nav--tabs nav--tabs__style2" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link {{$webPage=='email_config' ? 'active':''}}"
                                                       href="{{url('admin/configuration/get-third-party-config')}}?web_page=email_config">{{translate('Email Config')}}</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link {{$webPage == 'test_mail' ?'active':''}}"
                                                       href="{{url('admin/configuration/get-third-party-config')}}?web_page=test_mail">{{translate('Send Test Mail')}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-12 col-md-12 mb-30">
                                            <form action="javascript:" method="post">
                                                @csrf
                                                <input type="hidden" name="status"
                                                       value="{{(isset($data)&& isset($data['status'])) ? $data['status']:0 }}">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="tab-content">
                                                            <div class="tab-pane fade show active" id="test-mail">
                                                                <div class="row">
                                                                    <div class="col-lg-8">
                                                                        <form action="javascript:">
                                                                            <div class="row gx-3 gy-1">
                                                                                <div class="col-md-8 col-sm-7">
                                                                                    <div class="form-floating">
                                                                                        <input type="email"
                                                                                               class="form-control"
                                                                                               id="test-email"
                                                                                               name="email"
                                                                                               placeholder="{{translate('ex: abc@email.com')}}"
                                                                                               required=""
                                                                                               value="{{old('email')}}">
                                                                                        <label>{{translate('email')}}
                                                                                            *</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4 col-sm-5">
                                                                                    <button type="button" id="send-mail"
                                                                                            class="btn btn--primary">
                                                                                        <span class='material-icons'>send</span>
                                                                                        {{ translate('send_mail') }}
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($webPage == 'sms_config')
                    <div class="tab-content">
                        <div class="tab-pane fade {{$webPage == 'sms_config' ? 'show active' : ''}}"
                             id="sms_config">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="page-title">
                                        {{translate('Sms Setup')}}
                                    </h4>
                                </div>
                                <div class="card-body p-30">
                                    <div class="row">
                                        <div class="col-12 col-md-12 mb-30">
                                            @if($publishedStatus == 1)
                                                <div class="col-md-12 mb-3">
                                                    <div class="card">
                                                        <div class="card-body d-flex justify-content-around">
                                                            <h4 class="payment-module-warning">
                                                                <i class="tio-info-outined"></i>
                                                                {{translate('Your current sms settings are disabled, because you
                                                                have enabled
                                                                sms gateway addon, To visit your currently active
                                                                sms gateway settings please follow
                                                                the link.')}}</h4>

                                                            <a href="{{!empty($paymentUrl) ? $paymentUrl : ''}}"
                                                               class="btn btn-outline-primary">{{translate('settings')}}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif


                                            <div class="row">
                                                @php($isPublished = $publishedStatus == 1 ? 'disabled' : '')
                                                @foreach($dataValues as $keyValue => $gateway)
                                                    <div class="col-12 col-md-6 mb-30">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h4 class="page-title">{{translate($gateway->key_name)}}</h4>
                                                            </div>
                                                            <div class="card-body p-30">
                                                                <form
                                                                    action="{{route('admin.configuration.sms-set')}}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="discount-type">
                                                                        <div
                                                                            class="d-flex align-items-center gap-4 gap-xl-5 mb-30">
                                                                            <div class="custom-radio">
                                                                                <input type="radio"
                                                                                       id="{{$gateway->key_name}}-active"
                                                                                       name="status"
                                                                                       value="1" {{$dataValues->where('key_name',$gateway->key_name)->first()->live_values['status']?'checked':''}} {{$isPublished}}>
                                                                                <label
                                                                                    for="{{$gateway->key_name}}-active">{{translate('active')}}</label>
                                                                            </div>
                                                                            <div class="custom-radio">
                                                                                <input type="radio"
                                                                                       id="{{$gateway->key_name}}-inactive"
                                                                                       name="status"
                                                                                       value="0" {{$dataValues->where('key_name',$gateway->key_name)->first()->live_values['status']?'':'checked'}} {{$isPublished}}>
                                                                                <label
                                                                                    for="{{$gateway->key_name}}-inactive">{{translate('inactive')}}</label>
                                                                            </div>
                                                                        </div>

                                                                        <input name="gateway"
                                                                               value="{{$gateway->key_name}}"
                                                                               class="hide-div">
                                                                        <input name="mode" value="live"
                                                                               class="hide-div">

                                                                        @php($skip=['gateway','mode','status'])
                                                                        @foreach($dataValues->where('key_name',$gateway->key_name)->first()->live_values as $key=>$value)
                                                                            @if(!in_array($key,$skip))
                                                                                <div
                                                                                    class="form-floating mb-30 mt-30">
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="{{$key}}"
                                                                                           placeholder="{{translate($key)}} *"
                                                                                           value="{{env('APP_ENV')=='demo'?'':$value}}" {{$isPublished}}>
                                                                                    <label>{{translate($key)}}
                                                                                        *</label>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                    <div class="d-flex justify-content-end">
                                                                        <button type="submit"
                                                                                class="btn btn--primary demo_check" {{$isPublished}}>
                                                                            {{translate('update')}}
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($webPage == 'payment_config' && $type == 'digital_payment')
                    <div class="tab-content">
                        <div
                            class="tab-pane fade {{$webPage == 'payment_config' && $type == 'digital_payment' ? 'show active' : ''}}"
                            id="digital_payment">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="page-title">
                                        {{translate('Payment Gateway Configuration')}}
                                    </h4>
                                </div>
                                <div class="card-body p-30">
                                    <div class="row">
                                        <div class="col-12 col-md-12 mb-30">
                                            <div
                                                class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3">
                                                <ul class="nav nav--tabs nav--tabs__style2" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link {{$type=='digital_payment'?'active':''}}"
                                                           href="{{url('admin/configuration/get-third-party-config')}}?type=digital_payment&web_page=payment_config">{{translate('Digital Payment Gateways')}}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link {{$type=='offline_payment'?'active':''}}"
                                                           href="{{url('admin/configuration/offline-payment/list')}}?type=offline_payment&web_page=payment_config">{{translate('Offline Payment')}}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            @if($publishedStatus == 1)
                                                <div class="col-12 mb-3">
                                                    <div class="card">
                                                        <div class="card-body d-flex justify-content-around">
                                                            <h4 class="text-danger pt-2">
                                                                <i class="tio-info-outined"></i>
                                                                {{translate('Your current payment settings are disabled, because
                                                                you have enabled
                                                                payment gateway addon, To visit your currently
                                                                active payment gateway settings please follow
                                                                the link')}}.</h4>

                                                            <a href="{{!empty($paymentUrl) ? $paymentUrl : ''}}"
                                                               class="btn btn-outline-primary">{{translate('settings')}}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="row">
                                                @php($isPublished = $publishedStatus == 1 ? 'disabled' : '')
                                                @foreach($dataValues as $gateway)
                                                    <div class="col-12 col-md-6 mb-30">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h4 class="page-title">{{translate($gateway->key_name)}}</h4>
                                                            </div>
                                                            <div class="card-body p-30">
                                                                <form
                                                                    action="{{route('admin.configuration.payment-set')}}"
                                                                    method="POST"
                                                                    id="{{$gateway->key_name}}-form"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    @php($additional_data = $gateway['additional_data'] != null ? json_decode($gateway['additional_data']) : [])
                                                                    <div class="discount-type">
                                                                        <div
                                                                            class="d-flex align-items-center gap-4 gap-xl-5 mb-30">
                                                                            <div class="custom-radio">
                                                                                <input type="radio"
                                                                                       id="{{$gateway->key_name}}-active"
                                                                                       name="status"
                                                                                       value="1" {{$dataValues->where('key_name',$gateway->key_name)->first()->live_values['status']?'checked':''}} {{$isPublished}}>
                                                                                <label
                                                                                    for="{{$gateway->key_name}}-active">{{translate('active')}}</label>
                                                                            </div>
                                                                            <div class="custom-radio">
                                                                                <input type="radio"
                                                                                       id="{{$gateway->key_name}}-inactive"
                                                                                       name="status"
                                                                                       value="0" {{$dataValues->where('key_name',$gateway->key_name)->first()->live_values['status']?'':'checked'}} {{$isPublished}}>
                                                                                <label
                                                                                    for="{{$gateway->key_name}}-inactive">{{translate('inactive')}}</label>
                                                                            </div>
                                                                        </div>

                                                                        <div
                                                                            class="payment--gateway-img justify-content-center d-flex align-items-center">
                                                                            <img class="payment-image-preview"
                                                                                 id="{{$gateway->key_name}}-image-preview"
                                                                                 src="{{ onErrorImage(
                                                                                        $additional_data != null ? $additional_data->gateway_image : '',
                                                                                        asset('storage/app/public/payment_modules/gateway_image').'/' . ($additional_data != null ? $additional_data->gateway_image : ''),
                                                                                        asset('public/assets/admin-module/img/placeholder.png') ,
                                                                                        'payment_modules/gateway_image/'
                                                                                     ) }}"
                                                                                 alt="{{ translate('image') }}">
                                                                        </div>

                                                                        <input name="gateway"
                                                                               value="{{$gateway->key_name}}"
                                                                               class="hide-div">

                                                                        @php($mode=$dataValues->where('key_name',$gateway->key_name)->first()->live_values['mode'])
                                                                        <div class="form-floating mb-30 mt-30">
                                                                            <select
                                                                                class="js-select theme-input-style w-100"
                                                                                name="mode" {{$isPublished}}>
                                                                                <option
                                                                                    value="live" {{$mode=='live'?'selected':''}}>{{translate('live')}}</option>
                                                                                <option
                                                                                    value="test" {{$mode=='test'?'selected':''}}>{{translate('test')}}</option>
                                                                            </select>
                                                                        </div>

                                                                        @php($skip=['gateway','mode','status'])
                                                                        @foreach($dataValues->where('key_name',$gateway->key_name)->first()->live_values as $key=>$value)
                                                                            @if(!in_array($key,$skip))
                                                                                <div
                                                                                    class="form-floating mb-30 mt-30">
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="{{$key}}"
                                                                                           placeholder="{{translate($key)}} *"
                                                                                           value="{{env('APP_ENV')=='demo'?'':$value}}" {{$isPublished}}>
                                                                                    <label>{{translate($key)}}
                                                                                        *</label>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach

                                                                        @if($gateway['key_name'] == 'paystack')
                                                                            <div class="form-floating mb-30 mt-30">
                                                                                <input type="text"
                                                                                       class="form-control"
                                                                                       placeholder="{{translate('Callback Url')}} *"
                                                                                       readonly
                                                                                       value="{{env('APP_ENV')=='demo'?'': route('paystack.callback')}}" {{$isPublished}}>
                                                                                <label>{{translate('Callback Url')}}
                                                                                    *</label>
                                                                            </div>
                                                                        @endif

                                                                        <div class="form-floating gateway-title">
                                                                            <input type="text" class="form-control"
                                                                                   id="{{$gateway->key_name}}-title"
                                                                                   name="gateway_title"
                                                                                   placeholder="{{translate('payment_gateway_title')}}"
                                                                                   value="{{$additional_data != null ? $additional_data->gateway_title : ''}}" {{$isPublished}}>
                                                                            <label
                                                                                for="{{$gateway->key_name}}-title"
                                                                                class="form-label">{{translate('payment_gateway_title')}}</label>
                                                                        </div>

                                                                        <div class="form-floating mb-3">
                                                                            <input type="file" class="form-control"
                                                                                   name="gateway_image"
                                                                                   accept=".jpg, .png, .jpeg|image/*"
                                                                                   id="{{$gateway->key_name}}-image">
                                                                        </div>

                                                                    </div>
                                                                    <div class="d-flex justify-content-end">
                                                                        <button type="submit"
                                                                                class="btn btn--primary demo_check" {{$isPublished}}>
                                                                            {{translate('update')}}
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($webPage == 'payment_config' && $type == 'offline_payment')
                    <div class="tab-content">
                        <div
                            class="tab-pane fade {{$webPage == 'payment_config' && $type == 'offline_payment' ? 'show active' : ''}}"
                            id="offline_payment">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="page-title">
                                        {{translate('Payment Config')}}
                                    </h4>
                                </div>
                                <div class="card-body p-30">
                                    <div class="row">
                                        <div class="col-12 col-md-12 mb-30">
                                            <div
                                                class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3">
                                                <ul class="nav nav--tabs nav--tabs__style2" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link {{$type=='digital_payment'?'active':''}}"
                                                           href="{{url()->current()}}?type=digital_payment&web_page=payment_config">{{translate('Digital Payment Gateways')}}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link {{$type=='offline_payment'?'active':''}}"
                                                           href="{{url()->current()}}?type=offline_payment&web_page=payment_config">{{translate('Offline Payment')}}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            @if($publishedStatus == 1)
                                                <div class="col-12 mb-3">
                                                    <div class="card">
                                                        <div class="card-body d-flex justify-content-around">
                                                            <h4 class="text-danger pt-2">
                                                                <i class="tio-info-outined"></i>
                                                                {{translate(' Your current payment settings are disabled, because
                                                                 you have enabled
                                                                 payment gateway addon, To visit your currently
                                                                 active payment gateway settings please follow
                                                                 the link.')}}</h4>

                                                            <a href="{{!empty($paymentUrl) ? $paymentUrl : ''}}"
                                                               class="btn btn-outline-primary">{{translate('settings')}}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif


                                            <div class="row">
                                                @php($isPublished = $publishedStatus == 1 ? 'disabled' : '')
                                                @foreach($dataValues as $gateway)
                                                    <div class="col-12 col-md-6 mb-30">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h4 class="page-title">{{translate($gateway->key_name)}}</h4>
                                                            </div>
                                                            <div class="card-body p-30">
                                                                <form
                                                                    action="{{route('admin.configuration.payment-set')}}"
                                                                    method="POST"
                                                                    id="{{$gateway->key_name}}-form"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    @php($additional_data = $gateway['additional_data'] != null ? json_decode($gateway['additional_data']) : [])
                                                                    <div class="discount-type">
                                                                        <div
                                                                            class="d-flex align-items-center gap-4 gap-xl-5 mb-30">
                                                                            <div class="custom-radio">
                                                                                <input type="radio"
                                                                                       id="{{$gateway->key_name}}-active"
                                                                                       name="status"
                                                                                       value="1" {{$dataValues->where('key_name',$gateway->key_name)->first()->live_values['status']?'checked':''}} {{$isPublished}}>
                                                                                <label
                                                                                    for="{{$gateway->key_name}}-active">{{translate('active')}}</label>
                                                                            </div>
                                                                            <div class="custom-radio">
                                                                                <input type="radio"
                                                                                       id="{{$gateway->key_name}}-inactive"
                                                                                       name="status"
                                                                                       value="0" {{$dataValues->where('key_name',$gateway->key_name)->first()->live_values['status']?'':'checked'}} {{$isPublished}}>
                                                                                <label
                                                                                    for="{{$gateway->key_name}}-inactive">{{translate('inactive')}}</label>
                                                                            </div>
                                                                        </div>

                                                                        <div
                                                                            class="payment--gateway-img justify-content-center d-flex align-items-center">
                                                                            <img
                                                                                class="payment-image-preview"
                                                                                id="{{$gateway->key_name}}-image-preview"
                                                                                src="{{onErrorImage($additional_data != null ? $additional_data->gateway_image : '',
                                                                                    asset('storage/app/public/payment_modules/gateway_image').'/' . ($additional_data != null ? $additional_data->gateway_image : ''),
                                                                                    asset('public/assets/admin-module/img/placeholder.png') ,
                                                                                    'payment_modules/gateway_image/')}}"
                                                                                alt="{{translate('image')}}">
                                                                        </div>

                                                                        <input name="gateway"
                                                                               value="{{$gateway->key_name}}"
                                                                               class="hide-div">

                                                                        @php($mode=$dataValues->where('key_name',$gateway->key_name)->first()->live_values['mode'])
                                                                        <div class="form-floating mb-30 mt-30">
                                                                            <select
                                                                                class="js-select theme-input-style w-100"
                                                                                name="mode" {{$isPublished}}>
                                                                                <option
                                                                                    value="live" {{$mode=='live'?'selected':''}}>{{translate('live')}}</option>
                                                                                <option
                                                                                    value="test" {{$mode=='test'?'selected':''}}>{{translate('test')}}</option>
                                                                            </select>
                                                                        </div>

                                                                        @php($skip=['gateway','mode','status'])
                                                                        @foreach($dataValues->where('key_name',$gateway->key_name)->first()->live_values as $key=>$value)
                                                                            @if(!in_array($key,$skip))
                                                                                <div
                                                                                    class="form-floating mb-30 mt-30">
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="{{$key}}"
                                                                                           placeholder="{{translate($key)}} *"
                                                                                           value="{{env('APP_ENV')=='demo'?'':$value}}" {{$isPublished}}>
                                                                                    <label>{{translate($key)}}
                                                                                        *</label>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach

                                                                        <div class="form-floating gateway-title">
                                                                            <input type="text" class="form-control"
                                                                                   id="{{$gateway->key_name}}-title"
                                                                                   name="gateway_title"
                                                                                   placeholder="{{translate('payment_gateway_title')}}"
                                                                                   value="{{$additional_data != null ? $additional_data->gateway_title : ''}}" {{$isPublished}}>
                                                                            <label
                                                                                for="{{$gateway->key_name}}-title"
                                                                                class="form-label">{{translate('payment_gateway_title')}}</label>
                                                                        </div>

                                                                        <div class="form-floating mb-3">
                                                                            <input type="file" class="form-control"
                                                                                   name="gateway_image"
                                                                                   accept=".jpg, .png, .jpeg|image/*"
                                                                                   id="{{$gateway->key_name}}-image">
                                                                        </div>

                                                                    </div>
                                                                    <div class="d-flex justify-content-end">
                                                                        <button type="submit"
                                                                                class="btn btn--primary demo_check" {{$isPublished}}>
                                                                            {{translate('update')}}
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($webPage == 'social_login')
                    <div class="tab-content">
                        <div class="tab-pane fade {{$webPage == 'social_login' ? 'show active' : ''}}"
                             id="social_login">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="page-title">
                                        {{translate('Social Login Setup')}}
                                    </h4>
                                </div>
                                <div class="card-body p-30">
                                    <div class="row">
                                        <div class="col-12 col-md-12 mb-30">
                                            <div class="tab-content">
                                                <div class="tab-pane fade active show" id="social-login">
                                                    <div class="card">
                                                        @php($mediums = ['google', 'facebook'])
                                                        <div class="card-body p-30">
                                                            <div class="table-responsive">
                                                                <table id="example" class="table align-middle">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>{{translate('Medium')}}</th>
                                                                        <th>{{translate('Status')}}</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($mediums as $medium)
                                                                        @php($config = $socialLoginConfigs->where('key_name', $medium.'_social_login')->first())
                                                                        <tr>
                                                                            <td class="text-capitalize">{{translate($medium)}}</td>
                                                                            <td>
                                                                                <label class="switcher">
                                                                                    <input
                                                                                        class="switcher_input social-media-status"
                                                                                        data-key="{{$medium}}_social_login"
                                                                                        data-values="$(this).is(':checked')===true?1:0"
                                                                                        type="checkbox" {{isset($config) && $config->live_values ? 'checked' : ''}}>
                                                                                    <span
                                                                                        class="switcher_control"></span>
                                                                                </label>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($webPage == 'app_settings')
                    <div class="tab-content">
                        <div class="tab-pane fade {{$webPage == 'app_settings' ? 'show active' : ''}}"
                             id="app_settings">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="page-title">
                                        {{translate('Customer App Configuration')}}
                                    </h4>
                                </div>
                                <div class="card-body p-30">
                                    <div class="row">
                                        <div class="col-12 col-md-12 mb-30">
                                            <div class="mb-3">
                                                <ul class="nav nav--tabs nav--tabs__style2">
                                                    <li class="nav-item">
                                                        <button data-bs-toggle="tab" data-bs-target="#customer"
                                                                class="nav-link active">
                                                            {{translate('Customer')}}
                                                        </button>
                                                    </li>
                                                    <li class="nav-item">
                                                        <button data-bs-toggle="tab" data-bs-target="#provider"
                                                                class="nav-link">
                                                            {{translate('Provider')}}
                                                        </button>
                                                    </li>
                                                    <li class="nav-item">
                                                        <button data-bs-toggle="tab" data-bs-target="#serviceman"
                                                                class="nav-link">
                                                            {{translate('Serviceman')}}
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="customer">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h4 class="page-title">{{translate('Customer_app_configuration')}}</h4>
                                                        </div>
                                                        <div class="card-body p-30">
                                                            <div class="alert alert-danger mb-30">
                                                                <p>
                                                                    <i class="material-icons">info</i>
                                                                    {{translate('If there is any update available in the admin panel and for that the previous app will not work. You can force the customer from here by providing the minimum version for force update. That means if a customer has an app below this version the customers must need to update the app first. If you do not need a force update just insert here zero (0) and ignore it.')}}
                                                                </p>
                                                            </div>
                                                            <form
                                                                action="{{route('admin.configuration.set-app-settings')}}"
                                                                method="POST"
                                                                id="google-map-update-form"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="discount-type">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-12">
                                                                            <div class="mb-30">
                                                                                <div class="form-floating">
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="min_version_for_android"
                                                                                           placeholder="{{translate('min_version_for_android')}} *"
                                                                                           required=""
                                                                                           value="{{$customerDataValues->min_version_for_android??''}}">
                                                                                    <label>{{translate('min_version_for_android')}}
                                                                                        *</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-12">
                                                                            <div class="mb-30">
                                                                                <div class="form-floating">
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="min_version_for_ios"
                                                                                           placeholder="{{translate('min_version_for_IOS')}} *"
                                                                                           required=""
                                                                                           value="{{$customerDataValues->min_version_for_ios??''}}">
                                                                                    <label>{{translate('min_version_for_IOS')}}
                                                                                        *</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <input name="app_type" value="customer"
                                                                               class="hide-div">
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-end">
                                                                    <button type="submit"
                                                                            class="btn btn--primary demo_check">
                                                                        {{translate('update')}}
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-content">
                                                <div class="tab-pane fade" id="provider">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h4 class="page-title">{{translate('Provider_app_configuration')}}</h4>
                                                        </div>
                                                        <div class="card-body p-30">
                                                            <div class="alert alert-danger mb-30">
                                                                <p>
                                                                    <i class="material-icons">info</i>
                                                                    {{translate('If there is any update available in the admin panel and for that the previous app will not work. You can force the user from here by providing the minimum version for force update. That means if a user has an app below this version the users must need to update the app first. If you do not need a force update just insert here zero (0) and ignore it.')}}
                                                                </p>
                                                            </div>
                                                            <form
                                                                action="{{route('admin.configuration.set-app-settings')}}"
                                                                method="POST"
                                                                id="google-map-update-form"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="discount-type">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-12">
                                                                            <div class="mb-30">
                                                                                <div class="form-floating">
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="min_version_for_android"
                                                                                           placeholder="{{translate('min_version_for_android')}} *"
                                                                                           required=""
                                                                                           value="{{$providerDataValues->min_version_for_android??''}}">
                                                                                    <label>{{translate('min_version_for_android')}}
                                                                                        *</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-12">
                                                                            <div class="mb-30">
                                                                                <div class="form-floating">
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="min_version_for_ios"
                                                                                           placeholder="{{translate('min_version_for_IOS')}} *"
                                                                                           required=""
                                                                                           value="{{$providerDataValues->min_version_for_ios??''}}">
                                                                                    <label>{{translate('min_version_for_IOS')}}
                                                                                        *</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <input name="app_type" value="provider"
                                                                               class="hide-div">
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-end">
                                                                    <button type="submit"
                                                                            class="btn btn--primary demo_check">
                                                                        {{translate('update')}}
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-content">
                                                <div class="tab-pane fade" id="serviceman">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h4 class="page-title">{{translate('Serviceman_app_configuration')}}</h4>
                                                        </div>
                                                        <div class="card-body p-30">
                                                            <div class="alert alert-danger mb-30">
                                                                <p>
                                                                    <i class="material-icons">info</i>
                                                                    {{translate('If there is any update available in the admin panel and for that the previous app will not work. You can force the user from here by providing the minimum version for force update. That means if a user has an app below this version the users must need to update the app first. If you do not need a force update just insert here zero (0) and ignore it.')}}
                                                                </p>
                                                            </div>
                                                            <form
                                                                action="{{route('admin.configuration.set-app-settings')}}"
                                                                method="POST"
                                                                id="google-map-update-form"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="discount-type">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-12">
                                                                            <div class="mb-30">
                                                                                <div class="form-floating">
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="min_version_for_android"
                                                                                           placeholder="{{translate('min_version_for_android')}} *"
                                                                                           required=""
                                                                                           value="{{$servicemanDataValues->min_version_for_android??''}}">
                                                                                    <label>{{translate('min_version_for_android')}}
                                                                                        *</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-12">
                                                                            <div class="mb-30">
                                                                                <div class="form-floating">
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="min_version_for_ios"
                                                                                           placeholder="{{translate('min_version_for_IOS')}} *"
                                                                                           required=""
                                                                                           value="{{$servicemanDataValues->min_version_for_ios??''}}">
                                                                                    <label>{{translate('min_version_for_IOS')}}
                                                                                        *</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <input name="app_type" value="serviceman"
                                                                               class="hide-div">
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-end">
                                                                    <button type="submit"
                                                                            class="btn btn--primary demo_check">
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
    <script src="{{asset('public/assets/admin-module')}}/plugins/swiper/swiper-bundle.min.js"></script>
    <script>
        "use strict";

        $('.social-media-status').on('click', function () {
            let key = $(this).data('key');
            let values = $(this).is(':checked') === true ? 1 : 0;
            update_social_media_status(key, values)
        });

        $('#google-map').on('submit', function (event) {
            event.preventDefault();
            let formData = new FormData(document.getElementById("google-map-update-form"));

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.configuration.set-third-party-config')}}",
                data: formData,
                processData: false,
                contentType: false,
                type: 'post',
                success: function (response) {
                    console.log(response)
                    toastr.success('{{translate('successfully_updated')}}')
                },
                error: function () {

                }
            });
        });

        $('#firebase-form').on('submit', function (event) {
            event.preventDefault();

            let formData = new FormData(document.getElementById("firebase-form"));

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{route('admin.configuration.set-third-party-config')}}",
                data: formData,
                processData: false,
                contentType: false,
                type: 'post',
                success: function (response) {
                    console.log(response)
                    toastr.success('{{translate('successfully_updated')}}')
                },
                error: function () {

                }
            });
        });

        $('#recaptcha-form').on('submit', function (event) {
            event.preventDefault();

            let formData = new FormData(document.getElementById("recaptcha-form"));

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{route('admin.configuration.set-third-party-config')}}",
                data: formData,
                processData: false,
                contentType: false,
                type: 'post',
                success: function (response) {
                    console.log(response)
                    toastr.success('{{translate('successfully_updated')}}')
                },
                error: function () {

                }
            });
        });

        $('#apple-login-form').on('submit', function (event) {
            event.preventDefault();

            let formData = new FormData(document.getElementById("apple-login-form"));

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.configuration.set-third-party-config')}}",
                data: formData,
                processData: false,
                contentType: false,
                type: 'post',
                success: function (response) {
                    console.log(response)
                    toastr.success('{{translate('successfully_updated')}}')
                },
                error: function () {

                }
            });
        });

        $('#config-form').on('submit', function (event) {
            event.preventDefault();
            if ('{{env('APP_ENV')=='demo'}}') {
                demo_mode()
            } else {
                let formData = new FormData(document.getElementById("config-form"));

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('admin.configuration.set-email-config')}}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'post',
                    success: function (response) {
                        console.log(response)
                        if (response.response_code === 'default_400') {
                            toastr.error('{{translate('all_fields_are_required')}}')
                        } else {
                            toastr.success('{{translate('successfully_updated')}}')
                        }
                    },
                    error: function () {

                    }
                });
            }
        });

        function update_social_media_status(key_name, value) {
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
                        url: "{{route('admin.configuration.social-login-config-set')}}",
                        data: {
                            key: key_name,
                            value: value,
                        },
                        type: 'put',
                        success: function (response) {
                            toastr.success('{{translate('successfully_updated')}}')
                        },
                        error: function () {

                        }
                    });
                }
            })
        }

        let swiper = new Swiper(".modalSwiper", {
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
                dynamicBullets: true,
                autoHeight: true,
            },
        });
    </script>

    <script>
        "use strict";

        function ValidateEmail(inputText) {
            let mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            return !!inputText.match(mailformat);
        }

        $('#send-mail').on('click', function () {
            if (ValidateEmail($('#test-email').val())) {
                Swal.fire({
                    title: '{{ translate('Are you sure?') }}?',
                    text: "{{ translate('a_test_mail_will_be_sent_to_your_email') }}!",
                    showCancelButton: true,
                    cancelButtonColor: 'var(--c2)',
                    confirmButtonColor: 'var(--c1)',
                    confirmButtonText: '{{ translate('Yes') }}!'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.configuration.send-mail') }}",
                            method: 'GET',
                            data: {
                                "email": $('#test-email').val()
                            },
                            beforeSend: function () {
                                $('#loading').show();
                            },
                            success: function (data) {
                                if (data.success === 2) {
                                    toastr.error(
                                        '{{ translate('email_configuration_error') }} !!'
                                    );
                                } else if (data.success === 1) {
                                    toastr.success(
                                        '{{ translate('email_configured_perfectly!') }}!'
                                    );
                                } else {
                                    toastr.info(
                                        '{{ translate('email_status_is_not_active') }}!'
                                    );
                                }
                            },
                            complete: function () {
                                $('#loading').hide();

                            }
                        });
                    }
                })
            } else {
                toastr.error('{{ translate('invalid_email_address') }} !!');
            }
        });
    </script>
@endpush
