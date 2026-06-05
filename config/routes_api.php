<?php


// ===================== FOR ROUTES =====================
$var_routes = [];

// authentication
$var_routes[] = [
    'path' => 'authentication', 
    'controller' => 'Authentication', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'create_account', 
            'path' => '/create-account/{username}/{userTypeID}/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'login', 
            'path' => '/login/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'check_password', 
            'path' => '/check-password/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'change_password', 
            'path' => '/change-password/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'verify', 
            'path' => '/verify/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'user_details', 
            'path' => '/user-details/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'user_access', 
            'path' => '/user-access/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'user_notifications', 
            'path' => '/user-notifications/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'logout', 
            'path' => '/logout/', 
        ], 
    ], 
]; 

// profile-setup
$var_routes[] = [
    'path' => 'profile-setup', 
    'controller' => 'ProfileSetup', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_provinces', 
            'path' => '/get-provinces/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_cities', 
            'path' => '/get-cities/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_barangays', 
            'path' => '/get-barangays/{id}/', 
        ], 
    ], 
]; 

// home
$var_routes[] = [
    'path' => 'home', 
    'controller' => 'Home', 
    'paths' => [
        [ 
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_cards', 
            'path' => '/get-cards/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_calendar_events', 
            'path' => '/get-calendar-events/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_leave_applications', 
            'path' => '/get-leave-applications/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_travel_orders', 
            'path' => '/get-travel-orders/', 
        ], 
    ], 
];
// my-profile
$var_routes[] = [
    'path' => 'my-profile', 
    'controller' => 'MyProfile', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'signature_post', 
            'path' => '/signature/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'signature_upload', 
            'path' => '/signature-upload/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'signature_get', 
            'path' => '/signature/', 
        ], 
        // view
        [
            'method' => ['get'], 
            'function' => 'get_personal_information', 
            'path' => '/personal-information/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_family_background', 
            'path' => '/family-background/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_educational_background', 
            'path' => '/educational-background/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_civil_service_eligibilities', 
            'path' => '/civil-service-eligibilities/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_work_experiences', 
            'path' => '/work-experiences/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_training_programs', 
            'path' => '/training-programs/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_201_files', 
            'path' => '/201-files/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_employments', 
            'path' => '/employments/', 
        ],  


        // edit
        [
            'method' => ['get'], 
            'function' => 'put_page_personal_information', 
            'path' => '/page-put-personal-information/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_family_background', 
            'path' => '/page-put-family-background/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_educational_background', 
            'path' => '/page-put-educational-background/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_civil_service_eligibilities', 
            'path' => '/page-put-civil-service-eligibilities/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_work_experiences', 
            'path' => '/page-put-work-experiences/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_training_programs', 
            'path' => '/page-put-training-programs/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_personal_information', 
            'path' => '/personal-information/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_family_background', 
            'path' => '/family-background/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_educational_background', 
            'path' => '/educational-background/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_civil_service_eligibilities', 
            'path' => '/civil-service-eligibilities/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_work_experiences', 
            'path' => '/work-experiences/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_training_programs', 
            'path' => '/training-programs/{id}/', 
        ], 

        // changes
        [
            'method' => ['get'], 
            'function' => 'get_changes_personal_informations', 
            'path' => '/changes-personal-informations/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_personal_information', 
            'path' => '/changes-personal-information/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_family_backgrounds', 
            'path' => '/changes-family-backgrounds/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_family_background', 
            'path' => '/changes-family-background/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_educational_backgrounds', 
            'path' => '/changes-educational-backgrounds/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_educational_background', 
            'path' => '/changes-educational-background/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_civil_service_eligibilities', 
            'path' => '/changes-civil-service-eligibilities/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_civil_service_eligibility', 
            'path' => '/changes-civil-service-eligibility/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_work_experiences', 
            'path' => '/changes-work-experiences/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_work_experience', 
            'path' => '/changes-work-experience/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_training_programs', 
            'path' => '/changes-training-programs/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_training_program', 
            'path' => '/changes-training-program/{id}/', 
        ], 

        // print
        [
            'method' => ['get'], 
            'function' => 'print_pds_data', 
            'path' => '/print-pds-data/{id}/', 
        ],

        // others
        [
            'method' => ['get'], 
            'function' => 'get_provinces', 
            'path' => '/get-provinces/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_cities', 
            'path' => '/get-cities/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_barangays', 
            'path' => '/get-barangays/{id}/', 
        ], 
    ], 
]; 
// my-travel-requests
$var_routes[] = [
    'path' => 'my-travel-requests', 
    'controller' => 'MyTravelRequest', 
    'paths' => [
        [ 
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [ 
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [ 
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ], 
        [ 
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
    ], 
];
// my-leave-requests
$var_routes[] = [
    'path' => 'my-leave-requests', 
    'controller' => 'MyLeaveRequest', 
    'paths' => [
        [ 
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [ 
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [ 
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ], 
        [ 
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
    ], 
];
// my-attendances
$var_routes[] = [
    'path' => 'my-attendances', 
    'controller' => 'MyAttendance', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ],
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['delete'], 
            'function' => 'delete', 
            'path' => '/{id}/', 
        ], 
    ], 
]; 

// dashboard
$var_routes[] = [
    'path' => 'dashboard', 
    'controller' => 'Dashboard', 
    'paths' => [
        [ 
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_cards', 
            'path' => '/get-cards/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_genders', 
            'path' => '/get-genders/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_leave_applications', 
            'path' => '/get-leave-applications/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_travel_orders', 
            'path' => '/get-travel-orders/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_leave_days', 
            'path' => '/get-leave-days/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_travel_days', 
            'path' => '/get-travel-days/', 
        ], 
    ], 
]; 
// travel-requests
$var_routes[] = [
    'path' => 'travel-requests', 
    'controller' => 'TravelRequest', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_travel_report_page', 
            'path' => '/print-travel-report-page/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_travel_report_data', 
            'path' => '/print-travel-report-data/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'recommend', 
            'path' => '/{id}/recommend/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'check', 
            'path' => '/{id}/check/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'approve', 
            'path' => '/{id}/approve/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'disapprove', 
            'path' => '/{id}/disapprove/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_travel_order_data', 
            'path' => '/print-travel-order-data/{id}/', 
        ], 
    ], 
]; 
// leave-requests
$var_routes[] = [
    'path' => 'leave-requests', 
    'controller' => 'LeaveRequest', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'recommend', 
            'path' => '/{id}/recommend/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'check_page', 
            'path' => '/{id}/page-check/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'check', 
            'path' => '/{id}/check/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'approve', 
            'path' => '/{id}/approve/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'disapprove', 
            'path' => '/{id}/disapprove/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_leave_application_data', 
            'path' => '/print-leave-application-data/{id}/', 
        ], 
    ], 
]; 
// leave-credits
$var_routes[] = [
    'path' => 'leave-credits', 
    'controller' => 'LeaveCredit', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ],
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['delete'], 
            'function' => 'delete', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_leave_ledger_card_data', 
            'path' => '/print-leave-ledger-card-data/{id}', 
        ], 
    ], 
]; 
// biometric-logs
$var_routes[] = [
    'path' => 'biometric-logs', 
    'controller' => 'BiometricLog', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'get_logs', 
            'path' => '/get-logs/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ],
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['delete'], 
            'function' => 'delete', 
            'path' => '/{id}/', 
        ], 
    ], 
]; 
// attendances
$var_routes[] = [
    'path' => 'attendances', 
    'controller' => 'Attendance', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ],
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['delete'], 
            'function' => 'delete', 
            'path' => '/{id}/', 
        ], 
    ], 
]; 
// payroll-deductions
$var_routes[] = [
    'path' => 'payroll-deductions', 
    'controller' => 'PayrollDeduction', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page_deduction_details', 
            'path' => '/page-audit-deduction-details/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
    ], 
]; 
// payroll-deduction-types
$var_routes[] = [
    'path' => 'payroll-deduction-types', 
    'controller' => 'PayrollDeductionType', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
    ], 
]; 
// tax-brackets
$var_routes[] = [
    'path' => 'tax-brackets', 
    'controller' => 'TaxBracket', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['delete'], 
            'function' => 'delete', 
            'path' => '/{id}/', 
        ], 
    ], 
]; 
// employments
$var_routes[] = [
    'path' => 'employments', 
    'controller' => 'Employment', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_edit', 
            'path' => '/page-put-edit/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_edit', 
            'path' => '/edit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_reassign', 
            'path' => '/page-put-reassign/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_reassign', 
            'path' => '/reassign/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_demote', 
            'path' => '/page-put-demote/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_demote', 
            'path' => '/demote/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_promote', 
            'path' => '/page-put-promote/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_promote', 
            'path' => '/promote/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_terminate', 
            'path' => '/page-put-terminate/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_terminate', 
            'path' => '/terminate/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_rehire', 
            'path' => '/page-put-rehire/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_rehire', 
            'path' => '/rehire/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_new', 
            'path' => '/page-put-new/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_new', 
            'path' => '/new/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_dismiss', 
            'path' => '/dismiss/{id}/', 
        ], 
    ], 
]; 
// employees
$var_routes[] = [
    'path' => 'employees', 
    'controller' => 'Employee', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'post_page_201_file', 
            'path' => '/page-post-201-file/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'post_201_file', 
            'path' => '/201-file/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 

        // get
        [
            'method' => ['get'], 
            'function' => 'get_personal_information', 
            'path' => '/personal-information/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_family_background', 
            'path' => '/family-background/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_educational_background', 
            'path' => '/educational-background/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_civil_service_eligibilities', 
            'path' => '/civil-service-eligibilities/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_work_experiences', 
            'path' => '/work-experiences/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_training_programs', 
            'path' => '/training-programs/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_201_files', 
            'path' => '/201-files/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_employments', 
            'path' => '/employments/{id}/', 
        ], 

        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 

        // edit
        [
            'method' => ['get'], 
            'function' => 'put_page_personal_information', 
            'path' => '/page-put-personal-information/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_family_background', 
            'path' => '/page-put-family-background/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_educational_background', 
            'path' => '/page-put-educational-background/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_civil_service_eligibilities', 
            'path' => '/page-put-civil-service-eligibilities/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_work_experiences', 
            'path' => '/page-put-work-experiences/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page_training_programs', 
            'path' => '/page-put-training-programs/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_personal_information', 
            'path' => '/personal-information/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_family_background', 
            'path' => '/family-background/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_educational_background', 
            'path' => '/educational-background/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_civil_service_eligibilities', 
            'path' => '/civil-service-eligibilities/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_work_experiences', 
            'path' => '/work-experiences/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_training_programs', 
            'path' => '/training-programs/{id}/', 
        ], 

        // changes
        [
            'method' => ['get'], 
            'function' => 'get_changes_personal_informations', 
            'path' => '/changes-personal-informations/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_personal_information', 
            'path' => '/changes-personal-information/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_changes_personal_information', 
            'path' => '/changes-personal-information/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_family_backgrounds', 
            'path' => '/changes-family-backgrounds/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_family_background', 
            'path' => '/changes-family-background/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_changes_family_background', 
            'path' => '/changes-family-background/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_educational_backgrounds', 
            'path' => '/changes-educational-backgrounds/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_educational_background', 
            'path' => '/changes-educational-background/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_changes_educational_background', 
            'path' => '/changes-educational-background/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_civil_service_eligibilities', 
            'path' => '/changes-civil-service-eligibilities/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_civil_service_eligibility', 
            'path' => '/changes-civil-service-eligibility/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_changes_civil_service_eligibility', 
            'path' => '/changes-civil-service-eligibility/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_work_experiences', 
            'path' => '/changes-work-experiences/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_work_experience', 
            'path' => '/changes-work-experience/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_changes_work_experience', 
            'path' => '/changes-work-experience/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_training_programs', 
            'path' => '/changes-training-programs/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_changes_training_program', 
            'path' => '/changes-training-program/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put_changes_training_program', 
            'path' => '/changes-training-program/{id}/', 
        ], 
        
        // print
        [
            'method' => ['get'], 
            'function' => 'print_pds_data', 
            'path' => '/print-pds-data/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_service_record_data', 
            'path' => '/print-service-record-data/{id}/', 
        ], 
        
        // delete
        [
            'method' => ['delete'], 
            'function' => 'delete_201_file', 
            'path' => '/201-file/{id}/', 
        ], 

        // others
        [
            'method' => ['get'], 
            'function' => 'get_provinces', 
            'path' => '/get-provinces/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_cities', 
            'path' => '/get-cities/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get_barangays', 
            'path' => '/get-barangays/{id}/', 
        ], 
    ], 
]; 
// job-positions
$var_routes[] = [
    'path' => 'job-positions', 
    'controller' => 'JobPosition', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['delete'], 
            'function' => 'delete', 
            'path' => '/{id}/', 
        ], 
    ], 
]; 
// offices
$var_routes[] = [
    'path' => 'offices', 
    'controller' => 'Office', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['delete'], 
            'function' => 'delete', 
            'path' => '/{id}/', 
        ], 
    ], 
]; 
// provinces
$var_routes[] = [
    'path' => 'provinces', 
    'controller' => 'Province', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
    ], 
]; 
// municipalities
$var_routes[] = [
    'path' => 'municipalities', 
    'controller' => 'Municipality', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
    ], 
]; 
// barangays
$var_routes[] = [
    'path' => 'barangays', 
    'controller' => 'Barangay', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
    ], 
]; 

// users
$var_routes[] = [
    'path' => 'users', 
    'controller' => 'User', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page_user_access', 
            'path' => '/page-audit-user-access/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page_starting_information', 
            'path' => '/page-audit-starting-information/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['delete'], 
            'function' => 'delete', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'change_password', 
            'path' => '/change-password/{id}/', 
        ], 
    ], 
]; 
// user-types
$var_routes[] = [
    'path' => 'user-types', 
    'controller' => 'UserType', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'post_page', 
            'path' => '/page-post/', 
        ], 
        [
            'method' => ['post'], 
            'function' => 'post', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page_user_type_access', 
            'path' => '/page-audit-user-type-access/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['delete'], 
            'function' => 'delete', 
            'path' => '/{id}/', 
        ], 
    ], 
]; 
// audit-logs
$var_routes[] = [
    'path' => 'audit-logs', 
    'controller' => 'AuditLog', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items_main', 
            'path' => '/items-main/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items_main', 
            'path' => '/print-items-main/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'items_details', 
            'path' => '/items-details/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items_details', 
            'path' => '/print-items-details/', 
        ], 
    ], 
]; 
// authentication-logs
$var_routes[] = [
    'path' => 'authentication-logs', 
    'controller' => 'AuthenticationLog', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
    ], 
]; 
// configurations
$var_routes[] = [
    'path' => 'configurations', 
    'controller' => 'Configuration', 
    'paths' => [
        [
            'method' => ['get'], 
            'function' => 'items', 
            'path' => '/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'print_items', 
            'path' => '/print-items/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'get', 
            'path' => '/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'audit_page', 
            'path' => '/page-audit/{id}/', 
        ], 
        [
            'method' => ['get'], 
            'function' => 'put_page', 
            'path' => '/page-put/{id}/', 
        ], 
        [
            'method' => ['put'], 
            'function' => 'put', 
            'path' => '/{id}/', 
        ], 
    ], 
]; 

// ===================== END =====================

return [
    'var_routes' => $var_routes, 
];