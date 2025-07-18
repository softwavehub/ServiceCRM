<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ServiceDataTable;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(ServiceDataTable $dataTable){
        return $dataTable->render('backend.service.index');
    }

    public function store(Request $request){

        $request->validate([
           'name' => 'required',
           'description' => 'required',
           'inclusions' => 'required',
        ]);
        $service = new Service();
        $service->name = $request->name;
        $service->category_1 = $request->category_1;
        $service->category_2 = $request->category_2;
        $service->category_3 = $request->category_3;
        $service->description = $request->description;
        $service->inclusions = $request->inclusions;
        $service->tenture = $request->tenture;
        if ($request->hasFile('attachment')) {
            $image = $request->file('attachment');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Create directory if it doesn't exist
            $destinationPath = public_path('/assets/services');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);
            $service->attachment = $imageName;
        }
        $service->save();
        return response()->json([
            'status'  => true,
            'message' => 'Service Stored successfully',
        ]);
    }

    public function edit(Request $request){
        try {
            $job = Service::find($request->id);



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

    public function update(Request $request,Service $service){

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'inclusions' => 'required',
        ]);
        $service->name = $request->name;
        $service->category_1 = $request->category_1;
        $service->category_2 = $request->category_2;
        $service->category_3 = $request->category_3;
        $service->description = $request->description;
        $service->inclusions = $request->inclusions;
        $service->tenture = $request->tenture;
        if ($request->hasFile('attachment')) {
            $image = $request->file('attachment');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Create directory if it doesn't exist
            $destinationPath = public_path('/assets/services');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);
            $service->attachment = $imageName;
        }
        $service->save();
        return response()->json([
            'status'  => true,
            'message' => 'Service Updated successfully',
        ]);
    }

    public function delete(Service $service){
        try {
            if ($service->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Service deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Service not found!"
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
