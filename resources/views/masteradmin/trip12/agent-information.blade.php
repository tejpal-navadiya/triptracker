<div class="card">
    <div class="col-lg-12 card-body3">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6">
                <p class="company_business_name">Name :{{ $trip->users_first_name ?? '' }} {{ $trip->users_last_name ?? '' }}</p>
                <p class="company_business_name">Phone Number : {{ $trip->tr_phone ?? '' }}</p>
                <p class="company_business_name">Email Address : {{ $trip->tr_email ?? ''}}</p>
                <p class="company_business_name">Address : {{ $trip->tr_phone ?? '' }}</p>
            </div>
        </div>
    </div>

</div>
