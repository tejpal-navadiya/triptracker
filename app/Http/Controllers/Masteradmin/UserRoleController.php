<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserRole;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminMenu;
use App\Models\MasterUserAccess;
use DataTables;

class UserRoleController extends Controller
{
    //
    // public function index(): View
    // {
    //     $user = Auth::guard('masteradmins')->user();
    //     $roles = UserRole::where(['role_status' => 1, 'id' => $user->id])->get();
    //     return view('masteradmin.role.index', compact('roles'));
    // }

    

    public function index(Request $request)
    {
        $access = view()->shared('access');
        // dd($access);
        $user = Auth::guard('masteradmins')->user();
        $roles = UserRole::where(['id' => Auth::guard('masteradmins')->user()->id])->latest()->get();

    
        if ($request->ajax()) {
            $roles = UserRole::where(['id' => $user->id])->latest()->get();
            //  dd($access);
            return Datatables::of($roles)
                    ->addIndexColumn()
                    ->addColumn('action', function($role) use ($access){
                        $btn = '';
                        if(isset($access['assign_role']) && $access['assign_role']) {
                            $btn .= '<a href="'.route('masteradmin.role.userrole', $role->role_id).'" data-id="'.$role->role_id.'"  data-toggle="tooltip" data-original-title="Assign Role" class=""><i class="fas fa-key view_icon_grid"></i></a>';
                        }
                        if(isset($access['edit_role']) && $access['edit_role']) {
                            $btn .= '<a data-id="'.$role->role_id.'" data-toggle="tooltip" data-original-title="Edit Role" class="editRole"><i class="fas fa-pen-to-square edit_icon_grid"></i></a>';
                        }
                        if(isset($access['delete_role']) && $access['delete_role']) {
                            $btn .= '<a data-toggle="modal" data-target="#delete-role-modal-'.$role->role_id.'">
                                        <i class="fas fa-trash delete_icon_grid"></i>
                                        <div class="modal fade" id="delete-role-modal-'.$role->role_id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body pad-1 text-center">
                                                        <i class="fas fa-solid fa-trash delete_icon"></i>
                                                        <p class="company_business_name px-10"><b>Delete User Role</b></p>
                                                        <p class="company_details_text px-10">Are You Sure You Want to Delete This User Role?</p>
                                                        <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="delete_btn px-15 deleteRolebtn" data-id='.$role->role_id.'>Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>';
                        }
                        // dd($access);
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->toJson();
                  
        }
      
        return view('masteradmin.role.index', compact('roles'));

    }

    public function create(): View
    {
        return view('masteradmin.role.add');
    }

    public function store(Request $request)
    {
        $user = Auth::guard('masteradmins')->user();
        $validatedData = $request->validate([
            'role_name' => 'required|string|max:255',
        ], [
            'role_name.required' => 'The role name field is required.',
        ]);
        $userCertification = new UserRole();
        $tableName = $userCertification->getTable();
        $uniqueId = $this->GenerateUniqueRandomString($table = $tableName, $column = "role_id", $chars = 6);
      
        $validatedData['id'] = $user->id;
        $validatedData['role_id'] = $uniqueId;
        $validatedData['role_status'] = 1;
        UserRole::create($validatedData);
        \MasterLogActivity::addToLog('Master Admin Users Role Created.');

        return response()->json(['success'=>'Record saved successfully.']);
    }

    public function edit($role_id)
    {   
        
        $role = UserRole::where('role_id' , $role_id)->firstOrFail();

        return response()->json($role);
    }

    public function update(Request $request, $role_id)
    {   
        // dd($role_id);
        // Find the plan by sp_id
        $user = Auth::guard('masteradmins')->user();
        $roles = UserRole::where(['role_id' => $role_id, 'id' => $user->id])->firstOrFail();

        // Validate incoming request data
        $validatedData = $request->validate([
            'role_name' => 'required|string|max:255',
        ], [
            'role_name.required' => 'The role name field is required.',
        ]);

        // Update the plan attributes based on validated data
        $roles->where('role_id', $role_id)->update($validatedData);
        \MasterLogActivity::addToLog('Master Admin User Role is Edited.');
       
        return response()->json(['success'=>'Record saved successfully.']);       

    }

    public function destroy($role_id)
    {
        //
        // dd($role_id);
        $user = Auth::guard('masteradmins')->user();
        $roles = UserRole::where(['role_id' => $role_id, 'id' => $user->id])->firstOrFail();

        // Delete the plan
        $roles->where('role_id', $role_id)->delete();

        \MasterLogActivity::addToLog('Master Admin User role is Deleted.');
         
        return response()->json(['success'=>'Record deleted successfully.']);

    }
    
    public function Userrole($role_id): View
    {
        $id = $role_id;
        // dd($id);

        $permissions = AdminMenu::where('pmenu', 0)
        ->where('is_deleted', 0)
        ->get();

        $subPermissions = AdminMenu::where('mid', '>=', 12)
        ->where('is_deleted', 0)
        ->whereExists(function ($query) {
            $query->select('*')
                ->from('ta_admin_menu')
                ->whereRaw('`ta_admin_menu`.`pmenu` IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11)');
        })
        ->get();
        
        $useraccess = MasterUserAccess::where('role_id', $role_id)->get();


        // dd($useraccess);
        return view('masteradmin.role.user_role_access', compact('permissions', 'subPermissions','id','useraccess'));

    }

    public function updaterole(Request $request, $role_id): RedirectResponse
    {
        // dd($role_id);
        $user = Auth::guard('masteradmins')->user();
        MasterUserAccess::where('role_id', $role_id)->delete();

        $role_id = $request->input('role_id');
        $permissions = $request->input('permissions');
        //dd($permissions);
        foreach ($permissions as $permissionId => $permission) {
     
            if (isset($permission['subPermissions'])) {
                foreach ($permission['subPermissions'] as $subPermission) {
                    if ($subPermission['checked'] ?? '') {

                        $userMasterAccess = new MasterUserAccess();
                        $tableName = $userMasterAccess->getTable();
                        
                        $id = $this->GenerateUniqueRandomString($table=$tableName, $column="id", $chars=6);
                        MasterUserAccess::create([
                            'id' => $id,
                            'u_id' => $user->id,
                            'role_id' => $role_id,
                            'mname' => $subPermission['mname'],
                            'mtitle' => $subPermission['mtitle'],
                            'mid' => $subPermission['mid'],
                            'is_access' => 1
                        ]);
                    }
                }
            }
        }
        \MasterLogActivity::addToLog('Master Admin User role Assigned.');
        return redirect()->route('masteradmin.role.userrole', ['userrole' => $role_id])
        ->with('user-role', __('messages.masteradmin.user-role.roll_user_access_success'));
    }    

    
}
