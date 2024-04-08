<!DOCTYPE html>
@php
    $site_direction = session()->get('provider_site_direction');
@endphp
<html lang="en" dir="{{$site_direction}}">

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

    <link href="{{asset('public/assets/provider-module')}}/css/material-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/bootstrap.min.css"/>
    <link rel="stylesheet"
          href="{{asset('public/assets/provider-module')}}/plugins/perfect-scrollbar/perfect-scrollbar.min.css"/>

    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/plugins/apex/apexcharts.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/plugins/select2/select2.min.css"/>

    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/toastr.css">

    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/style.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/dev.css"/>
    @stack('css_or_js')
</head>

<body>
<script>
    localStorage.theme && document.querySelector('body').setAttribute("theme", localStorage.theme);
</script>

<div class="offcanvas-overlay"></div>

<div class="preloader"></div>

@include('providermanagement::layouts.partials._header')

@include('providermanagement::layouts.partials._aside')

@include('providermanagement::layouts.partials._settings-sidebar')

<main class="main-area">
    @yield('content')

    @include('providermanagement::layouts.partials._footer')

    <div class="modal fade" id="serviceRequestModal" tabindex="-1"
         aria-labelledby="serviceRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="--bs-modal-width: 430px">
            <div class="modal-content">
            </div>
        </div>
    </div>

</main>


<script src="{{asset('public/assets/provider-module')}}/js/jquery-3.6.0.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/main.js"></script>


<script src="{{asset('public/assets/provider-module')}}/plugins/select2/select2.min.js"></script>

<script src="{{asset('public/assets/provider-module')}}/js/sweet_alert.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/toastr.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/dev.js"></script>

<audio id="audio-element">
    <source src="{{asset('public/assets/provider-module')}}/sound/notification.mp3" type="audio/mpeg">
</audio>
<script>
    "use strict";

    var audio = document.getElementById("audio-element");

    function playAudio(status) {
        status ? audio.play() : audio.pause();
    }

    $(document).ready(function () {
        $('.js-select').select2();
    });

    $("#search-form__input").on("keyup", function () {
        var value = this.value.toLowerCase().trim();
        $(".show-search-result a").show().filter(function () {
            return $(this).text().toLowerCase().trim().indexOf(value) == -1;
        }).hide();
    });

    function checkBooking(count) {
        console.log(count)
        sessionStorage.setItem("booking_count", parseInt(count));
    }

    function update_notification() {
        let count = $('#notification_count').text();
        let notification_count = sessionStorage.getItem("notification_count");

        if (parseInt(count) > 0) {
            sessionStorage.setItem("notification_count", parseInt(notification_count) + parseInt(count));
        }
    }

    @if(!auth()->user()?->provider?->is_suspended || !business_config('suspend_on_exceed_cash_limit_provider', 'provider_config')->live_values)
    setInterval(function () {
        $.get({
            url: '{{ route('provider.get_updated_data') }}',
            dataType: 'json',
            success: function (response) {
                let data = response.data;

                let notification_count = sessionStorage.getItem("notification_count");
                if (notification_count == null || isNaN(notification_count)) {
                    notification_count = 0;
                    sessionStorage.setItem("notification_count", notification_count);
                }

                let booking_count = sessionStorage.getItem("booking_count");
                if (booking_count == null || isNaN(parseInt(booking_count)) || data.booking === 0) {
                    booking_count = 0;
                    sessionStorage.setItem("booking_count", parseInt(booking_count));
                }

                let count = data.notification_count;

                document.getElementById("message_count").innerHTML = data.message;
                document.getElementById("notification_count").innerHTML = parseInt(count) < 0 ? 0 : parseInt(count);
                document.getElementById("show-notification-list").innerHTML = data.notification_template;
                var availableStatus = "{{auth()->user()?->provider?->service_availability}}";

                if (data.booking !== parseInt(booking_count) && data.booking > 0 && availableStatus == 1) {
                    playAudio(true);
                    Swal.fire({
                        title: '{{translate('New_Notification')}}',
                        text: "{{translate('You_have_new_booking_arrived')}}",
                        icon: 'info',
                        showCloseButton: true,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonText: '{{translate('Show_Bookings')}}',
                    }).then((result) => {
                        if (result.value) {
                            playAudio(false);
                            checkBooking();
                            location.href = "{{route('provider.booking.list', ['booking_status'=>'pending'])}}";
                        } else if (result.dismiss === 'close') {
                            playAudio(false);
                            checkBooking();
                            location.href = "{{route('provider.booking.list', ['booking_status'=>'pending'])}}";
                        }
                    })
                }

                if (data.unchecked_posts > 0 && availableStatus == 1) {
                    playAudio(true);
                    if (data.post_content !== null) {
                        $('#serviceRequestModal .modal-content').html(data.post_content);
                    }
                    $('#serviceRequestModal').modal('show');
                }
            },
        });
    }, 10000);
    @endif


    @if(!request()->is('provider/profile-update') && is_null(auth()->user()->provider->coordinates))
    Swal.fire({
        title: "{{translate('Update Location')}}",
        text: "{{translate('You must update your location first')}}",
        type: 'warning',
        showCloseButton: false,
        showCancelButton: false,
        confirmButtonColor: 'var(--c1)',
        confirmButtonText: "{{translate('Update from profile')}}",
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            location.href = "{{route('provider.profile_update')}}";
        }
    })
    @endif

    $('.form-alert').on('click', function () {
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

    $('.route-alert').on('click', function () {
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
            cancelButtonText: '{{translate('Cancel')}}',
            confirmButtonText: '{{translate('Yes')}}',
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
                        console.log(data)
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


    $('.update-notification').on('click', function () {
        update_notification()
    });

    $('.provider-logout').on('click', function (event) {
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
                location.href = "{{route('provider.auth.logout')}}"
            }
        })
    });


</script>

{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif


@stack('script')

</body>

</html>
