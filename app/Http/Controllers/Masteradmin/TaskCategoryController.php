<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskCategory;


class TaskCategoryController extends Controller
{
    //
    public function index()
    {
        $user = Auth::guard('masteradmins')->user();

        $taskcategory = TaskCategory::where(['task_cat_status' => 1, 'id' => $user->users_id])->get();
        return view('masteradmin.task_category.index',compact('taskcategory'));
    }

    public function create(){

        return view('masteradmin.task_category.create');
    }

    public function store(Request $request)
    {

        $user = Auth::guard('masteradmins')->user();
    
        $dynamicId = $user->users_id;
    
        $validatedData = $request->validate([
             'task_cat_status' => 'required|string',
             'task_cat_name' => 'required|string',
        
         ], [
             'task_cat_status.required' => 'Status  is required',
             'task_cat_name.required' => 'Name is required',
         
         ]);
    
         $task_category = new TaskCategory();
         $tableName = $task_category->getTable();
         $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "task_cat_id", $chars = 6);
        
         $task_category->task_cat_id = $uniqueId1;
    
         $task_category->id = $user->users_id;
    
         $task_category->task_cat_status = $validatedData['task_cat_status'];
         $task_category->task_cat_name = $validatedData['task_cat_name'];
        
         $task_category->save();
    
         \MasterLogActivity::addToLog('Master Admin Task Category Created Created.');
    
         return redirect()->route('task-category.index')->with('success', 'Task Category created successfully.');
    
        }

        public function edit($id)
        {
            $task_categories = TaskCategory::where('task_cat_id', $id)->firstOrFail();

            return view('masteradmin.task_category.edit', compact('task_categories'));
        }

        public function update(Request $request, $id)
        {
            $user = Auth::guard('masteradmins')->user();
        
            $task_category = TaskCategory::where('task_cat_id', $id)->firstOrFail();
        
            $validatedData = $request->validate([
                'task_cat_status' => 'required|string',
                'task_cat_name' => 'required|string',
           
            ], [
                'task_cat_status.required' => 'Status  is required',
                'task_cat_name.required' => 'Name is required',
            
            ]);
        
            // Update the library record
            $task_category->where('task_cat_id', $id)->update($validatedData);
        
            \MasterLogActivity::addToLog('Master Admin Task Category Updated.');
        
            return redirect()->route('task-category.index')->with('success', 'Task Category Updated successfully.');
        }

        public function destroy($id)
        {
            $user = Auth::guard('masteradmins')->user();

            $task_category = TaskCategory::where('task_cat_id', $id)->firstOrFail();

            $task_category->where('task_cat_id', $id)->delete();

            \MasterLogActivity::addToLog('Master Admin Task Category Deleted.');

            return redirect()->route(route: 'task-category.index')->with('success', 'Task Category deleted successfully');
        }
}
