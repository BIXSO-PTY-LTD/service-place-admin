<!DOCTYPE html>
@php
    $site_direction = session()->get('site_direction');
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{$site_direction}}">

<head>
    <title>@yield('title')</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon"
          href="{{asset('storage/app/public/business')}}/{{(business_config('business_favicon', 'business_information'))->live_values ?? null}}"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
        rel="stylesheet">


    <link href="{{asset('public/assets/admin-module')}}/css/material-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/css/bootstrap.min.css"/>
    <link rel="stylesheet"
          href="{{asset('public/assets/admin-module')}}/plugins/perfect-scrollbar/perfect-scrollbar.min.css"/>


    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/apex/apexcharts.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.css"/>

    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/css/toastr.css">

    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/css/style.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/css/dev.css"/>

    @stack('css_or_js')
</head>

<body>
<script>
    localStorage.theme && document.querySelector('body').setAttribute("theme", localStorage.theme);
</script>

<div class="offcanvas-overlay"></div>


<div class="preloader"></div>


@include('adminmodule::layouts.partials._header')


@include('adminmodule::layouts.partials._aside')


@include('adminmodule::layouts.partials._settings-sidebar')


<main class="main-area">
    @yield('content')


    @include('adminmodule::layouts.partials._footer')
</main>


<script src="{{asset('public/assets/admin-module')}}/js/jquery-3.6.0.min.js"></script>
<script src="{{asset('public/assets/admin-module')}}/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('public/assets/admin-module')}}/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="{{asset('public/assets/admin-module')}}/js/main.js"></script>
<script src="{{asset('public/assets/admin-module')}}/js/helper.js"></script>

<script src="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.js"></script>


<script src="{{asset('public/assets/admin-module')}}/js/sweet_alert.js"></script>
<script src="{{asset('public/assets/admin-module')}}/js/toastr.js"></script>
<script src="{{asset('public/assets/admin-module')}}/js/dev.js"></script>
{!! Toastr::message() !!}

<audio id="audio-element">
    <source src="{{asset('public/assets/provider-module')}}/sound/notification.mp3" type="audio/mpeg">
</audio>

<script>
    "use strict";
    $(document).ready(function () {
        $('.js-select').select2();
    });

    @if ($errors->any())
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
   @endif

    $('.form-alert').on('click', function (){
        let id = $(this).data('id');
        let message = $(this).data('message');
        form_alert(id, message)
    });

    function form_alert(id, message) {
        Swal.fire({
            title: "{{translate('are_you_sure')}}?",
            text: message,
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
                $('#' + id).submit()
            }
        })
    }

    $('.route-alert').on('click', function (){
        let route = $(this).data('route');
        let message = $(this).data('message');
        route_alert(route, message)
    });

    function route_alert(route, message) {
        Swal.fire({
            title: "{{translate('are_you_sure')}}?",
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'var(--c2)',
            confirmButtonColor: 'var(--c1)',
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.get({
                    url: route,
                    dataType: 'json',
                    data: {},
                    beforeSend: function () {

                    },
                    success: function (data) {
                        toastr.success(data.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    },
                    complete: function () {

                    },
                });
            }
        })
    }

    $('.route-alert-reload').on('click', function (){
        let route = $(this).data('route');
        let message = $(this).data('message');
        route_alert_reload(route, message)
    });

    function route_alert_reload(route, message, reload = true) {
        Swal.fire({
            title: "{{translate('are_you_sure')}}?",
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'var(--c2)',
            confirmButtonColor: 'var(--c1)',
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.get({
                    url: route,
                    dataType: 'json',
                    data: {},
                    beforeSend: function () {

                    },
                    success: function (data) {
                        if (reload) {
                            setTimeout(location.reload.bind(location), 1000);
                        }
                        toastr.success(data.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    },
                    complete: function () {

                    },
                });
            }
        })
    }

    var audio = document.getElementById("audio-element");

    function playAudio(status) {
        status ? audio.play() : audio.pause();
    }

    setInterval(function () {
        $.get({
            url: '{{ route('admin.get_updated_data') }}',
            dataType: 'json',
            success: function (response) {
                let data = response.data;
                document.getElementById("message_count").innerHTML = data.message;
            },
        });
    }, 10000);


    $("#search-form__input").on("keyup", function () {
        var value = this.value.toLowerCase().trim();
        $(".show-search-result a").show().filter(function () {
            return $(this).text().toLowerCase().trim().indexOf(value) == -1;
        }).hide();
    });

    function demo_mode() {
        toastr.info('This function is disable for demo mode', {
            CloseButton: true,
            ProgressBar: true
        });
    }

    $('.demo_check').on('click', function (event) {
        if ('{{env('APP_ENV')=='demo'}}') {
            event.preventDefault();
            demo_mode()
        }
    });

    $('.admin-logout').on('click', function (event) {
        Swal.fire({
            title: "{{translate('are_you_sure')}}?",
            text: "{{translate('want_to_logout')}}",
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
                location.href = "{{route('admin.auth.logout')}}"
            }
        })
    });
</script>

@stack('script')
</body>

</html>
