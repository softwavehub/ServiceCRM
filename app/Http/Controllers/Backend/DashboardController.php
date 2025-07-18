<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InquiryVendorDetail;
use App\Models\Job;
use App\Models\ResInquiryMaster;
use App\Models\PreVendorCategory;
use App\Models\PreVendorSubCategory;
use App\Models\Service;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalServices = Service::count();


        return view('backend.dashboard', compact('totalServices'));
    }

    // In DashboardController.php or your existing controller

    public function staffDashboard(Request $request)
    {
        $staff = $request->user();

        if ($staff->role !== 'staff') {
            abort(403, 'Unauthorized access');
        }

        $leadCount = $staff->assignedLeads()->count();

        return view('backend.staff-dashboard', compact('leadCount'));
    }

    public function employerDashoard(){
        $totalJobs = Job::where('user_id',Auth::id())->count();
        $jobs = Job::where('user_id', Auth::id())->pluck('id')->toArray();
        $totalApplications = Application::whereIn('job_id',$jobs)->count();
        $pendingApplications = Application::whereIn('job_id',$jobs)->where('status','pending')->count();

        return view('backend.employer-dashboard', compact('pendingApplications', 'totalJobs','totalApplications'));
    }

    public function candidateDashboard(Request $request){
        $staff = $request->user();

        if ($staff->role !== 'staff') {
            abort(403, 'Unauthorized access');
        }

        $leadCount = $staff->assignedLeads()->count();

        return view('backend.staff-dashboard', compact('leadCount'));
    }

}
