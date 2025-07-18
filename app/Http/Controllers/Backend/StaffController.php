<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\StaffDataTable;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index(StaffDataTable $dataTable){
        return $dataTable->render('backend.staff.index');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email',
            'mobile' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = bcrypt($request->password);
        $user->role = 'staff';
        $user->save();

        $user->assignRole('staff');

        return response()->json([
            'status'  => true,
            'message' => 'Staff Stored successfully',
        ]);
    }

    public function edit(Request $request){
        try {
            $job = User::find($request->id);



            return response()->json([
                'status'  => true,
                'data'    => $job,
                'message' => 'User fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request,$user){
        $user = User::find($user);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'mobile' => 'required|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);


        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        if ($request->has('password')){
            $user->password = bcrypt($request->password);
        }

        $user->role = 'staff';
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'User Updated successfully',
        ]);
    }

    public function delete(User $user){
        try {
            if ($user->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'User deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "User not found!"
                ]);
            }


        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
