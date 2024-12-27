<div class="card">
    <div class="card-header">
    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @php
            Session::forget('success');
        @endphp
    @endif
   <!-- <?php //dD($trip_id); ?>      -->
    <form action="{{ route('trip-preferences.store',$trip_id) }}" method="POST">
    @csrf
   
    

    <div class="form-group">
        <label for="trvm_id">Household <span class="text-danger">*</span></label>
        <div class="d-flex">
            <select class="form-control select2" style="width: 100%;" id="trvm_id"
                name="traveler_id" onchange="fetchHouseholdPreferences(this)">
                <option value="" selected>Select Household</option>

                <!-- Loop through traveling members -->
                @foreach ($tripTravelingMembers as $member)
                    <option value="{{ $member->trtm_id }}">
                        {{ $member->trtm_first_name }}
                        {{ $member->trtm_last_name }}

                    </option>
                @endforeach

                <!-- Loop through trips -->
                @foreach ($tripData as $trip)
                    <option value="{{ $trip->trtm_id }}">
                        {{ $trip->trtm_first_name ?? '' }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('traveler_id')" />
        </div>
    </div>
    <div class="row"> 
    @foreach($tripTypes as $tripType)
       
        <div class="col-lg-4">
            <div class="preferences-box">
                <div class="preferences-title">{{ $tripType->ty_name }}</div>
                <div class="pr-content-box">
                    @if($tripType->ty_name === 'Air')
                    <div class="form-group">
                        <label for="text">Preferred Airport</label>
                        <input class="form-control" type="text" id="perferred_airport" name="perferred_airport" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Airport</label>
                        <input class="form-control" type="text" id="secondary_airport" name="secondary_airport" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Airline</label>
                        <input class="form-control" type="text" id="perferred_airline" name="perferred_airline" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Airline</label>
                        <input class="form-control" type="text" id="secondary_airline" name="secondary_airline" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Class</label>
                        <input  class="form-control" type="text" id="perferred_class" name="perferred_class" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Seat</label>
                        <input class="form-control" type="text" id="perferred_seat" name="perferred_seat" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Notes</label>
                        <textarea class="form-control" id="air_notes" name="air_notes"></textarea>
                    </div>
                    @elseif($tripType->ty_name === 'Cruise')
                    <div class="form-group">
                        <label for="text">Preferred Embarkation Port</label>
                        <input class="form-control" type="text" id="preferred_embarkation_port" name="preferred_embarkation_port" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Embarkation Port</label>
                        <input class="form-control" type="text" id="secondary_embarkation_port" name="secondary_embarkation_port" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Favorite Cruise Line</label>
                        <input class="form-control" type="text" id="favoriate_curuise_line" name="favoriate_curuise_line" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">2nd Favorite Cruise Line</label>
                        <input class="form-control" type="text" id="twond_favoriate_curuise_line" name="twond_favoriate_curuise_line" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Cabin Preference</label>
                        <input class="form-control" type="text" id="cabine_preference" name="cabine_preference" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Deck Location</label>
                        <input class="form-control" type="text" id="preferred_deck_location" name="preferred_deck_location" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Notes</label>
                        <textarea class="form-control" id="curuise_note" name="curuise_note"></textarea>
                    </div>
                    @elseif($tripType->ty_name === 'Hotel')

                    <div class="form-group">
                        <label for="text">Favorite Hotel Brand</label>
                        <input class="form-control" type="text" id="favorite_hotel_brand" name="favorite_hotel_brand" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Hotel Type</label>
                        <input class="form-control" type="text" id="preferred_hotel_type" name="preferred_hotel_type" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Bed Preference</label>
                        <input class="form-control" type="text" id="bed_preference" name="bed_preference" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Notes</label>
                        <textarea class="form-control" id="hotel_notes" name="hotel_notes"></textarea>
                    </div>

                    @elseif($tripType->ty_name === 'Resort')

                    <div class="form-group">
                        <label for="text">Favorite Resort</label>
                        <input class="form-control" type="text" id="favorite_resort" name="favorite_resort" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Resort</label>
                        <input class="form-control" type="text" id="secoundary_resort" name="secoundary_resort" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Room Type</label>
                        <input class="form-control" type="text" id="preferred_room_type" name="preferred_room_type" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Room Type</label>
                        <input class="form-control" type="text" id="secoundary_room_type" name="secoundary_room_type" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Meal Plan</label>
                        <input class="form-control" type="text" id="preferred_meal_plan" name="preferred_meal_plan" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Atmosphere</label>
                        <input class="form-control" type="text" id="preferred_atmosphere" name="preferred_atmosphere" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Resort Type</label>
                        <input class="form-control" type="text" id="preferred_resort_type" name="preferred_resort_type" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Notes</label>
                        <textarea class="form-control" id="resort_notes" name="resort_notes"></textarea>
                    </div>

                    @elseif($tripType->ty_name === 'Guided Tours')

                    <div class="form-group">
                        <label for="text">Favorite Tour Company</label>
                        <input class="form-control" type="text" id="favorite_toure_company" name="favorite_toure_company" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Favorite Tour Company</label>
                        <input class="form-control" type="text" id="secoundary_favorite_toure_company" name="secoundary_favorite_toure_company" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Room Type</label>
                        <input class="form-control" type="text" id="guided_tours_preferred_room_type" name="guided_tours_preferred_room_type" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Note</label>
                        <textarea class="form-control" id="guided_tours_notes" name="guided_tours_notes"></textarea>
                    </div>

                    @elseif($tripType->ty_name === 'Car Rental')
                    <div class="form-group">
                        <label for="text">Favorite Car Rental Company</label>
                        <input class="form-control" type="text" id="favorite_car_rental_company" name="favorite_car_rental_company" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Favorite Car Rental Company</label> 
                        <input class="form-control" type="text" id="secoundary_favrioute_car_reantal_company" name="secoundary_favrioute_car_reantal_company" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Car Type</label>
                        <input class="form-control" type="text" id="preferred_car_type" name="preferred_car_type" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Notes</label>
                        <input class="form-control" type="text" id="car_rental_notes" name="car_rental_notes" value="">
                    </div>
                    @elseif($tripType->ty_name === 'Theme Park')
                    <div class="form-group">
                        <label for="text">Favorite Theme Park</la>
                        <input class="form-control" type="text" id="favorite_theme_park" name="favorite_theme_park" value="">
                    <div class="form-group">
                        <label for="text">Secondary Favorite Theme Park</label>
                        <input class="form-control" type="text" id="secoundary_favorite_theme_park" name="secoundary_favorite_theme_park" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">On Site or off Site Hotel</label>
                        <input class="form-control" type="text" id="oneside_offside_hotel" name="oneside_offside_hotel" value="">
                    </div>
                    <div class="form-group">
                        <label for="text">Notes</label>
                        <textarea class="form-control" id="theme_park_notes" name="theme_park_notes"></textarea>
                    </div>
                    @endif
                        
                    
                </div>
            </div>
            </div>
            @endforeach
        </div>
            <button type="submit">Submit</button>
        </form>
        
    
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Bind the function to the onchange event after DOM content is loaded
        document.getElementById('trvm_id').addEventListener('change', function() {
            fetchHouseholdPreferences(this);
        });
    });

    function fetchHouseholdPreferences(selectElement) {
        const householdId = selectElement.value;

        if (householdId) {
            const url = "{{ route('trip-preferences.show', ':id') }}".replace(':id', householdId);
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    console.log(response.preference);
                    if (response.preference) {

                    document.getElementById('perferred_airport').value = response.preference.perferred_airport || '';
                    document.getElementById('secondary_airport').value = response.preference.secondary_airport || '';
                    document.getElementById('perferred_airline').value = response.preference.perferred_airline || '';
                    document.getElementById('secondary_airline').value = response.preference.secondary_airline || '';

                    document.getElementById('perferred_class').value = response.preference.perferred_class || '';
                    document.getElementById('perferred_seat').value = response.preference.perferred_seat || '';
                    document.getElementById('air_notes').value = response.preference.air_notes || '';
                    document.getElementById('preferred_embarkation_port').value = response.preference.preferred_embarkation_port || '';
                    document.getElementById('secondary_embarkation_port').value = response.preference.secondary_embarkation_port || '';
                    document.getElementById('favoriate_curuise_line').value = response.preference.favoriate_curuise_line || '';
                    document.getElementById('twond_favoriate_curuise_line').value = response.preference.twond_favoriate_curuise_line || '';
                    document.getElementById('cabine_preference').value = response.preference.cabine_preference || '';
                    document.getElementById('preferred_deck_location').value = response.preference.preferred_deck_location || '';
                    document.getElementById('curuise_note').value = response.preference.curuise_note || '';
                    document.getElementById('favorite_hotel_brand').value = response.preference.favorite_hotel_brand || '';
                    document.getElementById('preferred_hotel_type').value = response.preference.preferred_hotel_type || '';
                    document.getElementById('bed_preference').value = response.preference.bed_preference || '';
                    document.getElementById('hotel_notes').value = response.preference.hotel_notes || '';
                    document.getElementById('favorite_resort').value = response.preference.favorite_resort || '';
                    document.getElementById('secoundary_resort').value = response.preference.secoundary_resort || '';
                    document.getElementById('preferred_room_type').value = response.preference.preferred_room_type || '';
                    document.getElementById('secoundary_room_type').value = response.preference.secoundary_room_type || '';
                    document.getElementById('preferred_meal_plan').value = response.preference.preferred_meal_plan || '';
                    document.getElementById('preferred_atmosphere').value = response.preference.preferred_atmosphere || '';
                    document.getElementById('preferred_resort_type').value = response.preference.preferred_resort_type || '';
                    document.getElementById('resort_notes').value = response.preference.resort_notes || '';
                    // document.getElementById('favorite_toure_company').value = response.preference.favorite_toure_company || '';
                    $('#favorite_toure_company').val(response.preference.favorite_toure_company || '');
                    $('#secoundary_favorite_toure_company').val(response.preference.secoundary_favorite_toure_company || '');

                    //document.getElementById('secoundary_favorite_toure_company').value = response.preference.secoundary_favorite_toure_company || '';

                    $('#guided_tours_preferred_room_type').val(response.preference.guided_tours_preferred_room_type || '');
                    //document.getElementById('guided_tours_preferred_room_type').value = response.preference.guided_tours_preferred_room_type || '';
                    $('#guided_tours_notes').val(response.preference.guided_tours_notes || '');

                    //document.getElementById('guided_tours_notes').value = response.preference.guided_tours_notes || '';
                    document.getElementById('favorite_car_rental_company').value = response.preference.favorite_car_rental_company || '';
                    document.getElementById('secoundary_favrioute_car_reantal_company').value = response.preference.secoundary_favrioute_car_reantal_company || '';
                    document.getElementById('car_rental_notes').value = response.preference.car_rental_notes || '';
                    document.getElementById('favorite_theme_park').value = response.preference.favorite_theme_park || '';
                    

                    document.getElementById('secoundary_favorite_theme_park').value = response.preference.secoundary_favorite_theme_park || '';
                    document.getElementById('oneside_offside_hotel').value = response.preference.oneside_offside_hotel || '';
                    document.getElementById('theme_park_notes').value = response.preference.theme_park_notes || '';

                    document.getElementById('preferred_car_type').value = response.preference.preferred_car_type || '';
                
                    }else{
                    document.getElementById('perferred_airport').value =  '';
                    document.getElementById('secondary_airport').value =  '';
                    document.getElementById('perferred_airline').value =  '';
                    document.getElementById('secondary_airline').value = '';

                    document.getElementById('perferred_class').value =  '';
                    document.getElementById('perferred_seat').value =  '';
                    document.getElementById('air_notes').value = '';
                    document.getElementById('preferred_embarkation_port').value = '';
                    document.getElementById('secondary_embarkation_port').value = '';
                    document.getElementById('favoriate_curuise_line').value = '';
                    document.getElementById('twond_favoriate_curuise_line').value = '';
                    document.getElementById('cabine_preference').value = '';
                    document.getElementById('preferred_deck_location').value = '';
                    document.getElementById('curuise_note').value = '';
                    document.getElementById('favorite_hotel_brand').value = '';
                    document.getElementById('preferred_hotel_type').value ='';
                    document.getElementById('bed_preference').value = '';
                    document.getElementById('hotel_notes').value = '';
                    document.getElementById('favorite_resort').value = '';
                    document.getElementById('secoundary_resort').value = '';
                    document.getElementById('preferred_room_type').value = '';
                    document.getElementById('secoundary_room_type').value = '';
                    document.getElementById('preferred_meal_plan').value = '';
                    document.getElementById('preferred_atmosphere').value ='';
                    document.getElementById('preferred_resort_type').value =  '';
                    document.getElementById('resort_notes').value = '';
                    $('#favorite_toure_company').val('');
                    $('#secoundary_favorite_toure_company').val('');
                    $('#guided_tours_preferred_room_type').val('');
                    $('#guided_tours_notes').val('');
                    // document.getElementById('favorite_toure_company').value ='';
                    // document.getElementById('secoundary_favorite_toure_company').value = '';
                    // document.getElementById('guided_tours_preferred_room_type').value =  '';
                    // document.getElementById('guided_tours_notes').value =  '';
                    document.getElementById('favorite_car_rental_company').value =  '';
                    document.getElementById('secoundary_favrioute_car_reantal_company').value =  '';
                    document.getElementById('car_rental_notes').value =  '';
                    document.getElementById('favorite_theme_park').value =  '';
                
                    document.getElementById('secoundary_favorite_theme_park').value = '';
                    document.getElementById('oneside_offside_hotel').value = '';
                    document.getElementById('theme_park_notes').value = '';

                    document.getElementById('preferred_car_type').value = '';

                    
                    

                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        } else {
            // Reset the fields if no household is selected
            document.getElementById('perferred_airport').value = '';
            document.getElementById('secondary_airport').value = '';
            document.getElementById('perferred_airline').value = '';
            document.getElementById('preferred_car_type').value = '';
        }
    }
</script>

