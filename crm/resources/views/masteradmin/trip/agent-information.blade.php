<div class="card">
    <div class="col-lg-12 card-body3">
        <div class="card-body d-flex data-tab-content">
        <table class="table card-body-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td><p class="company_business_name">Name : {{ $trip->users_first_name ?? '' }} {{ $trip->users_last_name ?? '' }}</p></td>
                </tr>
                <tr>
                    <td><p class="company_business_name">Phone Number : 
                    {{ $trip->user_emergency_phone_number ?? ($trip->users_phone ?? '') }}
                    </p></td>
                </tr>
                <tr>
                    <td><p class="company_business_name">Email Address : {{ $trip->users_email ?? ''}}</p></td>                    
                </tr>                
            </table>

            <table class="table card-body-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td> <p class="company_business_name">Address :  {{ $trip->city_name ?? '' }}{{ $trip->city_name && ($trip->state_name || $trip->country_name || $trip->tr_zip) ? ', ' : '' }}
                                                    {{ $trip->state_name ?? '' }}{{ $trip->state_name && ($trip->country_name || $trip->users_zip) ? ', ' : '' }}
                                                    {{ $trip->country_name ?? '' }}{{ $trip->country_name && $trip->users_zip ? ' ' : '' }}
                                                    {{ $trip->users_zip ?? '' }}</p></td>
                </tr>
              
            </table>
          
        </div>
    </div>

</div>
