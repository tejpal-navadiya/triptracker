<div class="card">
    <div class="card-header">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3 class="card-title">Document</h3>
            </div>
            <div class="col-auto"><button href="javascript:void(0)" id="createNewDocument" class="reminder_btn">Add
                    Document</button></div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="col-md-12 table-responsive pad_table">
                <table id="example13" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Traveler Name</th>
                            <th>Type</th>
                            <th>Document</th>
                            <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="ajaxModelDocument" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeadingDocument"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="FormDocument" name="FormDocument" class="mt-6 space-y-6" enctype="multipart/form-data">

                <input type="hidden" name="trvd_id" id="trvd_id">
                <ul id="update_msgList"></ul>
                <div class="modal-body">
                    <div class="row pxy-15 px-10">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvd_name">Documents Type</label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trvd_name"
                                        name="trvd_name">
                                        <option default>Select Documents Type</option>
                                        @foreach ($documentType as $type)
                                            <option value="{{ $type->docty_id }}">{{ $type->docty_name }} </option>
                                        @endforeach
                                        <x-input-error class="mt-2" :messages="$errors->get('trvd_name')" />
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvm_id">Traveler <span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trvm_id"
                                        name="trvm_id">
                                        <option value="" disabled selected>Select Traveler</option>

                                        <!-- Loop through traveling members -->
                                        @foreach ($tripTravelingMembers as $member)
                                            <option value="{{ $member->trtm_id }}">
                                                {{ $member->trtm_first_name }}
                                                {{ $member->trtm_last_name }}

                                            </option>
                                        @endforeach

                                        <!-- Loop through trips -->
                                        @foreach ($tripData as $trip)
                                            <option value="{{ $trip->tr_id }}">
                                                {{ $trip->tr_traveler_name }}
                                            </option>
                                        @endforeach

                                        <x-input-error class="mt-2" :messages="$errors->get('trvm_id')" />
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 family-member-field">
                            <div class="form-group">
                                <label for="trvd_document">Upload Documents</label>
                                <div class="d-flex">
                                    <!-- <x-text-input type="file" name="trvd_document" id="trvd_document" accept="image/*" class="" /> -->
                                    <x-text-input type="file" class="form-control" id="trvd_document"
                                        name="trvd_document[]" multiple />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('trvd_document')" />
                                <p id="document_images"></p>
                                <label for="trvd_document">Only jpg, jpeg, png, and pdf files are allowed</label>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="add_btn_br" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="saveBtnDocument" value="create"
                            class="add_btn">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {


        //datatable list
        var table = $('#example13').DataTable();
        table.destroy()

        table = $('#example13').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('masteradmin.document.index', $trip_id) }}",
                type: 'GET',
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [{
                    data: 'traveler_name',
                    name: 'traveler_name'
                },
                {
                    data: 'document_type',
                    name: 'document_type'
                },
                {
                    data: 'trvd_document',
                    name: 'trvd_document',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        // console.log(data);

                        if (data && Array.isArray(data)) {
                            var imageLinks = '';
                            var userFolder = "{{ session('userFolder') }}";
                            var baseUrl = "{{ config('app.image_url') }}";

                            data.forEach(function(image) {
                                var imageUrl = baseUrl + userFolder +
                                    '/document_image/' + image;
                                imageLinks += '<a href="' + imageUrl +
                                    '" target="_blank">' + image + '</a>, ';
                            });

                            return imageLinks.slice(0, -2);
                        } else {
                            console.error("Expected array but got:", data);
                        }
                        return '';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });



        //create task
        $('#createNewDocument').click(function() {
            $('#saveBtnDocument').val("create-product");
            $('#trvd_id').val('');
            $('#FormDocument')[0].reset();
            $('#modelHeadingDocument').html("Add Document");
            $('body').addClass('modal-open');
            $('#document_images').html('');
            $('#trvd_name').trigger('change.select2');
            $('#trvm_id').trigger('change.select2');
            var editModal = new bootstrap.Modal(document.getElementById('ajaxModelDocument'));
            editModal.show();
        });

        //insert/update data
        $('#saveBtnDocument').click(function(e) {
            e.preventDefault();
            $(this).html('Sending..');

            var formData = new FormData($('#FormDocument')[0]);
            formData.append('_token', "{{ csrf_token() }}");

            var url = '';
            var method = 'POST'; // Default to POST for new tasks

            if ($('#trvd_id').val() === '') {
                // Create new
                url = "{{ route('masteradmin.document.store', $trip_id) }}";
                formData.append('_method', 'POST');
            } else {
                // Update existing
                var trvd_id = $('#trvd_id').val();
                url = "{{ route('masteradmin.document.update', [$trip_id, ':trvd_id']) }}";
                url = url.replace(':trvd_id', trvd_id);
                formData.append('_method', 'PATCH');
            }

            $.ajax({
                data: formData,
                url: url,
                type: method,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    table.draw();
                    $('#ajaxModelDocument').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('#ajaxModelDocument').css('display', 'none');
                    $('#saveBtnDocument').html('Save');
                    $('#FormDocument')[0].reset();

                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#FormDocument').html('Save Changes');
                }
            });
        });

        //edit popup open
        $(document).on('click', '.editDocument', function(e) {
            e.preventDefault();


            var id = $(this).data('id');
            // alert(id);
            $.get("{{ route('masteradmin.document.edit', ['id' => 'id', 'trip_id' => $trip_id]) }}"
                .replace('id', id).replace('{{ $trip_id }}', '{{ $trip_id }}'),
                function(data) {

                    // console.log(data);
                    $('#modelHeadingDocument').html("Edit Document");
                    $('#saveBtnDocument').val("edit-user");

                    var editModal = new bootstrap.Modal(document.getElementById(
                        'ajaxModelDocument'));
                    editModal.show();

                    $('#trvd_id').val(data.trvd_id);

                    $('#trvd_name').val(data.trvd_name).trigger('change.select2');
                    $('#trvm_id').val(data.trvm_id).trigger('change.select2');

                    $('#document_images').html('');
                    var baseUrl = "{{ config('app.image_url') }}";
                    var userFolder = "{{ session('userFolder') }}";
                    if (data.trvd_document) {
                        var images = JSON.parse(data.trvd_document);
                        var imageHtml = '';
                        imageHtml += '<table class="table table-bordered">';
                        imageHtml += '<thead>';
                        imageHtml += '<tr>';
                        imageHtml += '<th>Image Preview</th>';
                        imageHtml += '<th>Actions</th>';
                        imageHtml += '</tr>';
                        imageHtml += '</thead>';

                        $.each(images, function(index, image) {
                            imageHtml += '<tbody>';
                            imageHtml += '<tr>';
                            imageHtml += '<td>';
                            imageHtml += '<img src="' + baseUrl + userFolder +
                                '/document_image/' + image +
                                '" alt="Uploaded Image" class="img-thumbnail" style="max-width: 100px; height: auto;">';
                            imageHtml += '</td>';

                            var routeUrl =
                                "{{ route('document.image.delete', ['id' => ':trvd_id', 'image' => ':image']) }}";
                            routeUrl = routeUrl.replace(':trvd_id', data.trvd_id).replace(
                                ':image', image);

                            imageHtml += '<td>';
                            // Replace the form with a button and attach a click event
                            imageHtml +=
                                '<button class="btn btn-danger btn-sm delete-image" data-url="' +
                                routeUrl + '" data-image="' + image + '">Delete</button>';
                            imageHtml += '</td>';
                            imageHtml += '</tr>';
                            imageHtml += '</tbody>';
                        });
                        imageHtml += '</table>';
                        $('#document_images').html(imageHtml);

                        $('.delete-image').on('click', function(event) {
                            event.preventDefault();
                            var url = $(this).data('url');
                            var image = $(this).data('image');
                            var $button = $(this);
                            $.ajax({
                                type: 'POST',
                                url: url,
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr(
                                        'content'),
                                    _method: 'DELETE'
                                },
                                success: function(response) {

                                    $button.closest('tr').remove();
                                    table.draw();
                                    alert('Image deleted successfully');
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error deleting image:',
                                        error);
                                }
                            });
                        });
                    }

                });
        });

        //delete record
        $('body').on('click', '.deleteDocumentbtn', function(e) {
            e.preventDefault();
            var trvd_id = $(this).data("id");
            //  alert(trtm_id);
            var url = "{{ route('masteradmin.document.destroy', [$trip_id, ':trvd_id']) }}";
            url = url.replace(':trvd_id', trvd_id);
            // alert(url);
            $.ajax({
                type: "DELETE",
                url: url,
                success: function(data) {
                    alert(data.success);

                    $('.ajaxModelDocument').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('.ajaxModelDocument').css('display', 'none');

                    table.draw();

                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        });
    });
</script>
