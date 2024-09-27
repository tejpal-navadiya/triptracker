<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profityo | Business Detail</title>
  @include('masteradmin.layouts.headerlink')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    @include('masteradmin.layouts.navigation')
    @include('masteradmin.layouts.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2 align-items-center justify-content-between">
            <div class="col-auto">
              <h1 class="m-0">Bank Details</h1>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('business.home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Bank Details</li>
              </ol>
            </div><!-- /.col -->
            <div class="col-auto">
              <!-- <ol class="breadcrumb float-sm-right">
            <a data-toggle="modal" data-target="#delete-vendor-modal-{{ $PurchasVendorbank->purchases_vendor_id }}"><button class="add_btn_br"><i class="fas fa-solid fa-trash mr-2"></i>Delete</button></a>
              <a href="{{ route('business.purchasvendor.edit',$PurchasVendorbank->purchases_vendor_id) }}"><button class="add_btn_br"><i class="fas fa-solid fa-pen-to-square mr-2"></i>Edit</button></a>
              <a href="{{ route('business.purchasvendor.create') }}"><button class="add_btn">Create Another Vendor</button></a>
            </ol> -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
      <!-- Main content -->
      <section class="content px-10">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <h2>{{ $PurchasVendorbank->vendor->purchases_vendor_name }} {{ $PurchasVendorbank->vendor->purchases_vendor_last_name }}</h2>
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Current Bank Account for Direct Deposit</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-bordered table-gray table-fixed-width">
                    <tbody>
                      <tr>
                        <td>Routing Number</td>
                        <td>{{ $PurchasVendorbank->purchases_routing_number }}</td>
                      </tr>
                      <tr>
                        <td>Account Number</td>
                        <td>
                          {{ str_repeat('*', strlen($PurchasVendorbank->purchases_account_number) - 3) . substr($PurchasVendorbank->purchases_account_number, -3) }}
                        </td>
                      </tr>
                      <tr>
                        <td>Account Type</td>
                        <td>{{ $PurchasVendorbank->bank_account_type }}</td>
                      </tr>
                    </tbody>
                  </table>
                  <!-- <div class="row">
                            <div class="col-md-4">
                                <label for="routing_number">Routing Number :</label>
                                <p id="routing_number">{{ $PurchasVendorbank->purchases_routing_number }}</p>
                            </div>
                            <div class="col-md-4">
                                <label for="account_number">Account Number :</label>
                                <p id="account_number">
                                    {{ str_repeat('*', strlen($PurchasVendorbank->purchases_account_number) - 3) . substr($PurchasVendorbank->purchases_account_number, -3) }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label for="account_type">Account Type :</label>
                                <p id="account_type">{{ $PurchasVendorbank->bank_account_type }}</p>
                            </div>
                        </div> -->
                  <!-- Add more fields as needed -->
                </div>
              </div>
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

    <!-- ./wrapper -->


    @include('masteradmin.layouts.footerlink')

</body>

</html>