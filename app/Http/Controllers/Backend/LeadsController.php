<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\LeadsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadsController extends Controller
{
    public function index(LeadsDataTable $dataTable){
        return $dataTable->render('backend.leads.index');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:leads,email',
            'phone' => 'required|string|max:20',
        ]);
        $lead = new Lead();
        $lead->name = $request->name;
        $lead->email = $request->email;
        $lead->phone = $request->phone;
        $lead->save();
        return response()->json([
            'status'  => true,
            'message' => 'Leads Stored successfully',
        ]);
    }

    public function edit(Request $request){
        try {
            $job = Lead::find($request->id);



            return response()->json([
                'status'  => true,
                'data'    => $job,
                'message' => 'Service fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request,Lead $lead){
        $request->validate([
            'name' => 'required',
            'email' => [
                'nullable',
                'email',
                Rule::unique('leads')->ignore($lead->id)
            ],
            'phone' => 'required',
        ]);
        $lead->name = $request->name;
        $lead->email = $request->email;
        $lead->phone = $request->phone;
        $lead->save();
        return response()->json([
            'status'  => true,
            'message' => 'Lead Updated successfully',
        ]);
    }

    public function delete(Lead $lead){
        try {
            if ($lead->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Lead deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Lead not found!"
                ]);
            }


        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getAvailableStaff(Lead $lead)
    {
        // Get staff users not already assigned to this lead
        $availableStaff = User::staff()
            ->whereDoesntHave('assignedLeads', function($query) use ($lead) {
                $query->where('lead_id', $lead->id);
            })
            ->get(['id', 'name']);

        return response()->json([
            'status' => true,
            'data' => $availableStaff
        ]);
    }

    public function assignStaff(Request $request, Lead $lead){

        $request->validate([
            'staff_id' => 'required|exists:users,id,role,staff'
        ]);

        $lead->staff()->syncWithoutDetaching([$request->staff_id]);

        return response()->json([
            'status' => true,
            'message' => 'Lead assigned successfully',
            'data' => $lead->load('staff')
        ]);
    }

}
