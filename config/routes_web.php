<?php


// ===================== FOR ROUTES =====================
$var_routes = [];

// profile
$var_routes[] = [
    'title' => 'profile', 
    'name' => 'Profile', 
    'pages' => [
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
    ], 
];
// document-checker
$var_routes[] = [
    'title' => 'document-checker', 
    'name' => 'DocumentChecker', 
    'pages' => [
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
    ], 
];

// profile-setup
$var_routes[] = [
    'title' => 'profile-setup', 
    'name' => 'ProfileSetup', 
    'pages' => [
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
    ], 
];
// my-profile
$var_routes[] = [
    'title' => 'my-profile', 
    'name' => 'MyProfile', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit-personal-information', 
            'view' => 'edit_personal_information', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit-family-background', 
            'view' => 'edit_family_background', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit-educational-background', 
            'view' => 'edit_educational_background', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit-civil-service-eligibilities', 
            'view' => 'edit_civil_service_eligibilities', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit-work-experiences', 
            'view' => 'edit_work_experiences', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit-training-programs', 
            'view' => 'edit_training_programs', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'changes-personal-information', 
            'view' => 'changes_personal_information', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'changes-family-background', 
            'view' => 'changes_family_background', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'changes-educational-background', 
            'view' => 'changes_educational_background', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'changes-civil-service-eligibilities', 
            'view' => 'changes_civil_service_eligibilities', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'changes-work-experiences', 
            'view' => 'changes_work_experiences', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'changes-training-programs', 
            'view' => 'changes_training_programs', 
            'method' => ['get'], 
        ], 
        // prints
        [
            'name' => 'print-pds', 
            'view' => 'print_pds', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-leave-application', 
            'view' => 'print_leave_application', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-travel-report', 
            'view' => 'print_travel_report', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-travel-order', 
            'view' => 'print_travel_order', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-dtr', 
            'view' => 'print_dtr', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-payslip', 
            'view' => 'print_payslip', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-general-payroll', 
            'view' => 'print_general_payroll', 
            'method' => ['get'], 
        ], 
    ], 
];
// my-attendances
$var_routes[] = [
    'title' => 'my-attendances', 
    'name' => 'MyAttendance', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
    ], 
]; 

// home
$var_routes[] = [
    'title' => 'home', 
    'name' => 'Home', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
    ], 
];
// my-leave-requests
$var_routes[] = [
    'title' => 'my-leave-requests', 
    'name' => 'MyLeaveRequest', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
    ], 
];
// my-travel-requests
$var_routes[] = [
    'title' => 'my-travel-requests', 
    'name' => 'MyTravelRequest', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
    ], 
];

// dashboard
$var_routes[] = [
    'title' => 'dashboard', 
    'name' => 'Dashboard', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
    ], 
];
// travel-requests
$var_routes[] = [
    'title' => 'travel-requests', 
    'name' => 'TravelRequest', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-travel-order', 
            'view' => 'print_travel_order', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-travel-report', 
            'view' => 'print_travel_report', 
            'method' => ['get'], 
        ], 
    ], 
];
// leave-requests
$var_routes[] = [
    'title' => 'leave-requests', 
    'name' => 'LeaveRequest', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-leave-application', 
            'view' => 'print_leave_application', 
            'method' => ['get'], 
        ], 
    ], 
];
// leave-credits
$var_routes[] = [
    'title' => 'leave-credits', 
    'name' => 'LeaveCredit', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-leave-ledger-card', 
            'view' => 'print_leave_ledger_card', 
            'method' => ['get'], 
        ], 
    ], 
];
// biometric-logs
$var_routes[] = [
    'title' => 'biometric-logs', 
    'name' => 'BiometricLog', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];
// attendances
$var_routes[] = [
    'title' => 'attendances', 
    'name' => 'Attendance', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];
// payrolls
$var_routes[] = [
    'title' => 'payrolls', 
    'name' => 'Payroll', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];
// payroll-deductions
$var_routes[] = [
    'title' => 'payroll-deductions', 
    'name' => 'PayrollDeduction', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit2', 
            'view' => 'audit2', 
            'method' => ['get'], 
        ], 
    ], 
];
// payroll-deduction-types
$var_routes[] = [
    'title' => 'payroll-deduction-types', 
    'name' => 'PayrollDeductionType', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];
// tax-brackets
$var_routes[] = [
    'title' => 'tax-brackets', 
    'name' => 'TaxBracket', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];
// employments
$var_routes[] = [
    'title' => 'employments', 
    'name' => 'Employment', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'new', 
            'view' => 'new', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'reassign', 
            'view' => 'reassign', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'demote', 
            'view' => 'demote', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'promote', 
            'view' => 'promote', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'terminate', 
            'view' => 'terminate', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'rehire', 
            'view' => 'rehire', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];
// employees
$var_routes[] = [
    'title' => 'employees', 
    'name' => 'Employee', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit-personal-information', 
            'view' => 'edit_personal_information', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit-family-background', 
            'view' => 'edit_family_background', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit-educational-background', 
            'view' => 'edit_educational_background', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit-civil-service-eligibilities', 
            'view' => 'edit_civil_service_eligibilities', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit-work-experiences', 
            'view' => 'edit_work_experiences', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit-training-programs', 
            'view' => 'edit_training_programs', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'changes-personal-information', 
            'view' => 'changes_personal_information', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'changes-family-background', 
            'view' => 'changes_family_background', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'changes-educational-background', 
            'view' => 'changes_educational_background', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'changes-civil-service-eligibilities', 
            'view' => 'changes_civil_service_eligibilities', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'changes-work-experiences', 
            'view' => 'changes_work_experiences', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'changes-training-programs', 
            'view' => 'changes_training_programs', 
            'method' => ['get'], 
        ], 
        // prints
        [
            'name' => 'print-pds', 
            'view' => 'print_pds', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-service-record', 
            'view' => 'print_service_record', 
            'method' => ['get'], 
        ], 
    ], 
];
// job-positions
$var_routes[] = [
    'title' => 'job-positions', 
    'name' => 'JobPosition', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];
// offices
$var_routes[] = [
    'title' => 'offices', 
    'name' => 'Office', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];
// provinces
$var_routes[] = [
    'title' => 'provinces', 
    'name' => 'Province', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];
// municipalities
$var_routes[] = [
    'title' => 'municipalities', 
    'name' => 'Municipality', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];
// barangays
$var_routes[] = [
    'title' => 'barangays', 
    'name' => 'Barangay', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];

// users
$var_routes[] = [
    'title' => 'users', 
    'name' => 'User', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit2', 
            'view' => 'audit2', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit3', 
            'view' => 'audit3', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];
// user-types
$var_routes[] = [
    'title' => 'user-types', 
    'name' => 'UserType', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit2', 
            'view' => 'audit2', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];
// audit-logs
$var_routes[] = [
    'title' => 'audit-logs', 
    'name' => 'AuditLog', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list-main', 
            'view' => 'print_list_main', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list-details', 
            'view' => 'print_list_details', 
            'method' => ['get'], 
        ], 
    ], 
];
// authentication-logs
$var_routes[] = [
    'title' => 'authentication-logs', 
    'name' => 'AuthenticationLog', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];
// configurations
$var_routes[] = [
    'title' => 'configurations', 
    'name' => 'Configuration', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'add', 
            'view' => 'add', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'view', 
            'view' => 'view', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'audit', 
            'view' => 'audit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'edit', 
            'view' => 'edit', 
            'method' => ['get'], 
        ], 
        [
            'name' => 'print-list', 
            'view' => 'print_list', 
            'method' => ['get'], 
        ], 
    ], 
];

// login
$var_routes[] = [
    'title' => 'login', 
    'name' => 'Login', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
    ], 
];
// profile-setup
$var_routes[] = [
    'title' => 'profile-setup', 
    'name' => 'ProfileSetup', 
    'pages' => [
        [
            'name' => '', 
            'view' => 'index', 
            'method' => ['get'], 
        ], 
    ], 
];
// logs
$var_routes[] = [
    'title' => 'logs', 
    'name' => 'Log', 
    'pages' => [
        [
            'name' => 'entry', 
            'view' => 'entry', 
            'method' => ['get'], 
        ], 
    ], 
];

// ===================== END =====================

return [
    'var_routes' => $var_routes, 
];