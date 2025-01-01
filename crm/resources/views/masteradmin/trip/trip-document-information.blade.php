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
                            <input type="text" class="form-control" id="trp_name" name="trp_name" value="{{$trip->trp_name ?? ''}}">
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
                <div class="col-md-12">
                @php
                        $files = json_decode($trip->trp_document);
                    @endphp

                    @if (!empty($files) && is_array($files))
                        <div class="mt-2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>File Preview</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Loop through files and display each as an image or PDF -->
                                    @foreach ($files as $file)
                                        <tr>
                                        <td>
                                            @if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file))
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
                                        </td>

                                            <td>
                                                <!-- Delete Button -->
                                                <!-- <form method="POST"
                                                    action="{{ route('trip.image.delete', ['id' => $trip->tr_id, 'image' => basename($file)]) }}"
                                                    style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form> -->
                                                <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-image" data-image="{{ basename($file) }}" data-trip="{{ $trip->tr_id }}">Delete</a>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No files available.</p>
                    @endif

                                    
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
        var DocumentDataTable = $('#tripDocumentDataTable').DataTable();
   });
</script>