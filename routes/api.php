<?php

use App\Http\Controllers\ContactReportController;
use App\Http\Controllers\PlaningBoardController;
use App\Http\Controllers\ReportCategoryController;
use App\Http\Controllers\EloVersionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceTemplateController;
use App\Http\Controllers\ProductGroupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectManagementController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyLocationController;
use App\Http\Controllers\EmployeeVacationController;
use App\Http\Controllers\GlobalSettingController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PaymentPeriodController;
use App\Http\Controllers\PerformanceRecordController;
use App\Http\Controllers\TimeCheckingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierLocationController;
use App\Http\Controllers\SurveyChapterController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyQuestionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\ProductStoreRequestCommentsController;
use App\Http\Controllers\TimeTrackerController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\SurveyConfigurationController;
use App\Http\Controllers\TermsOfPaymentController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductSoftwareController;
use App\Http\Controllers\ServiceLevelAgreementController;
use App\Http\Controllers\OperatingSystemController;
use App\Http\Controllers\LeadStatusController;
use App\Http\Controllers\ProductUnitController;
use App\Http\Controllers\SystemCloudController;
use App\Http\Controllers\SystemHostController;
use App\Http\Controllers\UserDepartmentController;
use App\Http\Controllers\UserLocationController;
use App\Http\Controllers\UserProfilePictureController;
use App\Http\Controllers\UserTeamController;
use App\Http\Controllers\ContactReportSourceController;
use App\Http\Controllers\DatabaseCloudController;
use App\Http\Controllers\DistributedFilesystemController;
use App\Http\Controllers\FleetCarsController;
use App\Http\Controllers\FleetDriverController;
use App\Http\Controllers\FuelMonitoringController;
use App\Http\Controllers\FuelReceiptsController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\MileageMonitoringController;
use App\Http\Controllers\TravelExpenseController;
use App\Http\Controllers\TravelExpenseRequestTypeController;
use App\Http\Controllers\WorkshopTemplateController;
use App\Http\Controllers\WorkshopTemplateCardController;
use App\Http\Controllers\WorkshopTemplateRowController;
use App\Http\Controllers\WorkshopTemplateColumnController;
use App\Http\Controllers\WorkshopTemplateWidgetController;
use App\Http\Controllers\APIKeysController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\CustomerPortalNewsController;
use App\Http\Controllers\TravelExpenseReportBillController;
use App\Http\Controllers\TravelExpenseReportTransportationController;
use App\Http\Controllers\TravelExpenseReportDayController;
use App\Http\Controllers\DriverLicenceCheckController;
use App\Http\Controllers\FleetManagementIntervalSettingController;
use App\Http\Controllers\TravelExpenseInvoiceTypeController;
use App\Http\Controllers\VehicleClassController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\LicensesController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SkillGroupController;
use App\Http\Controllers\SkillMatrixController;
use App\Http\Controllers\SkillLevelController;
use App\Http\Controllers\JobLevelController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SlaInfrastructureController;
use App\Http\Controllers\SlaServiceTimeController;
use App\Http\Controllers\SlaLevelIncidentController;
use App\Http\Controllers\SlaLevelChangeController;
use App\Http\Controllers\FormOfContractController;
use App\Http\Controllers\OrderConfirmationController;
use App\Http\Controllers\AffectedGroupsController;
use App\Http\Controllers\ContinuousImprovementProcessController;
use App\Http\Controllers\InvoiceReminderLevelController;
use App\Http\Controllers\ProjectStatusController;
use App\Http\Controllers\ProtocolTypeController;
use App\Http\Controllers\ProjectProtocolController;
use App\Http\Controllers\ProjectProtocolDescriptionEntryController;
use App\Http\Controllers\HighestSchoolDegreeController;
use App\Http\Controllers\HighestEducationLevelController;
use App\Http\Controllers\ApplicationManagementServiceController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetDeliveryController;
use App\Http\Controllers\AssetEmployeeTextController;
use App\Http\Controllers\AssetListController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\ConsultingDashboardController;
use App\Http\Controllers\OpenPostsController;
use App\Http\Controllers\PriceListController;
use App\Http\Controllers\MailTemplateAssignmentController;
use App\Http\Controllers\SalesDashboardController;
use App\Http\Controllers\ConsultingTeamController;
use App\Http\Controllers\CostMonitoringController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CloudInfrastructureServerController;
use App\Http\Controllers\EmployeeAssetController;
use App\Http\Controllers\OutboundedContractController;
use App\Http\Controllers\PersonalModificationProcessManagerController;
use App\Http\Controllers\PersonalModificationProcessChecklistController;
use App\Http\Controllers\ModifyMyDataController;
use App\Http\Controllers\MyAssetController;
use App\Http\Controllers\PersonalRequestApproverController;
use App\Http\Controllers\PersonalRequestController;
use App\Http\Controllers\PersonalRequirementController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SlaLevelController;
use App\Http\Controllers\OfferRequestController;
use App\Http\Controllers\CreditorInvoiceController;
use App\Http\Controllers\ProjectOrderConfirmationController;
use App\Http\Controllers\ProductStoreController;
use App\Http\Controllers\ProductStoreReviewController;
use App\Http\Controllers\ProductStoreRequestController;
use App\Http\Controllers\PMDashboardTeamsController;
use App\Http\Controllers\TicketDashboard;
use App\Http\Controllers\GlobalNotificationListController;
use App\Http\Controllers\MyFeedController;
use App\Http\Controllers\MyTaskController;
use App\Http\Controllers\StorageLocationController;
use App\Http\Controllers\TaskBoardController;
use App\Http\Controllers\TaskBoardInitialization;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TimeTrackerAmsController;
use App\Http\Controllers\CloudInfrastructreController;
use App\Http\Controllers\EmployeeInterviewController;
use App\Http\Controllers\ReviewReportController;
use App\Http\Controllers\PartnerOrderController;
use App\Http\Controllers\PartnerCustomerController;
use App\Http\Controllers\PartnerTicketController;
use App\Http\Controllers\PartnerTicketCommentController;
use App\Http\Controllers\EloBusinessSolutionController;
use App\Http\Controllers\CancelationRequestController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['user.permissions'])->group(function () {
    // for swagger
    //     Route::post('/auth/token', [AuthController::class, 'login']);

    //export csv
    Route::get('/companies/download-csv', [CompanyController::class, 'exportCsv']);
    Route::get('/suppliers/download-csv', [SupplierController::class, 'exportCsv']);

    Route::get('/suppliers/download-latest-csv', [SupplierController::class, 'exportLatestCSV']);

    Route::get('/companies/download-latest-csv', [CompanyController::class, 'exportLatestCSV']);

    //Company module
    Route::apiResource('companies', CompanyController::class);

    //Company module
    Route::apiResource('cancelation-requests', CancelationRequestController::class);


    //Company location module
    Route::apiResource('company-locations', CompanyLocationController::class)->except(['index']);
    Route::get('/company-locations/company/{id}', [CompanyLocationController::class, 'getLocationByCompany']);

    //Contact report module
    Route::get('contact-reports/get-by-resubmit', [ContactReportController::class, 'getByResubmit']);
    Route::get('/contact-reports/company', [ContactReportController::class, 'getReportByCompany']);
    Route::apiResource('contact-reports', ContactReportController::class);

    //Report Category
    Route::apiResource('report-category', ReportCategoryController::class);

    //Invoice module
    Route::get('/invoices/elo-request-data/{id}', [InvoiceController::class, 'getELORequestData']);
    Route::post('/invoices/statistics', [InvoiceController::class, 'invoiceStatistics']);
    Route::get('/invoices/create', [InvoiceController::class, 'create']);
    Route::post('/systems-search', [InvoiceController::class, 'getCustomerSpecifiedSystems']);
    Route::get('/invoices/company', [InvoiceController::class, 'getInvoiceByCompany']);
    Route::apiResource('/invoices', InvoiceController::class);
    Route::patch('/update-invoice-status/{id}', [InvoiceController::class, 'updateInvoiceStatus']);
    Route::get('/reference/invoices/{id?}', [InvoiceController::class, 'getReferenceInvoices']);

    //Invoice module

    //Invoice Template
    Route::apiResource('/invoice-templates', InvoiceTemplateController::class);
    Route::get('invoice-templates/create-invoice/{id}', [InvoiceTemplateController::class, 'createInvoice']);


    //Product group module
    Route::apiResource('groups', ProductGroupController::class);

    //fleet cars
    Route::apiResource('fleet-cars', FleetCarsController::class);
    Route::post('/upload-fleet-documents', [FleetCarsController::class, 'uploadFleetDocuments']);
    Route::post('/update-fleet-documents', [FleetCarsController::class, 'updateFleetDocuments']);
    Route::post('/create-uvv', [FleetCarsController::class, 'createUvv']);

    //fuel monitoring
    Route::get('/fuel-monitoring', FuelMonitoringController::class);
    //fuel receipts
    Route::apiResource('fuel-receipts', FuelReceiptsController::class);
    //mileage monitoring
    Route::get('/mileage-monitoring', MileageMonitoringController::class);
    //fleet drivers
    Route::apiResource('/fleet-drivers', FleetDriverController::class);
    Route::get('/fleet-driver-history', [FleetDriverController::class, 'driverCarHistory']);
    //interval settings
    Route::apiResource('interval-settings', FleetManagementIntervalSettingController::class);

    //consulting dashboard
    Route::post('/consulting-dashboard', ConsultingDashboardController::class);

    Route::apiResource('/driver-licence-check', DriverLicenceCheckController::class);

    //vehicle classes
    Route::get('/vehicle-classes', VehicleClassController::class);
    //Elo version module

    //Project module
    Route::get('/project-management/projects/company', [ProjectController::class, 'getProjectByCompany']);
    Route::apiResource('project-management/projects', ProjectController::class);

    //Milestone module
    Route::apiResource('project-management/milestones', MilestoneController::class);
    Route::get('project-management/milestones/by-project/{project_id}', [MilestoneController::class, 'getMilestonesByProject']);
    Route::get('project-management/milestones/by-customer/{customer_id}', [MilestoneController::class, 'getMilestonesByCustomer']);

    //Task module
    Route::get('project-management/tasks/backlog', [TaskController::class, 'backlog']);
    Route::get('project-management/tasks/by-date', [TaskController::class, 'getTasksByDate']);
    Route::apiResource('project-management/tasks', TaskController::class);

    //Project management module
    Route::get('project-management/my-tasks', [ProjectManagementController::class, 'getMyTasks']);
    Route::get('project-management/{project_id}', [ProjectManagementController::class, 'index']);

    //countries route
    Route::apiResource('/countries', CountriesController::class);
    Route::get('/get-all-countries', [CountriesController::class, 'getAllCountries']);
    Route::post('/upload-countries', [CountriesController::class, 'uploadCsv']);
    Route::post('/store-countries-csv', [CountriesController::class, 'storeCsv']);
    //TEST CASE ROUTE
    Route::get('project-management/my-tasks-modified', [ProjectManagementController::class, 'getMyTasksModified']);

    //System cloud
    Route::apiResource('system-cloud', SystemCloudController::class);

    //System cloud
    Route::post('/cloud-pool', [CloudInfrastructureServerController::class, 'store']);

    //Cloud Infrastructure
    Route::apiResource('cloud-infrastructure', CloudInfrastructreController::class);

    Route::post('/cloud-pool/delete', [CloudInfrastructureServerController::class, 'destroy']);

    Route::get('/get-server/{id}', [CloudInfrastructureServerController::class, 'show']);
    //System module
    Route::get('/systems/company', [SystemController::class, 'getSystemByCompany']);
    Route::apiResource('systems', SystemController::class);
    Route::get('/systems/download/{system}/rdf', [SystemController::class, 'download']);
    Route::post('/save/system-cloud/tenant', [SystemCloudController::class, 'storeCloudInfrastructureTenant']);
    Route::get('/system-cloud-infrastructure/tenants/{id}', [SystemCloudController::class, 'getCloudInfrastructureTenants']);
    Route::get('/customers/tenants/{id}', [SystemCloudController::class, 'getCustomerTenants']);

    Route::get('/system-clouds/tenant', [SystemCloudController::class, 'cloudIndexForTenant']);
    Route::get('/system-clouds/tenant/{id}', [SystemCloudController::class, 'showSystemForTenant']);
    Route::delete('/system-clouds/tenant/{id}', [SystemCloudController::class, 'deleteTenant']);
    Route::put('system-cloud/tenant/{id}', [SystemCloudController::class, 'updateCloudTenant']);
    Route::delete('/system-cloud-infrastructure/tenants/{id}', [SystemCloudController::class, 'deleteCloudTenant']);
    Route::get('system-cloud/tenant/{id}', [SystemCloudController::class, 'getCloudInfrastructureTenant']);
    Route::delete('/system-cloud-infrastructure/tenant/repository/{repositoryId}', [SystemCloudController::class, 'deleteCloudTenantRepository']);
    Route::post('/system-cloud-infrastructure/tenant/repository/create', [SystemCloudController::class, 'createCloudTenantRepository']);



    //operating system
    Route::apiResource('operating-system', OperatingSystemController::class);

    Route::apiResource('sales/lead-status', LeadStatusController::class);

    //execute system commands
    Route::get('/install-products/{system}', [SystemController::class, 'install']);
    Route::get('/execute-system-commands/{system}', [SystemController::class, 'executeCustomCommands']);
    //groups and versions
    Route::apiResource('versions', EloVersionController::class);
    Route::get('/versions-by-software/{id}', [EloVersionController::class, 'getVersionsBySoftware']);
    Route::apiResource('groups', ProductGroupController::class);

    //product units
    Route::apiResource('units', ProductUnitController::class);

    //product software
    Route::apiResource('softwares', ProductSoftwareController::class);

    //service level agreements
    Route::apiResource('service-level-agreements', ServiceLevelAgreementController::class);

    //SLA Infrastructure
    Route::apiResource('sla-infrastructure', SlaInfrastructureController::class);

    // SLA Service Times
    Route::apiResource('sla-service-time', SlaServiceTimeController::class);

    // SLA Level Incident
    Route::apiResource('sla-level-incident', SlaLevelIncidentController::class);

    // SLA Level Change
    Route::apiResource('sla-level-change', SlaLevelChangeController::class);

    //payment period
    Route::apiResource('periods', PaymentPeriodController::class);

    //contact report source
    Route::apiResource('contact-report-source', ContactReportSourceController::class);

    //Ticket module
    Route::get('/tickets/open-count', [TicketController::class, 'openTicketCount']);
    Route::get('tickets/re-open/{id}/{status}', [TicketController::class, 'reOpenTicket']); // for customer portal
    Route::get('/company/tickets', [TicketController::class, 'showByCompany']);
    Route::apiResource('tickets', TicketController::class);
    Route::get('/get-ticket-comments', [TicketController::class, 'showTicketComments']);
    Route::get('/ticket/ticket-comments/{id}', [TicketController::class, 'getTicketByComment']);

    //Ticket Comment module
    Route::post('/ticket-comments/send-mail/{id}', [TicketCommentController::class, 'sendMail']);
    Route::get('/ticket-comments/ticket/{id}', [TicketCommentController::class, 'getCommentsByTicket']);
    Route::get('/ticket-comments/download-attachment/{id}', [TicketCommentController::class, 'getAttachmentBase64']); //for customer portal and admin portal
    Route::apiResource('ticket-comments', TicketCommentController::class)->except(['index']);
    Route::get('/ticket-comment/download/{id}', [TicketCommentController::class, 'getAttachmentDownload']);

    //Product Store Request Comments
    Route::apiResource('product-store-request-comments', ProductStoreRequestCommentsController::class)->except(['index']);
    Route::get('/product-store-request-comments/product-store-request/{id}', [ProductStoreRequestCommentsController::class, 'getCommentsByProductStoreRequest']);
    Route::get('/product-store-request-comments/download-attachment/{id}', [ProductStoreRequestCommentsController::class, 'getAttachmentBase64']); //for customer portal and admin portal
    Route::get('/product-store-request-comment/download/{id}', [ProductStoreRequestCommentsController::class, 'getAttachmentDownload']);


    Route::apiResource('versions', EloVersionController::class);
    Route::get('products/create', [ProductController::class, 'create']);

    Route::post('import-products', [ProductImportController::class, 'import']);

    Route::post('store-imported-products', [ProductImportController::class, 'store']);
    //product files upload route
    Route::post('products/files/upload', [ProductController::class, 'uploadFiles']);

    //product files update route
    Route::post('products/files/update', [ProductController::class, 'updateFiles']);

    //Product Module
    Route::apiResource('products', ProductController::class);
    Route::get('products-with-quantity', [ProductController::class, 'productsWithQuantity']);
    //execute invoice commands
    Route::get('/execute-commands/{invoice}', [InvoiceController::class, 'executeSSH']);
    Route::get('/execute-custom-commands/{invoice}', [InvoiceController::class, 'executeCustomCommands']);

    //product price
    Route::apiResource('/product-price', PriceListController::class);

    Route::get('/get-software-price/{id}', [PriceListController::class, 'getPriceBySoftware']);

    //Time Tracker
    Route::get('/time-trackers/monthly-forecast', [TimeTrackerController::class, 'timeTrackerMonthlyForecast']);

    Route::get('/time-trackers/overview', [TimeTrackerController::class, 'timeTrackerOverview']);
    Route::post('/time-trackers/overtime', [TimeTrackerController::class, 'getOvertime']);
    Route::get('/time-trackers/overview/reporting', [TimeTrackerController::class, 'timeTrackerOverviewReporting']);
    Route::post('/time-trackers/statistics', [TimeTrackerController::class, 'timeTrackerStatistics']);
    Route::get('/time-trackers', [TimeTrackerController::class, 'getTimeTrackerData']);
    Route::get('/time-trackers/export-user-data', [TimeTrackerController::class, 'exportUserTimeTrackerData']);
    Route::post('/time-tracker/validate-profile', [TimeTrackerController::class, 'validateUserProfile']);
    Route::post('/remove-ticket-comment', [TimeTrackerController::class, 'makeTicketCommentFalse']);
    Route::patch('/time-tracker-government/{id}', [TimeTrackerController::class, 'editTimeTrackerGovernment']);
    Route::patch('/time-tracker-company/{id}', [TimeTrackerController::class, 'editTimeTrackerCompanies']);

    //ticket dashboard
    Route::get('/ticket-dashboard', TicketDashboard::class);

    //post time tracker instance
    Route::post('/time-tracker', [TimeTrackerController::class, 'saveTimeTrackerInstance']);
    Route::delete('/tracker-government/{id}', [TimeTrackerController::class, 'removeTimeTrackerGovernment']);
    Route::delete('/tracker-company/{id}', [TimeTrackerController::class, 'removeTimeTrackerCompany']);
    //surveys route
    Route::post('survey-question/create', [SurveyQuestionController::class, 'store']);
    Route::patch('survey-question/update/{id}', [SurveyQuestionController::class, 'update']);
    Route::delete('survey-question/delete/{id}', [SurveyQuestionController::class, 'destroy']);
    Route::post('/survey-question/move', [SurveyChapterController::class, 'moveQuestionToChapter']);

    Route::post('/survey-question-change/order', [SurveyQuestionController::class, 'moveQuestion']);
    Route::post('/survey-chapter-change/order', [SurveyChapterController::class, 'moveChapter']);

    Route::post('/survey-chapter/create', [SurveyChapterController::class, 'store']);
    Route::patch('/survey-chapter/update/{survey_chapter}', [SurveyChapterController::class, 'update']);
    Route::delete('/survey-chapter/delete/{survey_chapter}', [SurveyChapterController::class, 'destroy']);

    Route::apiResource(('/surveys'), SurveyController::class);
    Route::get('survey-products/{id}', [SurveyController::class, 'getAllSurveyProducts']);

    Route::post('/surveys/store-style-configurations', [SurveyController::class, 'storeStyleConfigurations']);

    Route::apiResource('/survey-configuration', SurveyConfigurationController::class);

    //Employee vacation module
    Route::get('employee-vacation/send-mail/{id}', [EmployeeVacationController::class, 'sendMail']);
    Route::get('employee-vacation/requests-for-approval', [EmployeeVacationController::class, 'getApproveListing']);
    Route::delete('employee-vacation/delete-approval/{id}', [EmployeeVacationController::class, 'deleteApproval']);
    Route::post('employee-vacation/set-approval-status', [EmployeeVacationController::class, 'setApprovalStatus']);
    Route::get('employee-vacation/profile-data', [EmployeeVacationController::class, 'getVacationProfileData']);
    Route::post('employee-vacation/illness', [EmployeeVacationController::class, 'createIllnessLeave']);
    Route::get('employee-vacation/get-illness-leaves', [EmployeeVacationController::class, 'illnessLeavesByUser']);
    Route::apiResource('employee-vacation', EmployeeVacationController::class);
    Route::post('employee-vacation/calendar', [EmployeeVacationController::class, 'getVacationCalendar']);
    Route::post('employee-vacation/cancel-vacation', [EmployeeVacationController::class, 'cancelVacation']);
    Route::delete('employee-vacation/delete/{id}', [EmployeeVacationController::class, 'deleteIllnessLeave']);
    Route::put('/update-employee-vacation-illness-leave/{id}', [EmployeeVacationController::class, 'updateIllnessLeave']);
    //User profile
    Route::get('user-profile', [UserProfileController::class, 'index']);
    Route::get('user-profile/profile/{id}', [UserProfileController::class, 'showProfileData']);
    Route::delete('user-profile/profile/{id}', [UserProfileController::class, 'destroy']);
    Route::post('user-profile/profile', [UserProfileController::class, 'saveProfileData']);
    Route::get('user-profile/job/{id}', [UserProfileController::class, 'showJobData']);
    Route::get('user-profile/job-by-user/{id}', [UserProfileController::class, 'showJobDataByUser']);
    Route::get('user-profile/job-by-user-basic/{id}', [UserProfileController::class, 'showBasicJobDataByUser']);
    Route::post('user-profile/job', [UserProfileController::class, 'saveJobData']);
    Route::get('user-profile/compensation/{id}', [UserProfileController::class, 'showCompensationData']);
    Route::post('user-profile/compensation', [UserProfileController::class, 'saveCompensationData']);
    Route::get('user-profile/approval-permissions', [UserProfileController::class, 'getApprovalPermissions']);
    Route::get('user-profile/basic-employee-info/{id}', [UserProfileController::class, 'getBasicEmployeeInfo']);
    Route::get('user-profile/employee-info/{id}', [UserProfileController::class, 'getAllEmployeeInfo']);
    Route::get('/employee-assets', [EmployeeAssetController::class, 'employeeAssets']);
    Route::get('/sale-offers/elo-request-data/{id}', [OfferController::class, 'getELORequestData']);
    Route::post('/sale-offers/update-status/{id}', [OfferController::class, 'updateStatus']);
    Route::get('/sale-offers/document-generation/{id}', [OfferController::class, 'offerForDocumentGeneration']);
    Route::get('/sale-offers/download-csv', [OfferController::class, 'exportCsv']);
    Route::get('/sale-offers/download-latest-csv', [OfferController::class, 'exportLatestCSV']);
    Route::get('sale-offers-by-customer', [OfferController::class, 'getOffersByCompany']);
    Route::apiResource('sale-offers', OfferController::class);

    //invoice reminder level
    Route::get('invoice-reminder-level/get-by-level-name', [InvoiceReminderLevelController::class, 'getByLevelName']);
    Route::apiResource('invoice-reminder-level', InvoiceReminderLevelController::class);

    //changelog
    Route::apiResource('/changelogs', ChangelogController::class);

    //cost monitoring
    Route::get('/cost-monitoring', CostMonitoringController::class);

    //Order Confirmation
    Route::post('/order-confirmation/update-status/{id}', [OrderConfirmationController::class, 'updateStatus']);
    Route::get('/order-confirmation/document-generation/{id}', [OrderConfirmationController::class, 'offerConfirmationForDocumentGeneration']);
    Route::get('/order-confirmation/create-invoices/{id}', [OrderConfirmationController::class, 'createInvoices']);
    Route::apiResource('order-confirmation', OrderConfirmationController::class);

    //Product Category
    Route::get('product-category/get-by-product-type', [ProductCategoryController::class, 'getByProductType']);
    Route::apiResource('product-category', ProductCategoryController::class);

    //Terms Of Payment
    Route::apiResource('terms-of-payment', TermsOfPaymentController::class);

    //Supplier module
    Route::apiResource('suppliers', SupplierController::class);

    //asset module
    Route::apiResource('assets', AssetController::class);

    //asset article
    Route::apiResource('asset-articles', AssetListController::class);

    //asset article
    Route::apiResource('asset-lists', EmployeeAssetController::class);

    //my assets
    Route::get('/my-assets', MyAssetController::class);

    //get employee with id
    Route::get('/get-asset-employee/{id}', [EmployeeAssetController::class, 'getEmployeeDetails']);

    //get companies of partners
    Route::get('partner-companies', [CompanyController::class, 'partnerCompanies']);

    //employee interviews
    Route::apiResource('/employee-interview', EmployeeInterviewController::class);

    //Supplier location module
    Route::apiResource('supplier-locations', SupplierLocationController::class)->except(['index']);
    Route::get('/supplier-locations/supplier/{id}', [SupplierLocationController::class, 'getLocationBySupplier']);

    //Document Upload Routes
    Route::post('/user-document-contract', [UserProfileController::class, 'uploadDocumentAndContract']);
    Route::post('/delete-document-contract', [UserProfileController::class, 'deleteDocumentAndContract']);
    Route::get('/get-document-contract', [UserProfileController::class, 'listDocumentAndContract']);

    //Terms of payment
    Route::apiResource('terms-of-payment', TermsOfPaymentController::class);

    //User Department
    Route::apiResource('user/departments', UserDepartmentController::class);

    //User Location
    Route::apiResource('user/locations', UserLocationController::class);

    //User Team
    Route::apiResource('user/teams', UserTeamController::class);
    Route::get('get-teams-by-department/{department_id}', [UserTeamController::class, 'getTeamsByDepartment']);
    Route::get('get-member-by-team_ids', [UserTeamController::class, 'getMemberByTeams']);

    //upload profile picture
    Route::post('profile-picture/upload', [UserProfilePictureController::class, 'uploadProfilePicture']);
    Route::post('profile-picture/update', [UserProfilePictureController::class, 'updateProfilePicture']); //for customer portal
    Route::get('profile-picture/get/{user_id}', [UserProfilePictureController::class, 'getProfilePic']);

    //open posts
    Route::get('open-posts', [OpenPostsController::class, 'index']);
    Route::put('open-posts/{id}', [OpenPostsController::class, 'update']);
    Route::patch('open-posts-reminder/{id}', [OpenPostsController::class, 'updateOpenPosts']);
    //Global Settings
    Route::get('global-settings', [GlobalSettingController::class, 'index']);
    Route::post('global-settings', [GlobalSettingController::class, 'save']);
    //Global Setting - Document assignment
    Route::get('global-settings/document-assignment', [GlobalSettingController::class, 'documentAssignmentList']);
    Route::post('global-settings/document-assignment', [GlobalSettingController::class, 'documentAssignmentSave']);
    //Global Setting - Mail Template assignment
    Route::get('global-settings/mail-template-assignment', [GlobalSettingController::class, 'mailTemplateAssignmentList']);
    Route::post('global-settings/mail-template-assignment', [GlobalSettingController::class, 'mailTemplateAssignmentSave']);
    //Global Setting - Elo configuration
    Route::get('global-settings/elo-configuration', [GlobalSettingController::class, 'eloConfigurationList']);
    Route::post('global-settings/elo-configuration', [GlobalSettingController::class, 'eloConfigurationSave']);
    Route::get('/global-settings/get-template-by-name/{name}', [GlobalSettingController::class, 'getTemplateByName']);
    Route::post('/global-settings/elo-api-request', [GlobalSettingController::class, 'sendEloAPIRequest']);
    Route::get('/global-settings/default-cover-letter-text', [GlobalSettingController::class, 'coverLetterText']);
    Route::post('/global-settings/default-cover-letter-text-request', [GlobalSettingController::class, 'sendCoverLetterTextRequest']);
    Route::get('/global-settings/partner-management/discount', [GlobalSettingController::class, 'getPartnerDiscount']);
    Route::get('/partner-management/price-list', [PriceListController::class, 'priceList']);
    Route::post('/global-settings/partner-management/discount', [GlobalSettingController::class, 'storePartnerDiscount']);
    Route::get('/global-settings/default-offer-confirmation-cover-letter-text', [GlobalSettingController::class, 'offerConfirmationCoverLetterText']);
    Route::post('/global-settings/default-offer-confirmation-cover-letter-text-request', [GlobalSettingController::class, 'sendOfferConfirmationCoverLetterTextRequest']);
    Route::post('/global-settings/cip-configuration', [GlobalSettingController::class, 'cipConfigurationSave']);
    Route::get('/global-settings/cip-configuration', [GlobalSettingController::class, 'cipConfigurationList']);

    //profile
    Route::get('profile/user/{id}', [UserProfileController::class, 'profile']);

    //Host Controller
    Route::apiResource('hosts', SystemHostController::class);

     //Elo Business Solution Controller
     Route::apiResource('elo_business_solutions', EloBusinessSolutionController::class);
     Route::get('/elo_business_solution/list', [EloBusinessSolutionController::class, 'eloBusinessSolutionsList']);


    //my-tasks
    Route::get('my-tasks', [MyTaskController::class, 'index']);
    Route::get('my-tasks/{id}', [MyTaskController::class, 'show']);
    Route::post('my-tasks', [MyTaskController::class, 'store']);
    Route::patch('my-tasks/{id}', [MyTaskController::class, 'update']);
    Route::delete('my-tasks/{id}', [MyTaskController::class, 'destroy']);
    Route::post('my-task/update', [MyTaskController::class, 'updateStatus']);
    Route::post('my-task/resubmit', [MyTaskController::class, 'resubmit']);
    //task-boards
    Route::apiResource('task-boards', TaskBoardController::class);

    Route::post('board-users/{id}/edit', [TaskBoardController::class, 'editShareableUsers']);

    Route::apiResource('task-status', TaskStatusController::class);
    Route::post('/order-status', [TaskStatusController::class, 'orderStatus']);

    //task comments
    Route::apiResource('my-task/comments', TaskCommentController::class);

    Route::post('task/latest-comment', [TaskCommentController::class, 'showLatest']);

    //initialize task board
    Route::get('/board-initialize', TaskBoardInitialization::class);

    //get my tasks
    Route::get('/get-task-boards', [TaskBoardController::class, 'getTaskBoard']);

    //transtion-statuses
    Route::post('/task-transition', [TaskStatusController::class, 'taskTransitions']);
    Route::post('/remove-task-transition', [TaskStatusController::class, 'removeTaskTransitions']);
    Route::get('/get-transitions/{id}', [TaskStatusController::class, 'listTaskTranitions']);



    //task actions
    Route::post('/return-task', [MyTaskController::class, 'returnTask']);
    Route::post('/hand-over', [MyTaskController::class, 'handOver']);
    Route::post('/assume-task', [MyTaskController::class, 'assumeTask']);

    //vertical order
    Route::post('/order-task', [MyTaskController::class, 'orderTask']);

    // image upload
    Route::post('/image-upload', [MyTaskController::class, 'imageUpload']);


    //Performance Record Controller
    Route::post('store-performance-records', [PerformanceRecordController::class, 'store']);
    Route::delete('performance-records/{id}', [PerformanceRecordController::class, 'destroy']);
    Route::get('performance-records', [PerformanceRecordController::class, 'index']);
    Route::post('store-performance-records/manual', [PerformanceRecordController::class, 'storeManualPerformanceRecord']);
    Route::get('performance-records/show', [PerformanceRecordController::class, 'show']);
    Route::put('performance-records/{id}', [PerformanceRecordController::class, 'update']);
    Route::put('performance-record-entries/update/{id}', [PerformanceRecordController::class, 'editEntries']);
    Route::post('performance-record-entries/store/{id}', [PerformanceRecordController::class, 'addEntries']);
    Route::post('performance-record-entries/change-order/{id}', [PerformanceRecordController::class, 'changeOrder']);
    Route::post('import-performance-records', [PerformanceRecordController::class, 'importManualPerformanceRecords']);
    Route::post('performance-records/create-multiple', [PerformanceRecordController::class, 'createMultipleInvoices']);
    Route::get('/invoices/download/csv', [InvoiceController::class, 'downloadCSV']);
    Route::get('/invoices/download/latest-csv', [InvoiceController::class, 'downloadLatestCSV']);
    Route::get('/invoices/document-generation/{id}', [InvoiceController::class, 'documentGeneration']);
    Route::get('/performance-record/download-csv/{id}', [PerformanceRecordController::class, 'createPerformanceRecordCsv']);

    //Time checking
    Route::apiResource('time-checking', TimeCheckingController::class)->only(['index', 'update']);

    // Database Cloud Controller
    Route::apiResource('database-cloud', DatabaseCloudController::class);

    // Distributed Filesystem Controller
    Route::apiResource('distributed-filesystem', DistributedFilesystemController::class);

    // Licensescompany
    Route::get('licenses/license-statistics/{company}', [LicenseController::class, 'licenseStatistics']);
    Route::apiResource('licenses', LicenseController::class);

    //Travel Expenses
    Route::get('/travel-expenses/approve', [TravelExpenseController::class, 'getExpenseApprovalList']);
    Route::apiResource('/travel-expenses/request-types', TravelExpenseRequestTypeController::class);
    Route::apiResource('/travel-expenses/invoice-types', TravelExpenseInvoiceTypeController::class);
    Route::get('travel-expenses/send-for-approval/{id}', [TravelExpenseController::class, 'sendForApproval']);
    Route::apiResource('/travel-expenses', TravelExpenseController::class);
    Route::post('/travel-expense/bill-report/{id}', [TravelExpenseReportBillController::class, 'update']);
    Route::apiResource('/travel-expense/bill-report', TravelExpenseReportBillController::class)->only(['index', 'store', 'destroy']);
    Route::post('/travel-expenses/set-approval-status', [TravelExpenseController::class, 'setApprovalStatus']);
    Route::apiResource('/travel-expense/transportation-report', TravelExpenseReportTransportationController::class)
        ->only(['index', 'store', 'destroy']);
    Route::put('/travel-expense/transportation-report', [TravelExpenseReportTransportationController::class, 'update']);
    Route::get('/travel-expense/day-report', [TravelExpenseReportDayController::class, 'index']);
    Route::put('/travel-expense/day-report', [TravelExpenseReportDayController::class, 'update']);

    //Workshop Tempates
    Route::post('/workshop-templates/export-file', [WorkshopTemplateController::class, 'exportFile']);
    Route::post('/workshop-templates/upload-files', [WorkshopTemplateController::class, 'uploadFiles']);
    Route::delete('/workshop-templates/delete-file/{id}', [WorkshopTemplateController::class, 'deleteFile']);
    Route::apiResource('/workshop-templates', WorkshopTemplateController::class);
    Route::prefix('workshop-templates')->group(function () {
        Route::apiResource('/cards', WorkshopTemplateCardController::class);
        Route::apiResource('/rows', WorkshopTemplateRowController::class);
        Route::apiResource('/columns', WorkshopTemplateColumnController::class);
        Route::apiResource('/widgets', WorkshopTemplateWidgetController::class);
    });

    //Customer portal news
    Route::apiResource('/customer-portal-news', CustomerPortalNewsController::class);

    //API keys
    Route::apiResource('/api-keys', APIKeysController::class);

    //Rules
    Route::get('/rules', [RulesController::class, 'index']);
    Route::get('/rules/{id}', [RulesController::class, 'show']);

    //Licenses
    Route::get('/licensing/licenses', [LicensesController::class, 'index']);
    Route::get('/licensing/licenses/{id}', [LicensesController::class, 'show']);

    //Skills module
    Route::apiResource('skills', SkillController::class);

    //Skill Group module
    Route::apiResource('skill-group', SkillGroupController::class);

    //Skill Matrix
    Route::apiResource('skill-matrix', SkillMatrixController::class);
    Route::get('/matrix/{id}', [SkillMatrixController::class, 'matrix']);

    //Skill Level Global Setting
    Route::apiResource('skill-level', SkillLevelController::class);

    //Form Of Contract
    Route::apiResource('/form-of-contract', FormOfContractController::class);

    //Job Level
    Route::apiResource('/job-level', JobLevelController::class);

    //Jobs
    Route::apiResource('/jobs', JobController::class);

    //Affected Groups
    Route::apiResource('/affected-groups', AffectedGroupsController::class);

    //Continuous Improvement Process
    Route::get('/continuous-improvement-processes/get-approval-listing', [ContinuousImprovementProcessController::class, 'getApprovalListing']);
    Route::post('/continuous-improvement-processes/set-approval-status', [ContinuousImprovementProcessController::class, 'setApprovalStatus']);
    Route::apiResource('/continuous-improvement-processes', ContinuousImprovementProcessController::class);

    //Project Statuses
    Route::apiResource('/project-statuses', ProjectStatusController::class);

    //Protocol Types
    Route::apiResource('/protocol-types', ProtocolTypeController::class);

    //Project Protocols
    Route::apiResource('/project-protocols', ProjectProtocolController::class);

    //Project Protocol Desctiption Entries
    Route::get('/project-protocols/get-entries-by-protocol/{id}', [ProjectProtocolDescriptionEntryController::class, 'getEntriesByProjectProtocol']);
    Route::apiResource('/project-protocols/description-entries', ProjectProtocolDescriptionEntryController::class);

    //Highest School Degrees
    Route::apiResource('/highest-school-degrees', HighestSchoolDegreeController::class);

    //Highest Education Levels
    Route::apiResource('/highest-education-levels', HighestEducationLevelController::class);

    //Application Management Services
    Route::get('/application-management-services/get-by-customer-and-attachment/{customerId}', [ApplicationManagementServiceController::class, 'getByCustomerAndAttachment']);
    Route::get('/application-management-services/get-by-customer/{customerId}', [ApplicationManagementServiceController::class, 'getByCustomerId']);
    Route::apiResource('/application-management-services', ApplicationManagementServiceController::class);
    Route::get('/application-management-show/{id}', [ApplicationManagementServiceController::class, 'showRevamp']);
    Route::get('/get-tickets/{id}', [ApplicationManagementServiceController::class, 'getAmsTickets']);
    Route::post('/ams/time-tracker', TimeTrackerAmsController::class);
    //Mail Template Assignment
    Route::get('/mail-template-assignments', [MailTemplateAssignmentController::class, 'index']);
    Route::post('/mail-template-assignments', [MailTemplateAssignmentController::class, 'saveMailTemplateAssignment']);
    Route::get('/mail-template-assignments/get-by-module', [MailTemplateAssignmentController::class, 'mailTemplateByModule']);
    Route::get('/application-management-services/support/dashboard', [ApplicationManagementServiceController::class, 'getAmsSupportDashboard']);


    //Sale Dashboard
    Route::get('/sale-dashboard/offer-statistics', [SalesDashboardController::class, 'offersStatistics']);
    Route::get('/sale-dashboard/offer-data', [SalesDashboardController::class, 'offersData']);
    Route::get('/sale-dashboard/product-proportion-statistics', [SalesDashboardController::class, 'productProportionsStatistics']);
    Route::get('/sale-dashboard/lead-channels-statistics', [SalesDashboardController::class, 'leadChannelsStatistics']);

    //Consulting Teams
    Route::get('/consulting-teams', [ConsultingTeamController::class, 'index']);
    Route::post('/consulting-teams', [ConsultingTeamController::class, 'saveData']);

    //Contract Types
    Route::get('/contract-types/attachments/{id}', [ContractTypeController::class, 'attachmentsByContractType']);
    Route::apiResource('/contract-types', ContractTypeController::class);

    //Attachments
    Route::apiResource('/attachments', AttachmentController::class);

    //Contracts
    Route::get('/outbounded-contracts/create-invoice/{id}', [OutboundedContractController::class, 'createInvoice']);
    Route::get('/outbounded-contracts/create-invoice-template/{id}', [OutboundedContractController::class, 'createInvoiceTemplate']);
    Route::get('/contracts/company', [OutboundedContractController::class, 'showByCompany']);

    Route::apiResource('/outbounded-contracts', OutboundedContractController::class);

    //module history
    Route::get('/module-history/{id}', [GlobalSettingController::class, 'moduleHistory']);

    //Personal Modification Process Managers
    Route::get('/personal-modification-process-managers', [PersonalModificationProcessManagerController::class, 'index']);
    Route::post('/personal-modification-process-managers', [PersonalModificationProcessManagerController::class, 'saveData']);

    //Personal Modification Process Checklists
    Route::get('/personal-modification-process-checklists', [PersonalModificationProcessChecklistController::class, 'index']);
    Route::get('/personal-modification-process-checklists/{id}', [PersonalModificationProcessChecklistController::class, 'show']);
    Route::post('/personal-modification-process-checklists', [PersonalModificationProcessChecklistController::class, 'store']);
    Route::put('/personal-modification-process-checklists/{id}', [PersonalModificationProcessChecklistController::class, 'update']);
    Route::delete('/personal-modification-process-checklists/{id}', [PersonalModificationProcessChecklistController::class, 'destroy']);

    //Modify my data
    Route::get('/modify-my-data/requests', [ModifyMyDataController::class, 'requestForManager']);
    Route::get('/modify-my-data/requests/{id}', [ModifyMyDataController::class, 'showRequest']);
    Route::post('/modify-my-data/update-status', [ModifyMyDataController::class, 'updateStatus']);
    Route::apiResource('/modify-my-data', ModifyMyDataController::class);

    //Personal Requests
    Route::apiResource('/personal-requests', PersonalRequestController::class);

    //Personal Request Approvers
    Route::get('/personal-request-approvers', [PersonalRequestApproverController::class, 'index']);
    Route::post('/personal-request-approvers', [PersonalRequestApproverController::class, 'saveData']);

    //Personal Requirements
    Route::get('/personal-requirements', [PersonalRequirementController::class, 'index']);
    Route::post('/personal-requirements', [PersonalRequirementController::class, 'setStatus']);

    //Permissions
    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::get('/roles', [RoleController::class, 'index']);

    //SLA Levels
    Route::apiResource('/sla-levels', SlaLevelController::class);

    //Offer Requests
    Route::get('/offer-requests/create-offer/{id}', [OfferRequestController::class, 'createOffer']);
    Route::post('/offer-requests/{id}', [OfferRequestController::class, 'update']);
    Route::apiResource('/offer-requests', OfferRequestController::class);

    //Creditor Invoices
    Route::apiResource('/creditor-invoices', CreditorInvoiceController::class);

    //order confirmation with id
    Route::get('/order-confirmation-with-id/{company_id}', ProjectOrderConfirmationController::class);

    //performance record
    Route::post('performance-record-elo-request', [PerformanceRecordController::class, 'showEloRequestForPerformanceRecord']);

    //Global setting Review Reports

    Route::apiResource('/review-report', ReviewReportController::class);

    //Product Store
    Route::apiResource('/product-store', ProductStoreController::class);

    //Product Store Review
    Route::get('product-store-review/helpful/{id}/{feedback?}', [ProductStoreReviewController::class, 'storeHelpfulFeedback']);
    Route::post('product-store-review/report', [ProductStoreReviewController::class, 'storeReportReview']);
    Route::apiResource('/product-store-review', ProductStoreReviewController::class);

    //Product Store Request
    Route::apiResource('/product-store-request', ProductStoreRequestController::class);

    //PlaningBoard
    Route::get('/planing-board', [PlaningBoardController::class, 'index']);
    Route::post('/planing-board', [PlaningBoardController::class, 'update']);

    //PM Dashboard Teams
    Route::get('/pm-dashboard-teams', [PMDashboardTeamsController::class, 'index']);
    Route::post('/pm-dashboard-teams', [PMDashboardTeamsController::class, 'saveData']);


    //Global notification list
    Route::apiResource('/global-notification', GlobalNotificationListController::class)->only(['index', 'store']);

    //storage locations
    Route::apiResource('/storage-locations', StorageLocationController::class);

    //asset employee list
    Route::apiResource('asset-employee-text', AssetEmployeeTextController::class);

    //asset delivery
    Route::apiResource('/asset-deliveries', AssetDeliveryController::class);

    //api to create vacation request

    Route::post('/create-legal-holidays', [EmployeeVacationController::class, 'createLegalHolidays']);


    //My Feed
    Route::prefix('my-feed')->group(function () {
        Route::get('stats', [MyFeedController::class, 'getStats']);
        Route::get('/own-stream', [MyFeedController::class, 'myOwnFeed']);
        Route::get('/get-feed/{id}', [MyFeedController::class, 'getFeed']);
        Route::get('/get-feed-comments/{id}', [MyFeedController::class, 'getComments']);
        Route::get('/tag-stream', [MyFeedController::class, 'tagFeed']);
        Route::get('/mention-stream', [MyFeedController::class, 'mentionFeed']);
        Route::get('/subscribed-stream', [MyFeedController::class, 'subscribedFeed']);
        Route::post('/module-stream', [MyFeedController::class, 'moduleFeed']);
        Route::post('/', [MyFeedController::class, 'store']);
        Route::put('/{id}', [MyFeedController::class, 'update']);
        Route::delete('/{id}', [MyFeedController::class, 'destroy']);
        Route::post('/add-vote', [MyFeedController::class, 'addVote']);
        Route::get('/{id}/comment', [MyFeedController::class, 'feedComment']);
        Route::post('/comment', [MyFeedController::class, 'addComment']);
        Route::put('/comment/{id}', [MyFeedController::class, 'updateComment']);
        Route::delete('/comment/{id}', [MyFeedController::class, 'deleteComment']);
        Route::post('/subscribe', [MyFeedController::class, 'subscribeFeed']);
        Route::post('/unsubscribe', [MyFeedController::class, 'unsubscribeFeed']);
    });


    //Partner management
    Route::prefix('partner')->group(function () {
        Route::apiResource('order', PartnerOrderController::class);
        Route::apiResource('customer', PartnerCustomerController::class);
        Route::apiResource('tickets', PartnerTicketController::class);
        Route::apiResource('ticket-comments', PartnerTicketCommentController::class)->except(['index']);
    });
});

Route::group(['middleware' => ['cors']], function () {
    //public routes for survey api
    Route::get('/public/products-with-quantity', [ProductController::class, 'productsWithQuantity']);
    Route::get('/public/surveys/{id}', [SurveyController::class, 'show']);
    Route::get('/public/survey-configuration', [SurveyConfigurationController::class, 'index']);
    Route::get('/public/survey-products/{id}', [SurveyController::class, 'getAllSurveyProducts']);
});

Route::get('test', function (){
    \Illuminate\Support\Facades\Artisan::call("changelog:listener");
});
