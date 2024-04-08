@extends('adminmodule::layouts.master')

@section('title', translate('Request_List'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-wrap mb-3">
                    <h2 class="page-title">{{translate('Customized Booking Requests')}}</h2>
                </div>

                <div
                    class="d-flex flex-wrap justify-content-between align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                    <ul class="nav nav--tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{$type=='all'?'active':''}}"
                               href="{{url()->current()}}?type=all">{{translate('All')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$type=='new_booking_request'?'active':''}}"
                               href="{{url()->current()}}?type=new_booking_request">{{translate('No-Bid Request Yet')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$type=='placed_offer'?'active':''}}"
                               href="{{url()->current()}}?type=placed_offer">{{translate('Already Bid Requested')}}</a>
                        </li>
                    </ul>

                    <div class="d-flex gap-2 fw-medium">
                        <span class="opacity-75">{{translate('Total Customized Booking')}} : </span>
                        <span class="title-color">{{$posts->total()}}</span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                            <form action="{{url()->current()}}?type={{$type}}" method="POST"
                                  class="search-form search-form_style-two">
                                @csrf
                                <div class="input-group search-form__input_group">
                                        <span class="search-form__icon">
                                            <span class="material-icons">search</span>
                                        </span>
                                    <input type="search" class="theme-input-style search-form__input fz-10"
                                           name="search"
                                           value="{{$search??''}}"
                                           placeholder="{{translate('Search by customer info')}}">
                                </div>
                                <button type="submit" class="btn btn--primary text-capitalize">
                                    {{translate('Search')}}</button>
                            </form>

                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <div class="dropdown">
                                    <button type="button"
                                            class="btn btn--secondary text-capitalize dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                        <span class="material-icons">file_download</span> {{translate('download')}}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                        <li><a class="dropdown-item"
                                               href="{{route('admin.booking.post.export', ['type'=>$type, 'search' => $search??''])}}">{{translate('Excel')}}</a>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>

                        <div class="select-table-wrap">
                            <div
                                class="multiple-select-actions gap-3 flex-wrap align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-wrap gap-2 gap-lg-4">
                                    <div class="ms-sm-1">
                                        <input type="checkbox" class="multi-checker">
                                    </div>
                                    <p><span class="checked-count">2</span> {{translate('Item_Selected')}}</p>
                                </div>

                                <div class="d-flex align-items-center flex-wrap gap-3">
                                    <button class="btn btn--danger" id="multi-remove">{{translate('Delete')}}</button>
                                </div>
                            </div>
                            <div class="table-responsive position-relative">
                                <table class="table align-middle multi-select-table multi-select-table-booking">
                                    <thead>
                                    <tr>
                                        @if($type == 'new_booking_request')
                                            <th></th>
                                        @endif
                                        @if($type != 'new_booking_request')
                                            <th>{{translate('Booking ID')}}</th>
                                        @endif
                                        <th>{{translate('Customer Info')}}</th>
                                        <th>{{translate('Booking Request Time')}}</th>
                                        <th>{{translate('Service Time')}}</th>
                                        <th>{{translate('Category')}}</th>
                                        <th>{{translate('Provider Offering')}}</th>
                                        <th class="text-center">{{translate('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($posts as $key=>$post)
                                        <tr>
                                            @if($type == 'new_booking_request')
                                                <td><input type="checkbox" class="multi-check" value="{{$post->id}}">
                                                </td>
                                            @endif
                                            @if($type != 'new_booking_request')
                                                @if($post->booking)
                                                    <td>
                                                        <a href="{{route('admin.booking.details', [$post?->booking->id,'web_page'=>'details'])}}">{{$post?->booking->readable_id}}</a>
                                                    </td>
                                                @else
                                                    <td><small
                                                            class="badge-pill badge-primary">{{translate('Not Booked Yet')}}</small>
                                                    </td>
                                                @endif
                                            @endif
                                            <td>
                                                @if($post->customer)
                                                    <div>
                                                        <div class="customer-name fw-medium">
                                                            {{$post->customer?->first_name.' '.$post->customer?->last_name}}
                                                        </div>
                                                        <a href="tel:{{$post->customer?->phone}}"
                                                           class="fs-12">{{$post->customer?->phone}}</a>
                                                    </div>
                                                @else
                                                    <div><small
                                                            class="disabled">{{translate('Customer not available')}}</small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <div>{{$post->created_at->format('Y-m-d')}}</div>
                                                    <div>{{$post->created_at->format('h:ia')}}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div>{{date('d-m-Y',strtotime($post->booking_schedule))}}</div>
                                                    <div>{{date('h:ia',strtotime($post->booking_schedule))}}</div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($post->category)
                                                    {{$post->category?->name}}
                                                @else
                                                    <div><small
                                                            class="disabled">{{translate('Category not available')}}</small>
                                                    </div>
                                                @endif
                                            </td>
                                            @php($bids = $post->bids)
                                            <td>
                                                <div class="dropdown-hover">
                                                    <div class="dropdown-hover-toggle"
                                                         data-bs-toggle="dropdown">
                                                        {{$bids->count() ?? 0}} {{translate('Providers')}}
                                                    </div>

                                                    @if($bids->count() > 0)
                                                        <ul class="dropdown-hover-menu">
                                                            @foreach($bids as $bid)
                                                                <li>
                                                                    <div class="media gap-3">
                                                                        <div class="avatar border rounded"
                                                                             data-bs-toggle="modal"
                                                                             data-bs-target="#providerInfoModal--{{$bid->id}}">
                                                                            <img class="rounded"
                                                                                 src="{{onErrorImage(
                                                                                            $bid->provider?->logo,
                                                                                            asset('storage/app/public/provider/logo').'/' . $bid->provider?->logo,
                                                                                            asset('public/assets/admin-module/img/placeholder.png') ,
                                                                                            'provider/logo/')}}"
                                                                                 alt="{{ translate('logo') }}">
                                                                        </div>
                                                                        <div class="media-body">
                                                                            @if($bid->provider)
                                                                                <h5 data-bs-toggle="modal"
                                                                                    data-bs-target="#providerInfoModal--{{$bid->id}}">{{$bid->provider->company_name}}</h5>
                                                                            @else
                                                                                <small>{{translate('Provider not available')}}</small>
                                                                            @endif
                                                                            <div
                                                                                class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                                                            <span
                                                                                                class="text-danger">{{translate('price offered')}}</span>
                                                                                <h5 class="text-primary">{{with_currency_symbol($bid->offered_price)}}</h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="d-flex gap-2 justify-content-center">
                                                <a href="{{route('admin.booking.post.details', [$post->id])}}"
                                                   type="button"
                                                   class="action-btn btn--light-primary fw-medium text-capitalize fz-14" style="--size: 30px">
                                                    <span class="material-icons">visibility</span>
                                                </a>

                                                @if(!$post->booking)
                                                    <a type="button" class="action-btn btn--danger booking-deny"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#exampleModal--{{$post['id']}}"  style="--size: 30px">
                                                        <span class="material-symbols-outlined">delete</span>
                                                    </a>
                                                    <div class="modal fade" id="exampleModal--{{$post['id']}}"
                                                         tabindex="-1"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-body pt-5 p-md-5">
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                                                                    <div class="d-flex justify-content-center mb-4">
                                                                        <img width="75" height="75" src="{{asset('public/assets/admin-module/img/media/delete.png')}}" class="rounded-circle" alt="">
                                                                    </div>

                                                                    <h3 class="text-center mb-1 fw-medium">{{translate('Are you sure you want to delete the post?')}}</h3>
                                                                    <p class="text-center fs-12 fw-medium text-muted">{{translate('You will lost the custom booking request?')}}</p>
                                                                    <form method="post"
                                                                          action="{{route('admin.booking.post.delete', [$post->id])}}">
                                                                        @csrf
                                                                        <div class="form-floating">
                                                                            <textarea class="form-control h-69px"
                                                                                  placeholder="{{translate('Cancellation Note')}}"
                                                                                  name="post_delete_note" required
                                                                                  id="add-your-notes"></textarea>
                                                                            <label for="add-your-notes"
                                                                                   class="d-flex align-items-center gap-1">
                                                                                {{translate('Cancellation Note')}}
                                                                            </label>
                                                                        </div>
                                                                        <div class="d-flex justify-content-center gap-3 mt-3">
                                                                            <button type="button"
                                                                                    class="btn btn--secondary"
                                                                                    data-bs-dismiss="modal">{{translate('cancel')}}</button>
                                                                            <button type="submit"
                                                                                    class="btn btn-danger">{{translate('Delete')}}</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>

                                        @foreach($bids as $bid)
                                            <div class="modal fade"
                                                 id="providerInfoModal--{{$bid->id}}" tabindex="-1"
                                                 aria-labelledby="providerInfoModalLabel"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-lg bs-modal-width">
                                                    <div class="modal-content">
                                                        <div class="modal-header px-sm-4">
                                                            <h4 class="modal-title text-primary"
                                                                id="providerInfoModalLabel">{{translate('Provider Information')}}</h4>
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body pb-4 px-lg-4">
                                                            <div
                                                                class="media flex-column flex-sm-row flex-wrap gap-3">
                                                                <img width="173" class="radius-10"
                                                                     src="{{onErrorImage(
                                                                            $bid?->provider?->logo,
                                                                            asset('storage/app/public/provider/logo').'/' . $bid?->provider?->logo,
                                                                            asset('public/assets/placeholder.png') ,
                                                                            'provider/logo/')}}"
                                                                     alt="{{ translate('provider-logo') }}">
                                                                <div class="media-body">
                                                                    <h5 class="fw-medium mb-1">{{$bid->provider?->company_name}}</h5>
                                                                    <div
                                                                        class="fs-12 d-flex flex-wrap align-items-center gap-2 mt-1">
                                                                            <span
                                                                                class="common-list_rating d-flex gap-1">
                                                                                <span
                                                                                    class="material-icons text-primary fs-12">star</span>
                                                                                {{$bid->provider?->avg_rating}}
                                                                            </span>
                                                                        <span>{{$bid->provider?->rating_count}} {{translate('Reviews')}}</span>
                                                                    </div>

                                                                    <div
                                                                        class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1 mb-3">
                                                                            <span
                                                                                class="text-danger">{{translate('Price Offered')}}</span>
                                                                        <h4 class="text-primary">{{with_currency_symbol($bid->offered_price)}}</h4>
                                                                    </div>
                                                                    @if($bid->provider_note)
                                                                        <h3 class="text-muted mb-2">{{translate('Description')}}
                                                                            :</h3>
                                                                        <p>{{$bid->provider_note}}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="7">{{translate('No data available')}}</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            {!! $posts->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";

        $('#multi-remove').on('click', function () {
            var request_ids = [];
            $('input:checkbox.multi-check').each(function () {
                if (this.checked) {
                    request_ids.push($(this).val());
                }
            });

            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: "{{translate('Do you really want to remove the selected requests')}}?",
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
                        url: "{{route('admin.booking.post.multi-remove')}}",
                        data: {
                            post_ids: request_ids,
                        },
                        type: 'post',
                        success: function (response) {
                            toastr.success(response.message)
                            setTimeout(location.reload.bind(location), 1000);
                        },
                        error: function () {

                        }
                    });
                }
            })

        });
    </script>
@endpush
