# Trento HRIS

## Revisions 
- Signature hide draw and add upload image with crop feature ✔
- Printable Travel ✔
    - Report ✔
    - Order ✔
- Printable Leave ✔
    - Application ✔
    - Credits Ledger ✔
- Leave Application Filing: 
    - Hide commutation on UI but default Requested ✔
    - Sick leave 
        - minus to SL credits
        - if not enough minus to vacation with zero remainings
        - if still not enough add to days without pay 
    - CTO
    - Monetization:
        - No inclusive dates 
        - Remaining VL Credits must not be less than or equal 5
        - Show about that can be gain while user is changing number of days 
        - Add VL and SL credits then divide 2, ((VL+SL)/2), to get the max allowed credits to monetized
        - Subtract to VL first but remain atleast 5 credits, if still not enough subtract from SL, but the total credits to monetize must not be greater than the 50% of total earnings of both VL and SL 
    Terminal:
        - Can choose date on when to be terminated 
        - credits must be zero 
- Leave statuses 
    - Withdrawing 
    - Withdrawn
- Travel Status 
    - Withdrawing 
    - Withdrawn 
- Leave ledger monthly earnings release (last day of the month) ✔
- Leave credits calculation for new employees ✔
- Disable two active Mayor and HR head in user_employments ✔
- Printable Service Record ✔
- Biometric ID 
- Shiftings flexitime and regular 
    - Regular Logs (7am-8am, 12pm-12:19pm, 12:20pm-1pm, 5pm+) 
    - Regular Tardi (8:01am, 11:59am, 1:01pm, 4:59pm) 
- DTR 
    Current Process: 
        - 1st day in a month: upload previous zkteco logs 
        - 2nd day in a month: distribute printouts
        - 6th day in a month: release to employees 
        - 8th day in a month: release sa calendar

--- 

## 
User: 
✓ Authentication 
✓ Profile Setup
- My Profile 
    - Index Tabs
        ✓ Personal Information 
        ✓ Family Background 
        ✓ Educational Background 
        ✓ Civil Service Eligibilities 
        ✓ Work Experiences 
        ✓ Training Programs 
        ✓ 201 Files 
        ✓ Employments 
    - Print 
        ✓ PDS 
        - Service Record 
        - PDS 
- Home 
- My Leave Requests 
    - Index 
    - Add
    - View 
- My Travel Requests 
    - Index 
    - Add
    - View 
- My Attendances 
    - Index 
    - Print DTR
- My Payslips 
    - Index
        - View 
            - Print Payslip

Administrator: 
- Dashboard 
- Travel Orders 
    - Index
    - Print Travel Report
    - View 
        - Recommend 
        - Check 
        - Approve 
        - Deny 
        - Print Travel Order 
- Leave Applications 
    - Index
    - View 
        - Recommend 
        - Check 
        - Approve 
        - Deny 
        - Print Leave Application
- Leave Credits 
- Biometric Logs 
- Attendances 
- Payrolls 
✓ Tax Brackets  
    ✓ Index 
    ✓ Print List  
    ✓ View 
    ✓ View 
    ✓ Edit 
- Employments 
    ✓ Index 
    - Print List
    ✓ Add  
    ✓ View   
        ✓ Edit    
        ✓ Promote     
        ✓ Demote      
        ✓ Re-assign       
        ✓ Terminate        
        ✓ Rehire        
- Employees 
    ✓ Index
    - Print List
    - View
        ✓ Personal Information 
        ✓ Family Background 
        ✓ Educational Background 
        ✓ Civil Service Eligibilities 
        ✓ Work Experiences 
        ✓ Training Programs 
        ✓ Employments 
        ✓ 201 Files 
        - Print 
            - PDS 
            - Service Record 
            - DTR  
    ✓ Edit
        ✓ Personal Information 
        ✓ Family Background 
        ✓ Educational Background 
        ✓ Civil Service Eligibilities 
        ✓ Work Experiences 
        ✓ Training Programs 
    ✓ Changes
        ✓ Personal Information 
        ✓ Family Background 
        ✓ Educational Background 
        ✓ Civil Service Eligibilities 
        ✓ Work Experiences 
        ✓ Training Programs 
✓ Job Positions 
✓ Offices 
✓ Addresses 
    ✓ Provinces 
    ✓ Municipalities 
    ✓ Barangays 

System:
✓ Users
    ✓ Index 
    ✓ Print List 
    ✓ Add 
    ✓ View  
        ✓ Basic 
        ✓ Basic Audit Logs 
        ✓ Accesses 
        ✓ Accesses Audit Logs 
        ✓ Starting Info 
        ✓ Starting Info Audit Logs 
        ✓ Edit (This will change if the status is Activated or Deactivated)
        ✓ Delete (This will be hidden if the status is Activated or Deactivated)
        ✓ Change Password  (This will be shown if the status is Activated or Deactivated)
✓ User Types 
✓ Audit Logs 
✓ Authentication Logs 
✓ Configurations 

CRONJOBS:
- Monthly Leave Credits

Print Templates: 
✓ PDS (employees | my profile)
✓ Service Record (employees | my profile)
✓ Leave Ledger Card (leave credits)
✓ Leave Application (leave applications)
✓ Travel Order (travel orders)
✓ Travel Report (travel orders)
✓ DTR (attendances | my attendances)
✓ Payslip (payslips | payrolls) 
✓ General Payroll (Payroll) 


<!-- 
DELETE FROM users WHERE userID != 1;
DELETE FROM user_accesses WHERE userID != 1;
DELETE FROM user_childrens WHERE userID != 1;
DELETE FROM user_civil_services WHERE userID != 1;
DELETE FROM user_educations WHERE userID != 1;
DELETE FROM user_employments WHERE userID != 1;
DELETE FROM user_families WHERE userID != 1;
DELETE FROM user_leave_credits;
DELETE FROM user_leave_credit_details;
DELETE FROM user_pds_change_requests;
DELETE FROM user_pds_change_request_details;
DELETE FROM user_personal_informations WHERE userID != 1;
DELETE FROM user_starting_informations;
DELETE FROM user_trainings;
DELETE FROM user_works;
DELETE FROM travel_orders;
DELETE FROM leave_applications;
DELETE FROM tokens;
DELETE FROM authentication_logs;
DELETE FROM audit_logs;
DELETE FROM audit_log_details; 
-->

<!-- MODULE STATUSES -->
# All Employees 
✔ Authentication w/ Login, Logout, Change Pass 
- Pofile Setup (For new users) [ON_PROGRESS_UNTIL_ALL_MODULES_FINISHED] 
    - On progress due to, biometric logs ID, shiftings, and other data connected to it
✔ My Profile w/ PDS data and employments 
✔ My E-signature (For printables temporary only)
- Home [ON_PROGRESS_UNTIL_ALL_MODULES_FINISHED] (Calendar events not finished)
- My Travel Requests [FOR_CHECKING] 
- My Leave Requests [FOR_CHECKING] 
- My Attendances [NEXT] 
- My Payslips [NEXT] 

# Administrators 
✔ Dashboard 
- Travel Orders [FOR_CHECKING] 
- Leave Applications [FOR_CHECKING] 
- Leave Credits [ON_PROGRESS_AND_FOR_CHECKING] (other records connected to attendance not yet added/functioning) 
- Biometric Logs [NEXT]
- Attendances [NEXT]
- Shiftings [NEXT] 
- Payrolls [NEXT]
✔ Payroll Deductions [NEXT]
✔ Payroll Deduction Types [NEXT]
✔ Tax Brackets 
- Employments [FOR_CHECKING]
✔ Employees 
✔ Job Positions 
✔ Offices 
✔ Address Provinces 
✔ Address Municipalities 
✔ Address Barangays 

# System 
- Users [ON_PROGRESS_UNTIL_ALL_MODULES_FINISHED] 
    - On progress due to biometric logs ID, shiftings, and other data connected to it.
✔ User Types 
✔ Audit Logs 
✔ Authentication Logs 
- Bulk Data Uploads w/ Excel [INCOMING] 
    - Offices 
    - Positions  
    - Users (Note: For old/past employee records only)
    - Employments (Note: For old/past employee records only) 
    - Leave Credits (Note: For old/past employee records only)
✔ Configurations 

# Other features 
✔ Notifications (leave applications and travel orders)
✔ Cronjobs 
    ✔ Monthly Leave Credits

