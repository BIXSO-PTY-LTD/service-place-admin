@extends('adminmodule::layouts.master')

@section('title',translate('Expense_Report'))

@push('css_or_js')
@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Business Reports')}}</h2>
                    </div>

                    <div class="mb-4">
                        <ul class="nav nav--tabs nav--tabs__style2">
                            <li class="nav-item">
                                <a href="{{route('admin.report.business.overview')}}"
                                   class="nav-link">{{translate('Overview')}}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.report.business.earning')}}"
                                   class="nav-link">{{translate('Earning_Report')}}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.report.business.expense')}}"
                                   class="nav-link active">{{translate('Expense_Report')}}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 fz-16">{{translate('Search_Data')}}</div>

                            <form action="{{route('admin.report.business.expense')}}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 mb-30">
                                        <label class="mb-2">{{translate('zone')}}</label>
                                        <select class="js-select zone__select" name="zone_ids[]" id="zone_selector__select" multiple>
                                            <option value="all">{{translate('Select All')}}</option>
                                            @foreach($zones as $zone)
                                                <option
                                                    value="{{$zone['id']}}" {{array_key_exists('zone_ids', $queryParams) && in_array($zone['id'], $queryParams['zone_ids']) ? 'selected' : '' }}>{{$zone['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 mb-30">
                                        <label class="mb-2">{{translate('category')}}</label>
                                        <select class="js-select category__select" name="category_ids[]" id="category_selector__select" multiple>
                                            <option value="all">{{translate('Select All')}}</option>
                                            @foreach($categories as $category)
                                                <option
                                                    value="{{$category['id']}}" {{array_key_exists('category_ids', $queryParams) && in_array($category['id'], $queryParams['category_ids']) ? 'selected' : '' }}>{{$category['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 mb-30">
                                        <label class="mb-2">{{translate('sub_category')}}</label>
                                        <select class="js-select sub-category__select" name="sub_category_ids[]" id="sub_category_selector__select"
                                                multiple>
                                            <option value="all">{{translate('Select All')}}</option>
                                            @foreach($sub_categories as $sub_category)
                                                <option
                                                    value="{{$sub_category['id']}}" {{array_key_exists('sub_category_ids', $queryParams) && in_array($sub_category['id'], $queryParams['sub_category_ids']) ? 'selected' : '' }}>{{$sub_category['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 mb-30">
                                        <label class="mb-2">{{translate('date_range')}}</label>
                                        <select class="js-select" id="date-range" name="date_range">
                                            <option value="0" disabled selected>{{translate('Date_Range')}}</option>
                                            <option
                                                value="all_time" {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='all_time'?'selected':''}}>{{translate('All_Time')}}</option>
                                            <option
                                                value="this_week" {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='this_week'?'selected':''}}>{{translate('This_Week')}}</option>
                                            <option
                                                value="last_week" {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='last_week'?'selected':''}}>{{translate('Last_Week')}}</option>
                                            <option
                                                value="this_month" {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='this_month'?'selected':''}}>{{translate('This_Month')}}</option>
                                            <option
                                                value="last_month" {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='last_month'?'selected':''}}>{{translate('Last_Month')}}</option>
                                            <option
                                                value="last_15_days" {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='last_15_days'?'selected':''}}>{{translate('Last_15_Days')}}</option>
                                            <option
                                                value="this_year" {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='this_year'?'selected':''}}>{{translate('This_Year')}}</option>
                                            <option
                                                value="last_year" {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='last_year'?'selected':''}}>{{translate('Last_Year')}}</option>
                                            <option
                                                value="last_6_month" {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='last_6_month'?'selected':''}}>{{translate('Last_6_Month')}}</option>
                                            <option
                                                value="this_year_1st_quarter" {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='this_year_1st_quarter'?'selected':''}}>{{translate('This_Year_1st_Quarter')}}</option>
                                            <option
                                                value="this_year_2nd_quarter" {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='this_year_2nd_quarter'?'selected':''}}>{{translate('This_Year_2nd_Quarter')}}</option>
                                            <option
                                                value="this_year_3rd_quarter" {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='this_year_3rd_quarter'?'selected':''}}>{{translate('This_Year_3rd_Quarter')}}</option>
                                            <option
                                                value="this_year_4th_quarter" {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='this_year_4th_quarter'?'selected':''}}>{{translate('this_year_4th_quarter')}}</option>
                                            <option
                                                value="custom_date" {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='custom_date'?'selected':''}}>{{translate('Custom_Date')}}</option>
                                        </select>
                                    </div>
                                    <div
                                        class="col-lg-4 col-sm-6 {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='custom_date'?'':'d-none'}}"
                                        id="from-filter__div">
                                        <div class="form-floating mb-30">
                                            <input type="date" class="form-control" id="from" name="from"
                                                   value="{{array_key_exists('from', $queryParams)?$queryParams['from']:''}}">
                                            <label for="from">{{translate('From')}}</label>
                                        </div>
                                    </div>
                                    <div
                                        class="col-lg-4 col-sm-6 {{array_key_exists('date_range', $queryParams) && $queryParams['date_range']=='custom_date'?'':'d-none'}}"
                                        id="to-filter__div">
                                        <div class="form-floating mb-30">
                                            <input type="date" class="form-control" id="to" name="to"
                                                   value="{{array_key_exists('to', $queryParams)?$queryParams['to']:''}}">
                                            <label for="to">{{translate('To')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit"
                                                class="btn btn--primary btn-sm">{{translate('Filter')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row flex-wrap gap-4 mt-4">
                        <div class="card flex-row gap-4 p-30 flex-wrap flex-grow-1">
                            <img width="35" class="avatar"
                                    src="{{asset('public/assets/admin-module')}}/img/icons/total_expenses.png"
                                    alt="">
                            <div>
                                <h2 class="fz-26">{{with_currency_symbol($total_promotional_cost['total_expense'])}}</h2>
                                <span class="fz-12">{{translate('Total_Expenses')}}</span>
                            </div>
                        </div>

                        <div class="card flex-row gap-4 p-30 flex-wrap flex-grow-1">
                            <img width="35" class="avatar"
                                    src="{{asset('public/assets/admin-module')}}/img/icons/discount.png" alt="">
                            <div>
                                <h2 class="fz-26">{{with_currency_symbol($total_promotional_cost['discount'])}}</h2>
                                <span class="fz-12">{{translate('Normal_Service_Discount')}}</span>
                            </div>
                        </div>

                        <div class="card flex-row gap-4 p-30 flex-wrap flex-grow-1">
                            <img width="35" class="avatar"
                                    src="{{asset('public/assets/admin-module')}}/img/icons/campaign_discount.png"
                                    alt="">
                            <div>
                                <h2 class="fz-26">{{with_currency_symbol($total_promotional_cost['campaign'])}}</h2>
                                <span class="fz-12">{{translate('Campaign_Discount')}}</span>
                            </div>
                        </div>

                        <div class="card flex-row gap-4 p-30 flex-wrap flex-grow-1">
                            <img width="35" class="avatar"
                                    src="{{asset('public/assets/admin-module')}}/img/icons/coupon_discount.png"
                                    alt="">
                            <div>
                                <h2 class="fz-26">{{with_currency_symbol($total_promotional_cost['coupon'])}}</h2>
                                <span class="fz-12">{{translate('Coupon_Discount')}}</span>
                            </div>
                        </div>

                        <div class="card flex-row gap-4 p-30 flex-wrap flex-grow-1">
                            <img width="35" class="avatar"
                                    src="{{asset('public/assets/admin-module')}}/img/icons/extra_discount.png"
                                    alt="">
                            <div>
                                <h2 class="fz-26">{{with_currency_symbol($total_promotional_cost['bonus'])}}</h2>
                                <span class="fz-12 text-capitalize">{{translate('bonus_discount')}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body ps-0">
                            <h4 class="ps-20">{{translate('Booking_Expense_Statistics')}}</h4>
                            <div id="apex_column-chart"></div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
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
                                               placeholder="{{translate('search_by_Booking_ID')}}">
                                    </div>
                                    <button type="submit"
                                            class="btn btn--primary">{{translate('search')}}</button>
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
                                                   href="{{route('admin.report.business.expense.download').'?'.http_build_query($queryParams)}}">{{translate('Excel')}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="text-nowrap">
                                    <tr>
                                        <th>{{translate('SL')}}</th>
                                        <th>{{translate('Booking_ID')}}</th>
                                        <th>{{translate('Normal_Discount')}}</th>
                                        <th>{{translate('Coupon_Discount')}}</th>
                                        <th>{{translate('Campaign_Discount')}}</th>
                                        <th class="text--end">{{translate('Total_Expense')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($filtered_booking_amounts as $key=>$amount)
                                        @php($total_expense = $amount['discount_by_admin']+$amount['coupon_discount_by_admin']+$amount['campaign_discount_by_admin'])
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$amount->booking->readable_id}}</td>
                                            <td>{{with_currency_symbol($amount['discount_by_admin'])}}</td>
                                            <td>{{with_currency_symbol($amount['coupon_discount_by_admin'])}}</td>
                                            <td>{{with_currency_symbol($amount['campaign_discount_by_admin'])}}</td>
                                            <td class="text--end">{{with_currency_symbol($total_expense)}}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="6">{{translate('Data_not_available')}}</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                {!! $filtered_booking_amounts->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

    <script src="{{asset('public/assets/admin-module')}}/plugins/apex/apexcharts.min.js"></script>

    <script>
        "use strict";

        $('#zone_selector__select').on('change', function() {
            var selectedValues = $(this).val();
            if (selectedValues !== null && selectedValues.includes('all')) {
                $(this).find('option').not(':disabled').prop('selected', 'selected');
                $(this).find('option[value="all"]').prop('selected', false);
            }
        });

        $('#category_selector__select').on('change', function() {
            var selectedValues = $(this).val();
            if (selectedValues !== null && selectedValues.includes('all')) {
                $(this).find('option').not(':disabled').prop('selected', 'selected');
                $(this).find('option[value="all"]').prop('selected', false);
            }
        });

        $('#sub_category_selector__select').on('change', function() {
            var selectedValues = $(this).val();
            if (selectedValues !== null && selectedValues.includes('all')) {
                $(this).find('option').not(':disabled').prop('selected', 'selected');
                $(this).find('option[value="all"]').prop('selected', false);
            }
        });

        $(document).ready(function () {
            $('.zone__select').select2({
                placeholder: "{{translate('Select_zone')}}",
            });
            $('.category__select').select2({
                placeholder: "{{translate('Select_category')}}",
            });
            $('.sub-category__select').select2({
                placeholder: "{{translate('Select_sub_category')}}",
            });
        });

        $(document).ready(function () {
            $('#date-range').on('change', function () {
                if (this.value === 'custom_date') {
                    $('#from-filter__div').removeClass('d-none');
                    $('#to-filter__div').removeClass('d-none');
                }

                if (this.value !== 'custom_date') {
                    $('#from-filter__div').addClass('d-none');
                    $('#to-filter__div').addClass('d-none');
                }
            });
        });

        var options = {
            series: [
                {
                    name: '{{translate('normal_discount')}}',
                    data: {{json_encode($chart_data['normal_discount'])}}
                },
                {
                    name: '{{translate('campaign_discount')}}',
                    data: {{json_encode($chart_data['campaign_discount'])}}
                },
                {
                    name: '{{translate('coupon_discount')}}',
                    data: {{json_encode($chart_data['coupon_discount'])}}
                },
                {
                    name: '{{translate('expenses')}}',
                    data: {{json_encode($chart_data['expenses'])}}
                },
                {
                    name: '{{translate('bonus')}}',
                    data: {{json_encode($chart_data['bonus'])}}
                },
            ],
            chart: {
                type: 'bar',
                height: 511
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55px',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: {{json_encode($chart_data['timeline'])}}
            },
            yaxis: {
                title: {
                    text: '$ '
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "$ " + val + " "
                    }
                }
            },
            legend: {
                show: true
            },
        };

        var chart = new ApexCharts(document.querySelector("#apex_column-chart"), options);
        chart.render();
    </script>
@endpush
