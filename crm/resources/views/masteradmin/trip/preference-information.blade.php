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
   
    <div class="row"> 
    @foreach($tripTypes as $tripType)
       
        <div class="col-lg-4">
            <div class="preferences-box">
                <div class="preferences-title">{{ $tripType->ty_name }}</div>
                <div class="pr-content-box">
                    @if($tripType->ty_name === 'Air')
                    <div class="form-group">
                        <label for="text">Preferred Airport</label>
                        <input class="form-control" type="text" id="perferred_airport" name="perferred_airport" value="{{ $preference->perferred_airport ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Airport</label>
                        <input class="form-control" type="text" id="secondary_airport" name="secondary_airport" value="{{ $preference->secondary_airport ?? ''  }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Airline</label>
                        <input class="form-control" type="text" id="perferred_airline" name="perferred_airline" value="{{ $preference->perferred_airline ?? ''  }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Airline</label>
                        <input class="form-control" type="text" id="secondary_airline" name="secondary_airline" value="{{ $preference->secondary_airline ?? ''  }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Class</label>
                        <input  class="form-control" type="text" id="perferred_class" name="perferred_class" value="{{ $preference->perferred_class ?? ''  }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Seat</label>
                        <input class="form-control" type="text" id="perferred_seat" name="perferred_seat" value="{{ $preference->perferred_seat ?? ''  }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Notes</label>
                        <textarea class="form-control" id="air_notes" name="air_notes">{{ $preference->air_notes ?? ''  }}</textarea>
                    </div>
                    @elseif($tripType->ty_name === 'Cruise')
                    <div class="form-group">
                        <label for="text">Preferred embarkation port</label>
                        <input class="form-control" type="text" id="preferred_embarkation_port" name="preferred_embarkation_port" value="{{ $preference->preferred_embarkation_port  ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary embarkation port</label>
                        <input class="form-control" type="text" id="secondary_embarkation_port" name="secondary_embarkation_port" value="{{ $preference->secondary_embarkation_port ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Favorite Cruise Line</label>
                        <input class="form-control" type="text" id="favoriate_curuise_line" name="favoriate_curuise_line" value="{{ $preference->favoriate_curuise_line ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">2nd Favorite Cruise Line</label>
                        <input class="form-control" type="text" id="twond_favoriate_curuise_line" name="twond_favoriate_curuise_line" value="{{ $preference->twond_favoriate_curuise_line ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Cabin Preference</label>
                        <input class="form-control" type="text" id="cabine_preference" name="cabine_preference" value="{{ $preference->cabine_preference ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Deck Location</label>
                        <input class="form-control" type="text" id="preferred_deck_location" name="preferred_deck_location" value="{{ $preference->preferred_deck_location ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Notes</label>
                        <textarea class="form-control" id="curuise_note" name="curuise_note">{{ $preference->curuise_note ?? '' }}</textarea>
                    </div>
                    @elseif($tripType->ty_name === 'Hotel')

                    <div class="form-group">
                        <label for="text">Favorite Hotel Brand</label>
                        <input class="form-control" type="text" id="favorite_hotel_brand" name="favorite_hotel_brand" value="{{ $preference->favorite_hotel_brand ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Hotel Type</label>
                        <input class="form-control" type="text" id="preferred_hotel_type" name="preferred_hotel_type" value="{{ $preference->preferred_hotel_type ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Bed Preference</label>
                        <input class="form-control" type="text" id="bed_preference" name="bed_preference" value="{{ $preference->bed_preference ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Notes</label>
                        <textarea class="form-control" id="hotel_notes" name="hotel_notes">{{ $preference->hotel_notes ?? '' }}</textarea>
                    </div>

                    @elseif($tripType->ty_name === 'Resort')

                    <div class="form-group">
                        <label for="text">Favorite Resort</label>
                        <input class="form-control" type="text" id="favorite_resort" name="favorite_resort" value="{{ $preference->favorite_resort ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Resort</label>
                        <input class="form-control" type="text" id="secoundary_resort" name="secoundary_resort" value="{{ $preference->secoundary_resort ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Room Type</label>
                        <input class="form-control" type="text" id="preferred_room_type" name="preferred_room_type" value="{{ $preference->preferred_room_type ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Room Type</label>
                        <input class="form-control" type="text" id="secoundary_room_type" name="secoundary_room_type" value="{{ $preference->secoundary_room_type ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Meal Plan</label>
                        <input class="form-control" type="text" id="preferred_meal_plan" name="preferred_meal_plan" value="{{ $preference->preferred_meal_plan ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Atmosphere</label>
                        <input class="form-control" type="text" id="preferred_atmosphere" name="preferred_atmosphere" value="{{ $preference->preferred_atmosphere ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Resort Type</label>
                        <input class="form-control" type="text" id="preferred_resort_type" name="preferred_resort_type" value="{{ $preference->preferred_resort_type ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Notes</label>
                        <textarea class="form-control" id="resort_notes" name="resort_notes">{{ $preference->resort_notes ?? '' }}</textarea>
                    </div>

                    @elseif($tripType->ty_name === 'Guided Tours')

                    <div class="form-group">
                        <label for="text">Favorite Tour Company</label>
                        <input class="form-control" type="favorite_toure_company" id="" name="favorite_toure_company" value="{{ $preference->favorite_toure_company ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Favorite Tour Company</label>
                        <input class="form-control" type="text" id="secoundary_favorite_toure_company" name="secoundary_favorite_toure_company" value="{{ $preference->secoundary_favorite_toure_company ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Room Type</label>
                        <input class="form-control" type="text" id="guided_tours_preferred_room_type" name="guided_tours_preferred_room_type" value="{{ $preference->guided_tours_preferred_room_type ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Note</label>
                        <textarea class="form-control" id="guided_tours_notes" name="guided_tours_notes">{{ $preference->guided_tours_notes ?? '' }}</textarea>
                    </div>

                    @elseif($tripType->ty_name === 'Car Rental')
                    <div class="form-group">
                        <label for="text">Favorite Car Rental Company</label>
                        <input class="form-control" type="text" id="favorite_car_rental_company" name="favorite_car_rental_company" value="{{ $preference->favorite_car_rental_company ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Favorite Car Rental Company</label> 
                        <input class="form-control" type="text" id="secoundary_favrioute_car_reantal_company" name="secoundary_favrioute_car_reantal_company" value="{{ $preference->secoundary_favrioute_car_reantal_company ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Preferred Car Type</label>
                        <input class="form-control" type="text" id="preferred_car_type" name="preferred_car_type" value="{{ $preference->preferred_car_type ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Notes</label>
                        <input class="form-control" type="text" id="car_rental_notes" name="car_rental_notes" value="{{ $preference->car_rental_notes ?? '' }}">
                    </div>
                    @elseif($tripType->ty_name === 'Theme Park')
                    <div class="form-group">
                        <label for="text">Favorite Theme Park</la>
                        <input class="form-control" type="text" id="favorite_theme_park" name="favorite_theme_park" value="{{ $preference->favorite_theme_park ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Secondary Favorite Theme Park</label>
                        <input class="form-control" type="text" id="secoundary_favorite_theme_park" name="secoundary_favorite_theme_park" value="{{ $preference->secoundary_favorite_theme_park ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">On Site or off Site Hotel</label>
                        <input class="form-control" type="text" id="oneside_offside_hotel" name="oneside_offside_hotel" value="{{ $preference->oneside_offside_hotel ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="text">Notes</label>
                        <textarea class="form-control" id="theme_park_notes" name="theme_park_notes">{{ $preference->theme_park_notes ?? '' }}</textarea>
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
<script>

</script>