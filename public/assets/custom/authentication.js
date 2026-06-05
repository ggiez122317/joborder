const SIDENAV = {
    '' : {
        'home': {
            'icon'          : 'fa-solid fa-home', 
            'label'         : 'Home', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'my-travel-requests': {
            'icon'          : 'fa-solid fa-car-side', 
            'label'         : 'My Travel Requests', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'my-leave-requests': {
            'icon'          : 'fa-solid fa-calendar-days', 
            'label'         : 'My Leave Requests', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'my-attendances': {
            'icon'          : 'fa-solid fa-user-clock', 
            'label'         : 'My Attendances', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'my-payslips': {
            'icon'          : 'fa-solid fa-credit-card', 
            'label'         : 'My Payslips', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
    }, 
    'Administrator' : {
        'dashboard': {
            'icon'          : 'fa-solid fa-chart-simple', 
            'label'         : 'Dashboard', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'travel-requests': {
            'icon'          : 'fa-solid fa-car-side', 
            'label'         : 'Travel Orders', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'leave-requests': {
            'icon'          : 'fa-solid fa-calendar-days', 
            'label'         : 'Leave Applications', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'leave-credits': {
            'icon'          : 'fa-solid fa-coins', 
            'label'         : 'Leave Credits', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'biometric-logs': {
            'icon'          : 'fa-solid fa-fingerprint', 
            'label'         : 'Biometric Logs', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'attendances': {
            'icon'          : 'fa-solid fa-user-clock', 
            'label'         : 'Attendances', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        // 'payrolls': {
        //     'icon'          : 'fa-solid fa-hand-holding-dollar', 
        //     'label'         : 'Payrolls', 
        //     'controllers'   : [], 
        //     'submodules'    : {}
        // }, 
        'payrolls': {
            'icon'          : 'fa-solid fa-hand-holding-dollar', 
            'label'         : 'Payroll', 
            'controllers'   : ['payrolls', 'payroll-deductions', 'payroll-deduction-types'], 
            'submodules'    : {
                'payrolls'                  : 'List', 
                'payroll-deductions'        : 'Deductions', 
                'payroll-deduction-types'   : 'Deduction Types', 
            } 
        }, 
        'tax-brackets': {
            'icon'          : 'fa-solid fa-percent', 
            'label'         : 'Tax Brackets', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'employments': {
            'icon'          : 'fa-solid fa-user-tie', 
            'label'         : 'Employments', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'employees': {
            'icon'          : 'fa-solid fa-circle-user', 
            'label'         : 'Employees',  
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'job-positions': {
            'icon'          : 'fa-solid fa-briefcase', 
            'label'         : 'Job Positions', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'offices': {
            'icon'          : 'fa-solid fa-building', 
            'label'         : 'Offices',  
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'addresses': {
            'icon'          : 'fa-solid fa-map', 
            'label'         : 'Addresses', 
            'controllers'   : ['provinces', 'municipalities', 'barangays'], 
            'submodules'    : {
                'provinces'         : 'Provinces', 
                'municipalities'    : 'Municipalities', 
                'barangays'         : 'Barangays', 
            }
        }, 
    }, 
    'System' : {
        'users': {
            'icon'          : 'fa-solid fa-users', 
            'label'         : 'Users', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'user-types': {
            'icon'          : 'fa-solid fa-user-gear', 
            'label'         : 'User Types', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'audit-logs': {
            'icon'          : 'fa-solid fa-file-pen', 
            'label'         : 'Audit Logs',  
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'authentication-logs': {
            'icon'          : 'fa-solid fa-file-shield', 
            'label'         : 'Authentication Logs', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
        'configurations': {
            'icon'          : 'fa-solid fa-gears', 
            'label'         : 'Configurations', 
            'controllers'   : [], 
            'submodules'    : {}
        }, 
    }, 
}

function authenticationVerify()
{
    
    if (localStorage.getItem('t') !== null) {
        // 
        apiCall('/api/authentication/verify/', 'GET', null, 
            // beforesend 
            function() {
            }, 
            // done 
            function(res) { 
                if (res.status == 200) {
                    authenticationUserAccesses()
                } else { authenticationLogout() }
            }, 
            // always 
            function() {}, 
            localStorage.getItem('t') 
        )
        return
    }
    // go to login page
    const unprotected_controllers = ['login', 'forgot-password']
    if (!unprotected_controllers.includes(`${CONTROLLER}`)) window.location.href=BASE_URL_BACKEND+`/${unprotected_controllers[0]}/`

}

function authenticationUserAccesses()
{

    apiCall('/api/authentication/user-access/', 'GET', null, 
        // beforesend 
        function() {
            $('#myProfile').html('')
        }, 
        // done 
        function(res) { 
            if (res.status == 200) {
                console.log(res)

                // hide My Profile for Admin username 
                if (!res.items.isAdminUsername) {
                    $('#myProfile').html(`
                        <a class="dropdown-item" href="/my-profile/">
                          <i class="fa-solid fa-circle-user me-2"></i>&thinsp;
                          <span class="align-middle">My Profile</span>
                        </a>    
                    `)
                }

                // logout if admin username and controller is in my-profile
                if (res.items.isAdminUsername && CONTROLLER=='my-profile') {
                    authenticationLogout()
                }

                // if not in protected page, redirect
                if (!res.items.controllers.includes(CONTROLLER)) {
                    window.location.href=BASE_URL_BACKEND+`/${res.items.controller}/`
                    return
                }

                // else, generate navigations   
                html = ''
                for (snKey in SIDENAV) {
                    html1 = ''
                    html2 = ''
                    if (snKey) html1 = `<li class="menu-header small text-uppercase"><span class="menu-header-text">${snKey}</span></li>`
                    for (snKey2 in SIDENAV[snKey]) {

                        mainmodule = ''
                        submodules = ''

                        if (SIDENAV[snKey][snKey2].controllers.length>0) {
                            for (sm in SIDENAV[snKey][snKey2]['submodules']) {
                                if (res.items.controllers.includes(sm)) {
                                    submodules += `
                                        <li class="menu-item ${sm == CONTROLLER?'active':''}">
                                            <a href="${BASE_URL_BACKEND}/${sm}/" class="menu-link">
                                                <div>${SIDENAV[snKey][snKey2]['submodules'][sm]}</div>
                                            </a>
                                        </li>
                                    `
                                }
                            }
                        } else {
                            if (res.items.controllers.includes(snKey2)) {
                                mainmodule = `
                                    <li class="menu-item ${snKey2 == CONTROLLER?'active':''}">
                                        <a href="${BASE_URL_BACKEND}/${snKey2}/" class="menu-link" style="position: relative;">
                                            ${snKey2=='travel-requests'?`<div class="d-notif d-notif-travel d-none">0</div>`:''}
                                            ${snKey2=='leave-requests'?`<div class="d-notif d-notif-leave d-none">0</div>`:''}
                                            <i class="menu-icon tf-icons ${SIDENAV[snKey][snKey2].icon}"></i>
                                            <div>${SIDENAV[snKey][snKey2].label}</div>
                                        </a>
                                    </li>
                                `
                            }
                        }
                        html2 += `${SIDENAV[snKey][snKey2].controllers.length>0 && submodules?`
                                <li class="menu-item ${SIDENAV[snKey][snKey2].controllers.includes(CONTROLLER)?'open':''}">
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <i class="menu-icon tf-icons ${SIDENAV[snKey][snKey2].icon}"></i>
                                        <div>${SIDENAV[snKey][snKey2].label}</div>
                                    </a>
                                    <ul class="menu-sub">${submodules}</ul>
                                </li>
                            `:mainmodule}`
                    }
                    if (html2) html += html1
                    html += html2
                }
                $('#nav-side').html(html)

                authenticationUserDetails()

            } else { authenticationLogout() }
        }, 
        // always 
        function() {}, 
        localStorage.getItem('t') 
    )

}

function authenticationUserDetails()
{

    if (localStorage.getItem('t') !== null) {
        // 
        apiCall('/api/authentication/user-details/', 'GET', null, 
            // beforesend 
            function() {
            }, 
            // done 
            function(res) { 
                if (res.status == 200) {
                    $('.userDP').prop('src', res.items.avatar)
                    $('.username').text(res.items.username)
                    $('.userType').text(res.items.userType)
                    authenticationUserNotifications()
                } else { authenticationLogout() }
            }, 
            // always 
            function() {}, 
            localStorage.getItem('t') 
        )
        return
    }

}

function authenticationUserNotifications()
{

    if (localStorage.getItem('t') !== null) {
        // 
        apiCall('/api/authentication/user-notifications/', 'GET', null, 
            // beforesend 
            function() {
                $('.d-notif-travel').addClass('d-none').text(0)
                $('.d-notif-leave').addClass('d-none').text(0)
            }, 
            // done 
            function(res) { 
                if (res.status == 200) {
                    if (res.items.travelOrders > 0) $('.d-notif-travel').removeClass('d-none').text(res.items.travelOrders)
                    if (res.items.leaveApplications > 0) $('.d-notif-leave').removeClass('d-none').text(res.items.leaveApplications)
                } else { authenticationLogout() }
            }, 
            // always 
            function() {}, 
            localStorage.getItem('t') 
        )
        return
    }

}

function authenticationLogout() 
{

    apiCall('/api/authentication/logout/', 'GET', null, 
        // beforesend
        function() {}, 
        // done
        function(res) { 
            localStorage.removeItem('t') 
            localStorage.clear() 
            authenticationVerify()
        }, 
        // always
        function() {}, 
        localStorage.getItem('t') 
    )

}