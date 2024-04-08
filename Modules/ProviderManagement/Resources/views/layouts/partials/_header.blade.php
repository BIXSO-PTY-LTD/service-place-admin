<header class="header fixed-top">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between">
            <div class="col-2">
                <div class="header-toogle-menu">
                    <button class="toggle-menu-button aside-toggle border-0 bg-transparent p-0 dark-color">
                        <span class="material-icons">menu</span>
                    </button>
                </div>
            </div>
            <div class="col-10">
                <div class="header-right">
                    <ul class="nav justify-content-end align-items-center gap-30">
                        <li class="nav-item max-sm-m-0">
                            <div class="hs-unfold">
                                <div>
                                    @php( $local = session()->has('provider_local')?session('provider_local'):'en')
                                    @php($lang = Modules\BusinessSettingsModule\Entities\BusinessSettings::where('key_name','system_language')->first())
                                    @if ($lang)
                                        <div class="topbar-text dropdown d-flex">
                                            <a class="topbar-link dropdown-toggle d-flex align-items-center title-color gap-1 justify-content-between lagn-drop-btn"
                                               href="#" role="button" data-bs-toggle="dropdown"
                                               data-bs-offset="0,20"
                                               aria-expanded="false">
                                                <div class="d-flex align-items-center gap-1">
                                                    @foreach ($lang['live_values'] as $data)
                                                        @if($data['code']==$local)
                                                            @php($language = collect(LANGUAGES)->where('code', $data['code'])->first())
                                                            <span class="material-icons">language</span>
                                                            @if($language)
                                                                <span
                                                                    class="d-none d-sm-inline-block">{{ $language['nativeName'] }}</span>
                                                                <span class="fz-10">({{ $data['code'] }})</span>
                                                            @else
                                                                {{ $data['code'] }}
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </a>
                                            <ul class="dropdown-menu lang-menu min-w120">
                                                @foreach($lang['live_values'] as $key =>$data)
                                                    @if($data['status']==1)
                                                        @php($language = collect(LANGUAGES)->where('code', $data['code'])->first())
                                                        <li>
                                                            <a class="dropdown-item d-flex gap-2 align-items-center py-2 justify-content-between"
                                                               href="{{route('provider.lang',[$data['code']])}}">
                                                                @if($language)
                                                                    <div class="d-flex gap-2 align-items-center">
                                                                        <span class="text-capitalize">{{ $language['nativeName'] }}</span>
                                                                        <span class="fz-10">({{ $data['code'] }})</span>
                                                                    </div>
                                                                    @if($local == $data['code'])
                                                                        <span class="material-symbols-outlined text-muted">check_circle</span>
                                                                    @endif
                                                                @else
                                                                    <span
                                                                        class="text-capitalize">{{ $data['code'] }}</span>
                                                                @endif

                                                            </a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="messages">
                                <a href="{{route('provider.chat.index', ['user_type' => 'super_admin'])}}" class="header-icon count-btn">
                                    <span class="material-icons">sms</span>
                                    <span class="count" id="message_count">0</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="notification update-notification">
                                <a href="#"
                                   class="header-icon count-btn notification-icon" data-bs-toggle="dropdown">
                                    <span class="material-icons">notifications</span>
                                    <span class="count" id="notification_count">0</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <div class="show-notification-list" id="show-notification-list"></div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="user mt-n1">
                                <a href="#" class="header-icon user-icon" data-bs-toggle="dropdown">
                                    <img width="30" height="30" src="{{onErrorImage(
                                        auth()->user()->provider->logo,
                                        asset('storage/app/public/provider/logo').'/' . auth()->user()->provider->logo,
                                        asset('public/assets/provider-module/img/user2x.png') ,
                                        'provider/logo/')}}"
                                         class="rounded-circle" alt="{{ translate('logo') }}">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{route('provider.profile_update')}}"
                                       class="dropdown-item-text media gap-3 align-items-center">
                                        <div class="avatar">
                                            <img class="avatar-img rounded-circle" width="50" height="50" src="{{onErrorImage(
                                                    auth()->user()->provider->logo,
                                                    asset('storage/app/public/provider/logo').'/' . auth()->user()->provider->logo,
                                                    asset('public/assets/provider-module/img/user2x.png') ,
                                                    'provider/logo/')}}"
                                                 alt="{{ translate('logo') }}">
                                        </div>
                                        <div class="media-body ">
                                            <h5 class="card-title">{{ Str::limit(auth()->user()->provider->company_name, 15) }}</h5>
                                            <span class="card-text">{{ Str::limit(auth()->user()->email, 20) }}</span>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="{{route('provider.profile_update')}}">
                                        <span class="text-truncate" title="Settings">{{translate('Settings')}}</span>
                                    </a>
                                    <a class="dropdown-item provider-logout cursor-pointer">
                                        <span class="text-truncate" title="Sign Out">{{translate('Sign_Out')}}</span>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
