<div class="wrapper">
    @extends('masteradmin.layouts.app')
    <title>New User Role | Trip Tracker</title>
    @if (isset($access['assign_role']) && $access['assign_role'])
        @section('content')
            <div class="content-wrapper">
                <section class="content px-10">
                    @if (Session::has('user-role'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('user-role') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @php
                            Session::forget('user-role');
                        @endphp
                    @endif
                    <div class="container-fluid">
                        <div class="card">

                            <form method="POST" action="{{ route('masteradmin.role.updaterole', $id) }}">
                                @csrf
                                @method('PUT')
                                <div class="row pad-1">
                                    <input type="hidden" name="role_id" value="{{ $id }}">
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-6">
                                            <div class="user-role-box">
                                                <p class="module-title">{{ $permission->mtitle }}</p>
                                                <div class="row justify-content-between">
                                                    @foreach ($subPermissions as $subPermission)
                                                        @if ($subPermission->pmenu == $permission->mid)
                                                            <div class="col-auto">
                                                                @php
                                                                    $checkedPermissions = array_column(
                                                                        $useraccess->toArray(),
                                                                        'mid',
                                                                    );
                                                                @endphp
                                                                <input class="checkbox-inputbox" type="checkbox"
                                                                    id="{{ $subPermission->mtitle }}"
                                                                    name="permissions[{{ $permission->mid }}][subPermissions][{{ $subPermission->mid }}][checked]"
                                                                    value="1"
                                                                    {{ in_array($subPermission->mid, $checkedPermissions) ? 'checked' : '' }}>
                                                                <input type="hidden"
                                                                    name="permissions[{{ $permission->mid }}][subPermissions][{{ $subPermission->mid }}][mtitle]"
                                                                    value="{{ $subPermission->mtitle }}">
                                                                <input type="hidden"
                                                                    name="permissions[{{ $permission->mid }}][subPermissions][{{ $subPermission->mid }}][mid]"
                                                                    value="{{ $subPermission->mid }}">
                                                                <input type="hidden"
                                                                    name="permissions[{{ $permission->mid }}][subPermissions][{{ $subPermission->mid }}][mname]"
                                                                    value="{{ $subPermission->mname }}">
                                                                <label
                                                                    for="{{ $subPermission->mtitle }}">{{ $subPermission->mtitle }}</label>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-md-12">
                                        <button type="submit" class="add_btn px-10 m-auto p-auto">Submit</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </section>
            </div>
        @endsection

    @endif
