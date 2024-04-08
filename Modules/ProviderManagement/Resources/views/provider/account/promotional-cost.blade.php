@extends('providermanagement::layouts.master')

@section('title',translate('Commission_Info'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="page-title-wrap mb-3">
                <h2 class="page-title">{{translate('Account_Information')}}</h2>
            </div>

            <div class="mb-3">
                <ul class="nav nav--tabs nav--tabs__style2">
                    <li class="nav-item">
                        <a class="nav-link {{$pageType=='overview'?'active':''}}"
                           href="{{url()->current()}}?page_type=overview">{{translate('Overview')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$pageType=='commission-info'?'active':''}}"
                           href="{{url()->current()}}?page_type=commission-info">{{translate('Commission_Info')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$pageType=='review'?'active':''}}"
                           href="{{url()->current()}}?page_type=review">{{translate('Reviews')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$pageType=='promotional_cost'?'active':''}}"
                           href="{{url()->current()}}?page_type=promotional_cost">{{translate('Promotional_Cost')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$pageType=='withdraw_transaction'?'active':''}}"
                           href="{{route('provider.withdraw.list', ['page_type'=>'withdraw_transaction'])}}">{{translate('withdraw_list')}}</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="settings-tab-pane">
                    <div class="card">
                        <div class="card-body p-30">
                            <div class="mb-30">
                                <span>{{translate('Currently_you_are_using_the_following_percentages_as_the_promotional_cost-')}}</span>
                            </div>

                            <div class="mb-2">
                                @php($data = $promotionalCostPercentage->where('key_name', 'discount_cost_bearer')->first()->live_values ?? null)
                                {{translate('Discount_Cost_Percentage')}}: {{$data['provider_percentage']??null}}%
                            </div>

                            <div class="mb-2">
                                @php($data = $promotionalCostPercentage->where('key_name', 'campaign_cost_bearer')->first()->live_values ?? null)
                                {{translate('Campaign_Cost_Percentage')}}: {{$data['provider_percentage']??null}}%
                            </div>

                            <div class="mb-2">
                                @php($data = $promotionalCostPercentage->where('key_name', 'coupon_cost_bearer')->first()->live_values ?? null)
                                {{translate('Coupon_Cost_Percentage')}}: {{$data['provider_percentage']??null}}%
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
