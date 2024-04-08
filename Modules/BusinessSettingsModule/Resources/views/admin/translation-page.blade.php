@extends('adminmodule::layouts.master')

@section('title',translate('Language Setup'))

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Translated_Content ')}}</h2>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body pb-0">
                            <div
                                class="data-table-top d-flex align-items-center flex-wrap gap-10 justify-content-between mb-0">
                                <h5>{{translate('Language content table')}}</h5>
                                <form action="#"
                                      class="search-form search-form_style-two"
                                      method="GET">
                                    @csrf
                                    <div class="input-group search-form__input_group">
                                    <span class="search-form__icon">
                                        <span class="material-icons">search</span>
                                    </span>
                                        <input type="search" class="theme-input-style search-form__input" name="search"
                                               placeholder="{{translate('search_here')}}"
                                               value="{{ request()?->search ?? null }}">
                                    </div>
                                    <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                </form>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table id="example" class="table table-bordered align-middle">
                                    <thead class="text-nowrap bg-transparent">
                                    <tr>
                                        <th>{{translate('SL')}}</th>
                                        <th>{{translate('Current_Value')}}</th>
                                        <th>{{translate('Translated_Value')}}</th>
                                        <th>{{translate('Auto_Translate')}}</th>
                                        <th>{{translate('Update')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($count=0)
                                    @foreach($fullData as $key=>$value)
                                        @php($count++)
                                        <tr id="lang-{{$count}}">
                                            <td>{{ $count+$fullData->firstItem() -1}}</td>
                                            <td>
                                                <input type="text" name="key[]"
                                                       value="{{$key}}" hidden>
                                                <label>{{$key }}</label>
                                            </td>
                                            <td class="lan-key-name">
                                                <input type="text" class="form-control" name="value[]"
                                                       id="value-{{$count}}" value="{{$fullData[$key]}}">
                                            </td>
                                            <td>
                                                <button class="btn btn--light-primary auto-translate-data"
                                                        data-key="{{$key}}"
                                                        data-id="{{$count}}">
                                                    <span class="material-icons m-0">translate</span>
                                                </button>
                                            </td>
                                            <td>
                                                <button class="btn btn--primary update-language-data" type="button"
                                                        data-key="{{urlencode($key)}}"
                                                        data-id="{{$count}}">
                                                    <span class="material-icons m-0">save</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @if(count($fullData) !== 0)
                                    <hr>
                                @endif
                                <div class="page-area">
                                    <div class="d-flex justify-content-end">
                                        {!! $fullData->withQueryString()->links() !!}
                                    </div>
                                </div>
                                @if(count($fullData) === 0)
                                    <div class="empty--data text-center">
                                        <h5>
                                            {{translate('no_data_found')}}
                                        </h5>
                                    </div>
                                @endif
                            </div>
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

        $(".update-language-data").on('click', function(){
            let key = $(this).data('key');
            let id = $(this).data('id');
            let value = $('#value-' + id).val()
            update_lang(key, value);
        })

        function update_lang(key, value) {
            console.log(key);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.language.translate-submit',[$lang])}}",
                method: 'POST',
                data: {
                    key: key,
                    value: value
                },

                beforeSend: function () {
                    $('.preloader').show();
                },
                success: function (response) {
                    toastr.success('{{translate('text_updated_successfully')}}');
                },
                complete: function () {
                    $('.preloader').hide();
                },
            });
        }

        $(".auto-translate-data").on('click', function(){
            let key = $(this).data('key');
            let id = $(this).data('id');
            auto_translate(key, id);
        })

        function auto_translate(key, id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.language.auto-translate',[$lang])}}",
                method: 'POST',
                data: {
                    key: key
                },
                beforeSend: function () {
                    $('.preloader').show();
                },
                success: function (response) {
                    toastr.success('{{translate('Key translated successfully')}}');
                    console.log(response.translated_data)
                    $('#value-' + id).val(response.translated_data);
                },
                complete: function () {
                    $('.preloader').hide();
                },
            });
        }
    </script>
@endpush
