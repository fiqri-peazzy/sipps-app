@extends('layouts.editor')

@section('content')
    <div class="h-full w-full p-4 md:p-6 overflow-y-auto">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden p-6 md:p-10">
                @include('customer.partials.design-editor')
            </div>
        </div>
    </div>

    <!-- Config Data -->
    <input type="hidden" id="existing-config" value="{{ json_encode($existingConfig) }}">
    <input type="hidden" id="item-index" value="{{ $itemIndex }}">
@endsection

@push('scripts')
    <script>
        window.DesignEditorConfig = {
            baseUrl: "{{ asset('frontend/assets/img/kaos-templates') }}/"
        };
    </script>
    <script src="{{ asset('frontend/assets/js/design-editor.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Initialize Design Editor
            const itemIndex = $('#item-index').val();
            const existingConfigStr = $('#existing-config').val();
            let existingConfig = null;

            try {
                existingConfig = JSON.parse(existingConfigStr);
            } catch (e) {
                console.error("Error parsing existing config", e);
            }

            // Init Editor
            DesignEditor.init(itemIndex, existingConfig);

            // Save Design Button
            $('#btn-save-design').on('click', function () {
                const $btn = $(this);
                const originalHtml = $btn.html();

                $btn.prop('disabled', true).html('<i class="lni lni-spinner-arrow spinning"></i> Menyimpan...');

                const config = DesignEditor.getDesignConfig();

                $.ajax({
                    url: "{{ route('customer.design-editor.save') }}",
                    type: "POST",
                    data: {
                        item_index: itemIndex,
                        design_config: config
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            DesignEditor.showAlert("Desain berhasil disimpan!", "success");
                            setTimeout(() => {
                                window.location.href = "{{ route('customer.order.create') }}";
                            }, 1000);
                        } else {
                            DesignEditor.showAlert("Gagal menyimpan desain", "danger");
                            $btn.prop('disabled', false).html(originalHtml);
                        }
                    },
                    error: function () {
                        DesignEditor.showAlert("Terjadi kesalahan sistem", "danger");
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });
        });
    </script>
    <style>
        .spinning {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush