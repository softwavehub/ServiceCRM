@extends('backend.layouts.app')
@section('title')
    Leads
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger">{{Session::get('error')}}</div>
            @endif
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Leads</h5>
                    </div>
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons">
                            <button type="button" class="dt-button create-new btn btn-primary ms-2" id="sendMessageBtn" disabled>
            <span><i class="ti ti-send me-sm-1"></i>
                <span class="d-none d-sm-inline-block">Send Message</span>
            </span>
                            </button>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="sendMessageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="sendMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="sendMessageModalLabel">Send WhatsApp Message</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x close-button-icon"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="sendMessageForm">
                        @csrf
                        <input type="hidden" name="lead_id" id="lead_id">
                        <input type="hidden" name="phone" id="phone">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="template_id" class="form-label">Select Template<span class="text-danger">*</span></label>
                                <select class="form-control" name="template_id" id="template_id" required>
                                    <option value="">Loading templates...</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="sendWhatsAppMessage()">Send</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
<script>
    $(document).ready(function() {
        // Handle radio button selection
        $(document).on('change', '.lead-radio', function() {
            $('#sendMessageBtn').prop('disabled', false);
        });

        // Send Message button click
        $('#sendMessageBtn').click(function() {
            const selectedRadio = $('.lead-radio:checked');
            if (selectedRadio.length) {
                $('#lead_id').val(selectedRadio.val());
                $('#phone').val(selectedRadio.data('phone'));
                loadWhatsAppTemplates();
                $('#sendMessageModal').modal('show');
            }
        });
    });

    function loadWhatsAppTemplates() {
        $('#template_id').html('<option value="">Loading templates...</option>');

        $.ajax({
            url: '{{ route("whatsapp-template.list") }}',
            type: 'GET',
            success: function(response) {
                $('#template_id').empty();
                if (response.data.length > 0) {
                    $('#template_id').append('<option value="">Select Template</option>');
                    response.data.forEach(function(template) {
                        $('#template_id').append($('<option>', {
                            value: template.id,
                            text: template.title,
                            'data-message': template.message
                        }));
                    });
                } else {
                    $('#template_id').append('<option value="">No templates found</option>');
                }
            },
            error: function() {
                $('#template_id').empty().append('<option value="">Error loading templates</option>');
            }
        });
    }

    function sendWhatsAppMessage() {
        const templateId = $('#template_id').val();
        const message = $('#template_id option:selected').data('message');
        let phone = $('#phone').val();

        if (!templateId) {
            alert('Please select a template');
            return;
        }

        // Remove any existing country code and non-digit characters
        phone = phone.replace(/\D/g, '');
        phone = '91' + phone;
        // Prepend 91 if not already present


        // Ensure phone number starts with + and has country code
        // const formattedPhone = phone.startsWith('+') ? phone : `+${phone}`;

        // Encode message for URL
        const encodedMessage = encodeURIComponent(message);
        const whatsappUrl = `https://api.whatsapp.com/send?phone=${phone}&text=${encodedMessage}`;

        // Open in new tab
        window.open(whatsappUrl, '_blank');
        $('#sendMessageModal').modal('hide');
    }
</script>
@endpush
