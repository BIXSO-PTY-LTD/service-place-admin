@extends('providermanagement::layouts.master')

@section('title',translate('Service_Request_List'))

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3 d-flex gap-3 flex-wrap align-items-center justify-content-between">
                        <h2 class="page-title">{{translate('Service_Request_List')}}</h2>
                        <a class="btn btn--primary" href="{{route('provider.service.make-request')}}"><span class="material-icons">add</span> {{translate('Request')}}</a>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                        <div class="d-flex gap-2 fw-medium">
                            <span class="opacity-75">{{translate('Total_Requests')}}:</span>
                            <span class="title-color">{{$requests->total()}}</span>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body pb-5">
                            <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                <form action="{{url()->current()}}"
                                      class="search-form search-form_style-two"
                                      method="GET">
                                    <div class="input-group search-form__input_group">
                                            <span class="search-form__icon">
                                                <span class="material-icons">search</span>
                                            </span>
                                        <input type="search" class="theme-input-style search-form__input"
                                               value="{{$search??''}}" name="search"
                                               placeholder="{{translate('search_by_Category')}}">
                                    </div>
                                    <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="text-nowrap align-middle">
                                        <tr>
                                            <th>{{translate('SL')}}</th>
                                            <th>{{translate('Category')}}</th>
                                            <th>{{translate('Suggested Service ')}} <br> {{translate('Name')}}</th>
                                            <th>{{translate('Description')}}</th>
                                            <th>{{translate('Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($requests as $key=>$item)
                                            <tr>
                                                <td>{{$requests->firstitem()+$key}}</td>
                                                <td>
                                                    @if($item->category)
                                                        <span>{{translate($item->category->name)}}</span>
                                                    @else
                                                        <span>{{translate('Not available')}}</span>
                                                    @endif
                                                </td>
                                                <td>{{$item->service_name}}</td>
                                                <td>
                                                    <div class="max-w320 min-w180 text-justify" data-bs-toggle="modal" data-bs-target="#serviceRequestModal--{{$item['id']}}">
                                                        {{Str::limit($item->service_description, 150)}}
                                                    </div>

                                                    <div class="modal fade" id="serviceRequestModal--{{$item['id']}}" tabindex="-1" aria-labelledby="serviceRequestModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-body p-sm-4">
                                                                    <div class="mb-4">
                                                                        <h3 class="text-center">{{translate('service_Request_List')}}</h3>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>

                                                                    <div class="d-flex flex-column gap-2">
                                                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                                                            <span>{{translate('category')}} </span>:
                                                                            @if($item->category)
                                                                                <span>{{translate($item?->category->name)}}</span>
                                                                            @else
                                                                                <span>{{translate('Not available')}}</span>
                                                                            @endif
                                                                        </div>
                                                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                                                            <span>{{translate('service_Name')}} </span>:
                                                                            <span> {{$item->service_name}} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="c1-light-bg rounded py-2 px-3 mt-4 mb-3">
                                                                        <h5 class="fw-medium">{{translate('service_Description')}}</h5>
                                                                    </div>

                                                                    <p>{{$item->service_description}}</p>

                                                                    <div class="c1-light-bg rounded py-2 px-3 mt-4 mb-3">
                                                                        <h5 class="fw-medium">{{translate('given_Feedback')}}</h5>
                                                                    </div>
                                                                    <p class="{{ $item->admin_feedback ? '' : 'text-center' }}">{{$item->admin_feedback??translate('No feedback given yet')}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-actions">
                                                        @if($item->status == 'approved')
                                                            <button class="btn btn--success" data-bs-toggle="modal" data-bs-target="#modal--{{$key}}">{{translate('View Feedback')}}</button>
                                                        @elseif($item->status == 'denied')
                                                            <button class="btn btn--danger" data-bs-toggle="modal" data-bs-target="#modal--{{$key}}">{{translate('View Feedback')}}</button>
                                                        @elseif($item->status == 'pending')
                                                            <span class="badge-pill badge-info px-2 py-1 rounded lh-1">{{translate('Under review')}}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="modal--{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header align-items-start">
                                                            <div class="d-flex flex-column gap-1">
                                                                <h4 class="modal-title" id="exampleModalLabel">{{translate('Admin Feedback')}}</h4>
                                                                <div class="d-flex gap-1">
                                                                    <h5 class="text-muted">{{translate('Category Name')}} : </h5>
                                                                    <div class="fs-12">{{translate($item->category->name??'')}} </div>
                                                                </div>
                                                                <div class="d-flex gap-1">
                                                                    <h5 class="text-muted">{{translate('Service Name')}} : </h5>
                                                                    <div class="fs-12">{{$item->service_name??''}} </div>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-justify">
                                                            <p>{{$item->admin_feedback??translate('No feedback is available')}}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{translate('Close')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr class="text-center"><td colspan="5">{{translate('No request is available')}}</td></tr>
                                        @endforelse
                                     </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                {!! $requests->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
