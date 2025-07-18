<?php

namespace App\Http\Controllers\Staff;

use App\DataTables\StaffDataTable;
use App\DataTables\StaffLeadDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(StaffLeadDataTable $dataTable){
        return $dataTable->render('staff.leads.index');
    }
}
