<!DOCTYPE html>

@extends('masteradmin.layouts.app')
<title>Personal Profile | Trip Tracker</title>
@section('content')
@if(isset($access['edit_profile']) && $access['edit_profile']) 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 align-items-center">
          <div class="col-sm-12">
            <h1 class="m-0">Personal Profile</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Personal Profile</li>
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
                      
                          <option value="{{ $category->category }}" {{ isset($emailTemplate) && $emailTemplate->category == $category->category ? 'selected' : '' }}>
                              {{ $category->category }}
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
                  
                      <option value="{{ $travel->tr_id }}" {{ isset($emailTemplate) && $emailTemplate->traveller_id == $travel->traveller_id ? 'selected' : '' }}>
                          {{ $travel->tr_traveler_name }} <!-- Display the traveller name or other attribute -->
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
        // Listen for change on the category dropdown
        $('#category').on('change', function() {
            var selectedCategory = $(this).val(); // Get the selected category

            // Send AJAX request to fetch email text
            $.ajax({
                url: '{{ route('fetchEmailText') }}', // Route to fetchEmailText method
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Laravel CSRF token
                    category: selectedCategory
                },
                success: function(response) {
                    // Check if TinyMCE is initialized and update the content
                    if (tinymce.get('email_text')) {
                        tinymce.get('email_text').setContent(response.email_text);  // Correctly update TinyMCE content
                    } else {
                        // If TinyMCE is not used, update the textarea directly
                        $('#email_text').val(response.email_text);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        });
    });


     // When traveller is selected, fetch traveller details and replace placeholders
    //  $('#traveller_id').on('change', function() {
    //     var selectedTraveller = $(this).val(); // Get the selected traveller ID

    //     // Send AJAX request to fetch traveller details
    //     $.ajax({
    //         url: '{{ route('fetchTravellerDetails') }}', // Route to fetch traveller details
    //         type: 'POST',
    //         data: {
    //             _token: '{{ csrf_token() }}', // Laravel CSRF token
    //             traveller_id: selectedTraveller
    //         },
    //         success: function(response) {
    //             // Replace placeholders in the email template
    //             let updatedEmailText = emailTemplateText
    //                 .replace(/{client_name}/g, response.client_name)
    //                 .replace(/{phone_number}/g, response.phone_number)
    //                 .replace(/{booking_number}/g, response.booking_number);

    //             // Update TinyMCE or textarea with the updated content
    //             if (tinymce.get('email_text')) {
    //                 tinymce.get('email_text').setContent(updatedEmailText);
    //             } else {
    //                 $('#email_text').val(updatedEmailText);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('AJAX Error:', error);
    //         }
    //     });
    // });

//     $('#traveller_id').on('change', function() {
//     // var travellerId = $(this).val(); // Get selected traveller IDvar 
//     var travellerId = $('select#traveller_id option:selected').val();
// alert(travellerId);
//     // Send AJAX request to fetch traveller details
//     $.ajax({
//         url: '{{ route('fetchTravellerDetails') }}', // Route for fetching traveller details
//         type: 'POST',
//         data: {
//             _token: '{{ csrf_token() }}', // Laravel CSRF token
//             traveller_id: travellerId
//         },
//         success: function(response) {
//             if (response.error) {
//                 console.error(response.error); // Log any error message
//             } else {
//                 // Fill email text with traveller data
//                 if (tinymce.get('email_text')) {
//                     tinymce.get('email_text').setContent(response.tr_traveler_name);  // Update TinyMCE content
//                 } else {
//                     $('#email_text').val(response.tr_traveler_name); // If TinyMCE not used
//                 }
//             }
//         },
//         error: function(xhr, status, error) {
//             console.error('AJAX Error:', error);
//         }
//     });
// });

// $('#traveller_id').on('change', function() {
//     var travellerId = $('select#traveller_id option:selected').val(); // Get selected traveller ID

//     // Send AJAX request to fetch traveller details
//     $.ajax({
//         url: '{{ route('fetchTravellerDetails') }}', // Route for fetching traveller details
//         type: 'POST',
//         data: {
//             _token: '{{ csrf_token() }}', // Laravel CSRF token
//             traveller_id: travellerId
//         },
//         success: function(response) {
//             if (response.error) {
//                 console.error(response.error); // Log any error message
//             } else {
//                 // Fetch email template from the database via AJAX or already have it in JS
//                 // Get the response email text and modify it
// let emailTemplateText = `Dear {client_name},\n\n` + response.email_text;

// // Set the modified content in your editor (TinyMCE in this case)
// tinymce.get('email_text').setContent(emailTemplateText);


//                 // Replace placeholders with actual traveller data
//                 emailTemplateText = emailTemplateText.replace('{client_name}', response.tr_traveler_name)
//                                                      .replace('{client_email}', response.email); // If email is part of the template

//                 // Update TinyMCE or textarea with the updated content
//                 if (tinymce.get('email_text')) {
//                     tinymce.get('email_text').setContent(emailTemplateText);
//                 } else {
//                     $('#email_text').val(emailTemplateText); // If TinyMCE is not used
//                 }
//             }
//         },
//         error: function(xhr, status, error) {
//             console.error('AJAX Error:', error);
//         }
//     });
// });



</script>



@endif
@endsection
