@extends('backend.layouts.app')
@section('title')
    Services
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
                        <h5 class="card-title mb-0">Services</h5>
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
                                <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>

                            <!-- Category 1 -->
                            <div class="col-12 mb-2">
                                <label for="category_1" class="form-label">Category 1<span class="text-danger">*</span></label>
                                <select name="category_1" id="category_1" class="form-control" onchange="loadCategory2(this.value)">
                                    <option value="">Select Category 1</option>
                                    <!-- Options will be loaded via AJAX -->
                                </select>
                            </div>

                            <!-- Category 2 -->
                            <div class="col-12 mb-2">
                                <label for="category_2" class="form-label">Category 2</label>
                                <select name="category_2" id="category_2" class="form-control" onchange="loadCategory3(this.value)" disabled>
                                    <option value="">Select Category 2</option>
                                    <!-- Options will be loaded via AJAX -->
                                </select>
                            </div>

                            <!-- Category 3 -->
                            <div class="col-12 mb-2">
                                <label for="category_3" class="form-label">Category 3</label>
                                <select name="category_3" id="category_3" class="form-control" disabled>
                                    <option value="">Select Category 3</option>
                                    <!-- Options will be loaded via AJAX -->
                                </select>
                            </div>

                            <div class="col-12 mb-2">
                                <label for="description" class="form-label">Description<span class="text-danger">*</span></label>
                                <textarea name="description" id="description" class="form-control"></textarea>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="inclusions" class="form-label">Inclusions<span class="text-danger">*</span></label>
                                <input type="text" name="inclusions" id="inclusions" class="form-control">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="attachment" class="form-label">Attachment<span class="text-danger">*</span></label>
                                <input type="file" name="attachment" id="attachment" class="form-control">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="tenture" class="form-label">Tenture<span class="text-danger">*</span></label>
                                <input type="number" name="tenture" id="tenture" class="form-control" min="1">
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
        let loadedCategories = {
            category1: false,
            category2: {},
            category3: {}
        };
        $(document).ready(function() {
            // Object to track loaded categories


            // Reset tracker when modal closes
            $('#showStatusModal').on('hidden.bs.modal', function() {
                loadedCategories = {
                    category1: false,
                    category2: {},
                    category3: {}
                };
            });
        });
        $('#serviceForm').attr('action', '{{route('services.store')}}');
        function showStatusModal(id,status) {
            $('#showStatusModal').modal('show');
            $("#status").val(status);
            $("#id").val(id);
        }
        function showFormModal(id = '') {
            $('#showStatusModal').modal('show');
            $('#serviceFormModalTitle').text(id ? 'Update Service' : 'Add New Service');
            $('#serviceForm').attr('action', id ? '{{ route("services.update", "id") }}'.replace('id', id) : '{{ route("services.store") }}');

            // Clear form and errors
            $('#serviceForm')[0].reset();
            $('#serviceForm .form-control').removeClass('is-invalid');
            $('.error-message').remove();
            $('#category_2, #category_3').prop('disabled', true);

            // Load categories
            loadCategory1();

            if (id) {
                $.ajax({
                    url: '{{ route("services.edit") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if (response.status) {
                            const service = response.data;

                            // Fill basic fields
                            $('#name').val(service.name);
                            $('#description').val(service.description);
                            $('#inclusions').val(service.inclusions);
                            $('#tenture').val(service.tenture);

                            // Handle categories
                            if (service.category_1) {
                                loadCategory1(service.category_1);

                                if (service.category_2) {
                                    // We need to wait for category1 to load before loading category2
                                    const checkCategory2 = setInterval(function() {
                                        if ($('#category_1 option[value="' + service.category_1 + '"]').length > 0) {
                                            clearInterval(checkCategory2);
                                            loadCategory2(service.category_1, service.category_2);

                                            if (service.category_3) {
                                                // Wait for category2 to load before loading category3
                                                const checkCategory3 = setInterval(function() {
                                                    if ($('#category_2 option[value="' + service.category_2 + '"]').length > 0) {
                                                        clearInterval(checkCategory3);
                                                        loadCategory3(service.category_2, service.category_3);
                                                    }
                                                }, 100);
                                            }
                                        }
                                    }, 100);
                                }
                            }
                        }
                    }
                });
            }
        }

        function submitForm() {
            let formData = new FormData($('#serviceForm')[0]);
            let url = $('#serviceForm').attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#showStatusModal').modal('hide');
                    Swal.fire({
                        text: response.message,
                        icon: response.status ? "success" : "warning",
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#service-table').DataTable().ajax.reload(null, false);
                },
                error: function(error) {
                    let errors = error.responseJSON.errors;
                    $('#serviceForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();

                    $.each(errors, function(field, messages) {
                        let inputField = $('#serviceForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }

        function loadCategory1(selectedId = null) {
            if (!loadedCategories.category1) {
                $('#category_1').empty().append('<option value="">Select Category 1</option>');
                $('#category_2').empty().append('<option value="">Select Category 2</option>').prop('disabled', true);
                $('#category_3').empty().append('<option value="">Select Category 3</option>').prop('disabled', true);

                $.ajax({
                    url: '{{ route("categories.get-by-parent") }}',
                    type: 'GET',
                    data: { parent_id: null },
                    success: function(response) {
                        $('#category_1').empty().append('<option value="" selected hidden>Select</option>');

                        response.data.forEach(function(category) {
                            $('#category_1').append($('<option>', {
                                value: category.id,
                                text: category.name
                            }));
                        });

                        loadedCategories.category1 = true;

                        if (selectedId) {
                            $('#category_1').val(selectedId).trigger('change');
                        }
                    }
                });
            } else if (selectedId) {
                $('#category_1').val(selectedId).trigger('change');
            }
        }

        function loadCategory2(parentId, selectedId = null) {
            if (!parentId) {
                $('#category_2').empty().append('<option value="">Select Category 2</option>').prop('disabled', true);
                $('#category_3').empty().append('<option value="">Select Category 3</option>').prop('disabled', true);
                return;
            }

            // Only load if not already loaded for this parent
            if (!loadedCategories.category2[parentId]) {
                $('#category_2').empty().append('<option value="">Select Category 2</option>').prop('disabled', false);
                $('#category_3').empty().append('<option value="">Select Category 3</option>').prop('disabled', true);

                $.ajax({
                    url: '{{ route("categories.get-by-parent") }}',
                    type: 'GET',
                    data: { parent_id: parentId },
                    success: function(response) {
                        $('#category_2').empty().append('<option value="">Select Category 2</option>');

                        response.data.forEach(function(category) {
                            $('#category_2').append($('<option>', {
                                value: category.id,
                                text: category.name
                            }));
                        });

                        loadedCategories.category2[parentId] = true;

                        if (selectedId) {
                            $('#category_2').val(selectedId).trigger('change');
                        }
                    }
                });
            } else if (selectedId) {
                $('#category_2').val(selectedId).trigger('change');
            }
        }

        function loadCategory3(parentId, selectedId = null) {
            if (!parentId) {
                $('#category_3').empty().append('<option value="">Select Category 3</option>').prop('disabled', true);
                return;
            }

            // Only load if not already loaded for this parent
            if (!loadedCategories.category3[parentId]) {
                $('#category_3').empty().append('<option value="">Select Category 3</option>').prop('disabled', false);

                $.ajax({
                    url: '{{ route("categories.get-by-parent") }}',
                    type: 'GET',
                    data: { parent_id: parentId },
                    success: function(response) {
                        $('#category_3').empty().append('<option value="">Select Category 3</option>');

                        response.data.forEach(function(category) {
                            $('#category_3').append($('<option>', {
                                value: category.id,
                                text: category.name
                            }));
                        });

                        loadedCategories.category3[parentId] = true;

                        if (selectedId) {
                            $('#category_3').val(selectedId);
                        }
                    }
                });
            } else if (selectedId) {
                $('#category_3').val(selectedId);
            }
        }


    </script>

@endpush
