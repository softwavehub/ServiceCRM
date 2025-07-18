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
                            <button type="button" onclick="showBulkAssignModal()" class="dt-button create-new btn btn-primary bulk-action-btn" disabled>
                <span><i class="ti ti-users me-sm-1"></i>
                    <span class="d-none d-sm-inline-block">Bulk Assign</span>
                </span>
                            </button>
                            <button type="button" class="dt-button create-new btn btn-success"
                                    onclick="showFormModal()">
                                        <span><i class="ti ti-plus me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Add New</span>
                                        </span>
                            </button>
                            <button type="button" class="dt-button create-new btn btn-primary ms-2"
                                    onclick="showImportModal()">
            <span><i class="ti ti-upload me-sm-1"></i>
                <span class="d-none d-sm-inline-block">Import Leads</span>
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

    <div class="modal fade" id="showStatusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="userFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="serviceFormModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="serviceForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="name" class="form-label">Name<span
                                            class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="name" class="form-label">Phone<span
                                            class="text-danger">*</span></label>
                                <input type="number" name="phone" id="phone" class="form-control" max="0">
                            </div>


                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit" onclick="submitForm()">Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showAssignModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="userFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="serviceFormModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="assignForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="lead_id">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="name" class="form-label">Assign To Staff<span
                                            class="text-danger">*</span></label>
                                <select class="form-control" name="staff_id" id="staff_id">

                                </select>
                            </div>


                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit" onclick="submitStaffForm()">Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="importModalLabel">Import Leads</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x close-button-icon"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="importForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="import_file" class="form-label">Select File (CSV/Excel)</label>
                                <input type="file" name="import_file" id="import_file" class="form-control"
                                       accept=".csv, .xlsx, .xls" required>
                                <div class="form-text">File should contain Name, Email, and Phone columns</div>
                            </div>
                            <div class="col-12">
                                <a href="{{ route('leads.download-sample') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-download me-1"></i> Download Sample File
                                </a>
                            </div>
                        </div>
                    </form>
                    <div id="importErrors" class="mt-3" style="display: none;">
                        <div class="alert alert-danger">
                            <h6>Import Errors:</h6>
                            <ul id="errorList" class="mb-0"></ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitImportForm()">Import</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bulkAssignModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="bulkAssignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="bulkAssignModalLabel">Bulk Assign Leads</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x close-button-icon"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="bulkAssignForm">
                        @csrf
                        <input type="hidden" name="lead_ids" id="lead_ids">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="bulk_staff_id" class="form-label">Assign To Staff<span
                                            class="text-danger">*</span></label>
                                <select class="form-control" name="staff_id" id="bulk_staff_id" required>
                                    <option value="">Select Staff Member</option>
                                    <!-- Options will be loaded via AJAX -->
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitBulkAssignForm()">Assign</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        $('#serviceForm').attr('action', '{{route('leads.store')}}');

        function showStatusModal(id, status) {
            $('#showStatusModal').modal('show');
            $("#status").val(status);
            $("#id").val(id);
        }

        function assignStaff(leadId) {
            $('#staff_id').empty();

            // Show loading state
            $('#staff_id').html('<option value="">Loading staff members...</option>');
            $.ajax({
                url: `/api/leads/${leadId}/available-staff`,
                type: 'GET',
                success: function (response) {
                    $('#staff_id').empty();

                    if (response.data.length > 0) {
                        // Add default option
                        $('#staff_id').append('<option value="">Select Staff Member</option>');

                        // Add staff options
                        $.each(response.data, function (key, staff) {
                            $('#staff_id').append(`<option value="${staff.id}">${staff.name}</option>`);
                        });
                    } else {
                        $('#staff_id').append('<option value="">No available staff members</option>');
                    }

                    // Set the lead ID in hidden field
                    $('#lead_id').val(leadId);
                    $('#showAssignModal').modal('show');
                },
                error: function (xhr) {
                    $('#staff_id').empty();
                    $('#staff_id').append('<option value="">Error loading staff</option>');
                    console.error(xhr.responseText);
                }
            });
            $('#showAssignModal').modal('show');
        }

        function showFormModal(id = '') {
            $('#showStatusModal').modal('show');

            let inputInvalid = $('#serviceForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#serviceFormModalTitle').text('Add New');
            $('#serviceForm').attr('action', '{{route('leads.store')}}');

            if (id != '') {
                $('#serviceFormModalTitle').text('Update');
                $('#password_blank_message').removeClass('d-none');

                let route = '{{route('leads.edit')}}';


                $.ajax({
                    type: 'post',
                    url: route,
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function (response) {
                        if (response.status == true) {
                            $('#serviceForm').attr('action', '{{route('leads.update','id')}}'.replace('id', response.data.id));

                            $('input[name="name"]').val(response.data.name);
                            $('#email').val(response.data.email);
                            $('#phone').val(response.data.phone);


                        }
                    },
                    error: function (error) {
                    }
                });
            }
        }

        function submitStaffForm() {
            var leadId = $("#lead_id").val();
            $('#assignForm').attr('action', '{{route('leads.assign-staff','id')}}'.replace('id', leadId));
            let url = $('#assignForm').attr('action');
            let formData = new FormData($('#assignForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#showAssignModal').modal('hide');
                    if (response.status == true) {
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    $('#leads-table').DataTable().ajax.reload(null, false);
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#assignForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#assignForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        if (inputField.is('select')) {
                            inputField.next('span').after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                        } else {
                            inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                        }
                    });
                }
            });
        }

        function submitForm() {
            let url = $('#serviceForm').attr('action');
            let formData = new FormData($('#serviceForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#showStatusModal').modal('hide');
                    if (response.status == true) {
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    $('#leads-table').DataTable().ajax.reload(null, false);
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#serviceForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#userForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        if (inputField.is('select')) {
                            inputField.next('span').after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                        } else {
                            inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                        }
                    });
                }
            });
        }
    </script>
    <script>
        function showImportModal() {
            $('#importModal').modal('show');
            $('#importErrors').hide();
            $('#importForm')[0].reset();
        }

        function submitImportForm() {
            let formData = new FormData($('#importForm')[0]);

            $.ajax({
                url: '{{ route("leads.import") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        $('#importModal').modal('hide');
                        $('#leads-table').DataTable().ajax.reload(null, false);
                    } else {
                        // Show validation errors
                        showImportErrors(response.errors || [response.message]);
                    }
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON?.errors || [xhr.responseJSON?.message || 'An error occurred'];
                    showImportErrors(errors);
                }
            });
        }

        function showImportErrors(errors) {
            const errorList = $('#errorList');
            errorList.empty();

            if (Array.isArray(errors)) {
                errors.forEach(error => {
                    errorList.append(`<li>${error}</li>`);
                });
            } else if (typeof errors === 'object') {
                for (const [field, messages] of Object.entries(errors)) {
                    messages.forEach(message => {
                        errorList.append(`<li>${field}: ${message}</li>`);
                    });
                }
            } else {
                errorList.append(`<li>${errors}</li>`);
            }

            $('#importErrors').show();
            $('#importModal').animate({scrollTop: 0}, 'fast');
        }

        function loadStaffMembers() {
            $.ajax({
                url: '{{ route("staff.list") }}',
                type: 'GET',
                success: function(response) {
                    $('#bulk_staff_id').empty().append('<option value="">Select Staff Member</option>');
                    response.data.forEach(function(staff) {
                        $('#bulk_staff_id').append($('<option>', {
                            value: staff.id,
                            text: staff.name
                        }));
                    });
                }
            });
        }

        function showBulkAssignModal() {
            const selectedIds = [];
            $('.lead-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) return;

            $('#lead_ids').val(selectedIds.join(','));
            loadStaffMembers();
            $('#bulkAssignModal').modal('show');
        }

        function submitBulkAssignForm() {
            const formData = $('#bulkAssignForm').serialize();

            $.ajax({
                url: '{{ route("leads.bulk-assign") }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#bulkAssignModal').modal('hide');
                    Swal.fire({
                        text: response.message,
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#leads-table').DataTable().ajax.reload(null, false);
                },
                error: function(error) {
                    let errors = error.responseJSON.errors;
                    $('#bulkAssignForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function(field, messages) {
                        let inputField = $('#bulkAssignForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }
        $(document).ready(function() {
            // Handle select all checkbox
            $(document).on('change', '.select-all-checkbox', function() {
                $('.lead-checkbox').prop('checked', $(this).prop('checked'));
                toggleBulkActionButton();
            });

            // Handle individual checkbox changes
            $(document).on('change', '.lead-checkbox', function() {
                toggleBulkActionButton();
                // Uncheck "select all" if not all checkboxes are checked
                if ($('.lead-checkbox:checked').length !== $('.lead-checkbox').length) {
                    $('.select-all-checkbox').prop('checked', false);
                }
            });

            function toggleBulkActionButton() {
                const anyChecked = $('.lead-checkbox:checked').length > 0;
                $('.bulk-action-btn').prop('disabled', !anyChecked);
            }
        });
    </script>
@endpush
