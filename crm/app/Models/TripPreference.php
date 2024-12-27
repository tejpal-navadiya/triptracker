<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class TripPreference extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'preference_id',
        'id',
        'traveler_id',
        'perferred_airport',
        'secondary_airport',
        'perferred_airline',
        'secondary_airline',
        'perferred_class',
        'perferred_seat',
        'air_notes',
        'preferred_embarkation_port',
        'secondary_embarkation_port',
        'favoriate_curuise_line',
        'twond_favoriate_curuise_line',
        'cabine_preference',
        'preferred_deck_location',
        'curuise_note',
        'favorite_hotel_brand',
        'preferred_hotel_type',
        'bed_preference',
        'hotel_notes',
        'favorite_resort',
        'secoundary_resort',
        'preferred_room_type',
        'secoundary_room_type',
        'preferred_meal_plan',
        'preferred_atmosphere',
        'preferred_resort_type',
        'resort_notes',
        'favorite_toure_company',
        'secoundary_favorite_toure_company',
        'guided_tours_preferred_room_type',
        'guided_tours_notes',
        'favorite_car_rental_company',
        'secoundary_favrioute_car_reantal_company',
        'preferred_car_type',
        'car_rental_notes',
        'favorite_theme_park',
        'secoundary_favorite_theme_park',
        'oneside_offside_hotel',
        'theme_park_notes',
        'preference_status',
        'tr_id'
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_trip_preference');
    }

}
