@extends('backend.layouts.app')
@section('title')
    Staffs
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
                        <h5 class="card-title mb-0">Staffs</h5>
                    </div>
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons">
                            <button type="button" class="dt-button create-new btn btn-success"
                                    onclick="showFormModal()">
                                        <span><i class="ti ti-plus me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Add New</span>
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
                                <input type="number" name="mobile" id="mobile" class="form-control" max="0">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="name" class="form-label">Password<span
                                            class="text-danger">*</span></label>
                                <input type="password" name="password" id=password" class="form-control">
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


@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        $('#serviceForm').attr('action', '{{route('staff.store')}}');
        function showStatusModal(id,status) {
            $('#showStatusModal').modal('show');
            $("#status").val(status);
            $("#id").val(id);
        }
        function showFormModal(id = '') {
            $('#showStatusModal').modal('show');

            let inputInvalid = $('#serviceForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#serviceFormModalTitle').text('Add New');
            $('#serviceForm').attr('action', '{{route('staff.store')}}');

            if (id != '') {
                $('#serviceFormModalTitle').text('Update');
                $('#password_blank_message').removeClass('d-none');

                let route = '{{route('staff.edit')}}';


                $.ajax({
                    type: 'post',
                    url: route,
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function (response) {
                        if (response.status == true) {
                            $('#serviceForm').attr('action', '{{route('staff.update','id')}}'.replace('id', response.data.id));

                            $('input[name="name"]').val(response.data.name);
                            $('#email').val(response.data.email);
                            $('#mobile').val(response.data.mobile);



                        }
                    },
                    error: function (error) {
                    }
                });
            }
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
                    $('#staff-table').DataTable().ajax.reload(null, false);
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
@endpush
