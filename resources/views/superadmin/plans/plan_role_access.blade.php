<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trip Tracker | Subscription Plans Access</title>
    @include('layouts.headerlink')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('layouts.navigation')
        @include('layouts.sidebar')

        <!-- /.Main Sidebar Container -->
        <div class="content-wrapper">
            <section class="content px-10">
            @if(Session::has('plan-role'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('plan-role') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                @php
                Session::forget('plan-role');
                @endphp
            @endif
            <div class="container-fluid">
                <div class="card">
                    
                    <form action="{{ route('plans.updaterole', $id) }}" method="POST">
                    @csrf
                    <div class="row pad-1">
                    <input type="hidden" name="sp_id" value="{{ $id }}">
                        @foreach($permissions as $permission)
                            <div class="col-md-6">
                                <div class="user-role-box">
                                    <p class="module-title">{{ $permission->mtitle }}</p>
                                        <div class="row justify-content-between">
                                            @foreach($subPermissions as $subPermission)
                                                @if($subPermission->pmenu == $permission->mid)
                                                <div class="col-auto">
                                                    @php
                                                        $checkedPermissions = array_column($useraccess->toArray(), 'mid');
                                                    @endphp
                                                    <input class="checkbox-inputbox" type="checkbox" id="{{ $subPermission->mtitle }}" name="permissions[{{ $permission->mid }}][subPermissions][{{ $subPermission->mid }}][checked]" value="1" {{ in_array($subPermission->mid, $checkedPermissions) ? 'checked' : '' }}>
                                                    <input type="hidden" name="permissions[{{ $permission->mid }}][subPermissions][{{ $subPermission->mid }}][mtitle]" value="{{ $subPermission->mtitle }}">
                                                    <input type="hidden" name="permissions[{{ $permission->mid }}][subPermissions][{{ $subPermission->mid }}][mid]" value="{{ $subPermission->mid }}">
                                                    <input type="hidden" name="permissions[{{ $permission->mid }}][subPermissions][{{ $subPermission->mid }}][mname]" value="{{ $subPermission->mname }}">
                                                    <label for="{{ $subPermission->mtitle }}">{{ $subPermission->mtitle }}</label>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                </div>
                            </div>
                        @endforeach
                        <button type="submit" class="add_btn px-10">Submit</button>
                        </div>
                        </form>
                    
                </div>
            </div>
            </section>
        </div>
    </div>

    @include('layouts.footerlink')
   

</body>
</html>
