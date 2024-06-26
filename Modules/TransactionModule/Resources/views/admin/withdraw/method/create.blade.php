@extends('adminmodule::layouts.master')

@section('title',translate('withdrawal_method'))

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div
                        class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                        <h2 class="page-title">{{translate('Withdrawal_Methods')}}</h2>
                        <button class="btn btn--primary" id="add-more-field">
                            <span class="material-icons">add</span> {{translate('Add_fields')}}
                        </button>
                    </div>

                    <div class="">
                        <form action="{{route('admin.withdraw.method.store')}}" method="POST">
                            @csrf
                            @php($language= Modules\BusinessSettingsModule\Entities\BusinessSettings::where('key_name','system_language')->first())
                            @php($default_lang = str_replace('_', '-', app()->getLocale()))
                            @if($language)
                                <ul class="nav nav--tabs border-color-primary mb-4">
                                    <li class="nav-item">
                                        <a class="nav-link lang_link active"
                                           href="#"
                                           id="default-link">{{translate('default')}}</a>
                                    </li>
                                    @foreach ($language?->live_values as $lang)
                                        <li class="nav-item">
                                            <a class="nav-link lang_link"
                                               href="#"
                                               id="{{ $lang['code'] }}-link">{{ get_language_name($lang['code']) }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            @if ($language)
                                <div class="form-floating form-floating__icon mb-30 lang-form" id="default-form">
                                    <input type="text" name="method_name[]" class="form-control"
                                           placeholder="{{translate('method_name')}}" required>
                                    <label>{{translate('method_name')}} ({{ translate('default') }})</label>
                                    <span class="material-icons">note_alt</span>
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                                @foreach ($language?->live_values as $lang)
                                    <div class="form-floating form-floating__icon mb-30 d-none lang-form" id="{{$lang['code']}}-form">
                                        <input type="text" name="method_name[]" class="form-control"
                                               placeholder="{{translate('method_name')}}">
                                        <label>{{translate('method_name')}} ({{strtoupper($lang['code'])}})</label>
                                        <span class="material-icons">note_alt</span>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                @endforeach
                            @else
                                <div class="form-floating form-floating__icon mb-30">
                                    <input type="text" name="method_name[]" class="form-control"
                                           placeholder="{{translate('method_name')}}" required>
                                    <label>{{translate('method_name')}}</label>
                                    <span class="material-icons">note_alt</span>
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                        @endif
                            <div class="mt-3">
                                <div id="custom-field-section">
                                    <div class="card card-body">
                                        <div class="row gy-4 align-items-center">
                                            <div class="col-md-6 col-12">
                                                <select class="form-control js-select" name="field_type[]" required>
                                                    <option value="" selected disabled>{{translate('Input Field Type')}} *</option>
                                                    <option value="string">{{translate('String')}}</option>
                                                    <option value="number">{{translate('Number')}}</option>
                                                    <option value="date">{{translate('Date')}}</option>
                                                    <option value="password">{{translate('Password')}}</option>
                                                    <option value="email">{{translate('Email')}}</option>
                                                    <option value="phone">{{translate('Phone')}}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-floating form-floating__icon">
                                                    <input type="text" class="form-control" name="field_name[]"
                                                           placeholder="{{translate('Select field name')}}" value="" required>
                                                    <label>{{translate('field_name')}} *</label>
                                                    <span class="material-icons">article</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-floating form-floating__icon">
                                                    <input type="text" class="form-control" name="placeholder_text[]"
                                                           placeholder="{{translate('Select placeholder text')}}" value="" required>
                                                    <label>{{translate('placeholder_text')}} *</label>
                                                    <span class="material-icons">edit_note</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1" name="is_required[0]" id="flexCheckDefault__0" checked>
                                                    <label class="form-check-label" for="flexCheckDefault__0">
                                                        {{translate('This_field_required')}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex my-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="is_default" id="flexCheckDefaultMethod">
                                        <label class="form-check-label" for="flexCheckDefaultMethod">
                                            {{translate('default_method')}}
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn--secondary mx-2">{{translate('Reset')}}</button>
                                    <button type="submit" class="btn btn--primary demo_check">{{translate('Submit')}}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        "use strict";

        function remove_field(fieldRowId) {
            $( `#field-row--${fieldRowId}` ).remove();
            counter--;
        }

        jQuery(document).ready(function ($) {
            var counter = 1;

            $(document).on('click', '.remove-field-btn', function () {
                var counter = $(this).data('counter');
                remove_field(counter);
            });

            $('#add-more-field').on('click', function (event) {
                if(counter < 15) {
                    event.preventDefault();

                    $('#custom-field-section').append(
                        `<div class="card card-body mt-3" id="field-row--${counter}">
                            <div class="row gy-4 align-items-center">
                                <div class="col-md-6 col-12">
                                    <select class="form-control js-select" name="field_type[]" required>
                                        <option value="" selected disabled>{{translate('Input Field Type')}} *</option>
                                        <option value="string">{{translate('String')}}</option>
                                        <option value="number">{{translate('Number')}}</option>
                                        <option value="date">{{translate('Date')}}</option>
                                        <option value="password">{{translate('Password')}}</option>
                                        <option value="email">{{translate('Email')}}</option>
                                        <option value="phone">{{translate('Phone')}}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-floating form-floating__icon">
                                        <input type="text" class="form-control" name="field_name[${counter}]"
                                               placeholder="{{translate('Select field name')}}" value="" required>
                                        <label>{{translate('field_name')}} *</label>
                                        <span class="material-icons">article</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-floating form-floating__icon">
                                        <input type="text" class="form-control" name="placeholder_text[${counter}]"
                                               placeholder="{{translate('Select placeholder text')}}" value="" required>
                                        <label>{{translate('placeholder_text')}} *</label>
                                        <span class="material-icons">edit_note</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="is_required[${counter}]" id="flexCheckDefault__${counter}" checked>
                                        <label class="form-check-label" for="flexCheckDefault__${counter}">
                                            {{translate('This_field_required')}}
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <span class="btn btn--danger remove-field-btn" data-counter="${counter}">
                                        <span class="material-icons">delete</span>
                                            {{translate('Remove')}}
                                    </span>
                                </div>
                            </div>
                        </div>`
                    );

                    $(".js-select").select2();

                    counter++;
                } else {
                    Swal.fire({
                        title: '{{translate('Reached maximum')}}',
                        confirmButtonText: '{{translate('ok')}}',
                    });
                }
            })

            $('form').on('reset', function (event) {
                if(counter > 1) {
                    $('#custom-field-section').html("");
                    $('#method_name').val("");
                }

                counter = 1;
            })
        });

        $(".lang_link").on('click', function (e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang-form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.substring(0, form_id.length - 5);
            $("#" + lang + "-form").removeClass('d-none');
        });
    </script>


@endpush
