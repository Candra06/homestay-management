@extends('template.app')

@section('title')
    {{ $data->title }}
@endsection
@section('main')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <span class="text-muted mt-1 tx-13 ms-2 mb-0">Dashboard / {{ $data->subtitle }} / <strong
                        class="text-black">{{ $data->title }}</strong>
                </span>

            </div>

        </div>

    </div>

    <x-alert />

    <div class="card  box-shadow-0">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $data->title }}</h4>
            {{-- <p class="mb-2">It is Very Easy to Customize and it uses in your website apllication.</p> --}}
        </div>
        <div class="card-body pt-0">
            <form enctype="multipart/form-data"
                class="form-horizontal row {{ $errors->any() && 'needs-validation was-validated' }}"
                action="{{ $data->action }}" method="POST">
                @if ($data->type != 'add')
                    @method('PUT')
                @endif
                @csrf
                @php
                    $dataarray = $data->data;

                @endphp
                @foreach ($data->forms as $item)
                    @php
                        $currentValue =
                            $item['type'] == 'currency' && isset($dataarray[$item['name']])
                                ? App\Helper\Helpers::rupiah($dataarray[$item['name']], '')
                                : $dataarray[$item['name']] ?? old($item['name']);
                    @endphp
                    <div
                        class="form-group has-success {{ isset($item['custom-class-wrapper']) ? $item['custom-class-wrapper'] : 'col-md-6 col-12' }}">
                        @if (in_array($item['type'], [
                                'text',
                                'number',
                                'email',
                                'currency',
                                'hidden',
                                'date',
                                'datetime-local',
                                'label',
                                'time',
                            ]))
                            @if ($item['type'] != 'hidden')
                                <label for="{{ $item['name'] }}">{{ $item['title'] }} {!! $item['required']?'<span class="tx-danger">*</span>':''!!}</label>
                            @endif
                            @if ($item['type'] == 'label')
                                <p lass="text-blacktext-sm font-medium inline-block mb-2">{{ $currentValue }}</p>
                            @else
                                <input type="{{ $item['type'] }}" class="form-control {{ $item['class_input'] ?? '' }}"
                                    id="{{ $item['name'] }}" {{ $item['required'] ?? false ? ' required ' : '' }}
                                    {{ isset($item['other-attr']) ? $item['other-attr'] : '' }}
                                    value="{{ $currentValue }}" name="{{ $item['name'] }}"
                                    type="{{ $item['type'] == 'currency' ? 'text' : $item['type'] }}"
                                    {{ $item['readonly'] ?? false ? ' readonly' : '' }}
                                    placeholder="{{ $item['placeholder'] ?? '' }}" />
                            @endif
                        @elseif(in_array($item['type'], ['password']))
                            <label for="{{ $item['name'] }}">{{ $item['title'] }} {!! $item['required']?'<span class="tx-danger">*</span>':''!!}</label>
                            <input type="password" class="form-control {{ $item['class_input'] ?? '' }}"
                                id="{{ $item['name'] }}" {{ $item['required'] ?? false ? ' required ' : '' }}
                                {{ isset($item['other-attr']) ? $item['other-attr'] : '' }} value="{{ $currentValue }}"
                                name="{{ $item['name'] }}"
                                type="{{ $item['type'] == 'currency' ? 'text' : $item['type'] }}"
                                {{ $item['readonly'] ?? false ? ' readonly' : '' }}
                                placeholder="{{ $item['placeholder'] ?? '' }}" />
                        @elseif (in_array($item['type'], ['textarea', 'editor']))
                            <label for="{{ $item['name'] }}">{{ $item['title'] }} {!! $item['required']?'<span class="tx-danger">*</span>':''!!}</label>
                            <textarea class="form-control {{ $item['class_input'] ?? '' }}" id="{{ $item['name'] }}"
                                {{ $item['readonly'] ?? false ? ' readonly' : '' }} placeholder="{{ $item['placeholder'] ?? '' }}"
                                {{ $item['required'] ?? false ? ' required ' : '' }} {{ isset($item['other-attr']) ? $item['other-attr'] : '' }}
                                type="{{ $item['type'] }}" name="{{ $item['name'] }}"
                                value="{{ $dataarray[$item['name']] ?? old($item['name']) }}">{{ $dataarray[$item['name']] ?? old($item['name']) }}</textarea>
                        @elseif (in_array($item['type'], ['select']))
                            @php
                                $value = $dataarray[$item['name']] ?? old($item['name']);
                            @endphp
                            <label for="{{ $item['name'] }}">{{ $item['title'] }} {!! $item['required']?'<span class="tx-danger">*</span>':''!!}</label>
                            <select name="{{ $item['name'] }}" class="form-control {{ $item['class'] }}"
                                {{ $item['required'] ?? false ? ' required ' : '' }}
                                {{ $item['readonly'] ?? false ? ' readonly' : '' }}
                                placeholder="{{ $item['placeholder'] }}" {{ $item['required'] ? ' required ' : '' }}
                                value="{{ $value }}">
                                <option value="">{{ $item['placeholder'] }}</option>
                                @foreach ($item['data'] as $it)
                                    <option value="{{ $it['id'] }}" {{ $it['id'] == $value ? 'selected' : '' }}>
                                        {{ $it['val'] }}
                                    </option>
                                @endforeach
                            </select>
                        @elseif (in_array($item['type'], ['checkbox']))
                            <div class="form-check">

                                <input type="checkbox" class="form-check-input {{ $item['class_input'] ?? '' }}"
                                    id="{{ $item['name'] }}" {{ $item['required'] ?? false ? ' required ' : '' }}
                                    {{ isset($item['other-attr']) ? $item['other-attr'] : '' }}
                                    value="{{ $item['value'] }}" {{ $currentValue == $item['value'] ? 'checked' : '' }}
                                    name="{{ $item['name'] }}" />
                                <label class="form-check-label" for="{{ $item['name'] }}">{{ $item['title'] }}</label>
                            </div>
                        @elseif(in_array($item['type'], ['file']))
                            @php
                                $isMultiple = isset($item['other-attr']) && str_contains($item['other-attr'], 'multiple');
                                $classInput = $item['class_input'] ?? '';
                                if ($isMultiple) {
                                    $classInput = trim(str_replace('dropify', '', $classInput));
                                }
                            @endphp
                            <label for="{{ $item['name'] }}">{{ $item['title'] }}</label><br>
                            @if ($isMultiple)
                                <small class="text-secondary">Anda dapat memilih lebih dari 1 file. Urutan pertama pada list akan dijadikan sebagai gambar utama.
                                </small>
                            @endif
                            <input type="file" accept=".jpg, .png, image/jpeg, image/png" class="{{ $classInput }}"
                                id="{{ $isMultiple ? 'demo' : $item['name'] }}" {{ $item['required'] ?? false ? ' required ' : '' }}
                                {{ isset($item['other-attr']) ? $item['other-attr'] : '' }}
                                @if(!$isMultiple && !empty($currentValue))
                                    data-default-file="{{ asset($currentValue) }}"
                                @endif
                                name="{{ $item['name'] }}"
                                {{ $item['readonly'] ?? false ? ' readonly' : '' }}
                                placeholder="{{ $item['placeholder'] ?? '' }}" />

                            @if(isset($dataarray['attachments']) && count($dataarray['attachments']) > 0)
                                <div class="mt-3 mb-2 p-3 bg-light rounded border">
                                    <label class="fw-semibold text-dark mb-2 d-block">Foto saat ini:</label>
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        @foreach($dataarray['attachments'] as $idx => $att)
                                            @php
                                                $imgUrl = str_starts_with($att['file_url'], 'storage/') ? asset($att['file_url']) : asset('storage/' . $att['file_url']);
                                            @endphp
                                            <div class="position-relative d-inline-block border rounded bg-white p-1 me-2 mb-2 shadow-sm" style="width: 105px; height: 105px;">
                                                <img src="{{ $imgUrl }}" class="rounded w-100 h-100" style="object-fit: cover;" alt="Preview foto {{ $idx + 1 }}">
                                                @if($idx === 0)
                                                    <span class="badge bg-primary position-absolute top-0 start-0 m-1 shadow-sm" style="font-size: 10px;">Utama</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="text-muted d-block mt-1 tx-12">
                                        <i class="fe fe-info me-1"></i> Upload foto baru di atas jika Anda ingin mengganti seluruh foto saat ini.
                                    </small>
                                </div>
                            @endif
                        @endif

                        @error($item['name'])
                            <small class=" text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                @endforeach
                @if (isset($data->custom_element))
                    {!! $data->custom_element !!}
                @endif
                <div class="d-flex form-group has-success col-12 mb-0 mt-3 justify-content-end">
                    <div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        @if (isset($data->custom_button))
                            {!! $data->custom_button !!}
                        @endif

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        window.addEventListener("load", function(event) {
            $(document).ready(function() {
                const selectName = $('.select2');
                const elmSelectWInnerCodeBs = ".winner-select-code-bs";
                initForm()
                // initializeSelect2($('.select2').attr('name'),$('.select2').attr('placeholder'))
            });
            $('<style>')
                .html(`
                .select2-readonly .select2-selection {
                pointer-events: none;
                background-color: #eee;
                }
                .ff_fileupload_wrap .ff_fileupload_start_upload {
                    display: none !important;
                }
                .ff_fileupload_wrap table.ff_fileupload_uploads td.ff_fileupload_actions {
                    width: 50px !important;
                    min-width: 50px !important;
                    text-align: center !important;
                    vertical-align: middle !important;
                }
                .ff_fileupload_wrap table.ff_fileupload_uploads button.ff_fileupload_remove_file,
                .ff_fileupload_wrap .ff_fileupload_actions_mobile button.ff_fileupload_remove_file {
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    width: 36px !important;
                    min-width: 36px !important;
                    max-width: 36px !important;
                    height: 36px !important;
                    min-height: 36px !important;
                    max-height: 36px !important;
                    border-radius: 6px !important;
                    background-color: #fce8e6 !important;
                    border: 1px solid #f8d7da !important;
                    color: #dc3545 !important;
                    font-size: 16px !important;
                    line-height: 1 !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    box-sizing: border-box !important;
                    opacity: 1 !important;
                    cursor: pointer !important;
                    outline: none !important;
                }
                .ff_fileupload_wrap table.ff_fileupload_uploads button.ff_fileupload_remove_file:hover,
                .ff_fileupload_wrap .ff_fileupload_actions_mobile button.ff_fileupload_remove_file:hover {
                    background-color: #dc3545 !important;
                    color: #ffffff !important;
                    border-color: #dc3545 !important;
                }
                `).appendTo('head');

        });
        const elmSelectWInnerSupplier = ".winner-select-supplier";
        const elmFloorNumber = ".floor_number";
        const elmRoomNumber = ".room_number";

        async function initForm() {
            const elmSelectYear = ".get-year-container";
            if ($(elmSelectYear).length > 0) {
                var currentYear = new Date().getFullYear(),
                    years = [];
                let endYear = currentYear + 100;
                while (currentYear <= endYear) {
                    years.push(currentYear);
                    currentYear++
                }
                const valueSelect = $(elmSelectYear).attr('value')
                $(`${elmSelectYear} option`).remove();
                $(elmSelectYear).append(`<option value="">Select Departure Year</option>`);
                years.forEach(element => {
                    var selected = valueSelect == element ? "selected" :
                        "";
                    $(elmSelectYear).append(
                        `<option value="${element}" ${selected}>${element}</option>`);
                });
            }
            
            $('.floor_number').on('change keyup', function() {
                setRoomNumber();
            });

            $('.select2').each(function() {
                const $el = $(this);

                var placeholder = $(this).attr('placeholder').split(/\s+/);
                $el.select2({
                    placeholder: placeholder,
                    searchInputPlaceholder: 'Search',
                    dropdownPosition: 'below'
                });
                if ($el.is('[readonly]')) {
                    console.log('The element select2 has the readonly attribute.');

                    $el.on('select2:opening select2:selecting', function(e) {
                        e.preventDefault();
                    });
                    $el.next('.select2-container');
                    const container = $el.next('.select2-container');
                    container.addClass('select2-readonly');

                    if (!$('#select2-readonly-style').length) {
                        $('<style id="select2-readonly-style">')
                            .html(`
                            .select2-readonly .select2-selection {
                            pointer-events: none;
                            background-color: #dde2ef;
                            }
                        `).appendTo('head');
                    }
                }
            });
            $('.select2-no-search').each(function() {
                const el = $(this);
                var placeholder = $(this).attr('placeholder').split(/\s+/);
                el.select2({
                    minimumResultsForSearch: Infinity,
                    placeholder: placeholder
                });
                if (el.is('[readonly]')) {
                    console.log('The element has the readonly attribute.');

                    el.on('select2:opening select2:selecting', function(e) {
                        e.preventDefault();
                    });

                    el.next('.select2-container');
                    const container = el.next('.select2-container');
                    container.addClass('select2-readonly');
                    container.addClass('select2-readonly');

                    if (!$('#select2-readonly-style').length) {
                        $('<style id="select2-readonly-style">')
                            .html(`
                            .select2-readonly .select2-selection {
                            pointer-events: none;
                            background-color: #dde2ef;
                            }
                        `).appendTo('head');
                    }
                }
            });
        }

        async function setRoomNumber() {
            if ($(elmFloorNumber).val() !== '') {
                const response = await fetch(
                    `{{ url('/room-number') }}/` + $(elmFloorNumber).val()
                );
                const data = await response.json();
                if (data.status) {
                    const max_number = data.data;
                    const split = (max_number ?? 0).toString().split('');
                    console.log(split);

                    var number = '0';
                    if (split[2] > 0) {
                        sequence = parseInt(split[2]);
                        if (sequence > 0 && sequence < 9) {
                            sequence = '0' + (sequence + 1);
                        } else {
                            sequence = (sequence + 1);
                        }
                        number = $(elmFloorNumber).val() + sequence;
                    } else {
                        number = $(elmFloorNumber).val() + '01';
                    }
                    $(elmRoomNumber).val(number);
                }
            }
        }

        function previewImage() {
            const image = document.querySelector('#avatar');
            const imgPreview = document.querySelector('#preview-image');

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        // Jquery Dependency

        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });


        function formatNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        }


        function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.

            // get input value
            var input_val = input.val();

            // don't validate empty input
            if (input_val === "") {
                return;
            }

            // original length
            var original_len = input_val.length;

            // initial caret position
            var caret_pos = input.prop("selectionStart");

            input_val = formatNumber(input_val);

            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    </script>
    <script src="{{ asset('assets')}}/plugins/fileuploads/js/fileupload.js"></script>
    <script src="{{ asset('assets')}}/plugins/fileuploads/js/file-upload.js"></script>

    <!--Internal Fancy uploader js-->
    <script src="{{ asset('assets')}}/plugins/fancyuploder/jquery.ui.widget.js"></script>
    <script src="{{ asset('assets')}}/plugins/fancyuploder/jquery.fileupload.js"></script>
    <script src="{{ asset('assets')}}/plugins/fancyuploder/jquery.iframe-transport.js"></script>
    <script src="{{ asset('assets')}}/plugins/fancyuploder/jquery.fancy-fileupload.js"></script>
    <script src="{{ asset('assets')}}/plugins/fancyuploder/fancy-uploader.js"></script>
@endsection
