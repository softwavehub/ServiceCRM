<?php

use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Auth\VendorOTPVerifyController;
use App\Http\Controllers\Backend\AdminBranchController;
use App\Http\Controllers\Backend\AdminVendorController;
use App\Http\Controllers\Backend\ApprovalController;
use App\Http\Controllers\Backend\BranchController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CountryStateCityController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DocumentController;
use App\Http\Controllers\Backend\GeneralChargeController;
use App\Http\Controllers\Backend\GeneralTermConditionCategoryController;
use App\Http\Controllers\Backend\GeneralTermConditionController;
use App\Http\Controllers\Backend\InquiryAwardController;
use App\Http\Controllers\Backend\InquiryContactDetailController;
use App\Http\Controllers\Backend\InquiryGeneralChargeController;
use App\Http\Controllers\Backend\InquiryProductDetailController;
use App\Http\Controllers\Backend\InquiryReportController;
use App\Http\Controllers\Backend\InquiryVendorRateDetailController;
use App\Http\Controllers\Backend\LeadsController;
use App\Http\Controllers\Backend\PreVendorCategoryController;
use App\Http\Controllers\Backend\PreVendorDetailController;
use App\Http\Controllers\Backend\PreVendorFollowupDetailController;
use App\Http\Controllers\Backend\PreVendorSubCategoryController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\ReplaceFileController;
use App\Http\Controllers\Backend\ResetPasswordController;
use App\Http\Controllers\Backend\ResInquiryMasterController;
use App\Http\Controllers\Backend\SendMailController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\SmtpSettingController;
use App\Http\Controllers\Backend\StaffController;
use App\Http\Controllers\Backend\TermConditionCategoryController;
use App\Http\Controllers\Backend\TermConditionController;
use App\Http\Controllers\Backend\TestController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\VendorController;
use App\Http\Controllers\Backend\VendorDashboardController;
use App\Http\Controllers\Backend\VendorDocTypeController;
use App\Http\Controllers\Backend\VendorInquiryMasterController;
use App\Http\Controllers\Backend\VendorReportController;
use App\Http\Controllers\Backend\VendorTypeController;
use App\Http\Controllers\Backend\WhatsAppSettingController;
use App\Http\Controllers\Backend\HeadInquiryController;
use App\Http\Controllers\Backend\HeadInquiryProductDetailController;
use App\Http\Controllers\Frontend\InvitationController;
use App\Http\Controllers\Staff\WhatsappTemplateController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes(['register' => false]);

Route::get('fresh-seed', function () {
    try {
        Artisan::call('migrate:fresh --seed');
        dd("Fresh seed done");
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
});

Route::get('cache-clear', function () {
    try {
        Artisan::call('optimize:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:cache');
        dd("optimize, config, and cache cleared");
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
});

Route::get('up', function () {
    try {
        Artisan::call('up');
        dd("Website Up");
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
});

Route::get('down', function () {
    try {
        Artisan::call('down');
        dd("Website Down");
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
});

/*Route::fallback(function () {
    return view('errors.500');
});*/

Route::get('/', function () {
    if (!Auth::user()) {
        return redirect()->route('login');
    }
    if (Auth::user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    if (Auth::user()->hasRole('staff')) {
        return redirect()->route('staff.dashboard');
    }

    return redirect()->route('staff.dashboard');
})->name('index');

Route::get('home', function () {
    return redirect()->route('index');
})->name('home');

Route::get('back-to-login', function () {
    if (Auth::check()) {
        Auth::logout();
    }
    return redirect()->route('login');
})->name('back.to.login');



Route::group(['middleware' => ['auth']], function () {
    Route::get('admin-dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('candidate-dashboard', [DashboardController::class, 'candidateDashboard'])->name('candidate.dashboard');
    Route::get('staff-dashboard', [DashboardController::class, 'staffDashboard'])->name('staff.dashboard');
    Route::group(['prefix' => 'jobs', 'as' => 'jobs.'], function () {
        Route::get('/', [JobController::class, 'index'])->name('index');
        Route::post('apply', [JobController::class, 'apply'])->name('apply');
        Route::post('store', [JobController::class, 'store'])->name('store');
        Route::post('edit', [JobController::class, 'edit'])->name('edit');
        Route::group(['prefix' => '{job}'], function () {
            Route::post('status-change', [JobController::class, 'statusChange'])->name('status.change');
            Route::post('update', [JobController::class, 'update'])->name('update');
            Route::post('delete', [JobController::class, 'delete'])->name('delete');
        });
    });

    Route::group(['prefix' => 'services', 'as' => 'services.'], function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::post('store', [ServiceController::class, 'store'])->name('store');
        Route::post('edit', [ServiceController::class, 'edit'])->name('edit');
        Route::group(['prefix' => '{service}'], function () {
            Route::post('update', [ServiceController::class, 'update'])->name('update');
            Route::post('delete', [ServiceController::class, 'delete'])->name('delete');
        });
    });

    Route::group(['prefix' => 'staff', 'as' => 'staff.'], function () {
        Route::get('/', [StaffController::class, 'index'])->name('index');
        Route::get('list', [StaffController::class, 'stafflist'])->name('list');
        Route::post('store', [StaffController::class, 'store'])->name('store');
        Route::post('edit', [StaffController::class, 'edit'])->name('edit');
        Route::group(['prefix' => '{user}'], function () {
            Route::post('update', [StaffController::class, 'update'])->name('update');
            Route::post('delete', [StaffController::class, 'delete'])->name('delete');
        });
    });

    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('store', [CategoryController::class, 'store'])->name('store');
        Route::post('edit', [CategoryController::class, 'edit'])->name('edit');
        // In your web.php routes file
        Route::get('get-by-parent', [CategoryController::class, 'getByParent'])->name('get-by-parent');
        Route::group(['prefix' => '{category}'], function () {
            Route::post('update', [CategoryController::class, 'update'])->name('update');
            Route::post('delete', [CategoryController::class, 'delete'])->name('delete');
        });
    });

    Route::group(['prefix' => 'leads', 'as' => 'leads.'], function () {
        Route::get('/', [LeadsController::class, 'index'])->name('index');
        Route::post('bulk-assign', [LeadsController::class, 'bulkAssign'])->name('bulk-assign');
        Route::post('store', [LeadsController::class, 'store'])->name('store');
        Route::post('edit', [LeadsController::class, 'edit'])->name('edit');
        Route::post('import', [LeadsController::class, 'import'])->name('import');
        Route::get('download-sample', [LeadsController::class, 'downloadSample'])->name('download-sample');
        Route::group(['prefix' => '{lead}'], function () {
            Route::post('update', [LeadsController::class, 'update'])->name('update');
            Route::post('assign-staff', [LeadsController::class, 'assignStaff'])->name('assign-staff');
            Route::post('delete', [LeadsController::class, 'delete'])->name('delete');
        });
    });

    Route::group(['prefix' => 'staff-leads', 'as' => 'staff-leads.'], function () {
        Route::get('/', [\App\Http\Controllers\Staff\LeadController::class, 'index'])->name('index');
        Route::post('store', [\App\Http\Controllers\Staff\LeadController::class, 'store'])->name('store');
        Route::post('edit', [\App\Http\Controllers\Staff\LeadController::class, 'edit'])->name('edit');
        Route::group(['prefix' => '{lead}'], function () {
            Route::post('update', [\App\Http\Controllers\Staff\LeadController::class, 'update'])->name('update');
            Route::post('assign-staff', [\App\Http\Controllers\Staff\LeadController::class, 'assignStaff'])->name('assign-staff');
            Route::post('delete', [\App\Http\Controllers\Staff\LeadController::class, 'delete'])->name('delete');
        });
    });

    Route::group(['prefix' => 'whatsapp-template', 'as' => 'whatsapp-template.'], function () {
        Route::get('/', [WhatsappTemplateController::class, 'index'])->name('index');
        Route::get('list', [WhatsappTemplateController::class, 'templateList'])->name('list');
        Route::post('store', [WhatsappTemplateController::class, 'store'])->name('store');
        Route::post('edit', [WhatsappTemplateController::class, 'edit'])->name('edit');
        Route::group(['prefix' => '{whatsappTemplate}'], function () {
            Route::post('update', [WhatsappTemplateController::class, 'update'])->name('update');
            Route::post('assign-staff', [WhatsappTemplateController::class, 'assignStaff'])->name('assign-staff');
            Route::post('delete', [WhatsappTemplateController::class, 'delete'])->name('delete');
        });
    });

    Route::group(['prefix' => 'submited-application', 'as' => 'submited-application.'], function () {
        Route::get('/', [JobController::class, 'submitedApplication'])->name('index');
        Route::post('store', [JobController::class, 'store'])->name('store');
        Route::post('edit', [JobController::class, 'edit'])->name('edit');
        Route::post('status-change', [JobController::class, 'statusChange'])->name('status.change');
        Route::group(['prefix' => '{application}'], function () {

            Route::post('update', [JobController::class, 'update'])->name('update');
            Route::post('delete', [JobController::class, 'delete'])->name('delete');
        });
    });

});




