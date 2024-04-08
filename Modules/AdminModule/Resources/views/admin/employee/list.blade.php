@extends('adminmodule::layouts.master')

@section('title', translate('employee_list'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/select.dataTables.min.css"/>
@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                        <h2 class="page-title">{{translate('employee_list')}}</h2>
                        <div>
                            <a href="{{route('admin.employee.create')}}" class="btn btn--primary">
                                <span class="material-icons">add</span>
                                {{translate('add_employee')}}
                            </a>
                        </div>
                    </div>

                    <div
                        class="d-flex flex-wrap justify-content-between align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                        <ul class="nav nav--tabs">
                            <li class="nav-item">
                                <a class="nav-link {{$status=='all'?'active':''}}"
                                   href="{{url()->current()}}?status=all">
                                    {{translate('all')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$status=='active'?'active':''}}"
                                   href="{{url()->current()}}?status=active">
                                    {{translate('active')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$status=='inactive'?'active':''}}"
                                   href="{{url()->current()}}?status=inactive">
                                    {{translate('inactive')}}
                                </a>
                            </li>
                        </ul>

                        <div class="d-flex gap-2 fw-medium">
                            <span class="opacity-75">{{translate('Total_Employees')}}:</span>
                            <span class="title-color">{{$employees->total()}}</span>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="all-tab-pane">
                            <div class="card">
                                <div class="card-body">
                                    <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                        <form action="{{url()->current()}}?status={{$status}}"
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
                                            <button type="submit"
                                                    class="btn btn--primary">{{translate('search')}}</button>
                                        </form>

                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <div class="dropdown">
                                                <button type="button"
                                                        class="btn btn--secondary text-capitalize dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                    <span class="material-icons">file_download</span> download
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                    <a class="dropdown-item"
                                                       href="{{route('admin.employee.download')}}?search={{$search}}">
                                                        {{translate('excel')}}
                                                    </a>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="example" class="table align-middle">
                                            <thead>
                                            <tr>
                                                <th>{{translate('Sl')}}</th>
                                                <th>{{translate('Employee_Name')}}</th>
                                                <th class="text-center">{{translate('Contact_Info')}}</th>
                                                <th class="text-center">{{translate('Role')}}</th>
                                                <th class="text-center">{{translate('status')}}</th>
                                                <th class="text-center">{{translate('action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($employees as $key => $employee)
                                                <tr>
                                                    <td data-bs-target="#exampleModal--{{$employee['id']}}"
                                                        data-bs-toggle="modal">{{$key+$employees?->firstItem()}}</td>
                                                    <td data-bs-target="#exampleModal--{{$employee['id']}}"
                                                        data-bs-toggle="modal">
                                                        <span>{{$employee->first_name}} {{$employee->last_name}}</span>

                                                        <div class="modal fade cursor-auto" tabindex="-1"
                                                             id="exampleModal--{{$employee['id']}}" aria-hidden="true">
                                                            <div class="modal-dialog modal-xl">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <div
                                                                            class="d-flex justify-content-between gap-3 mb-4">
                                                                            <h3 class="text-primary">{{translate('Employee Details')}}</h3>
                                                                            <p class="text-primary font-weight-bold">{{$employee->is_active? translate('Active'): translate('Inactive')}}</p>
                                                                        </div>

                                                                        <form>
                                                                            <div class="row gy-3">
                                                                                <div class="col-lg-8">
                                                                                    <div
                                                                                        class="media align-items-center flex-wrap gap-xl-5 gap-4">
                                                                                        <img width="260" src="{{onErrorImage($employee->profile_image, asset('storage/app/public/employee/profile').'/' . $employee->profile_image,
                                                                                             asset('public/assets/admin-module/img/media/upload-file.png') ,'employee/profile/')}}"
                                                                                             class="dark-support shadow rounded"
                                                                                             alt="{{translate('profile image')}}">
                                                                                        <div class="media-body">
                                                                                            <h3 class="mb-2">{{$employee->first_name . ' ' .  $employee->last_name}}</h3>
                                                                                            <div
                                                                                                class="fs-12 fw-medium text-primary mb-4">
                                                                                                @foreach($employee->roles as $item)
                                                                                                    {{$item->role_name}}
                                                                                                @endforeach
                                                                                            </div>

                                                                                            <ul class="list-info">
                                                                                                <li>
                                                                                                    <span
                                                                                                        class="material-symbols-outlined">assignment_ind</span>
                                                                                                    ID:
                                                                                                    #{{$employee->id}}
                                                                                                </li>
                                                                                                <li>
                                                                                                    <span
                                                                                                        class="material-symbols-outlined">phone_iphone</span>
                                                                                                    <a href="tel:{{$employee->phone}}">{{$employee->phone}}</a>
                                                                                                </li>
                                                                                                <li>
                                                                                                    <span
                                                                                                        class="material-symbols-outlined">mail</span>
                                                                                                    <a href="mailto:{{$employee->email}}">{{$employee->email}}</a>
                                                                                                </li>
                                                                                                <li>
                                                                                                    <span
                                                                                                        class="material-symbols-outlined">map</span>
                                                                                                    {{$employee->addresses->first()->value('address') ??  'not found'}}
                                                                                                </li>
                                                                                                <li class="text-uppercase">
                                                                                                    <span
                                                                                                        class="material-symbols-outlined">credit_card</span>
                                                                                                    {{str_replace('_', " " , $employee->identification_type)}}
                                                                                                    - {{$employee->identification_number}}
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <div class="p-3 bg-light rounded">
                                                                                        <div class="card border-0 mb-3">
                                                                                            <div
                                                                                                class="card-body d-flex align-items-center gap-2">
                                                                                                <span
                                                                                                    class="material-symbols-outlined text-primary">calendar_month</span>
                                                                                                Join: {{$employee->created_at}}
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="card border-0">
                                                                                            <div class="card-body">
                                                                                                <div
                                                                                                    class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                                                                                                    <div
                                                                                                        class="d-flex align-items-center gap-2">
                                                                                                        <span
                                                                                                            class="material-symbols-outlined text-primary">assignment_ind</span>
                                                                                                        {{translate('Access Available')}}
                                                                                                        :
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div
                                                                                                    class="d-flex flex-wrap gap-2 align-items-center">
                                                                                                    @php
                                                                                                        $modules = [];
                                                                                                        foreach ($employee->roles as $role) {
                                                                                                            $modules = array_merge($modules, $role->modules);
                                                                                                        }
                                                                                                        $modules = array_unique($modules);
                                                                                                    @endphp

                                                                                                    @foreach ($modules as $module)
                                                                                                        <span
                                                                                                            class="badge bg-custom title-color">{{ str_replace('_', ' ', $module) }}</span>
                                                                                                    @endforeach
                                                                                                </div>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div
                                                                                        class="d-flex justify-content-end gap-3">
                                                                                        <button type="button"
                                                                                                data-remove="{{$employee->id}}"
                                                                                                class="btn btn--danger remove">
                                                                                            {{translate('Delete')}}</button>

                                                                                        <form
                                                                                            action="{{route('admin.employee.delete',[$employee->id])}}"
                                                                                            method="post"
                                                                                            id="delete-{{$employee->id}}"
                                                                                            class="hidden">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                        </form>

                                                                                        <a type="text"
                                                                                           href="{{route('admin.employee.edit', [$employee->id])}}"
                                                                                           class="btn btn-primary">{{translate('edit')}}</a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column align-items-center gap-1">
                                                            <a href="mailto:{{$employee->email}}"
                                                               class="fz-12 fw-medium">{{$employee->email}}</a>
                                                            <a href="tel:{{$employee->phone}}"
                                                               class="fz-12 fw-medium">{{$employee->phone}}</a>
                                                        </div>
                                                    </td>

                                                    <td class="text-center"
                                                        data-bs-target="#exampleModal--{{$employee['id']}}"
                                                        data-bs-toggle="modal">
                                                        {{implode(',',$employee->roles->pluck('role_name')->toArray())}}
                                                    </td>

                                                    <td>
                                                        <label class="switcher mx-auto" data-bs-toggle="modal"
                                                               data-bs-target="#deactivateAlertModal">
                                                            <input class="switcher_input"
                                                                   type="checkbox"
                                                                   {{$employee->is_active?'checked':''}} data-status="{{$employee->id}}">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <a href="{{route('admin.employee.edit',[$employee->id])}}"
                                                               class="action-btn btn--light-primary"
                                                               style="--size: 30px">
                                                                <span class="material-icons">edit</span>
                                                            </a>
                                                            <button type="button" data-delete="{{$employee->id}}"
                                                                    class="action-btn btn--danger" style="--size: 30px">
                                                                <span class="material-symbols-outlined">delete</span>
                                                            </button>
                                                        </div>
                                                        <form
                                                            action="{{route('admin.employee.delete',[$employee->id])}}"
                                                            method="post" id="delete-{{$employee->id}}"
                                                            class="hidden">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="14"><p class="text-center">{{translate('no_data_available')}}</p></td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {!! $employees->links() !!}
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
    <script>
        "use strict";

        $('.switcher_input').on('click', function () {
            let itemId = $(this).data('status');
            let route = '{{ route('admin.employee.status-update', ['id' => ':itemId']) }}';
            route = route.replace(':itemId', itemId);
            route_alert(route, '{{ translate('want_to_update_status') }}');
        })

        $('.action-btn.btn--danger').on('click', function (event) {
            event.stopPropagation();
            let itemId = $(this).data('delete');
            @if(env('APP_ENV')!='demo')
            form_alert('delete-' + itemId, '{{translate('want_to_delete_this')}}?')
            @endif
        })

        $('.remove').on('click', function (event) {
            event.stopPropagation();
            let itemId = $(this).data('remove');
            @if(env('APP_ENV')!='demo')
            form_alert('delete-' + itemId, '{{translate('want_to_delete_this')}}?')
            @endif
        })
    </script>
@endpush
