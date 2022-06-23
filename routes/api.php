<?php

use App\Http\Controllers\Address\AddressController;
use App\Http\Controllers\AllPhotoController;
use App\Http\Controllers\Admission\AdmissionController;
use App\Http\Controllers\Admission\UnitController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Address\BasicInfoController;
use App\Http\Controllers\Admission\UniversityController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\Address\DistrictController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\Address\PostOfficeController;
use App\Http\Controllers\Address\UpazilaController;
use App\Http\Controllers\Admission\UserAdmissionController;
use App\Http\Controllers\Address\ExperienceController;
use App\Http\Controllers\Address\SkillController;
use App\Http\Controllers\Admission\AdmissionStatusController;
use App\Http\Controllers\AllPhotoSubController;
use App\Http\Controllers\AppliedJobController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CourseDurationController;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\GraduateController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HigherGraduateController;
use App\Http\Controllers\WorkerPaymentController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\PassingYearController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\UserFavouriteDepartmentController;
use App\Http\Controllers\UserFavouriteGradeController;
use App\Http\Controllers\UserFavouriteUniversityController;
use App\Http\Controllers\FavouriteListController;
use App\Http\Controllers\QuotaController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AppliedJobStatusController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\ExcelUploadController;
use App\Http\Controllers\MerchantUserController;
use App\Http\Controllers\NonAppliedJobController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\NonAppliedJobStatusController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentJobTransferController;
use App\Http\Controllers\PaymentSendMerchantController;
use App\Http\Controllers\PaymentTransfarController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\TransferController;
use App\Models\AdmissionStatus;
use App\Models\Job;
use App\Models\Payment;
use Illuminate\Support\Facades\Route;
use Twilio\Rest\Api\V2010\Account\Call\PaymentContext;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('apiRules')->group(function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('admin/login', [AuthController::class, 'adminlogin']);




    // Route::post("forget-password/email", [ForgetPasswordController::class, 'sendForgetPassword']);
    // Route::post("forget-password/code", [ForgetPasswordController::class, 'codeVerify']);
    // Route::post("forget-password/", [ForgetPasswordController::class, 'forgetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        //user profile api
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile/view', [AuthController::class, 'show']);
        Route::put('/profile/update', [AuthController::class, 'changeProfile']);
        Route::put('/profile-image-upload', [AuthController::class, 'ProfileUpload']);
        Route::post('/change-profile-picture', [AuthController::class, 'changeProfilepicture']);
        Route::get('/user-list', [\App\Http\Controllers\Website\HomeController::class, 'userList']);
        Route::get('/user-show/{id}', [\App\Http\Controllers\Website\HomeController::class, 'userShow']);
        Route::put('user/{user}',[UserController::class,'update']);
        Route::delete('user/{user}', [UserController::class, 'destroy']);

        // Addmission Backend Resource
        //Applied
        Route::apiResource('university', UniversityController::class);


        Route::apiResource('admission-status', AdmissionStatusController::class);

        Route::get('university-list', [UniversityController::class, 'universityList'])->name('university.list');
        Route::get('university-list', [UniversityController::class, 'universityList'])->name('university.list');
        Route::get('admission/list', [AdmissionController::class, 'list'])->name('admission.list');;
        Route::get('admission/all-list', [AdmissionController::class, 'userIndex'])->name('admission.all.list');
        Route::get('admission/users-list/{admission}', [AdmissionController::class, 'eligibleUsers'])->name('admission.eligible.Users');
        Route::post('admission-history-fillter/', [AdmissionController::class, 'admisionHistoryFillter'])->name('admission.History.fillter');
        Route::post('admission-fillter/', [AdmissionController::class, 'admissionFillter'])->name('admission.fillter');
        Route::post('admission/file/{id}', [AdmissionController::class, 'fileUpload'])->name('admission.file.upload');
        Route::get('user-admission/appliedList', [UserAdmissionController::class, 'appliedList']);
        Route::post('user-admission/status/{id}', [UserAdmissionController::class, 'statusUpdate']);
        Route::get('user-admission/status/{id}', [AdmissionStatusController::class, 'statusList']);
        Route::get('admission/user-index', [AdmissionController::class, 'nonApplied'])->name('nonApplied.admission.user.index');
        Route::get('admission/status/{id}', [AdmissionController::class, 'statusUpdate'])->name('admission.status.update');
        Route::get('admission/user/status/{id}', [AdmissionStatusController::class, 'statusList'])->name('admission.usser.status.list');
        Route::post('admission/user/status/store/{id}', [AdmissionStatusController::class, 'store'])->name('admission.usser.status.store');

        Route::get('merchant-admission-users/{id}' , [TransferController::class , 'admissionMerchantUser']);
        Route::get('all-applied-adsmission-marchent', [UserAdmissionController::class, 'addmissionMarchent'])->name('applied-addmission.marchent');
        Route::post('admission-transfer-by-merchant/{id}', [TransferController::class, 'admissionTransferByMerchant']);
        Route::post('admission-no-transfer/{id}', [TransferController::class, 'admissionNoTransfer']);


        // jobs
        Route::apiResource('jobs', JobController::class);
        Route::get('jobs-active', [JobController::class, 'active'])->name('job.active');
        Route::put('jobs-inactive/{id}', [JobController::class, 'isInActive'])->name('job.inactive');
        Route::get('jobs-with-out-user', [JobController::class, 'jobWithoutUser'])->name('job.without.user');
        //Route::get('applied-job-all', [AppliedJobController::class , 'app']);
        Route::get('all-applied-job', [AppliedJobController::class, 'index'])->name('applied-job.index');
        Route::get('all-applied-job-marchent', [AppliedJobController::class, 'marchent'])->name('applied-job.marchent');
        Route::get('all-applied-job-pending', [AppliedJobController::class, 'appliedJobPending'])->name('applied-job.pending');
        Route::post('applied-job-store/{id}', [AppliedJobController::class, 'store'])->name('applied-job.store');
        Route::post('applied-job-update/{appliedJob}', [AppliedJobController::class, 'update'])->name('applied-job.update');
        Route::get('applied-job-show', [AppliedJobController::class, 'show'])->name('applied-job.show');
        Route::post('applied-job-status-create/{id}', [AppliedJobController::class, 'CreateStatus'])->name('applied-job.create.status');
        Route::post('applied-job-status-active/{id}', [AppliedJobController::class, 'ActiveStatus'])->name('applied-job.active.status');
        Route::Put('applied-job-status-update/{id}', [AppliedJobController::class, 'UpdateStatus'])->name('applied-job.update.status');
        Route::delete('applied-job-delete/{id}', [AppliedJobController::class, 'delete'])->name('applied-job.delete');
        Route::get('applied-job-show/{id}', [AppliedJobController::class, 'singleAppliedJob'])->name('applied.job.show');
        Route::post('applied-job-status/{id}', [AppliedJobController::class, 'jobStatus'])->name('applied.job.status');
        Route::post('applied-job-roll-update/{id}', [AppliedJobController::class, 'appliedJobStatusUpdate'])->name('applied.job.roll.update');
        Route::post('job-history-fillter/', [JobController::class, 'jobHistoryFillter'])->name('job.history.fillter');
        Route::post('job-fillter/', [JobController::class, 'jobFillter'])->name('job.fillter');

        Route::get('job/users-list/{job}', [JobController::class, 'eligibleUsers'])->name('eligible.Users');
        Route::get('job/user-index', [JobController::class, 'nonApplied'])->name('nonApplied.job.user.index');

        Route::get('merchant-users/{id}' , [TransferController::class , 'merchantUser']);
        Route::post('job-transfer-by-merchant/{id}', [TransferController::class, 'jobTransferByMerchant']);
        Route::post('job-no-transfer/{id}', [TransferController::class, 'jobNoTransfer']);

        // Route::get('usermatching/{id}', [JobController::class, 'usermatching']);
        Route::apiResource('non-applied-status', NonAppliedJobStatusController::class);
        Route::post('non-applied-status-check', [NonAppliedJobStatusController::class , 'check']);


        Route::get('non-applied',[NonAppliedJobController::class,'index']);
        Route::apiResource('applied-job-status', AppliedJobStatusController::class);

        Route::apiResource('/admission', AdmissionController::class);
        //user Favourite List
        Route::apiResource('user-favourite-department', UserFavouriteDepartmentController::class)->only('index', 'store',);
        Route::apiResource('user-favourite-grade', UserFavouriteGradeController::class)->only('index', 'store',);
        Route::apiResource('user-favourite-university', UserFavouriteUniversityController::class)->only('index', 'store',);
        Route::get('user-favourite-list', [FavouriteListController::class, 'index']);
        Route::get('user-favourite-admission-list', [FavouriteListController::class, 'admissionFavourites']);
        Route::get('user-history-list', [FavouriteListController::class, 'history']);
        Route::get('user-history-admission-list', [FavouriteListController::class, 'admissionHistory']);
        Route::get('non-applied-admissions',[AdmissionStatusController::class,'index']);



    // All APi Resource
        Route::apiResource('user-admission', UserAdmissionController::class);
        //Terms
        Route::apiResource('term', TermController::class);
        //Policy
        Route::apiResource('policy', PolicyController::class);
        //grade
        Route::apiResource('grade', GradeController::class);
        // Examination
        Route::apiResource('examination', ExaminationController::class);
        // Group
        Route::apiResource('group', GroupController::class);
        //Board
        Route::apiResource('board', BoardController::class);
        //Passing Year
        Route::apiResource('passing_year', PassingYearController::class);
        // Graducate
        Route::apiResource('graduate', GraduateController::class);
        //Unit
        Route::apiResource('unit', UnitController::class);
        //Image Upload
        Route::apiResource('photos', AllPhotoController::class);
        Route::apiResource('photos-sub', AllPhotoSubController::class);
        //Basic Info
        Route::apiResource('basicInfo', BasicInfoController::class);
        //Basic Info->Experience
        Route::apiResource('experience', ExperienceController::class);
        //Basic Info ->Skill
        Route::apiResource('skill', SkillController::class);
        //Basic Info->Address
        Route::apiResource('address', AddressController::class);
        //Image Upload
        Route::apiResource('photos', AllPhotoController::class);
        Route::apiResource('photos-sub', AllPhotoSubController::class);
        //University
        Route::apiResource('/university', UniversityController::class);
        //Deparment
        Route::apiResource('departments', DepartmentController::class);
        //District
        Route::apiResource('district', DistrictController::class);
        //Upazila,
        Route::apiResource('upazila', UpazilaController::class);
        //Post Office
        Route::apiResource('post-office', PostOfficeController::class);
        // subjects
        Route::apiResource('subjects', SubjectController::class);
        //major
        Route::apiResource('major', MajorController::class);
        //Course Duration
        Route::apiResource('course_duration', CourseDurationController::class);
        //Graducate
        Route::apiResource('graduate', GraduateController::class);
        Route::apiResource('higher-graduate', HigherGraduateController::class);
        //quota
        Route::apiResource('quotas', QuotaController::class);
        //posy
        Route::apiResource('post', PostController::class);
        //payment
        Route::apiResource('user-payment-send', PaymentController::class);
         Route::get('admin-payment-send', [PaymentController::class , 'adminPayment']);
        //marchent payment
        Route::apiResource('worker-payment-send', WorkerPaymentController::class);
         Route::post('user-payment-send-update/{id}', [PaymentController::class , 'paymentUpdate']);
        //Role
        Route::apiResource('role', RoleController::class);
        Route::apiResource('excel-upload', ExcelUploadController::class);
        Route::post('excel-upload-check', [ExcelUploadController::class , 'check']);
        Route::get('role-user', [RoleController::class, 'roleUser']);
        Route::get('role/assign/user', [RoleController::class, 'roleAssign']);
        Route::post('role/assign', [RoleController::class, 'storeAssign']);

        // All list
        Route::get('grade-list', [GradeController::class, 'gradeList']);
        Route::get('examination-list', [ExaminationController::class, 'examinationList']);
        Route::get('group-list', [GroupController::class, 'groupList']);
        Route::get('board-list', [BoardController::class, 'boardList']);
        Route::get('passing-year-list', [PassingYearController::class, 'passingYearList']);
        Route::get('unit-list', [UnitController::class, 'unitList']);
        Route::get('departments-list', [DepartmentController::class, 'departmentsList']);
        Route::get('district-list', [DistrictController::class, 'districtList']);
        Route::get('upazila-list', [UpazilaController::class, 'upazilaList']);
        Route::get('post-office-list', [PostOfficeController::class, 'postOfficeList']);
        Route::get('subjects-list', [SubjectController::class, 'subjectsList']);
        Route::get('major-list', [MajorController::class, 'majorList']);
        Route::get('course-duration-list', [CourseDurationController::class, 'courseDurationList']);
        Route::get('quota-list', [QuotaController::class, 'quotaList']);
        Route::get('post-list', [PostController::class, 'postList']);
        //Dasboard Count
        Route::get('all-count', [DashBoardController::class, 'allCount']);

        //Notification
        Route::get('user-notification' , [NotificationController::class , 'index']);


        //AdminMarchent
        Route::post('admin/register', [AuthController::class, 'adminRegister']);
        Route::apiResource('merchant-list', MerchantUserController::class);
        Route::post('merchant-transfer/{user_id}', [MerchantUserController::class , 'merchantTransferUser']);
        Route::get('admin-merchant-list', [MerchantUserController::class , 'marchentByAdmin']);
        Route::get('all-merchant-list', [MerchantUserController::class , 'allmarchent']);

        //React job
        Route::post('applied-job-web-store/{id}', [AppliedJobController::class, 'appliedJobStore'])->name('applied-web-job.store');
        Route::post('applied-job-store-merchant/{id}', [PaymentController::class, 'marchentPayment'])->name('applied-job-payment.store');

        //React  Admission
        Route::post('user-admission-web-store/{id}', [UserAdmissionController::class, 'userAdmissionStore'])->name('user-web-admission.store');
        Route::post('user-admission-store-merchant/{id}', [PaymentController::class, 'marchentAdmissionPayment'])->name('user-web-admission.-payment.store');


        Route::get('payment-send-merchant-all-list', [PaymentSendMerchantController::class, 'index']);
        Route::post('payment-send-merchant/{id}', [PaymentSendMerchantController::class, 'paymentSendMerchant']);
        Route::get('payment-send-merchant-list', [PaymentSendMerchantController::class, 'paymentSendMerchantList']);
        Route::post('payment-send-merchant-job-pay-now/{id}', [PaymentSendMerchantController::class, 'paymentSendMerchantPayNow']);
        Route::post('payment-send-merchant-admission-pay-now/{id}', [PaymentSendMerchantController::class,
         'paymentSendMerchantadmissionPayNow']);


        Route::post('merchant-job-payment/{id}', [PaymentSendMerchantController::class, 'merchantJobPayment']);
        Route::post('merchant-admission-payment/{id}', [PaymentSendMerchantController::class, 'merchantAdmissionPayment']);
        
        Route::get('user-notification-setting' , [NotificationController::class , 'setting']);
        Route::post('user-notification-setting-store' , [NotificationController::class , 'settingStore']);

        Route::get('user-balance-reload' , [UserController::class , 'balanceReload']);
        Route::post('user-notice', [\App\Http\Controllers\Admin\UserController::class, 'notice']);
        //sms
        // Route::get('user-sms-demo', [\App\Http\Controllers\Admin\UserController::class, 'sms']);
         Route::get('user-notice', [\App\Http\Controllers\Admin\UserController::class, 'notice']);
        // Route::get('user-demo', [\App\Http\Controllers\Admin\UserController::class, 'demo']);
    });
});

  //facebook Login
Route::get('/login/{provider}', [ProviderController::class, 'redirectToProvider']);
Route::get('login/{provider}/callback', [ProviderController::class, 'handleProviderCallback']);
