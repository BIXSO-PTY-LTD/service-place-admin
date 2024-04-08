<div class="modal-header pb-0 border-0">
    <button type="button" class="btn-close" id="service-request-alert-close" data-bs-dismiss="modal"
            aria-label="Close"></button>
</div>
<div class="modal-body pb-4 px-lg-4">
    <div class="d-flex flex-column align-items-center">
        <img width="75" class="mb-3"
             src="{{asset('public/assets/provider-module')}}/img/icons/notification.png"
             alt="">

        <h3 class="text-primary text-center mb-3">{{translate('New Service Request')}}</h3>

        <div class="mb-4 text-center d-flex flex-column align-items-center">
            <div class="avatar avatar-lg mb-2">
                <img class="rounded"
                     src="{{onErrorImage(
                    $post?->customer?->profile_image,
                    asset('storage/app/public/user/profile_image').'/' . $post?->customer?->profile_image,
                    asset('public/assets/placeholder.png') ,
                    'user/profile_image/')}}"
                     alt="{{translate('image')}}">
            </div>
            <h5 class="text-primary">{{$post?->customer?->first_name.' '.$post?->customer?->last_name}}</h5>
            <div class="text-muted fs-12">
                @if($distance)
                    {{$distance . ' ' . translate('away from you')}}
                @endif
            </div>
        </div>

        <div class="media gap-2 mb-4">
            <img width="30"
                 src="{{onErrorImage(
                    $post?->sub_category?->image,
                    asset('storage/app/public/category').'/' . $post?->sub_category?->image,
                    asset('public/assets/placeholder.png') ,
                    'category/')}}"
                 alt="{{translate('image')}}">
            <div class="media-body">
                <h5>{{$post?->service?->name}}</h5>
                <div class="text-muted fs-12">{{$post?->sub_category?->name}}</div>
            </div>
        </div>

        <a href="{{route('provider.booking.post.list', ['type'=>'all'])}}" class="btn btn--primary">
            {{translate('Ok')}}, {{translate('Let me check')}}</a>
    </div>
</div>

<script>
    "use strict";

    $("#service-request-alert-close").click(function () {
        $.ajax({
            url: "{{route('provider.booking.post.check_all')}}",
            method: 'GET',
            success: function (response) {

            },
            error: function (xhr, status, error) {

            }
        });
    });
</script>
