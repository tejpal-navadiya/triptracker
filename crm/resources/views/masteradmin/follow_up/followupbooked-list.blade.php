<div id="filter_data">
<div class="card-header d-flex p-0 justify-content-center tab_panal mt-4">
    <ul class="nav nav-pills p-2 tab_box tab_box12">
        <li class="nav-item">
            <a class="nav-link active" href="#inprocessTrip" data-toggle="tab" data-tab="pending">Trip Traveling</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#completeTrip" data-toggle="tab" data-tab="complete">Welcome Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#sixmonthTrip" data-toggle="tab" data-tab="sixmonth">6 month Review</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#oneyearTrip" data-toggle="tab" data-tab="oneyear">1 year Review</a>
        </li>
    </ul>
</div><!-- /.card-header -->

<div class="tab-content tab-content12">
    <div class="tab-pane active" id="inprocessTrip">
        @include('masteradmin.follow_up.pending-information')
    </div>
    <!-- /.tab-pane -->
    <div class="tab-pane" id="completeTrip">
        @include('masteradmin.follow_up.complete-information')
    </div>
    <div class="tab-pane" id="sixmonthTrip">
        @include('masteradmin.follow_up.sixmonth-information')
    </div>
    <div class="tab-pane" id="oneyearTrip">
        @include('masteradmin.follow_up.oneyear-information')
    </div>
    <!-- /.tab-pane -->
</div>
</div>