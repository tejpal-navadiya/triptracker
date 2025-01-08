<div class="card">
    <div class="card-header">
    @if (Session::has('document-success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('document-success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @php
            Session::forget('document-success');
        @endphp
    @endif
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3 class="card-title">Trip Document</h3>
            </div>
 
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form id="trip-edit-form" action="{{ route('trip.document.update', $trip->tr_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
            <div class="col-md-12 table-responsive pad_table">
            <div class="row pxy-15 px-10">
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="trvd_name">Name</label>
                        <div class="d-flex">
                            <input type="text" class="form-control" id="trp_name" name="trp_name" value="">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="trvd_document">Upload Documents</label>
                        <div class="d-flex">
                            <!-- <x-text-input type="file" name="trvd_document" id="trvd_document" accept="image/*" class="" /> -->
                            <input type="file" class="form-control" id="trp_document" name="trp_document[]" multiple>
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('trvd_document')" />
                        <p id="document_images"></p>
                        <label for="trvd_document">Only jpg, jpeg, png, and pdf files are allowed</label>
                        

                    </div>

                                
                </div>
                <div class="card-body">
                    <div class="col-md-12 table-responsive pad_table">
                        <table id="tripdocumentsDataTable" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>File Preview</th>
                                    <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php //dd($document);?>
                            @if (count($tripdocument) > 0)
                        @foreach ($tripdocument as $document)
                        @php
                            // Safely decode the JSON
                            $files = json_decode($document->trp_document, true);
                        @endphp

                        @if (!empty($files) && is_array($files))
                            @foreach ($files as $file)
                                <tr>
                                    <!-- Name -->
                                    <td>{{ $document->trp_name ?? 'No Name Available' }}</td>

                                    <!-- File Preview -->
                                    <td>
                                        @if (preg_match('/\.(jpg|jpeg|png|gif|bmp|svg|webp|jfif)$/i', $file))

                                            <!-- Display Image Preview -->
                                            <a target="_blank" href="{{ route('tripdocument.access', ['filename' => $file]) }}">
                                                <img src="{{ route('tripdocument.access', ['filename' => $file]) }}" 
                                                    alt="Uploaded Image" class="img-thumbnail"
                                                    style="width: 100px; height: auto;">
                                            </a>
                                        @elseif (preg_match('/\.pdf$/i', $file))
                                            <!-- Display PDF Preview -->
                                            <div class="embed-responsive embed-responsive-4by3" style="max-width: 100px;">
                                                <embed src="{{ route('tripdocument.access', ['filename' => $file]) }}" 
                                                    type="application/pdf" class="embed-responsive-item" />
                                            </div>
                                            <a target="_blank" href="{{ route('tripdocument.access', ['filename' => $file]) }}">View</a>
                                        @endif
                                        <?php

                                                                                                            
                                    $imageUrl = route('tripdocument.access', ['filename' => $file]);
                                    echo "<br><a href='" . htmlspecialchars($imageUrl) . "' target='_blank'>" . htmlspecialchars($file) . "</a><br>";


                                    ?>

                                    
                                    </td>

                                    <!-- Actions -->
                                    <td>
                                    <?php 
                               
                               $images = json_decode($document->trp_document, true);

                               $userFolder = session('userFolder');
                               $baseUrl = config('app.image_url');
                             ?>
                               
                             <div class="dropdown">
                                 <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                     <i class="fas fa-download download_icon_grid"></i>
                                 </button>
                                 <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                 <?php
                                //  foreach ($images as $image) {
                                     $imageUrl = route('tripdocument.access', ['filename' => $file]);
                                 ?>
                                     <a class="dropdown-item" href="{{ $imageUrl }}" download> {{ $file }}</a>
                                 <?php
                                //  }
                                 ?>
                                 </div>
                             </div>

                                        <a href="javascript:void(0);" 
                                        class="btn btn-danger btn-sm delete-image" 
                                        data-toggle="modal"
                                        data-target="#delete-product-modal-{{ $document->trp_id }}"
                                        >
                                            Delete
                                        </a>

                                        <div class="modal fade"
                                            id="delete-product-modal-{{ $document->trp_id }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered"
                                                role="document">
                                                <div class="modal-content">
                                                    <form 
                                                        method="POST">
                                                        <div class="modal-body pad-1 text-center">
                                                            <i class="fas fa-solid fa-trash delete_icon"></i>
                                                            <p class="company_business_name px-10"><b>Delete
                                                            Trip Document</b></p>
                                                            <p class="company_details_text">Are You Sure You
                                                                Want to Delete This Trip Document?</p>
                                                            <button type="button" class="add_btn px-15"
                                                                data-dismiss="modal">Cancel</button>
                                                            <a href="javascript:void(0);" 
                                                            data-image="{{ basename($file) }}" 
                                                            data-document="{{ $document->trp_id }}"
                                                            data-trip="{{ $trip->tr_id }}"

                                                            class="btn btn-danger delete-image">Delete</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    
                                    </td>
                                </tr>
                            @endforeach
                        
                        @endif
                        @endforeach
                       
                        @endif
                        </tbody>
                    </table>
                </div>

            </div>

              
                <div class="col-md-12 text-center">
                    <!-- <a href="{{ route('trip.index') }}" class="add_btn_br px-10">Cancel</a> -->
                    <button type="submit" class="add_btn px-10">Save</button>
                </div>
            </div>
        </div>
    </form>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
   
    $(document).ready(function() {
        $('#tripdocumentsDataTable').DataTable();  
         });
</script>

<script>
    $(document).on('click', '.delete-image', function() {
        var tripId = $(this).data('trip');
        var image = $(this).data('image');
        var documentId = $(this).data('document');

        // Confirm the delete action (optional, you can remove this if you don't want confirmation)
        // if (confirm("Are you sure you want to delete this image?")) {
            // Use the route() function to generate the correct URL for the delete request

            var actionUrl = '{{ route("view.trip.image.delete", ["tripid" => ":tripId", "documentid" => ":documentId", "image" => ":image"]) }}'
                .replace(':tripId', tripId)
                .replace(':documentId', documentId)
                .replace(':image', image);



            // Send AJAX DELETE request
            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                    
                    // Redirect to the page after deletion
                    window.location.href = response.redirect_url;
                }
                },
                error: function(xhr, status, error) {
                    // Handle the error (you can add a simple error message here)
                    console.error('Error:', error);
                }
            });
        // }
    });
</script>