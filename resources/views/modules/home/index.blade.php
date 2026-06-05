@extends('layouts.app')

@section('title', $title)

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-calendar.css') }}" />
@endsection

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1">
            <li class="breadcrumb-item"><a href="javascript:void(0);">{{ $title }}</a></li>
            <li class="breadcrumb-item {{ $page ? '' : 'd-none' }}"><a href="javascript:void(0);">{{ $page }}</a></li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-sm-3 mb-4">
            <div class="card bg-gradient-to-r from-cyan-500 to-cyan-400">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar">
                            <span class="avatar-initial bg-primary rounded-circle">
                                <i class="bx bx-calendar fs-4"></i>
                            </span>
                        </div>
                        <div class="card-info">
                            <h5 class="card-title mb-0 me-2 card-leave">...</h5>
                            <small class="text-muted">Leave Applications</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar">
                            <span class="avatar-initial bg-warning rounded-circle">
                                <i class="bx bx-taxi fs-4"></i>
                            </span>
                        </div>
                        <div class="card-info">
                            <h5 class="card-title mb-0 me-2 card-travel">...</h5>
                            <small class="text-muted">Travel Orders</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar">
                            <span class="avatar-initial bg-info rounded-circle">
                                <i class="bx bx-coin-stack fs-4"></i>
                            </span>
                        </div>
                        <div class="card-info">
                            <h5 class="card-title mb-0 me-2 card-vacation-leave-credits">...</h5>
                            <small class="text-muted">Vacation Leave Credits</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar">
                            <span class="avatar-initial bg-danger rounded-circle">
                                <i class="bx bx-coin-stack fs-4"></i>
                            </span>
                        </div>
                        <div class="card-info">
                            <h5 class="card-title mb-0 me-2 card-sick-leave-credits">...</h5>
                            <small class="text-muted">Sick Leave Credits</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-6 mb-4 app-calendar-content">
            <div class="card shadow-none border-0">
                <div class="card-body pb-0">
                    <!-- FullCalendar -->
                    <div id="calendar"></div>
                </div>
            </div>
            <div class="app-overlay"></div>
        </div>
        <div class="col-12 col-sm-6 mb-4">
            <div class="row">
                <!-- Leave Applications Per Status -->
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header header-elements">
                            <h5 class="card-title mb-0">Leave Applications per Status</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="leaveApplicationStatuses" class="chartjs" style="height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Travel Orders Per Status -->
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header header-elements">
                            <h5 class="card-title mb-0">Travel Orders per Status</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="travelOrderStatuses" class="chartjs" style="height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

@endsection

@section('scripts')
    <script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.js') }}"></script>
    <script src="{{ asset('assets/js/app-calendar-events.js') }}"></script>
    <script src="{{ asset('assets/custom/page_index.js') }}"></script>
    <script>

        const purpleColor = '#836AF9',
            yellowColor = '#ffe800',
            cyanColor = '#28dac6',
            orangeColor = '#FF8132',
            orangeLightColor = '#FDAC34',
            oceanBlueColor = '#299AFF',
            greyColor = '#4F5D70',
            greyLightColor = '#EDF1F4',
            blueColor = '#2B9AFF',
            blueLightColor = '#84D0FF';
        let cardColor, headingColor, labelColor, borderColor, legendColor;
        if (isDarkStyle) {
            cardColor = config.colors_dark.cardColor;
            headingColor = config.colors_dark.headingColor;
            labelColor = config.colors_dark.textMuted;
            legendColor = config.colors_dark.bodyColor;
            borderColor = config.colors_dark.borderColor;
        } else {
            cardColor = config.colors.cardColor;
            headingColor = config.colors.headingColor;
            labelColor = config.colors.textMuted;
            legendColor = config.colors.bodyColor;
            borderColor = config.colors.borderColor;
        }

        function generateCards()
        {

            apiCall(`/api/{{ "$controller/get-cards" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $('.card-leave').text('...')
                    $('.card-travel').text('...')
                    $('.card-vacation-leave-credits').text('...')
                    $('.card-sick-leave-credits').text('...')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        $('.card-leave').text(res.items.leave)
                        $('.card-travel').text(res.items.travel)
                        $('.card-vacation-leave-credits').text(res.items.creditsVacation)
                        $('.card-sick-leave-credits').text(res.items.creditsSick)

                    } else if (res.status == 401 && res.message == 'Invalid token') {
                        authenticationLogout()
                    } else {
                        Toast.fire({ icon : "warning", title : res.name, html : res.message })
                    }

                }, 
                // always
                function() {}, 
                localStorage.getItem('t') 
            )

        }

        function generateCalendar()
        {

            const calendarsColor = {
                Leave       : 'primary',
                Travel      : 'warning',
                MissedLog   : 'danger',
                Attendance  : 'success',
            }

            apiCall(`/api/{{ "$controller/get-calendar-events" }}/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    var calendarEl = document.getElementById('calendar')
                    const calendar = new Calendar(calendarEl, {
                        plugins             : [ dayGridPlugin ],
                        initialView         : 'dayGridMonth',
                        dayMaxEventRows     : true, 
                        displayEventTime    : false, 
                        headerToolbar       : {
                            left    : 'prev,next today',
                            center  : 'title',
                            right   : '', 
                        }, 
                        eventClassNames     : function ({ event: calendarEvent }) {
                            const colorName = calendarsColor[calendarEvent._def.extendedProps.calendar];
                            // Background Color
                            return ['fc-event-' + colorName];
                        },
                        eventClick: function(info) {
                            console.log(info.event.title);
                        },
                        events: res.items.events
                    })
                    calendar.render()

                }, 
                // always
                function() {}, 
                localStorage.getItem('t') 
            )

        }

        function generateLeaveApplications()
        {

            apiCall(`/api/{{ "$controller/get-leave-applications" }}/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    const leaveApplicationStatuses = document.getElementById('leaveApplicationStatuses');
                    if (leaveApplicationStatuses) {
                        const leaveApplicationStatusesVar = new Chart(leaveApplicationStatuses, {
                            type: 'bar',
                            data: {
                                labels: res.items.leave_applications[0],
                                datasets: [
                                    {
                                        data: res.items.leave_applications[1],
                                        backgroundColor: [
                                            config.colors.danger,    
                                            config.colors.info,      
                                            config.colors.primary,   
                                            config.colors.warning,   
                                            config.colors.secondary, 
                                            config.colors.success,   
                                        ],
                                        borderColor: 'transparent',
                                        maxBarThickness: 15,
                                        borderRadius: {
                                            topRight: 15,
                                            topLeft: 15
                                        }
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: { duration: 700 },
                                plugins: {
                                    tooltip: {
                                        rtl: isRtl,
                                        backgroundColor: cardColor,
                                        titleColor: headingColor,
                                        bodyColor: legendColor,
                                        borderWidth: 1,
                                        borderColor: borderColor
                                    },
                                    legend: {
                                        display: false
                                    },
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            color: borderColor,
                                            drawBorder: false
                                        },
                                        ticks: { color: labelColor }
                                    },
                                    y: {
                                        min: 0,
                                        grid: {
                                            color: borderColor,
                                            drawBorder: false
                                        },
                                        ticks: {
                                            stepSize: 1,
                                            color: labelColor
                                        }
                                    }
                                }
                            }, 
                        })
                    }

                }, 
                // always
                function() {}, 
                localStorage.getItem('t') 
            )

        }

        function generateTravelOrders()
        {

            apiCall(`/api/{{ "$controller/get-travel-orders" }}/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    const travelOrderStatuses = document.getElementById('travelOrderStatuses');
                    if (travelOrderStatuses) {
                        const travelOrderStatusesVar = new Chart(travelOrderStatuses, {
                            type: 'bar',
                            data: {
                                labels: res.items.travel_orders[0],
                                datasets: [
                                    {
                                        data: res.items.travel_orders[1],
                                        backgroundColor: [
                                            config.colors.danger, 
                                            config.colors.info, 
                                            config.colors.warning, 
                                            config.colors.primary, 
                                            config.colors.success, 
                                        ],
                                        borderColor: 'transparent',
                                        maxBarThickness: 15,
                                        borderRadius: {
                                            topRight: 15,
                                            topLeft: 15
                                        }
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: { duration: 700 },
                                plugins: {
                                    tooltip: {
                                        rtl: isRtl,
                                        backgroundColor: cardColor,
                                        titleColor: headingColor,
                                        bodyColor: legendColor,
                                        borderWidth: 1,
                                        borderColor: borderColor
                                    },
                                    legend: {
                                        display: false
                                    },
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            color: borderColor,
                                            drawBorder: false
                                        },
                                        ticks: { color: labelColor }
                                    },
                                    y: {
                                        min: 0,
                                        grid: {
                                            color: borderColor,
                                            drawBorder: false
                                        },
                                        ticks: {
                                            stepSize: 1,
                                            color: labelColor
                                        }
                                    }
                                }
                            }, 
                        })
                    }

                }, 
                // always
                function() {}, 
                localStorage.getItem('t') 
            )

        }

        $(document).ready(function() {
            resetFilterItems(`{{ "$controller" }}`)
            generateCards()
            generateCalendar()
            generateLeaveApplications()
            generateTravelOrders()
        })

    </script>
@endsection