@extends('layouts.app')

@section('page_title', 'Dashboard')
@section('page_subtitle', 'LGU Trento — PDS Management · Administrative access only')

@section('page_actions')
    <a href="{{ route('pds.upload') }}" class="dash-ghost-btn">Upload file</a>
    <a href="{{ route('records.index') }}" class="dash-ghost-btn">View records</a>
    <a href="{{ route('pds.create') }}" class="dash-primary-btn">Add new PDS</a>
@endsection

@section('content')
    @php
        $safeTotal = max($totalEmployees, 1);
        $malePercent = round(($maleCount / $safeTotal) * 100, 1);
        $femalePercent = round(($femaleCount / $safeTotal) * 100, 1);
        $deltaLabel = $addedThisMonth > 0 ? 'added this month' : 'new this month';
        $officeMax = max((int) $officeStats->max(), 1);
        $intakeMax = max(max($monthlyIntake), 1);
        $officeChartMax = max((int) $officeChart->max(), 1);

        $sparkline = function (array $values, int $width = 220, int $height = 72, int $padding = 8) {
            $count = count($values);
            $peak = max($values ?: [0]);
            $max = max((float) $peak, 1);
            $step = $count > 1 ? ($width - ($padding * 2)) / ($count - 1) : 0;
            $points = [];

            foreach ($values as $index => $value) {
                $x = $padding + ($index * $step);
                $y = $height - $padding - (($value / $max) * ($height - ($padding * 2)));
                $points[] = round($x, 2) . ',' . round($y, 2);
            }

            return implode(' ', $points);
        };

        $generateAreaPaths = function ($values, int $width = 600, int $height = 200, int $left = 30, int $right = 30, int $top = 20, int $bottom = 30, $customMax = null) {
            $valuesArray = is_a($values, \Illuminate\Support\Collection::class) ? $values->all() : array_values((array)$values);
            $count = count($valuesArray);
            $peak = max($valuesArray ?: [0]);
            $max = $customMax !== null ? max((float) $customMax, 1) : max((float) $peak, 1);
            
            $usableWidth = $width - $left - $right;
            $usableHeight = $height - $top - $bottom;
            $step = $count > 1 ? $usableWidth / ($count - 1) : 0;
            
            $points = [];
            foreach ($valuesArray as $index => $value) {
                $x = $left + ($index * $step);
                $y = $top + ($usableHeight - (($value / $max) * $usableHeight));
                $points[] = round($x, 2) . ',' . round($y, 2);
            }
            
            $linePoints = implode(' ', $points);
            
            $firstX = $left;
            $lastX = $left + (($count - 1) * $step);
            $baselineY = $height - $bottom;
            $areaPath = "M " . round($firstX, 2) . "," . round($baselineY, 2) . " ";
            foreach ($points as $p) {
                $areaPath .= "L " . $p . " ";
            }
            $areaPath .= "L " . round($lastX, 2) . "," . round($baselineY, 2) . " Z";
            
            return [
                'line' => $linePoints,
                'area' => $areaPath,
                'points' => $points,
                'max' => $max,
            ];
        };

        $officeLinePoints = function ($values, int $width = 260, int $height = 170, int $left = 24, int $bottom = 28, int $top = 18) {
            $values = array_values($values);
            $count = count($values);
            $usableWidth = $width - $left - 12;
            $usableHeight = $height - $top - $bottom;
            $step = $count > 1 ? $usableWidth / ($count - 1) : 0;
            $max = max(max($values ?: [0]), 1);
            $points = [];

            foreach ($values as $index => $value) {
                $x = $left + ($index * $step);
                $y = $top + ($usableHeight - (($value / $max) * $usableHeight));
                $points[] = round($x, 2) . ',' . round($y, 2);
            }

            return implode(' ', $points);
        };

        $columnWidth = count($monthlyIntake) > 0 ? floor(100 / count($monthlyIntake)) : 0;
    @endphp

    <style>
        /* Import Premium Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        /* Base Typography & Color Adjustments */
        .dash-shell {
            color: #0f172a;
            font-family: 'Plus Jakarta Sans', 'Outfit', 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .dash-section-label {
            margin-bottom: 12px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #64748b;
        }

        /* Layout Grids */
        .dash-stats,
        .dash-mini,
        .dash-analytics {
            display: grid;
            gap: 12px
        }

        .dash-stats {
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 18px
        }

        .dash-mini {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin-bottom: 18px
        }

        .dash-analytics {
            grid-template-columns: 1.3fr 1fr 1fr;
            margin-bottom: 18px
        }

        .dash-bottom {
            display: grid;
            grid-template-columns: 1fr 1.55fr;
            gap: 12px
        }

        .dash-roadmap {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 18px
        }

        /* Sleek card base & elegant hover uplift transition */
        .dash-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(15, 23, 42, 0.015), 0 1px 2px rgba(15, 23, 42, 0.025);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dash-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -6px rgba(15, 23, 42, 0.05), 0 8px 12px -8px rgba(15, 23, 42, 0.03);
        }

        .dash-card-pad {
            padding: 16px
        }

        /* Lavender White Theme (dash-hero) */
        .dash-hero {
            position: relative;
            overflow: hidden;
            background: #ffffff !important;
            border: 1px solid #ddd6fe !important;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.05) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dash-hero:hover {
            transform: translateY(-4px) scale(1.01);
            border-color: #c084fc !important;
            box-shadow: 0 12px 24px rgba(124, 58, 237, 0.12) !important;
        }

        .dash-hero .dash-shape-circle-1 {
            background: rgba(124, 58, 237, 0.04) !important;
        }

        .dash-hero .dash-shape-circle-2 {
            background: rgba(124, 58, 237, 0.02) !important;
        }

        .dash-hero .dash-icon-box-glass {
            background: #f5f3ff !important;
            border: 1px solid #ddd6fe !important;
            box-shadow: none !important;
            color: #7c3aed !important;
        }

        .dash-hero .dash-stat-label-glass {
            color: #7c3aed !important;
        }

        .dash-hero .dash-stat-value-glass {
            color: #1e1b4b !important;
            text-shadow: none !important;
        }

        .dash-hero .dash-stat-badge-glass {
            background: #f5f3ff !important;
            border: 1px solid #ddd6fe !important;
            color: #7c3aed !important;
            box-shadow: none !important;
        }

        /* Amber White Theme (dash-job-orders) */
        .dash-job-orders {
            position: relative;
            overflow: hidden;
            background: #ffffff !important;
            border: 1px solid #fde68a !important;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.05) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dash-job-orders:hover {
            transform: translateY(-4px) scale(1.01);
            border-color: #fbbf24 !important;
            box-shadow: 0 12px 24px rgba(245, 158, 11, 0.12) !important;
        }

        .dash-job-orders .dash-shape-circle-1 {
            background: rgba(245, 158, 11, 0.04) !important;
        }

        .dash-job-orders .dash-shape-circle-2 {
            background: rgba(245, 158, 11, 0.02) !important;
        }

        .dash-job-orders .dash-icon-box-glass {
            background: #fffbeb !important;
            border: 1px solid #fde68a !important;
            box-shadow: none !important;
            color: #d97706 !important;
        }

        .dash-job-orders .dash-stat-label-glass {
            color: #d97706 !important;
        }

        .dash-job-orders .dash-stat-value-glass {
            color: #451a03 !important;
            text-shadow: none !important;
        }

        .dash-job-orders .dash-stat-badge-glass {
            background: #fffbeb !important;
            border: 1px solid #fde68a !important;
            color: #d97706 !important;
            box-shadow: none !important;
        }

        /* Floating glass shapes inside hero cards */
        .dash-hero-shapes {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
        }

        .dash-shape-circle-1 {
            position: absolute;
            top: -24px;
            right: -24px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            filter: blur(12px);
        }

        .dash-shape-circle-2 {
            position: absolute;
            bottom: -32px;
            left: -16px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            filter: blur(10px);
        }

        .dash-hero-content {
            position: relative;
            z-index: 2;
        }

        /* Frosted Glass Icon Box */
        .dash-icon-box-glass {
            display: flex;
            height: 36px;
            width: 36px;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin-bottom: 12px;
            background: rgba(255, 255, 255, 0.16) !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .dash-card:hover .dash-icon-box-glass {
            transform: rotate(5deg) scale(1.08);
        }

        /* Frosted Glass Stat Labels */
        .dash-stat-label-glass {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.88);
        }

        /* Frosted Glass Large Metric Values */
        .dash-stat-value-glass {
            margin-top: 6px;
            font-size: 30px;
            line-height: 1;
            font-weight: 800;
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Frosted Glass Pill Badges */
        .dash-stat-badge-glass {
            display: inline-flex;
            align-items: center;
            margin-top: 12px;
            padding: 4px 10px;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 99px;
            font-size: 10px;
            font-weight: 600;
            color: #ffffff;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        .dash-stat-badge-glass.amber-badge {
            background: rgba(255, 255, 255, 0.12);
            font-weight: 500;
        }

        /* Legend/Stats labels for normal cards */
        .dash-stat-label,
        .dash-mini-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #64748b;
        }

        .dash-stat-value {
            margin-top: 6px;
            font-size: 24px;
            line-height: 1;
            font-weight: 700;
            color: #0f172a;
            font-family: 'Outfit', sans-serif;
        }

        .dash-stat-delta {
            margin-top: 8px;
            font-size: 11px;
            color: #64748b;
        }

        /* Accent left-borders on mini-cards */
        .dash-mini .dash-card:nth-child(1) {
            border-left: 4px solid #3b82f6;
        }

        .dash-mini .dash-card:nth-child(2) {
            border-left: 4px solid #10b981;
        }

        .dash-mini-value {
            margin-top: 6px;
            font-size: 20px;
            line-height: 1.1;
            font-weight: 700;
            color: #0f172a;
            font-family: 'Outfit', sans-serif;
        }

        .dash-mini-sub {
            margin-top: 6px;
            font-size: 11px;
            color: #64748b;
        }

        /* Cards Header & Typography */
        .dash-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 16px 0;
        }

        .dash-card-title {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            font-family: 'Outfit', sans-serif;
        }

        .dash-card-note {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        .dash-link {
            font-size: 11px;
            font-weight: 600;
            color: #16a34a;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .dash-link:hover {
            color: #15803d;
        }

        .dash-chart-wrap {
            padding: 14px 16px 16px
        }

        .dash-bars-svg {
            display: block;
            width: 100%
        }

        /* Sparkline Cards (Small Multiples) */
        .dash-spark-card {
            padding: 14px 16px;
            transition: all 0.25s ease;
        }

        .dash-spark-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(15, 23, 42, 0.03);
        }

        .dash-spark-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .dash-spark-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #64748b;
        }

        .dash-spark-value {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            font-family: 'Outfit', sans-serif;
        }

        .dash-spark-sub {
            font-size: 10px;
            color: #94a3b8;
        }

        /* Progress bars */
        .dash-progress-row {
            margin-bottom: 12px
        }

        .dash-progress-row:last-child {
            margin-bottom: 0
        }

        .dash-progress-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .dash-progress-meta span {
            font-size: 11px;
            color: #475569;
            font-weight: 500;
        }

        .dash-progress-meta b {
            font-size: 11px;
            font-weight: 600;
            color: #0f172a;
        }

        .dash-progress-track {
            height: 6px;
            border-radius: 99px;
            background: #f1f5f9;
            overflow: hidden;
        }

        .dash-progress-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.6s ease;
        }

        /* Office stats list */
        .dash-office-list {
            margin-top: 14px;
            border-top: 1px solid #f1f5f9;
            padding-top: 12px;
        }

        .dash-office-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 0;
        }

        .dash-office-name {
            font-size: 11px;
            color: #475569;
            font-weight: 500;
        }

        .dash-office-bar {
            flex: 1;
            height: 4px;
            margin: 0 12px;
            border-radius: 99px;
            background: #f1f5f9;
        }

        .dash-office-fill {
            height: 100%;
            border-radius: 99px;
            background: #bbf7d0;
            transition: width 0.6s ease;
        }

        .dash-office-count {
            font-size: 11px;
            font-weight: 600;
            color: #0f172a;
        }

        /* Table wrap & Elegant table layout */
        .dash-table-wrap {
            padding: 8px 16px 16px
        }

        .dash-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 11px
        }

        .dash-table th {
            padding: 0 10px 10px;
            border-bottom: 1px solid #f1f5f9;
            text-align: left;
            font-weight: 600;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dash-table td {
            padding: 10px 10px;
            border-bottom: 1px solid #f8fafc;
            color: #334155;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dash-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .dash-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .dash-table tr:last-child td {
            border-bottom: none
        }

        .dash-name-cell {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
            text-decoration: none;
            color: inherit;
        }

        .dash-name-cell span:last-child {
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .dash-name-cell:hover span:last-child {
            color: #16a34a;
        }

        /* Avatar styles */
        .dash-avatar {
            display: flex;
            height: 24px;
            width: 24px;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .avatar-regular {
            background: #f0fdf4;
            color: #15803d
        }

        .avatar-job-order {
            background: #fffbeb;
            color: #b45309
        }

        .avatar-plantilla {
            background: #eff6ff;
            color: #1d4ed8
        }

        /* Badge styles */
        .dash-badge {
            display: inline-block;
            border-radius: 999px;
            padding: 2px 8px;
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .badge-regular {
            background: #f0fdf4;
            color: #15803d
        }

        .badge-job-order {
            background: #fffbeb;
            color: #b45309
        }

        .badge-plantilla {
            background: #eff6ff;
            color: #0369a1
        }

        /* Interactive slide-in on lists */
        .dash-feature-list {
            display: grid;
            gap: 8px;
            padding: 0;
            margin: 12px 0 0;
            list-style: none
        }

        .dash-feature-item {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 12px;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            background: #f8fafc;
            transition: all 0.2s ease-in-out;
        }

        .dash-feature-item:hover {
            background: #f1f5f9;
            border-color: #e2e8f0;
            transform: translateX(4px);
        }

        .dash-feature-dot {
            display: inline-flex;
            height: 20px;
            width: 20px;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #dcfce7;
            color: #15803d;
            font-size: 11px;
            font-weight: 700;
        }

        .dash-feature-title {
            font-size: 12px;
            font-weight: 600;
            color: #0f172a;
        }

        .dash-feature-copy {
            margin-top: 3px;
            font-size: 11px;
            line-height: 1.5;
            color: #64748b;
        }

        /* Empty states & Buttons */
        .dash-empty {
            display: flex;
            min-height: 180px;
            align-items: center;
            justify-content: center;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            background: #fbfdff;
            padding: 16px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }

        .dash-primary-btn,
        .dash-ghost-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .dash-primary-btn {
            border: 1px solid #16a34a;
            background: #16a34a;
            color: #fff;
        }

        .dash-primary-btn:hover {
            background: #15803d;
            border-color: #15803d;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
        }

        .dash-ghost-btn {
            border: 1px solid #dbe5ee;
            background: #fff;
            color: #475569;
        }

        .dash-ghost-btn:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        @media (max-width: 1200px) {

            .dash-stats,
            .dash-mini,
            .dash-analytics,
            .dash-bottom,
            .dash-roadmap {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media (max-width: 768px) {

            .dash-stats,
            .dash-mini,
            .dash-analytics,
            .dash-bottom,
            .dash-roadmap {
                grid-template-columns: 1fr
            }
        }
    </style>

    <div class="dash-shell">
        <div class="dash-section-label">Overview</div>
        <section class="dash-stats">
            <article class="dash-card dash-card-pad dash-hero" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div class="dash-hero-shapes">
                    <div class="dash-shape-circle-1"></div>
                    <div class="dash-shape-circle-2"></div>
                </div>
                <div class="dash-hero-content" style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="dash-icon-box-glass">
                                <svg viewBox="0 0 16 16" width="16" height="16" fill="none" aria-hidden="true">
                                    <circle cx="6" cy="5" r="2.5" fill="currentColor"></circle>
                                    <circle cx="10.5" cy="5" r="2.5" fill="currentColor" opacity=".6"></circle>
                                    <path d="M1 13c0-2.5 2-4.5 5-4.5s5 2 5 4.5" stroke="currentColor" stroke-width="1.3"
                                        stroke-linecap="round"></path>
                                    <path d="M10.5 8.5c1.5.3 2.5 1.5 2.5 3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                                        opacity=".6"></path>
                                </svg>
                            </div>
                            <div class="dash-stat-badge-glass">
                                <span>+{{ $addedThisMonth }} this month</span>
                            </div>
                        </div>
                        <div class="dash-stat-label-glass" style="margin-top: 8px;">Total Employees</div>
                        <div class="dash-stat-value-glass" style="font-size: 36px;">{{ number_format($totalEmployees) }}</div>
                    </div>
                    
                    <!-- Premium Lavender Sparkline Chart -->
                    <div style="height: 50px; margin-top: 15px; overflow: hidden; position: relative;">
                        @php
                            $sparkPath = $generateAreaPaths($monthlyIntake ?: [0,0,0,0,0,0], 280, 50, 5, 5, 5, 5);
                        @endphp
                        <svg viewBox="0 0 280 50" width="100%" height="50" style="display: block;">
                            <defs>
                                <linearGradient id="spark-pink-grad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#a855f7" stop-opacity="0.15"/>
                                    <stop offset="100%" stop-color="#a855f7" stop-opacity="0.0"/>
                                </linearGradient>
                            </defs>
                            <path d="{{ $sparkPath['area'] }}" fill="url(#spark-pink-grad)" />
                            <polyline points="{{ $sparkPath['line'] }}" fill="none" stroke="#8b5cf6" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
            </article>

            <article class="dash-card dash-card-pad" style="grid-column: span 2; background: #ffffff !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 2px 4px rgba(15, 23, 42, 0.015), 0 1px 2px rgba(15, 23, 42, 0.025) !important;">
                <div class="flex h-full flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="dash-icon-box" style="background: #eff6ff; padding: 6px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;">
                                <svg viewBox="0 0 16 16" width="14" height="14" fill="none" stroke="#1d4ed8" stroke-width="1.8">
                                    <path d="M8 1v7h7" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <circle cx="8" cy="8" r="7"></circle>
                                </svg>
                            </div>
                            <div class="dash-stat-label" style="color: #0f172a; font-weight: 700; font-size: 11px; letter-spacing: 0.02em;">Gender Distribution Breakdown</div>
                        </div>
                        <div class="text-[10px] font-bold text-[#16a34a] bg-[#f0fdf4] px-2 py-0.5 rounded border border-[#dcfce7]">LIVE DATA</div>
                    </div>
                    <div class="flex flex-1 items-center justify-center">
                        <div id="genderApexChart" class="w-full"></div>
                    </div>
                    <div class="mt-2 border-t border-[#f1f5f9] pt-2 text-center">
                        <span class="text-[10px] font-semibold text-[#64748b]">TOTAL ENCODED:
                            {{ number_format($totalEmployees) }}</span>
                    </div>
                </div>
            </article>

            <article class="dash-card dash-card-pad dash-job-orders" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div class="dash-hero-shapes">
                    <div class="dash-shape-circle-1"></div>
                    <div class="dash-shape-circle-2"></div>
                </div>
                <div class="dash-hero-content" style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="dash-icon-box-glass">
                                <svg viewBox="0 0 16 16" width="16" height="16" fill="none" aria-hidden="true">
                                    <rect x="2" y="4" width="12" height="9" rx="1" stroke="currentColor" stroke-width="1.4"></rect>
                                    <path d="M5 4V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1" stroke="currentColor" stroke-width="1.4"></path>
                                </svg>
                            </div>
                            <div class="dash-stat-badge-glass amber-badge">
                                <span>Active tracking</span>
                            </div>
                        </div>
                        <div class="dash-stat-label-glass" style="margin-top: 8px;">Job Orders</div>
                        <div class="dash-stat-value-glass" style="font-size: 36px;">{{ number_format($jobOrderCount) }}</div>
                    </div>
                    
                    <!-- Premium Amber Sparkline Chart -->
                    <div style="height: 50px; margin-top: 15px; overflow: hidden; position: relative;">
                        @php
                            $sparkPathJO = $generateAreaPaths($typeTrend['Job Order'] ?: [0,0,0,0,0,0], 280, 50, 5, 5, 5, 5);
                        @endphp
                        <svg viewBox="0 0 280 50" width="100%" height="50" style="display: block;">
                            <defs>
                                <linearGradient id="spark-amber-grad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.15"/>
                                    <stop offset="100%" stop-color="#f59e0b" stop-opacity="0.0"/>
                                </linearGradient>
                            </defs>
                            <path d="{{ $sparkPathJO['area'] }}" fill="url(#spark-amber-grad)" />
                            <polyline points="{{ $sparkPathJO['line'] }}" fill="none" stroke="#f59e0b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
            </article>
        </section>

        <section class="dash-mini">
            <article class="dash-card dash-card-pad">
                <div class="dash-mini-label">Active Offices</div>
                <div class="dash-mini-value">{{ number_format($activeOffices) }}</div>
                <div class="dash-mini-sub">Distinct offices represented in records</div>
            </article>
            <article class="dash-card dash-card-pad">
                <div class="dash-mini-label">PDS Completion Rate</div>
                <div class="dash-mini-value">{{ $completionRate }}%</div>
                <div class="dash-mini-sub">{{ number_format($completeRecords) }} of {{ number_format($totalEmployees) }}
                    records fully encoded</div>
            </article>
        </section>

        <div class="dash-section-label">Admin tools</div>
        <section class="dash-roadmap">
            @if(config('app.show_data_quality_tools'))
            <article class="dash-card dash-card-pad">
                <div class="dash-card-title">Data quality and intake</div>
                <ul class="dash-feature-list">
                    <li class="dash-feature-item">
                        <span class="dash-feature-dot">1</span>
                        <div>
                            <div class="dash-feature-title">Bulk import history</div>
                            <div class="dash-feature-copy">{{ number_format($importHistoryTotal) }} uploads tracked.
                                {{ number_format($importHistoryCompleted) }} completed and
                                {{ number_format($importHistoryFailed) }} failed with downloadable error reports.</div>
                            <a href="{{ route('admin.import-history') }}" class="dash-link">Open import history</a>
                        </div>
                    </li>
                    <li class="dash-feature-item">
                        <span class="dash-feature-dot">2</span>
                        <div>
                            <div class="dash-feature-title">Incomplete PDS queue</div>
                            <div class="dash-feature-copy">{{ number_format($incompleteCount) }} records are missing office,
                                photo, work data, or contact information and are ready for quick cleanup.</div>
                            <a href="{{ route('admin.incomplete-queue') }}" class="dash-link">Open incomplete queue</a>
                        </div>
                    </li>
                </ul>
            </article>
            @endif

            <article class="dash-card dash-card-pad">
                <div class="dash-card-title">Office completion status</div>
                <ul class="dash-feature-list">
                    @forelse ($officeCompletion->take(5) as $index => $officeRow)
                        <li class="dash-feature-item">
                            <span class="dash-feature-dot">{{ $index + 1 }}</span>
                            <div>
                                <div class="dash-feature-title">{{ $officeRow['office'] }}</div>
                                <div class="dash-feature-copy">{{ $officeRow['complete'] }} of {{ $officeRow['total'] }}
                                    complete, {{ $officeRow['completion_rate'] }}% completion,
                                    {{ $officeRow['missing_fields_count'] }} missing-field flags.</div>
                                <a href="{{ route('records.index', ['office' => $officeRow['office'] === 'Unassigned' ? '' : $officeRow['office']]) }}"
                                    class="dash-link">Filter records</a>
                            </div>
                        </li>
                    @empty
                        <li class="dash-feature-item">
                            <span class="dash-feature-dot">0</span>
                            <div>
                                <div class="dash-feature-title">No office data yet</div>
                                <div class="dash-feature-copy">Office completion metrics will appear here once employee records
                                    are assigned to offices.</div>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </article>
        </section>

        <div class="dash-section-label">Analytics</div>
        <section class="dash-analytics">
            <article class="dash-card">
                <div class="dash-card-head">
                    <div>
                        <div class="dash-card-title">Monthly intake</div>
                        <div class="dash-card-note">New employee records encoded in the last 6 months</div>
                    </div>
                </div>
                <div class="dash-chart-wrap">
                    @if (array_sum($monthlyIntake) === 0)
                        <div class="dash-empty">No encoded records yet. New PDS saves will populate this monthly line chart
                            automatically.</div>
                    @else
                        <svg viewBox="0 0 260 180" width="100%" height="180" aria-label="Monthly intake trend">
                            <line x1="12" y1="152" x2="248" y2="152" stroke="#e8edf2" stroke-width="1"></line>
                            @foreach ($months as $index => $month)
                                <line x1="{{ 20 + ($index * 40) }}" y1="24" x2="{{ 20 + ($index * 40) }}" y2="152" stroke="#f1f5f9"
                                    stroke-width="1"></line>
                            @endforeach
                            <polyline points="{{ $sparkline($monthlyIntake, 248, 152, 20) }}" fill="none" stroke="#16a34a"
                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></polyline>
                            @foreach ($monthlyIntake as $index => $value)
                                @php
                                    $x = 20 + ($index * 40);
                                    $y = 152 - (($value / $intakeMax) * 128);
                                @endphp
                                <circle cx="{{ $x }}" cy="{{ $y }}" r="4" fill="#ffffff" stroke="#16a34a" stroke-width="2"></circle>
                                <text x="{{ $x }}" y="{{ max($y - 10, 16) }}" text-anchor="middle" font-size="10"
                                    fill="#64748b">{{ $value }}</text>
                                <text x="{{ $x }}" y="172" text-anchor="middle" font-size="10"
                                    fill="#94a3b8">{{ $months[$index] }}</text>
                            @endforeach
                        </svg>
                    @endif
                </div>
            </article>

            <article class="dash-card">
                <div class="dash-card-head">
                    <div>
                        <div class="dash-card-title">Office distribution</div>
                        <div class="dash-card-note">Top offices by active PDS records</div>
                    </div>
                </div>
                <div class="dash-chart-wrap">
                    @if ($officeChart->isEmpty())
                        <div class="dash-empty">Office counts appear here once records include office assignments.</div>
                    @else
                        @php
                            $officeValues = $officeChart->values()->all();
                            $officeCount = max(count($officeValues), 1);
                            $chartWidth = 260;
                            $chartHeight = 180;
                            $chartLeft = 24;
                            $chartBottom = 34;
                            $chartTop = 18;
                            $usableWidth = $chartWidth - $chartLeft - 12;
                            $usableHeight = $chartHeight - $chartTop - $chartBottom;
                            $barSlot = $usableWidth / $officeCount;
                            $barWidth = max(min($barSlot * 0.58, 28), 16);
                            $linePoints = $officeLinePoints($officeValues, $chartWidth, $chartHeight, $chartLeft, $chartBottom, $chartTop);
                        @endphp
                        <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" class="dash-bars-svg"
                            aria-label="Office distribution chart">
                            @for ($i = 0; $i <= 4; $i++)
                                @php
                                    $y = $chartTop + (($usableHeight / 4) * $i);
                                @endphp
                                <line x1="{{ $chartLeft - 4 }}" y1="{{ $y }}" x2="{{ $chartWidth - 8 }}" y2="{{ $y }}"
                                    stroke="{{ $i % 2 === 0 ? '#edf2f7' : '#f8fafc' }}" stroke-width="10" />
                            @endfor
                            <line x1="{{ $chartLeft - 2 }}" y1="{{ $chartHeight - $chartBottom }}" x2="{{ $chartWidth - 8 }}"
                                y2="{{ $chartHeight - $chartBottom }}" stroke="#dbe5ee" stroke-width="1" />

                            @foreach ($officeChart->values() as $index => $count)
                                @php
                                    $x = $chartLeft + ($index * $barSlot) + (($barSlot - $barWidth) / 2);
                                    $barHeight = ($count / $officeChartMax) * $usableHeight;
                                    $y = $chartTop + ($usableHeight - $barHeight);
                                    $labelX = $chartLeft + ($index * $barSlot) + ($barSlot / 2);
                                @endphp
                                <rect x="{{ round($x, 2) }}" y="{{ round($y, 2) }}" width="{{ round($barWidth, 2) }}"
                                    height="{{ round(max($barHeight, 6), 2) }}" fill="{{ $loop->even ? '#2f7de1' : '#23c6c8' }}"
                                    rx="2" />
                                <text x="{{ round($labelX, 2) }}" y="{{ max($y - 6, 12) }}" text-anchor="middle" font-size="9"
                                    fill="#334155">{{ $count }}</text>
                            @endforeach

                            @if ($officeCount > 1)
                                <polyline points="{{ $linePoints }}" fill="none" stroke="#0f172a" stroke-width="1.4"
                                    stroke-dasharray="3 2" stroke-linecap="round" stroke-linejoin="round" />
                            @elseif ($officeCount === 1)
                                @php
                                    $singleX = $chartLeft + ($barSlot / 2);
                                    $singleY = $chartTop + ($usableHeight - (($officeValues[0] / $officeChartMax) * $usableHeight));
                                @endphp
                                <circle cx="{{ round($singleX, 2) }}" cy="{{ round($singleY, 2) }}" r="2.5" fill="#0f172a" />
                            @endif

                            @foreach ($officeChart->keys() as $index => $office)
                                @php
                                    $labelX = $chartLeft + ($index * $barSlot) + ($barSlot / 2);
                                @endphp
                                <text x="{{ round($labelX, 2) }}" y="{{ $chartHeight - 12 }}" text-anchor="middle" font-size="8.5"
                                    fill="#94a3b8">
                                    {{ \Illuminate\Support\Str::limit($office, 10, '') }}
                                </text>
                            @endforeach
                        </svg>
                    @endif
                </div>
            </article>

            <article class="dash-card">
                <div class="dash-card-head">
                    <div>
                        <div class="dash-card-title">Small multiples</div>
                        <div class="dash-card-note">Hiring mix by classification over time</div>
                    </div>
                </div>
                <div class="dash-chart-wrap" style="display:grid;gap:10px">
                    @foreach ($typeTrend as $label => $trend)
                        @php
                            $seriesTotal = array_sum($trend);
                            $stroke = $label === 'Job Order' ? '#f59e0b' : ($label === 'Plantilla' ? '#3b82f6' : ($label === 'Regular' ? '#16a34a' : '#64748b'));
                        @endphp
                        <div class="dash-card dash-spark-card">
                            <div class="dash-spark-head">
                                <div>
                                    <div class="dash-spark-label">{{ $label }}</div>
                                    <div class="dash-spark-sub">Last six months</div>
                                </div>
                                <div class="dash-spark-value">{{ $seriesTotal }}</div>
                            </div>
                            @if ($seriesTotal === 0)
                                <div class="dash-card-note">No {{ strtolower($label) }} records encoded in this period.</div>
                            @else
                                <svg viewBox="0 0 220 72" width="100%" height="72" aria-label="{{ $label }} trend">
                                    <polyline points="{{ $sparkline($trend) }}" fill="none" stroke="{{ $stroke }}"
                                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></polyline>
                                    @foreach ($trend as $index => $value)
                                        @php
                                            $trendMax = max(max($trend), 1);
                                            $x = 8 + ($index * ((220 - 16) / max(count($trend) - 1, 1)));
                                            $y = 72 - 8 - (($value / $trendMax) * (72 - 16));
                                        @endphp
                                        <circle cx="{{ $x }}" cy="{{ $y }}" r="2.5" fill="#fff" stroke="{{ $stroke }}"
                                            stroke-width="1.5"></circle>
                                    @endforeach
                                </svg>
                            @endif
                        </div>
                    @endforeach
                </div>
            </article>
        </section>

        <section class="dash-bottom">
            <article class="dash-card">
                <div class="dash-card-head">
                    <div>
                        <div class="dash-card-title">Gender breakdown</div>
                        <div class="dash-card-note">Current recorded employees by sex at birth and office</div>
                    </div>
                </div>
                <div class="dash-chart-wrap">
                    <div class="dash-progress-row">
                        <div class="dash-progress-meta"><span>Male</span><b>{{ number_format($maleCount) }}</b></div>
                        <div class="dash-progress-track">
                            <div class="dash-progress-fill" style="width: {{ $malePercent }}%; background:#16a34a"></div>
                        </div>
                    </div>
                    <div class="dash-progress-row">
                        <div class="dash-progress-meta"><span>Female</span><b>{{ number_format($femaleCount) }}</b></div>
                        <div class="dash-progress-track">
                            <div class="dash-progress-fill" style="width: {{ $femalePercent }}%; background:#86efac"></div>
                        </div>
                    </div>

                    <div class="dash-office-list">
                        <div class="dash-section-label" style="margin-bottom:6px">By office</div>
                        @forelse ($officeStats as $office => $count)
                            <div class="dash-office-row">
                                <span class="dash-office-name">{{ $office }}</span>
                                <div class="dash-office-bar">
                                    <div class="dash-office-fill" style="width: {{ ($count / $officeMax) * 100 }}%"></div>
                                </div>
                                <span class="dash-office-count">{{ $count }}</span>
                            </div>
                        @empty
                            <div class="dash-card-note">No office-tagged records yet.</div>
                        @endforelse
                    </div>
                </div>
            </article>

            <article class="dash-card">
                <div class="dash-card-head">
                    <div>
                        <div class="dash-card-title">Recent records</div>
                        <div class="dash-card-note">Latest PDS entries from the records office</div>
                    </div>
                    <a href="{{ route('records.index') }}" class="dash-link">View all</a>
                </div>
                <div class="dash-table-wrap">
                    <table class="dash-table">
                        <colgroup>
                            <col style="width:34%">
                            <col style="width:25%">
                            <col style="width:23%">
                            <col style="width:18%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Office</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentRecords as $record)
                                <tr>
                                    <td>
                                        <a href="{{ route('profile.show', $record['employee']) }}" class="dash-name-cell">
                                            <span
                                                class="dash-avatar {{ $record['avatarClass'] }}">{{ $record['initials'] }}</span>
                                            <span>{{ $record['employee']->full_name }}</span>
                                        </a>
                                    </td>
                                    <td>{{ $record['employee']->position_title ?: 'Unassigned' }}</td>
                                    <td>{{ $record['employee']->office ?: 'Not set' }}</td>
                                    <td><span class="dash-badge {{ $record['badgeClass'] }}">{{ $record['type'] }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="dash-empty" style="min-height:120px">No PDS records encoded yet. Use “Add
                                            new PDS” or “Upload file” to start building the dashboard.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // ApexChart for Gender Distribution (Matching the "Figure 3" style)
                const genderOptions = {
                    series: [{{ $maleCount }}, {{ $femaleCount }}, {{ $otherCount }}],
                    chart: {
                        height: 320,
                        type: 'donut',
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '50%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'GENDER',
                                        formatter: function (w) {
                                            return ''
                                        }
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            return Math.round(val) + "%"
                        },
                        dropShadow: { enabled: false }
                    },
                    stroke: { width: 2, colors: ['#fff'] },
                    colors: ['#4A89C8', '#F7941D', '#9A89A0'],
                    labels: ['Male Representation', 'Female Representation', 'Other / Not Specified'],
                    legend: {
                        show: true,
                        position: 'bottom',
                        fontSize: '11px',
                        markers: { radius: 12 },
                        itemMargin: { horizontal: 10, vertical: 5 }
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val + " records"
                            }
                        }
                    }
                };

                const chart = new ApexCharts(document.querySelector("#genderApexChart"), genderOptions);
                chart.render();

                // The rest of the Chart.js charts (unchanged)
                // ...
            });
        </script>
    @endpush
@endsection