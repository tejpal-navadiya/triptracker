<div class="row">
    @if(!empty($libraries) && $libraries->count())
        @foreach ($libraries as $library)
            <div class="col-lg-3">
                <div class="libary-box">
                    @if ($library->lib_image)
                        @php
                            $files = json_decode($library->lib_image, true);
                        @endphp

                        @if (!empty($files) && is_array($files) && isset($files[0]))
                            @php
                                $file = $files[0];
                                $imageUrl =
                                    config('app.image_url') . '/library_images/' . $file;
                            @endphp

                            @if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file))
                                <img src="{{ $imageUrl }}" alt="Library Image" class="libary-img img-fluid img-thumbnail">
                            @elseif (preg_match('/\.pdf$/i', $file))
                                <div class="embed-responsive embed-responsive-4by3">
                                    <embed src="{{ $imageUrl }}" type="application/pdf" class="embed-responsive-item">
                                </div>
                            @endif
                        @else
                            <img src="../public/dist/img/no_image.jpg" class="libary-img">
                        @endif
                    @else
                        <img src="../public/dist/img/no_image.jpg" class="libary-img">
                    @endif

                    <p class="libary-name">{{ $library->lib_name }}</p>

                    <div class="libary-btnbox">
                        <a href="{{ route('library.show', $library->lib_id) }}" class="l-view-btn">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('library.edit', $library->lib_id) }}" class="l-edit-btn">
                            <i class="fas fa-pen"></i> Edit
                        </a>
                        <a href="#" data-toggle="modal" data-target="#delete-library-modal-{{ $library->lib_id }}"
                            class="l-delete-btn">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="delete-library-modal-{{ $library->lib_id }}" tabindex="-1" role="dialog"
                aria-labelledby="deleteLibraryModalLabel{{ $library->lib_id }}" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form action="{{ route('library.destroy', $library->lib_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body text-center">
                                <i class="fas fa-trash fa-2x text-danger mb-3"></i>
                                <p><strong>Delete Library</strong></p>
                                <p>Are you sure you want to delete this library?</p>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div>
            <h5 colspan="10">There are no data.</h5>
        </div>
    @endif
</div>

<!-- Pagination Links -->
<div class="row">
    <div class="col-12">
        {{ $libraries->links() }}
    </div>
</div>