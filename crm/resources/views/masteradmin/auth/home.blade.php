@extends('masteradmin.layouts.app')
<title>Analytics | Trip Tracker</title>
@section('content')
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ url('public/dist/img/logo.png') }}" alt="Trip Tracker Logo">
</div>
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">
<style>.container {
    margin-top: 20px;
}

.section-title {
    font-weight: bold;
    color: #4CAF50;
    margin-bottom: 15px;
}

.table th, .table td {
    vertical-align: middle;
}

.table .text-right {
    text-align: right;
}

.status-btn {
    padding: 4px 10px;
    border-radius: 15px;
    color: white;
}

.sent-status {
    background-color: #00C851;
}

.draft-status {
    background-color: #D980FA;
}

.paid-status {
    background-color: #33b5e5;
}

.statistics-card {
    border: 1px solid #e0e0e0;
    padding: 15px;
    border-radius: 8px;
    background-color: #f8f9fa;
}

.statistics-card h6 {
    font-weight: bold;
    color: #4CAF50;
    margin-bottom: 15px;
}

.stats-list {
    list-style-type: none;
    padding: 0;
}

.stats-list li {
    display: flex;
    justify-content: space-between;
    font-size: 16px;
    margin-bottom: 10px;
}

.stats-list li span {
    font-weight: bold;
}
</style>
<div class="content-wrapper">

    @if(session('alert-configured-data'))
    <div class="alert alert-info" id="alertConfigured">
        {{ session('alert-configured-data') }}
    </div>
    @endif
    <!-- Main content -->

    
    @if (session('beforshowModal'))
    <div class="alert alert-info" id="subscriptionStatus">
       {{ session('beforshowModal') }}
    </div>
    {{ session()->forget('beforshowModal') }}
    @endif


    <section class="content">
        <div class="container-fluid">
            <!-- @if (session('alert-configured-data')) -->
            <div class="modal fade" id="configured-modal" tabindex="-1" role="dialog"
            aria-labelledby="configuredModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <i class="fas fa-trash fa-2x text-danger mb-3"></i>
                        <p><strong>
                            <div class="alert alert-info">
                                {{ session('alert-configured-data') }}
                            </div>
                        </strong></p>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function () {
                        // $('#configured-modal').modal('show');

                        // setTimeout(function () {
                        //     $('#configured-modal').modal('hide');
                        // }, 5000);
                        setTimeout(function () {
                            $('#alertConfigured').hide();
                        }, 5000);

                        setTimeout(function () {
                            $('#subscriptionStatus').hide();
                        }, 6000);
                    });
                </script>
                <!-- @endif -->
                <!-- Small boxes (Stat box) -->

                <!-- /.row -->
                <!-- Main row -->

                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>

        <!-- add by dx -->
        <section class="content">
          <div class="container-fluid">
            <div class="dadh_bord_heding">Analytics</div>
            <!-- Small boxes (Stat box) -->
            <div class="row px-20">

              <!-- ./col -->
              <div class="col-lg-3 col-md-6 col-mdash-box">
                <!-- small box -->
                <div class="small-box bg-customers-new">
                  <svg width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
<rect width="75" height="75" rx="7.5" fill="#FEEDED"/>
<path d="M42.688 54H26C23.242 54 21 51.758 21 49C21 46.242 23.242 44 26 44H40C43.86 44 47 40.86 47 37C47 33.14 43.86 30 40 30H30.278C29.5435 31.4398 28.6339 32.7833 27.57 34H40C41.654 34 43 35.346 43 37C43 38.654 41.654 40 40 40H26C21.038 40 17 44.038 17 49C17 53.962 21.038 58 26 58H45.186C44.2046 56.7655 43.3666 55.4236 42.688 54ZM23 18C19.692 18 17 20.692 17 24C17 30.376 23 34 23 34C23 34 29 30.374 29 24C29 20.692 26.308 18 23 18ZM23 27C22.6059 26.9999 22.2157 26.9221 21.8516 26.7712C21.4876 26.6202 21.1568 26.3991 20.8783 26.1203C20.5997 25.8416 20.3787 25.5107 20.2281 25.1465C20.0774 24.7824 19.9999 24.3921 20 23.998C20.0001 23.6039 20.0779 23.2137 20.2288 22.8496C20.3798 22.4856 20.6009 22.1548 20.8797 21.8763C21.1584 21.5977 21.4893 21.3767 21.8535 21.2261C22.2176 21.0754 22.6079 20.9979 23.002 20.998C23.7979 20.9983 24.5611 21.3147 25.1237 21.8777C25.6863 22.4407 26.0023 23.2041 26.002 24C26.0017 24.7959 25.6853 25.5591 25.1223 26.1217C24.5593 26.6843 23.7959 27.0003 23 27Z" fill="#FF9F43"/>
<path d="M51 42C47.692 42 45 44.692 45 48C45 54.376 51 58 51 58C51 58 57 54.374 57 48C57 44.692 54.308 42 51 42ZM51 51C50.6059 50.9999 50.2157 50.9221 49.8516 50.7712C49.4876 50.6202 49.1568 50.3991 48.8783 50.1203C48.5997 49.8416 48.3787 49.5107 48.2281 49.1465C48.0774 48.7824 47.9999 48.3921 48 47.998C48.0001 47.6039 48.0779 47.2137 48.2288 46.8496C48.3798 46.4856 48.6009 46.1548 48.8797 45.8763C49.1584 45.5977 49.4893 45.3767 49.8535 45.2261C50.2176 45.0754 50.6079 44.9979 51.002 44.998C51.7979 44.9983 52.5611 45.3147 53.1237 45.8777C53.6863 46.4407 54.0023 47.2041 54.002 48C54.0017 48.7959 53.6853 49.5591 53.1223 50.1217C52.5593 50.6843 51.7959 51.0003 51 51Z" fill="#FF9F43"/>
</svg>

                  <div><p class="total_text">Total Trips</p>
                  <h3 class="customer_total">{{$totalTrips}}</h3>
                  </div>
              </div>
          </div>
          <!-- ./col --> 
          <div class="col-lg-3 col-md-6 col-mdash-box">
            <!-- small box -->
            <div class="small-box bg-customers-new">
              <svg width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
<rect width="75" height="75" rx="7.5" fill="#E0F9FC"/>
<path d="M42.688 54H26C23.242 54 21 51.758 21 49C21 46.242 23.242 44 26 44H40C43.86 44 47 40.86 47 37C47 33.14 43.86 30 40 30H30.278C29.5435 31.4398 28.6339 32.7833 27.57 34H40C41.654 34 43 35.346 43 37C43 38.654 41.654 40 40 40H26C21.038 40 17 44.038 17 49C17 53.962 21.038 58 26 58H45.186C44.2046 56.7655 43.3666 55.4236 42.688 54ZM23 18C19.692 18 17 20.692 17 24C17 30.376 23 34 23 34C23 34 29 30.374 29 24C29 20.692 26.308 18 23 18ZM23 27C22.6059 26.9999 22.2157 26.9221 21.8516 26.7712C21.4876 26.6202 21.1568 26.3991 20.8783 26.1203C20.5997 25.8416 20.3787 25.5107 20.2281 25.1465C20.0774 24.7824 19.9999 24.3921 20 23.998C20.0001 23.6039 20.0779 23.2137 20.2288 22.8496C20.3798 22.4856 20.6009 22.1548 20.8797 21.8763C21.1584 21.5977 21.4893 21.3767 21.8535 21.2261C22.2176 21.0754 22.6079 20.9979 23.002 20.998C23.7979 20.9983 24.5611 21.3147 25.1237 21.8777C25.6863 22.4407 26.0023 23.2041 26.002 24C26.0017 24.7959 25.6853 25.5591 25.1223 26.1217C24.5593 26.6843 23.7959 27.0003 23 27Z" fill="#00CFE8"/>
<path d="M51 42C47.692 42 45 44.692 45 48C45 54.376 51 58 51 58C51 58 57 54.374 57 48C57 44.692 54.308 42 51 42ZM51 51C50.6059 50.9999 50.2157 50.9221 49.8516 50.7712C49.4876 50.6202 49.1568 50.3991 48.8783 50.1203C48.5997 49.8416 48.3787 49.5107 48.2281 49.1465C48.0774 48.7824 47.9999 48.3921 48 47.998C48.0001 47.6039 48.0779 47.2137 48.2288 46.8496C48.3798 46.4856 48.6009 46.1548 48.8797 45.8763C49.1584 45.5977 49.4893 45.3767 49.8535 45.2261C50.2176 45.0754 50.6079 44.9979 51.002 44.998C51.7979 44.9983 52.5611 45.3147 53.1237 45.8777C53.6863 46.4407 54.0023 47.2041 54.002 48C54.0017 48.7959 53.6853 49.5591 53.1223 50.1217C52.5593 50.6843 51.7959 51.0003 51 51Z" fill="#00CFE8"/>
</svg>

              <div><p class="total_text">Total Trip Accept</p>
              <h3 class="customer_total">{{$acceptTrips}}</h3></div>
          </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-md-6 col-mdash-box">
        <!-- small box -->
        <div class="small-box bg-customers-new">
          <svg width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
<rect width="75" height="75" rx="7.5" fill="#FEF2E9"/>
<path d="M42.688 54H26C23.242 54 21 51.758 21 49C21 46.242 23.242 44 26 44H40C43.86 44 47 40.86 47 37C47 33.14 43.86 30 40 30H30.278C29.5435 31.4398 28.6339 32.7833 27.57 34H40C41.654 34 43 35.346 43 37C43 38.654 41.654 40 40 40H26C21.038 40 17 44.038 17 49C17 53.962 21.038 58 26 58H45.186C44.2046 56.7655 43.3666 55.4236 42.688 54ZM23 18C19.692 18 17 20.692 17 24C17 30.376 23 34 23 34C23 34 29 30.374 29 24C29 20.692 26.308 18 23 18ZM23 27C22.6059 26.9999 22.2157 26.9221 21.8516 26.7712C21.4876 26.6202 21.1568 26.3991 20.8783 26.1203C20.5997 25.8416 20.3787 25.5107 20.2281 25.1465C20.0774 24.7824 19.9999 24.3921 20 23.998C20.0001 23.6039 20.0779 23.2137 20.2288 22.8496C20.3798 22.4856 20.6009 22.1548 20.8797 21.8763C21.1584 21.5977 21.4893 21.3767 21.8535 21.2261C22.2176 21.0754 22.6079 20.9979 23.002 20.998C23.7979 20.9983 24.5611 21.3147 25.1237 21.8777C25.6863 22.4407 26.0023 23.2041 26.002 24C26.0017 24.7959 25.6853 25.5591 25.1223 26.1217C24.5593 26.6843 23.7959 27.0003 23 27Z" fill="#F6A96D"/>
<path d="M51 42C47.692 42 45 44.692 45 48C45 54.376 51 58 51 58C51 58 57 54.374 57 48C57 44.692 54.308 42 51 42ZM51 51C50.6059 50.9999 50.2157 50.9221 49.8516 50.7712C49.4876 50.6202 49.1568 50.3991 48.8783 50.1203C48.5997 49.8416 48.3787 49.5107 48.2281 49.1465C48.0774 48.7824 47.9999 48.3921 48 47.998C48.0001 47.6039 48.0779 47.2137 48.2288 46.8496C48.3798 46.4856 48.6009 46.1548 48.8797 45.8763C49.1584 45.5977 49.4893 45.3767 49.8535 45.2261C50.2176 45.0754 50.6079 44.9979 51.002 44.998C51.7979 44.9983 52.5611 45.3147 53.1237 45.8777C53.6863 46.4407 54.0023 47.2041 54.002 48C54.0017 48.7959 53.6853 49.5591 53.1223 50.1217C52.5593 50.6843 51.7959 51.0003 51 51Z" fill="#F6A96D"/>
</svg>

          <div><p class="total_text">In Progress Trips</p>
          <h3 class="customer_total">{{$inProgressTrips}}</h3></div>
      </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-md-6 col-mdash-box">
    <!-- small box -->
    <div class="small-box bg-customers-new">
      <svg width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
<rect width="75" height="75" rx="7.5" fill="#E5F8ED"/>
<path d="M42.688 54H26C23.242 54 21 51.758 21 49C21 46.242 23.242 44 26 44H40C43.86 44 47 40.86 47 37C47 33.14 43.86 30 40 30H30.278C29.5435 31.4398 28.6339 32.7833 27.57 34H40C41.654 34 43 35.346 43 37C43 38.654 41.654 40 40 40H26C21.038 40 17 44.038 17 49C17 53.962 21.038 58 26 58H45.186C44.2046 56.7655 43.3666 55.4236 42.688 54ZM23 18C19.692 18 17 20.692 17 24C17 30.376 23 34 23 34C23 34 29 30.374 29 24C29 20.692 26.308 18 23 18ZM23 27C22.6059 26.9999 22.2157 26.9221 21.8516 26.7712C21.4876 26.6202 21.1568 26.3991 20.8783 26.1203C20.5997 25.8416 20.3787 25.5107 20.2281 25.1465C20.0774 24.7824 19.9999 24.3921 20 23.998C20.0001 23.6039 20.0779 23.2137 20.2288 22.8496C20.3798 22.4856 20.6009 22.1548 20.8797 21.8763C21.1584 21.5977 21.4893 21.3767 21.8535 21.2261C22.2176 21.0754 22.6079 20.9979 23.002 20.998C23.7979 20.9983 24.5611 21.3147 25.1237 21.8777C25.6863 22.4407 26.0023 23.2041 26.002 24C26.0017 24.7959 25.6853 25.5591 25.1223 26.1217C24.5593 26.6843 23.7959 27.0003 23 27Z" fill="#28C76F"/>
<path d="M51 42C47.692 42 45 44.692 45 48C45 54.376 51 58 51 58C51 58 57 54.374 57 48C57 44.692 54.308 42 51 42ZM51 51C50.6059 50.9999 50.2157 50.9221 49.8516 50.7712C49.4876 50.6202 49.1568 50.3991 48.8783 50.1203C48.5997 49.8416 48.3787 49.5107 48.2281 49.1465C48.0774 48.7824 47.9999 48.3921 48 47.998C48.0001 47.6039 48.0779 47.2137 48.2288 46.8496C48.3798 46.4856 48.6009 46.1548 48.8797 45.8763C49.1584 45.5977 49.4893 45.3767 49.8535 45.2261C50.2176 45.0754 50.6079 44.9979 51.002 44.998C51.7979 44.9983 52.5611 45.3147 53.1237 45.8777C53.6863 46.4407 54.0023 47.2041 54.002 48C54.0017 48.7959 53.6853 49.5591 53.1223 50.1217C52.5593 50.6843 51.7959 51.0003 51 51Z" fill="#28C76F"/>
</svg>

      <div><p class="total_text">Completed Trips</p>
      <h3 class="customer_total">{{$totalcompletedTrips}}</h3>
      </div>
  </div>
</div>
</div>
<!-- ./col -->
<!-- ./col -->
<div class="row">
@if ($user->role_id == 0)
<div class="col-lg-3 col-md-6 col-mdash-box">
    <!-- small box -->
    <div class="small-box bg-customers-new1">      
      <div><p class="total_text">Total Users</p>
      <h3 class="customer_total">{{$totalUserCount}}</h3>
      </div>
      <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M64.1287 54.629C63.9944 55.521 63.6549 56.3696 63.137 57.1081C62.6217 57.8339 61.9392 58.4248 61.1472 58.831C60.3553 59.2371 59.4771 59.4465 58.587 59.4415H53.7716C53.4291 59.4402 53.0919 59.3576 52.7876 59.2004C52.4834 59.0432 52.2208 58.8159 52.0216 58.5373C51.8199 58.259 51.6859 57.9374 51.6302 57.5982C51.5745 57.2589 51.5986 56.9114 51.7008 56.5831C52.7799 53.1415 52.5466 49.2623 42.7729 43.0498C42.3746 42.7889 42.0695 42.4081 41.9018 41.9625C41.734 41.5169 41.7122 41.0295 41.8395 40.5706C41.9762 40.1187 42.2536 39.7222 42.6313 39.4389C43.0091 39.1556 43.4674 39.0003 43.9395 38.9956C48.1108 39.0538 52.1814 40.2857 55.685 42.5502C59.1886 44.8147 61.9835 48.0203 63.7495 51.7998C64.1083 52.6969 64.2386 53.6691 64.1287 54.629ZM56.8341 22.4581C56.8264 25.8754 55.4653 29.1504 53.0487 31.5664C50.6321 33.9825 47.3568 35.3429 43.9395 35.3498C43.557 35.342 43.183 35.2355 42.8538 35.0406C42.5246 34.8457 42.2514 34.569 42.0607 34.2374C41.8699 33.9058 41.7681 33.5305 41.7651 33.1479C41.7622 32.7654 41.8581 32.3885 42.0437 32.054C43.8293 29.1986 44.7761 25.8988 44.7761 22.5311C44.7761 19.1634 43.8293 15.8635 42.0437 13.0081C41.8149 12.6812 41.6799 12.298 41.6533 11.8998C41.6267 11.5017 41.7095 11.1039 41.8927 10.7494C42.076 10.3949 42.3527 10.0973 42.6929 9.88884C43.0331 9.68034 43.4239 9.56886 43.8229 9.56648C45.5307 9.56076 47.2216 9.90454 48.7916 10.5767C50.3616 11.2488 51.7774 12.2351 52.952 13.4748C55.3513 15.8974 56.7034 19.1652 56.7174 22.5748L56.8341 22.4581Z" fill="white"/>
<path d="M48.6358 54.5418C48.7541 55.9697 48.4731 57.4023 47.8242 58.6797C47.1752 59.9571 46.1838 61.0288 44.9608 61.7752C43.7416 62.5247 42.3358 62.9185 40.9066 62.9127H13.5687C12.1377 62.9189 10.7334 62.5249 9.51455 61.7752C8.28899 61.033 7.29675 59.9613 6.65106 58.6823C6.00538 57.4034 5.73226 55.9686 5.86288 54.5418C5.97179 53.1189 6.47698 51.7549 7.32122 50.6043C9.64552 47.509 12.6404 44.98 16.0813 43.2071C19.5222 41.4341 23.3199 40.4631 27.1895 40.3668C31.0724 40.4429 34.887 41.4015 38.3445 43.1701C41.8019 44.9388 44.8116 47.4711 47.1454 50.5752C47.9942 51.7367 48.5116 53.1086 48.6358 54.5418ZM41.5483 21.3502C41.5406 25.1419 40.0326 28.7765 37.3536 31.4598C34.6746 34.1432 31.0425 35.6571 27.2508 35.671C25.1396 35.6658 23.0558 35.1932 21.149 34.2872C19.2421 33.3812 17.5595 32.0642 16.2219 30.4308C14.8844 28.7974 13.9251 26.8882 13.4129 24.8401C12.9007 22.792 12.8483 20.6559 13.2595 18.5852C13.6725 16.5139 14.5394 14.56 15.7978 12.8638C17.0563 11.1677 18.675 9.77156 20.5375 8.77585C22.4001 7.78015 24.4602 7.20962 26.5696 7.10533C28.679 7.00103 30.7854 7.36556 32.737 8.17268C35.3467 9.25099 37.5787 11.0779 39.1515 13.423C40.7242 15.7682 41.5674 18.5265 41.5745 21.3502H41.5483Z" fill="white"/>
</svg>

  </div>
</div>
@endif
<!-- ./col --> 
<div class="col-lg-3 col-md-6 col-mdash-box">
    <!-- small box -->
    <div class="small-box bg-customers-new1" style="background: #00CFE8;">
      
      <div><p class="total_text">Total Travelers</p>
      <h3 class="customer_total">{{$travelercount}}</h3>
</div>
      <svg width="70" height="56" viewBox="0 0 70 56" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_408_827)">
<path d="M15.75 0C18.0706 0 20.2962 0.921872 21.9372 2.56282C23.5781 4.20376 24.5 6.42936 24.5 8.75C24.5 11.0706 23.5781 13.2962 21.9372 14.9372C20.2962 16.5781 18.0706 17.5 15.75 17.5C13.4294 17.5 11.2038 16.5781 9.56282 14.9372C7.92187 13.2962 7 11.0706 7 8.75C7 6.42936 7.92187 4.20376 9.56282 2.56282C11.2038 0.921872 13.4294 0 15.75 0ZM56 0C58.3206 0 60.5462 0.921872 62.1872 2.56282C63.8281 4.20376 64.75 6.42936 64.75 8.75C64.75 11.0706 63.8281 13.2962 62.1872 14.9372C60.5462 16.5781 58.3206 17.5 56 17.5C53.6794 17.5 51.4538 16.5781 49.8128 14.9372C48.1719 13.2962 47.25 11.0706 47.25 8.75C47.25 6.42936 48.1719 4.20376 49.8128 2.56282C51.4538 0.921872 53.6794 0 56 0ZM0 32.6703C0 26.2281 5.22812 21 11.6703 21H16.3406C18.0797 21 19.7313 21.3828 21.2188 22.0609C21.0766 22.8484 21.0109 23.6688 21.0109 24.5C21.0109 28.6781 22.8484 32.4297 25.7469 35H2.32969C1.05 35 0 33.95 0 32.6703ZM44.3297 35H44.2531C47.1625 32.4297 48.9891 28.6781 48.9891 24.5C48.9891 23.6688 48.9125 22.8594 48.7812 22.0609C50.2687 21.3719 51.9203 21 53.6594 21H58.3297C64.7719 21 70 26.2281 70 32.6703C70 33.9609 68.95 35 67.6703 35H44.3406H44.3297ZM24.5 24.5C24.5 21.7152 25.6062 19.0445 27.5754 17.0754C29.5445 15.1062 32.2152 14 35 14C37.7848 14 40.4555 15.1062 42.4246 17.0754C44.3938 19.0445 45.5 21.7152 45.5 24.5C45.5 27.2848 44.3938 29.9555 42.4246 31.9246C40.4555 33.8938 37.7848 35 35 35C32.2152 35 29.5445 33.8938 27.5754 31.9246C25.6062 29.9555 24.5 27.2848 24.5 24.5ZM14 53.0797C14 45.0297 20.5297 38.5 28.5797 38.5H41.4094C49.4703 38.5 56 45.0297 56 53.0797C56 54.6875 54.6984 56 53.0797 56H16.9094C15.3016 56 13.9891 54.6984 13.9891 53.0797H14Z" fill="white"/>
</g>
<defs>
<clipPath id="clip0_408_827">
<rect width="70" height="56" fill="white"/>
</clipPath>
</defs>
</svg>

  </div>
</div>
<!-- ./col -->
          <!-- <div class="col-lg-2 col-md-6 col-mdash-box">
            <!-- small box --
            <div class="small-box bg-invoices">
              <img src="{{url('public/dist/img/invoice.png')}}" alt="invoice" class="small_box_icon">
              <p class="total_text">Total Suppliers</p>
              <h3 class="customer_total invoice_total">0</h3>
            </div>
        </div> -->
        <!-- ./col -->
    </div>


    <!-- /.row -->
    <!-- Main row -->

    
            <div class="row">
                <div class="col-md-6">
                <div class="card">
        <div class="card-header">            
                    <h6>Trip Completed ({{$totalcompletedTrips}})</h6>
                    <canvas id="monthlyTripChart"></canvas>            
                </div>
            </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
        <div class="card-header">
                    <div>
                        <h6>Trip Request vs Booked</h6>
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>


            </div>
        </div>
        </div>
        <div class="row">
                <div class="col-md-12">
        <div class="card">
    <div class="card-header">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3 class="card-title">View All Reminder Task</h3>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="col-md-12 table-responsive pad_table">
                <table id="exampledashboard" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Trip Name</th>
                            <th>Agent Name</th>
                            <th>Traveler Name</th>
                            <th>Task</th>
                            <th>Category</th>
                            <th>Due Date</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
</div>





    </div>


</section>


</div>


<!-- barchart -->





<!-- end barchart -->


<div class="modal fade" id="ajaxModelTask" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeadingTask"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <form id="FormTask" name="FormTask" class="mt-6 space-y-6" enctype="multipart/form-data">

                <input type="hidden" name="trvt_id" id="trvt_id">
                <ul id="update_msgList"></ul>
                <div class="modal-body">
                    <div class="row pxy-15 px-10">
                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="tr_id">Trip Name</label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="tr_id"
                                        name="tr_id">
                                        <option  value="" default>Select Trip Name</option>
                                        @foreach ($trip as $tripvalue)
                                            <option value="{{ $tripvalue->tr_id }}">{{ $tripvalue->tr_name }}
                                            </option>
                                        @endforeach
                                        <x-input-error class="mt-2" :messages="$errors->get('tr_name')" />
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvt_name">Task</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" id="trvt_name" name="trvt_name"
                                        placeholder="Enter Task">
                                    <x-input-error class="mt-2" :messages="$errors->get('trvt_name')" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 family-member-field">
                            <div class="form-group">
                                <label for="trvt_agent_id">Assign Agent</label>
                                <div class="d-flex">
                                    <select id="trvt_agent_id" style="width: 100%;" name="trvt_agent_id"
                                        class="form-control select2">
                                        <option value="">Select Agent</option>
                                        @foreach ($agency_user as $value)
                                            <option value="{{ $value->users_id }}">
                                                {{ $value->users_first_name ?? '' }} {{ $value->users_last_name ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvt_category">Category<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trvt_category"
                                        name="trvt_category">
                                        <option value="" default>Select Category</option>
                                        @foreach ($taskCategory as $taskcat)
                                            <option value="{{ $taskcat->task_cat_id }}">{{ $taskcat->task_cat_name }}
                                            </option>
                                        @endforeach
                                        <x-input-error class="mt-2" :messages="$errors->get('trvt_category')" />
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvt_priority">Priority</label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trvt_priority"
                                        name="trvt_priority">
                                        <option value="" default>Select Priority</option>
                                        <option value="Medium">Medium</option>
                                        <option value="High">High</option>
                                        <option value="Low">Low</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('trvt_priority')" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvt_date">Create Date</label>
                                <div class="d-flex">
                                    <div class="input-group date" id="trvt_date" data-target-input="nearest">
                                        <x-flatpickr id="create_date" name="trvt_date" placeholder="mm/dd/yyyy" />
                                        <div class="input-group-append">
                                            <div class="input-group-text" id="create-date-icon">
                                                <i class="fa fa-calendar-alt"></i>
                                                <input type="hidden" id="trvt_date_hidden" />
                                            </div>
                                        </div>
                                        <x-input-error class="mt-2" :messages="$errors->get('trvt_date')" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvt_due_date">Due Date</label>
                                <div class="d-flex">
                                    <div class="input-group date" id="trvt_due_date" data-target-input="nearest">
                                        <x-flatpickr id="due_date" name="trvt_due_date" placeholder="mm/dd/yyyy" />
                                        <div class="input-group-append">
                                            <div class="input-group-text" id="due-date-icon">
                                                <i class="fa fa-calendar-alt"></i>
                                                <input type="hidden" id="trvt_due_date_hidden" />
                                            </div>
                                        </div>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('trvt_due_date')" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 family-member-field">
                            <div class="form-group">
                                <label for="trvt_document">Upload Documents</label>
                                <div class="d-flex">
                                    <x-text-input type="file" name="trvt_document" id="trvt_document" />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('trvt_document')" />
                                <p id="task_document"></p>
                                <label for="trvt_document">Only jpg, jpeg, png, and pdf files are allowed</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvt_note">Notes </label>
                                <div class="d-flex">
                                    <textarea type="text" class="form-control" id="trvt_note" placeholder="Enter Notes" name="trvt_note"
                                    autofocus autocomplete="trvt_note">{{ old('trvt_note') }}</textarea>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6" id="statusField" style="display: none;"> <!-- Initially hidden -->
                            <div class="form-group">
                                <label for="trvt_category">Status<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trvt_status"
                                        name="status">
                                        <option value="0" default>Select Status</option>
                                        @foreach ($taskstatus as $value)
                                            <option value="{{ $value->ts_status_id }}">
                                                {{ $value->ts_status_name }}
                                            </option>
                                        @endforeach
                                        <x-input-error class="mt-2" :messages="$errors->get('trvt_status')" />
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="add_btn_br" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="saveBtnTask" value="create"
                            class="add_btn">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="task-success-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body pad-1 text-center">
                <i class="fas fa-check-circle success_icon"></i>
                <p class="company_business_name px-10"><b>Success!</b></p>
                <p class="company_details_text px-10" id="task-success-message">Data has been successfully inserted!</p>
                <button type="button" class="add_btn px-15" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

       // datatable list
        var table = $('#exampledashboard').DataTable();
        table.destroy();
        setTimeout(function(){
        //list
        table = $('#exampledashboard').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('masteradmin.dashboardtask.incomplete') }}",
                type: 'GET',
            },
            debug: true,
            columns: [{
                    data: 'trip_name',
                    name: 'trip_name'
                },
                {
                    data: 'agent_name',
                    name: 'agent_name'
                },
                {
                    data: 'traveler_name',
                    name: 'traveler_name'
                },
                {
                    data: 'trvt_name',
                    name: 'trvt_name'
                },
                {
                    data: 'task_cat_name',
                    name: 'task_cat_name'
                },
                {
                    data: 'trvt_due_date',
                    name: 'trvt_due_date'
                },
                {
                    data: 'trvt_priority',
                    name: 'trvt_priority'
                },
                {
                    data: 'task_status_name',
                    name: 'task_status_name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    },1000);

              //insert/update data
              $('#saveBtnTask').click(function(e) {
            e.preventDefault();
            $(this).html('Sending..');
        

            var formData = new FormData($('#FormTask')[0]);
            formData.append('_token', "{{ csrf_token() }}");

            var url = '';
            var method = 'POST'; // Default to POST for new tasks
            var tasksuccessMessage = '';
            if ($('#trvt_id').val() === '') {
                // Create new task
                url = "{{  route('masteradmin.taskdetails.store') }}";
                formData.append('_method', 'POST');
                tasksuccessMessage = 'Data has been successfully inserted!'; 
            } else {
                // Update existing task
                var trvt_id = $('#trvt_id').val();
                var url = "{{ route('masteradmin.taskdetails.update', ':trvt_id') }}";
                url = url.replace(':trvt_id', trvt_id);
                formData.append('_method', 'PATCH');
                tasksuccessMessage = 'Data has been successfully updated!';
            }

            $.ajax({
                data: formData,
                url: url,
                type: method,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#exampledashboard').DataTable().ajax.reload();
                    $('#allTaskDataTable').DataTable().ajax.reload();
                    $('#task-success-message').text(tasksuccessMessage);
                    $('#task-success-modal').modal('show');
                    $('#ajaxModelTask').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('#ajaxModelTask').css('display', 'none');
                    $('#saveBtnTask').html('Save');
                    $('#FormTask')[0].reset();


                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtnTask').html('Save Changes');
                }
            });
        });

      
  //edit popup open
  $(document).on('click', '.editTask', function(e) {
            e.preventDefault();


            var id = $(this).data('id');
            var url = "{{ route('masteradmin.taskdetails.editTask', ['id' => ':id']) }}";
            if (url) {
                url = url.replace(':id', id);
            // alert(id);
             $.get(url, function(data) {


                    // console.log(data);
                    $('#modelHeadingTask').html("Edit Task");
                    $('#saveBtnTask').val("edit-user");

                    var editModal = new bootstrap.Modal(document.getElementById('ajaxModelTask'));
                    editModal.show();

                    $('#trvt_id').val(data.trvt_id);
                    $('#trvt_name').val(data.trvt_name);
                    $('#tr_id').val(data.tr_id).trigger('change.select2');

                    $('#trvt_agent_id').val(data.trvt_agent_id).trigger('change.select2');

                    if (data.trvt_category === '0') {

                        if ($('#trvt_category option[value="0"]').length === 0) {

                            $('#trvt_category').append('<option value="0">System Created</option>');

                        }

                        $('#trvt_category').val('0').trigger('change');

                        } else {

                        $('#trvt_category option[value="0"]').remove();

                        $('#trvt_category').val(data.trvt_category).trigger('change.select2');

                        }


                    
                    $('#trvt_date').val(data.trvt_date);
                    $('#trvt_due_date').val(data.trvt_due_date);
                    $('#trvt_note').val(data.trvt_note);

                    $('#trvt_date_hidden').val(data.trvt_date);
                    $('#trvt_due_date_hidden').val(data.trvt_due_date);

                    $('#trvt_priority').val(data.trvt_priority).trigger('change.select2');

                    $('#task_document').html('');
                    var baseUrl = "{{ config('app.image_url') }}";
                    if (data.trvt_document) {
                        $('#task_document').append(
                            '<a href="' + baseUrl + '/tasks/' + data
                            .trvt_document + '" target="_blank">' +
                            data.trvt_document +
                            '</a>'
                        );
                    }
                    
                    $('#statusField').show();
                    $('#trvt_status').val(data.status).trigger(
                        'change.select2'); // set the selected status
                    var trvt_date_hidden = document.getElementById('trvt_date_hidden');
                    var trvt_due_date_hidden = document.getElementById('trvt_due_date_hidden');

                    if (trvt_date_hidden && trvt_due_date_hidden) {
                        var completed_date = flatpickr("#create_date", {
                            locale: 'en',
                            altInput: true,
                            dateFormat: "m/d/Y",
                            altFormat: "m/d/Y",
                            allowInput: true,
                            defaultDate: trvt_date_hidden.value || null,
                        });

                        var todatepicker = flatpickr("#due_date", {
                            locale: 'en',
                            altInput: true,
                            dateFormat: "m/d/Y",
                            altFormat: "m/d/Y",
                            allowInput: true,
                            defaultDate: trvt_due_date_hidden.value || null,
                        });

                        document.getElementById('create-date-icon').addEventListener('click',
                            function() {
                                fromdatepicker.open();
                            });

                        document.getElementById('due-date-icon').addEventListener('click',
                            function() {
                                todatepicker.open();
                            });
                    }

                });
            }
        });

        //delete record
        $('body').on('click', '.deleteTaskbtn', function(e) {
            e.preventDefault();
            //  alert(trtm_id);
             var trvt_id = $(this).data("id");
            
                if (trvt_id) {
                    var url = "{{ route('masteradmin.taskdetails.destroy', ':trvt_id') }}";
                    url = url.replace(':trvt_id', trvt_id);
          
            // alert(url);
            $.ajax({
                type: "DELETE",
                url: url,
                success: function(data) {
                    // alert(data.success);
                    $('#exampledashboard').DataTable().ajax.reload();
                    $('#task-success-message').text('Data has been successfully Deleted!');
                    $('#task-success-modal').modal('show');
                    $('.ajaxModelTask').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('.ajaxModelTask').css('display', 'none');

                    $('#example15').DataTable().ajax.reload();

                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
        });

      

    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        var fromdatepicker = flatpickr("#create_date", {
          locale: 'en',
          altInput: true,
          dateFormat: "m/d/Y",
          altFormat: "m/d/Y",
          allowInput: true,
      });

        var todatepicker = flatpickr("#due_date", {
          locale: 'en',
          altInput: true,
          dateFormat: "m/d/Y",
          altFormat: "m/d/Y",
          allowInput: true,
      });

        document.getElementById('create-date-icon').addEventListener('click', function () {
          fromdatepicker.open();
      });

        document.getElementById('due-date-icon').addEventListener('click', function () {
          todatepicker.open();
      });


    });


</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Monthly data passed from the backend
        const monthlyData = @json($monthlyData);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        const ctx = document.getElementById('monthlyTripChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Completed Trips',
                    data: monthlyData,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Trip Completion by Month'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Trips'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    }
                }
            }
        });
    });

    
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data from controller
        const requestPercentage = {{ $requestPercentage }};
        const bookedPercentage = {{ $bookedPercentage }};

        // Create chart
        const ctx = document.getElementById('doughnutChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Requested Trips', 'Accepted Trips'],
                datasets: [{
                    data: [requestPercentage, bookedPercentage],
                    backgroundColor: ['#FF6384', '#36A2EB'], // Colors
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: ${value}%`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

