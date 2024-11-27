<div class="card">
    <div class="col-lg-12 card-body3">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6">
                <p class="company_business_name">Name :{{ $trip->users_first_name ?? '' }} {{ $trip->users_last_name ?? '' }}</p>
                <p class="company_business_name">Phone Number : {{ $trip->user_emergency_phone_number ?? '' }}</p>
                <p class="company_business_name">Email Address : {{ $trip->users_email ?? ''}}</p>
                <p class="company_business_name">Address :  {{ $trip->city_name ?? '' }}{{ $trip->city_name && ($trip->state_name || $trip->country_name || $trip->tr_zip) ? ', ' : '' }}
                                                    {{ $trip->state_name ?? '' }}{{ $trip->state_name && ($trip->country_name || $trip->users_zip) ? ', ' : '' }}
                                                    {{ $trip->country_name ?? '' }}{{ $trip->country_name && $trip->users_zip ? ' ' : '' }}
                                                    {{ $trip->users_zip ?? '' }}</p>
            </div>
        </div>
    </div>

</div>
