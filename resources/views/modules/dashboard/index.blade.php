@extends('layouts.app')

@section('title', $title)

@section('styles')

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
                                <i class="bx bx-user fs-4"></i>
                            </span>
                        </div>
                        <div class="card-info">
                            <h5 class="card-title mb-0 me-2 card-employee-total">...</h5>
                            <small class="text-muted">Total Employees</small>
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
                            <span class="avatar-initial bg-success rounded-circle">
                                <i class="bx bx-user-circle fs-4"></i>
                            </span>
                        </div>
                        <div class="card-info">
                            <h5 class="card-title mb-0 me-2 card-employee-active">...</h5>
                            <small class="text-muted">Active Employees</small>
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
                                <i class="bx bx-calendar fs-4"></i>
                            </span>
                        </div>
                        <div class="card-info">
                            <h5 class="card-title mb-0 me-2 card-leave">...</h5>
                            <small class="text-muted">Total Leave Applications</small>
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
                            <small class="text-muted">Total Travel Orders</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <!-- Employee Count Per Office -->
        <div class="col-12 col-sm-4 mb-4">
            <div class="card">
                <h5 class="card-header">Employees per Gender (Active)</h5>
                <div class="card-body">
                    <canvas id="employeeGenderCount" class="chartjs" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <!-- Leave Applications Per Status -->
        <div class="col-12 col-sm-4 mb-4">
            <div class="card">
                <div class="card-header header-elements">
                    <h5 class="card-title mb-0">Leave Applications per Status</h5>
                </div>
                <div class="card-body">
                    <canvas id="leaveApplicationStatuses" class="chartjs" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <!-- Travel Orders Per Status -->
        <div class="col-12 col-sm-4 mb-4">
            <div class="card">
                <div class="card-header header-elements">
                    <h5 class="card-title mb-0">Travel Orders per Status</h5>
                </div>
                <div class="card-body">
                    <canvas id="travelOrderStatuses" class="chartjs" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <!-- Leave Days per Office -->
        <div class="col-12 col-sm-6 mb-4">
            <div class="card">
                <div class="card-header header-elements">
                    <h5 class="card-title mb-0">Leave Days per Office</h5>
                </div>
                <div class="card-body">
                    <canvas id="leaveDaysPerOffice" class="chartjs" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <!-- Travel Days per Office -->
        <div class="col-12 col-sm-6 mb-4">
            <div class="card">
                <div class="card-header header-elements">
                    <h5 class="card-title mb-0">Travel Days per Office</h5>
                </div>
                <div class="card-body">
                    <canvas id="travelDaysPerOffice" class="chartjs" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('modals')

@endsection

@section('scripts')
    <script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>
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
                    $('.card-employee-total').text('...')
                    $('.card-employee-active').text('...')
                    $('.card-leave').text('...')
                    $('.card-travel').text('...')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        $('.card-employee-total').text(res.items.employees_total)
                        $('.card-employee-active').text(res.items.employees_active)
                        $('.card-leave').text(res.items.leave)
                        $('.card-travel').text(res.items.travel)

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

        function generateEmployeeCount()
        {

            apiCall(`/api/{{ "$controller/get-genders" }}/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    const employeeGenderCount = document.getElementById('employeeGenderCount');
                    if (employeeGenderCount) {
                        const employeeGenderCountVar = new Chart(employeeGenderCount, {
                            type: 'doughnut',
                            data: {
                                labels: res.items.genders[0],
                                datasets: [
                                {
                                    data: res.items.genders[1],
                                    backgroundColor: [blueColor, orangeLightColor],
                                    borderWidth: 0,
                                    pointStyle: 'rectRounded'
                                }
                                ]
                            },
                            options: {
                                maintainAspectRatio: false, 
                                responsive: true,
                                animation: { duration: 200 },
                                cutout: '70%',
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'bottom',
                                        labels: {
                                            generateLabels: function (chart) {
                                                const data = chart.data
                                                const dataset = data.datasets[0]
                                                const total = dataset.data.reduce((sum, val) => sum + val, 0)

                                                return data.labels.map((label, index) => {
                                                    const value = dataset.data[index]
                                                    const percentage = ((value / total) * 100).toFixed(1)
                                                    const backgroundColor = dataset.backgroundColor[index]
                                                    
                                                    return {
                                                        text: `${label}: ${value} (${percentage}%)`,
                                                        fillStyle: backgroundColor,
                                                        strokeStyle: backgroundColor,
                                                        index: index
                                                    }
                                                })
                                            }
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function (context) {
                                                const dataset = context.chart.data.datasets[context.datasetIndex]
                                                const label = context.label || ''
                                                const value = context.parsed
                                                const total = dataset.data.reduce((sum, val) => sum + val, 0)
                                                const percentage = ((value / total) * 100).toFixed(1)

                                                return ` ${label}: ${value} (${percentage}%)`
                                            }
                                        },
                                        // Updated default tooltip UI
                                        rtl: isRtl,
                                        backgroundColor: cardColor,
                                        titleColor: headingColor,
                                        bodyColor: legendColor,
                                        borderWidth: 1,
                                        borderColor: borderColor
                                    }
                                }
                            }
                        })
                    }

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

        function generateLeaveDaysPerOffice()
        {

            apiCall(`/api/{{ "$controller/get-leave-days" }}/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    const leaveDaysPerOffice = document.getElementById('leaveDaysPerOffice');
                    if (leaveDaysPerOffice) {
                        const leaveDaysPerOfficeVar = new Chart(leaveDaysPerOffice, {
                            type: 'bar',
                            data: {
                                labels: res.items.leave_days[0],
                                datasets: [
                                    {
                                        data: res.items.leave_days[1],
                                        // backgroundColor: [
                                        //     config.colors.danger, 
                                        //     config.colors.info, 
                                        //     config.colors.warning, 
                                        //     config.colors.primary, 
                                        //     config.colors.success, 
                                        // ],
                                        backgroundColor: purpleColor,
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

        function generateTravelDaysPerOffice()
        {

            apiCall(`/api/{{ "$controller/get-travel-days" }}/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    const travelDaysPerOffice = document.getElementById('travelDaysPerOffice');
                    if (travelDaysPerOffice) {
                        const travelDaysPerOfficeVar = new Chart(travelDaysPerOffice, {
                            type: 'bar',
                            data: {
                                labels: res.items.travel_days[0],
                                datasets: [
                                    {
                                        data: res.items.travel_days[1],
                                        // backgroundColor: [
                                        //     config.colors.danger, 
                                        //     config.colors.info, 
                                        //     config.colors.warning, 
                                        //     config.colors.primary, 
                                        //     config.colors.success, 
                                        // ],
                                        backgroundColor: blueColor,
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
            generateEmployeeCount()
            generateLeaveApplications()
            generateTravelOrders()
            generateLeaveDaysPerOffice()
            generateTravelDaysPerOffice()
        })

    </script>
@endsection