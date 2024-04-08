@extends('adminmodule::layouts.master')

@section('title', translate('role_settings'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/select.dataTables.min.css"/>
@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Employee_Role')}}</h2>
                    </div>

                    <div class="card mb-30">
                        <div class="card-body p-30">
                            <form action="{{route('admin.role.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <div class="form-floating form-floating__icon mb-30">
                                            <input type="text" class="form-control" name="role_name"
                                                   placeholder="{{translate('role_name')}} *"
                                                   required="">
                                            <label>{{translate('role_name')}} *</label>
                                            <span class="material-icons">subtitles</span>
                                        </div>

                                        <div class="mb-3 fw-bold">{{translate('modules')}}</div>
                                        <div class="grid-column-4 mb-30">
                                            @foreach(SYSTEM_MODULES as $key=>$module)
                                                <div class="custom-radio">
                                                    <input type="checkbox" id="{{$module['key']}}" name="modules[]"
                                                           value="{{$module['key']}}">
                                                    <label for="{{$module['key']}}">{{$module['value']}}</label>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-20 mt-30">
                                            <button class="btn btn--secondary"
                                                    type="reset">{{translate('reset')}}</button>
                                            <button class="btn btn--primary"
                                                    type="submit">{{translate('submit')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="all-tab-pane">
                            <div class="card">
                                <div class="card-body">
                                    <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                        <form action="{{url()->current()}}"
                                              class="search-form search-form_style-two"
                                              method="POST">
                                            @csrf
                                            <div class="input-group search-form__input_group">
                                            <span class="search-form__icon">
                                                <span class="material-icons">search</span>
                                            </span>
                                                <input type="search" class="theme-input-style search-form__input"
                                                       value="{{$search}}" name="search"
                                                       placeholder="{{translate('search_here')}}">
                                            </div>
                                            <button type="submit" class="btn btn--primary">
                                                {{translate('search')}}
                                            </button>
                                        </form>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="example" class="table align-middle">
                                            <thead>
                                            <tr>
                                                <th>{{translate('Sl')}}</th>
                                                <th>{{translate('role_name')}}</th>
                                                <th>{{translate('Permitted_Modules')}}</th>
                                                <th>{{translate('status')}}</th>
                                                <th class="text-center">{{translate('action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($roles as $key => $item)
                                                <tr>
                                                    <td>{{$key+$roles?->firstItem()}}</td>
                                                    <td>{{$item->role_name}}</td>
                                                    <td class="text-capitalize">{{str_replace('_',' ',implode(', ',$item->modules))}}</td>

                                                    <td>
                                                        <label class="switcher">
                                                            <input class="switcher_input"
                                                                   type="checkbox"
                                                                   {{$item->is_active?'checked':''}} data-status="{{$item->id}}">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <a href="{{route('admin.role.edit',[$item->id])}}"
                                                                class="action-btn btn--light-primary" style="--size: 30px">
                                                                <span class="material-icons">edit</span>
                                                            </a>
                                                            <button type="button" data-delete="{{$item->id}}"
                                                                class="action-btn btn--danger" style="--size: 30px">
                                                                <span class="material-symbols-outlined">delete</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{route('admin.role.delete',[$item->id])}}"
                                                              method="post" id="delete-{{$item->id}}"
                                                              class="hidden">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {!! $roles->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{asset('public/assets/admin-module')}}/js/custom.js"></script>
    <script src="{{asset('public/assets/admin-module')}}/plugins/dataTables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/admin-module')}}/plugins/dataTables/dataTables.select.min.js"></script>
    <script>
        "use strict";

        $('.switcher_input').on('click', function () {
            let itemId = $(this).data('status');
            let route = '{{ route('admin.role.status-update', ['id' => ':itemId']) }}';
            route = route.replace(':itemId', itemId);
            route_alert(route, '{{ translate('want_to_update_status') }}');
        })

        $('.action-btn.btn--danger').on('click', function () {
            let itemId = $(this).data('delete');
            @if(env('APP_ENV')!='demo')
            form_alert('delete-' + itemId, '{{translate('want_to_delete_this')}}?')
            @endif
        })
    </script>
@endpush
