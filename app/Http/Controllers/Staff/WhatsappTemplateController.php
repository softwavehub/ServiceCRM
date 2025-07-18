<?php

namespace App\Http\Controllers\Staff;

use App\DataTables\StaffWhatsappTemplateDataTable;
use App\Http\Controllers\Controller;
use App\Models\WhatsappTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsappTemplateController extends Controller
{
    public function index(StaffWhatsappTemplateDataTable $dataTable){
        return $dataTable->render('staff.whatsapp-template.index');
    }

    public function store(Request $request){
        $request->validate([
           'title' => 'required',
           'message' => 'required',
        ]);

        $WhatsappTemplate = new WhatsappTemplate();
        $WhatsappTemplate->title = $request->title;
        $WhatsappTemplate->message = $request->message;
        $WhatsappTemplate->staff_id = Auth::id();
        $WhatsappTemplate->save();


        return response()->json([
            'status'  => true,
            'message' => 'Whatsapp Template Stored successfully',
        ]);
    }

    public function edit(Request $request){
        try {
            $WhatsappTemplate = WhatsappTemplate::find($request->id);



            return response()->json([
                'status'  => true,
                'data'    => $WhatsappTemplate,
                'message' => 'Whatsapp Template fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request,WhatsappTemplate $whatsappTemplate){
        $request->validate([
            'title' => 'required',
            'message' => 'required',
        ]);

        $whatsappTemplate->title = $request->title;
        $whatsappTemplate->message = $request->message;
        $whatsappTemplate->save();


        return response()->json([
            'status'  => true,
            'message' => 'Whatsapp Template Udated successfully',
        ]);
    }

    public function delete(WhatsappTemplate $whatsappTemplate){
        try {
            if ($whatsappTemplate->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Whatsapp Template deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Whatsapp Template not found!"
                ]);
            }


        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function templateList(){
        $templates = WhatsAppTemplate::where('staff_id', auth()->id())->get();
        return response()->json([
            'status' => true,
            'data' => $templates
        ]);
    }
}
