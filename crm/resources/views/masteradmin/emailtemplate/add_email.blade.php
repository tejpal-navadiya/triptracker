<!DOCTYPE html>

@extends('masteradmin.layouts.app')
<title>Email Templates | Trip Tracker</title>
@section('content')
@if(isset($access['add_email_template']) && $access['add_email_template']) 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 align-items-center">
          <div class="col-sm-12">
            <h1 class="m-0">Email Templates</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
              <li class="breadcrumb-item active">Email Templates</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
    <!-- Main content -->
    <section class="content px-10">
      <div class="container-fluid">
        <div class="card customcard">
          <div class="row">
            
            <!-- Category Dropdown Field -->
            <form action="{{ route('email-template.store') }}" method="POST">
              @csrf <!-- For CSRF protection -->
              
              <!-- Category Dropdown -->
              <div class="form-group">
                  <label for="category">Category</label>
                  <select class="form-control" id="category" name="category_id">
                  <option>Select</option>
                      @foreach($categories as $category)
                          <option value="{{ $category->email_cat_id }}" {{ isset($emailTemplate) && $emailTemplate->category == $category->email_cat_id ? 'selected' : '' }}>
                              {{ $category->email_cat_name }}
                          </option>
                      @endforeach
                  </select>
              </div>

              <div class="form-group">
                <label for="category">Traveller</label>
                <!-- <label for="category">Category</label> -->
                <select class="form-control" id="traveller_id" name="traveller_id">
                <option>Select</option>
                  @foreach($travellers as $travel) <!-- Ensure the variable name is correct -->
                  
                      <option value="{{ $travel->trtm_id }}" {{ isset($emailTemplate) && $emailTemplate->traveller_id == $travel->traveller_id ? 'selected' : '' }}>
                          {{ $travel->trtm_first_name }} <!-- Display the traveller name or other attribute -->
                      </option>
                  @endforeach
              </select>

              </div>
              <!-- </div> -->

              <!-- Basic Information Textarea -->
              <x-input-label for="email_text" :value="__('Basic Information')" />
              <textarea class="form-control" id="email_text" name="email_text" placeholder="Enter Basic Information"></textarea>
              <x-input-error class="mt-2" :messages="$errors->get('lib_basic_information')" />

              <!-- Submit Button -->
              <button type="submit" class="btn btn-primary mt-3">Save</button>
            </form>
            
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here --> 
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<script src="{{ url('public/js/tinymce/tinymce.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    tinymce.init({
        selector: 'textarea', // This will apply TinyMCE to all textareas
        menubar: false,
        plugins: 'code table lists image',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | table | image',
    });
</script>


<script>
   
    $(document).ready(function() {
      $('#category').on('change', function() {
    var selectedCategory = $(this).val(); // Get the selected category

    // Send AJAX request to fetch email text
    $.ajax({
        url: '{{ route('fetchEmailText') }}', // Route to fetch email text
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}', // Laravel CSRF token
            category: selectedCategory
        },
        success: function(response) {
            // Update the email text with the fetched content
            let emailText = response.email_text;

            // Check if TinyMCE is initialized and update the content
            if (tinymce.get('email_text')) {
                tinymce.get('email_text').setContent(emailText);
            } else {
                $('#email_text').val(emailText);
            }

            // Optionally, you can fetch traveller details if a traveller is already selected
            // var selectedTraveller = $('#traveller_id').val();
            // if (selectedTraveller) {
            //     fetchTravellerDetails(selectedTraveller, emailText);
            // }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });
});

$('#traveller_id').on('change', function() {
    var selectedTraveller = $(this).val(); // Get the selected traveller ID
    var currentEmailText = tinymce.get('email_text') ? tinymce.get('email_text').getContent() : $('#email_text').val();

    // Fetch traveller details and update email text
    fetchTravellerDetails(selectedTraveller, currentEmailText);
});

function fetchTravellerDetails(selectedTraveller, currentEmailText) {
    // Send AJAX request to fetch traveller details
    $.ajax({
        url: '{{ route('fetchTravellerDetails') }}', // Route to fetch traveller details
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}', // Laravel CSRF token
            traveller_id: selectedTraveller
        },
        success: function(response) {
            // Replace placeholders in the email template
            let updatedEmailText = currentEmailText
                .replace(/\[First Name\]/g, response.tr_traveler_name) 
                .replace(/\[Dummy Sender Name\]/g, response.tr_traveler_name) 
                // .replace(/\[Dummy Title\]/g, 'Test Title') 
                .replace(/\[Dummy Contact Info\]/g, response.tr_number); 

            // Update TinyMCE or textarea with the updated content
            if (tinymce.get('email_text')) {
                tinymce.get('email_text').setContent(updatedEmailText);
            } else {
                $('#email_text').val(updatedEmailText);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });
}
    });
      
</script>



@endif
@endsection
