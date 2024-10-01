<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Plan;
use App\Models\AdminMenu;
use App\Models\UserAccess;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        //
        // dd('hii');
        $plan = Plan::all();
        return view('superadmin.plans.index')->with('plan',$plan);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //
        return view('superadmin.plans.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // $value  = $request->authenticate();
        $validatedData = $request->validate([
            'sp_name' => 'required|string|max:255',
            'sp_amount' => 'required|numeric',
            'sp_month' => 'required|integer',
            'sp_desc' => 'nullable|string',
            'sp_user' => 'nullable|integer',
        ], [
            'sp_name.required' => 'The name field is required.',
            'sp_amount.required' => 'The amount field is required.',
            'sp_month.required' => 'The validity field is required.',
            'sp_desc.string' => 'The description must be a string.',
            'sp_user.integer' => 'The user field must be an integer.',
        ]);
        $id = $this->GenerateUniqueRandomString($table='ta_subscription_plans', $column="sp_id", $chars=6);
        $validatedData['sp_id'] = $id;
        // Assuming you have a Plan model to handle database interactions
        Plan::create($validatedData);
        \LogActivity::addToLog('Admin Plan Created.');

        return redirect()->route('plans.index')->with(['plan-add' =>__('messages.admin.plan.add_plan_success')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($sp_id, Request $request): View
    {
        $plan = Plan::where('sp_id', $sp_id)->firstOrFail();

        return view('superadmin.plans.edit', compact('plan'));
    }  

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $sp_id): RedirectResponse
    {
        // Find the plan by sp_id
        $plan = Plan::where('sp_id', $sp_id)->firstOrFail();

        // Validate incoming request data
        $validatedData = $request->validate([
            'sp_name' => 'required|string|max:255',
            'sp_amount' => 'required|numeric',
            'sp_month' => 'required|integer',
            'sp_desc' => 'nullable|string',
            'sp_user' => 'nullable|integer',
        ], [
            'sp_name.required' => 'The name field is required.',
            'sp_amount.required' => 'The amount field is required.',
            'sp_month.required' => 'The validity field is required.',
            'sp_desc.string' => 'The description must be a string.',
            'sp_user.integer' => 'The user field must be an integer.',
        ]);

        // Update the plan attributes based on validated data
        $plan->where('sp_id', $sp_id)->update($validatedData);
        \LogActivity::addToLog('Admin Plan Edited.');

        // Redirect back to the edit form with a success message
        return redirect()->route('plans.edit', ['plan' => $plan->sp_id])
                        ->with('plan-edit', __('messages.admin.plan.edit_plan_success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($sp_id): RedirectResponse
    {
        //
        $plan = Plan::where('sp_id', $sp_id)->firstOrFail();

        // Delete the plan
        $plan->where('sp_id', $sp_id)->delete();

        \LogActivity::addToLog('Admin Plan Deleted.');
         
        return redirect()->route('plans.index')
                        ->with('plan-delete', __('messages.admin.plan.delete_plan_success'));

    }
    public function planrole($sp_id): View
    {
        // dd($sp_id);
        $id = $sp_id;
        $plan = Plan::where('sp_id', $sp_id)->firstOrFail();
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
        
        $useraccess = UserAccess::where('sp_id', $sp_id)->get();


        // dd($useraccess);

        return view('superadmin.plans.plan_role_access', compact('permissions', 'subPermissions','id','useraccess'));
    }


    public function updaterole(Request $request, $sp_id): RedirectResponse
    {
        UserAccess::where('sp_id', $sp_id)->delete();

        $sp_id = $request->input('sp_id');
        $permissions = $request->input('permissions');
        //dd($permissions);
        foreach ($permissions as $permissionId => $permission) {
     
            if (isset($permission['subPermissions'])) {
                foreach ($permission['subPermissions'] as $subPermission) {
                    if ($subPermission['checked'] ?? '') {
                        $id = $this->GenerateUniqueRandomString($table='ta_user_access', $column="id", $chars=6);
                        UserAccess::create([
                            'id' => $id,
                            'sp_id' => $sp_id,
                            'mname' => $subPermission['mname'],
                            'mtitle' => $subPermission['mtitle'],
                            'mid' => $subPermission['mid'],
                            'is_access' => 1
                        ]);
                    }
                }
            }
        }

        return redirect()->route('plans.planrole', ['plan' => $sp_id])
            ->with('plan-role', __('messages.admin.plan.roll_plan_access_success'));
    }


}
