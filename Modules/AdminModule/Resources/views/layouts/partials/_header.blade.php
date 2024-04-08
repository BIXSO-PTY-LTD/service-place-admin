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
                                    @php( $local = session()->has('local')?session('local'):'en')
                                    @php($lang = Modules\BusinessSettingsModule\Entities\BusinessSettings::where('key_name','system_language')->first())
                                    @if ($lang)
                                        <div class="topbar-text dropdown d-flex">
                                            <a class="topbar-link dropdown-toggle d-flex align-items-center title-color gap-1 justify-content-between lagn-drop-btn"
                                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="0,20">
                                                @foreach ($lang['live_values'] as $data)
                                                    @if($data['code']==$local)
                                                        @php($language = collect(LANGUAGES)->where('code', $data['code'])->first())
                                                        <span class="material-icons">language</span>
                                                        @if($language)
                                                            {{ $language['nativeName'] }} <span class="fz-10">({{ $data['code'] }})</span>
                                                        @else
                                                            {{ $data['code'] }}
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </a>
                                            <ul class="dropdown-menu lang-menu">
                                                @foreach($lang['live_values'] as $key =>$data)
                                                    @if($data['status']==1)
                                                        @php($language = collect(LANGUAGES)->where('code', $data['code'])->first())
                                                        <li>
                                                            <a class="dropdown-item d-flex gap-2 align-items-center py-2 justify-content-between"
                                                               href="{{route('admin.lang',[$data['code']])}}">
                                                                @if($language)
                                                                    <div class="d-flex gap-2 align-items-center">
                                                                        <span class="text-capitalize">{{ $language['nativeName'] }}</span>
                                                                        <span class="fz-10">({{ $data['code'] }})</span>
                                                                    </div>
                                                                    @if($local == $data['code'])
                                                                        <span class="material-symbols-outlined text-muted">check_circle</span>
                                                                    @endif
                                                                @else
                                                                    <span class="text-capitalize">{{ $data['code'] }}</span>
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
                                <a href="{{route('admin.chat.index', ['user_type' => 'customer'])}}" class="header-icon count-btn">
                                    <span class="material-icons">sms</span>
                                    <span class="count" id="message_count">0</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="user mt-n1">
                                <a href="#" class="header-icon user-icon" data-bs-toggle="dropdown">
                                    <img width="30" height="30"
                                         src="{{onErrorImage(
                                                        auth()->user()->profile_image,
                                                        auth()->user()->user_type == 'admin-employee' ? asset('storage/app/public/employee/profile').'/' . auth()->user()->profile_image : asset('storage/app/public/user/profile_image').'/' . auth()->user()->profile_image,
                                                        asset('public/assets/provider-module/img/user2x.png') ,
                                                        auth()->user()->user_type == 'admin-employee' ? 'employee/profile/' :'user/profile_image/')}}"

                                         class="rounded-circle" alt="{{ translate('profile_image') }}">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{route('admin.profile_update')}}"
                                       class="dropdown-item-text media gap-3 align-items-center">
                                        <div class="avatar">
                                            <img class="avatar-img rounded-circle" width="50" height="50"
                                                 src="{{onErrorImage(
                                                        auth()->user()->profile_image,
                                                        auth()->user()->user_type == 'admin-employee' ? asset('storage/app/public/employee/profile').'/' . auth()->user()->profile_image : asset('storage/app/public/user/profile_image').'/' . auth()->user()->profile_image,
                                                        asset('public/assets/provider-module/img/user2x.png') ,
                                                        auth()->user()->user_type == 'admin-employee' ? 'employee/profile/' :'user/profile_image/')}}"
                                                 alt="{{ translate('profile-image') }}">
                                        </div>
                                        <div class="media-body ">
                                            <h5 class="card-title">{{ Str::limit(auth()->user()?->first_name, 20) }}</h5>
                                            <span class="card-text">{{ Str::limit(auth()->user()?->email, 20) }}</span>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="{{route('admin.profile_update')}}">
                                        <span class="text-truncate" title="{{translate('Settings')}}">{{translate('Settings')}}</span>
                                    </a>
                                    <a class="dropdown-item admin-logout">
                                        <span class="text-truncate cursor-pointer" title="{{translate('Sign Out')}}">{{translate('Sign_Out')}}</span>
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
