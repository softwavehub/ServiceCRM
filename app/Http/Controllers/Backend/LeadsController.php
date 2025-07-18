<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\LeadsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Imports\LeadsImport;
use Maatwebsite\Excel\Facades\Excel;

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

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,xlsx,xls|max:2048'
        ]);

        try {
            Excel::import(new LeadsImport, $request->file('import_file'));

            return response()->json([
                'status' => true,
                'message' => 'Leads imported successfully'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $errors = collect($e->failures())
                ->map(function($failure) {
                    return "Row {$failure->row()}: {$failure->errors()[0]}";
                })
                ->all();

            return response()->json([
                'status' => false,
                'errors' => $errors
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    public function downloadSample()
    {
        $file = public_path('samples/leads_import_sample.csv');

        if (!file_exists($file)) {
            // Create sample file if it doesn't exist
            $headers = ['Name', 'Email', 'Phone'];
            $sampleData = [
                ['John Doe', 'john@example.com', '1234567890'],
                ['Jane Smith', 'jane@example.com', '0987654321']
            ];

            $handle = fopen($file, 'w');
            fputcsv($handle, $headers);
            foreach ($sampleData as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }

        return response()->download($file, 'leads_import_sample.csv');
    }

    public function bulkAssign(Request $request)
    {
        $request->validate([
            'lead_ids' => 'required',
            'staff_id' => 'required|exists:users,id,role,staff'
        ]);

        $leadIds = explode(',', $request->lead_ids);
        $staffId = $request->staff_id;

        foreach ($leadIds as $leadId) {
            $lead = Lead::find($leadId);
            if ($lead && !$lead->staff()->where('staff_id', $staffId)->exists()) {
                $lead->staff()->sync([$staffId]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Leads assigned successfully'
        ]);
    }

}
