@extends('layouts.driver')

@section('title', 'My Schedule')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<style>
/* Schedule Page Specific Styles */
.schedule-header {
    background: linear-gradient(135deg, var(--color-primary-500) 0%, var(--color-primary-600) 100%);
    color: white;
    padding: var(--space-5);
    border-radius: var(--radius-xl);
    margin-bottom: var(--space-6);
    position: relative;
    overflow: hidden;
}

.schedule-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    transform: rotate(30deg);
}

.schedule-title {
    font-size: var(--font-size-2xl);
    font-weight: var(--font-weight-bold);
    margin-bottom: var(--space-1);
}

.schedule-subtitle {
    opacity: 0.9;
    font-size: var(--font-size-base);
}

/* View Toggle */
.view-toggle {
    display: flex;
    gap: var(--space-2);
    background: var(--color-gray-100);
    padding: var(--space-1);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-gray-200);
}

.view-btn {
    padding: var(--space-2) var(--space-4);
    border: none;
    background: transparent;
    border-radius: var(--radius-base);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
    color: var(--color-gray-600);
    cursor: pointer;
    transition: all var(--transition-fast);
    display: flex;
    align-items: center;
    gap: var(--space-2);
}

.view-btn:hover {
    color: var(--color-primary-600);
    background: var(--color-white);
}

.view-btn.active {
    background: var(--color-white);
    color: var(--color-primary-600);
    box-shadow: var(--shadow-sm);
}

/* Stats Cards */
.schedule-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: var(--space-3);
    margin-bottom: var(--space-5);
}

.schedule-stat {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-xl);
    padding: var(--space-4);
    display: flex;
    align-items: center;
    gap: var(--space-3);
    transition: all var(--transition-base);
}

.schedule-stat:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.stat-icon-wrapper {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--font-size-lg);
    flex-shrink: 0;
}

.stat-icon-wrapper.primary { background: var(--color-primary-100); color: var(--color-primary-600); }
.stat-icon-wrapper.success { background: var(--color-success-light); color: var(--color-success); }
.stat-icon-wrapper.warning { background: var(--color-warning-light); color: var(--color-warning-dark); }
.stat-icon-wrapper.info { background: var(--color-info-light); color: var(--color-info); }

.stat-content {
    flex: 1;
    min-width: 0;
}

.stat-value {
    font-size: var(--font-size-xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-gray-900);
    line-height: 1;
}

.stat-label {
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-top: var(--space-1);
}

/* Calendar View */
.calendar-container {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-xl);
    overflow: hidden;
}

.calendar-header {
    padding: var(--space-4) var(--space-5);
    background: linear-gradient(135deg, var(--color-gray-50) 0%, var(--color-white) 100%);
    border-bottom: 1px solid var(--card-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: var(--space-3);
}

.calendar-title {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
}

.calendar-nav {
    display: flex;
    gap: var(--space-2);
}

.calendar-nav-btn {
    width: 36px;
    height: 36px;
    border: 1px solid var(--color-gray-200);
    background: var(--color-white);
    border-radius: var(--radius-base);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-gray-600);
    cursor: pointer;
    transition: all var(--transition-fast);
}

.calendar-nav-btn:hover {
    border-color: var(--color-primary-300);
    color: var(--color-primary-600);
    background: var(--color-primary-50);
}

.calendar-nav-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

#calendar {
    padding: var(--space-4);
    height: 600px;
}

/* FullCalendar Custom Styles */
.fc {
    font-family: 'Inter', sans-serif !important;
}

.fc-theme-standard {
    --fc-border-color: var(--color-gray-200) !important;
    --fc-button-bg-color: var(--color-primary-500) !important;
    --fc-button-border-color: var(--color-primary-500) !important;
    --fc-button-hover-bg-color: var(--color-primary-600) !important;
    --fc-button-hover-border-color: var(--color-primary-600) !important;
    --fc-button-active-bg-color: var(--color-primary-700) !important;
    --fc-today-bg-color: var(--color-primary-50) !important;
}

.fc-button {
    font-weight: var(--font-weight-medium) !important;
    text-transform: capitalize !important;
    font-size: var(--font-size-sm) !important;
    padding: var(--space-2) var(--space-3) !important;
}

.fc-toolbar-title {
    font-size: var(--font-size-lg) !important;
    font-weight: var(--font-weight-semibold) !important;
    color: var(--color-gray-900) !important;
}

.fc-col-header-cell {
    background: var(--color-gray-50) !important;
    border-bottom: 2px solid var(--color-gray-200) !important;
}

.fc-col-header-cell-cushion {
    padding: var(--space-2) !important;
    font-weight: var(--font-weight-semibold) !important;
    color: var(--color-gray-700) !important;
    text-decoration: none !important;
}

.fc-daygrid-day {
    border-color: var(--color-gray-100) !important;
}

.fc-daygrid-day-number {
    padding: var(--space-2) !important;
    font-weight: var(--font-weight-medium) !important;
    color: var(--color-gray-900) !important;
}

.fc-day-today .fc-daygrid-day-number {
    background: var(--color-primary-500) !important;
    color: white !important;
    border-radius: 50% !important;
    width: 32px !important;
    height: 32px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.fc-daygrid-event {
    border-radius: var(--radius-base) !important;
    border: none !important;
    padding: 2px 6px !important;
    font-size: var(--font-size-xs) !important;
    font-weight: var(--font-weight-medium) !important;
    margin: 2px 4px !important;
    cursor: pointer !important;
    transition: all var(--transition-fast) !important;
}

.fc-daygrid-event:hover {
    transform: scale(1.02) !important;
    box-shadow: var(--shadow-sm) !important;
}

.fc-daygrid-more-link {
    font-size: var(--font-size-xs) !important;
    font-weight: var(--font-weight-medium) !important;
    color: var(--color-primary-600) !important;
    text-decoration: none !important;
}

/* Event Colors */
.fc-event.pickup { background: var(--color-primary-500) !important; }
.fc-event.delivery { background: var(--color-success) !important; }
.fc-event.offline { background: var(--color-gray-400) !important; }
.fc-event.break { background: var(--color-warning) !important; }
.fc-event.maintenance { background: var(--color-danger) !important; }

/* Timeline View */
.timeline-container {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-xl);
    overflow: hidden;
}

.timeline-header {
    padding: var(--space-4) var(--space-5);
    background: linear-gradient(135deg, var(--color-gray-50) 0%, var(--color-white) 100%);
    border-bottom: 1px solid var(--card-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: var(--space-3);
}

.timeline-date {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
}

.timeline-filters {
    display: flex;
    gap: var(--space-2);
    flex-wrap: wrap;
}

.filter-chip {
    padding: var(--space-1) var(--space-3);
    border: 1px solid var(--color-gray-200);
    background: var(--color-white);
    border-radius: var(--radius-full);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    color: var(--color-gray-600);
    cursor: pointer;
    transition: all var(--transition-fast);
}

.filter-chip:hover {
    border-color: var(--color-primary-300);
    color: var(--color-primary-600);
    background: var(--color-primary-50);
}

.filter-chip.active {
    background: var(--color-primary-500);
    border-color: var(--color-primary-500);
    color: var(--color-white);
}

.timeline-list {
    max-height: 600px;
    overflow-y: auto;
    padding: var(--space-4);
}

.timeline-day {
    margin-bottom: var(--space-6);
}

.timeline-day:last-child {
    margin-bottom: 0;
}

.day-header {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3) var(--space-4);
    background: var(--color-gray-50);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-3);
    border: 1px solid var(--color-gray-100);
}

.day-name {
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
    font-size: var(--font-size-base);
}

.day-date {
    color: var(--color-gray-500);
    font-size: var(--font-size-sm);
}

.day-summary {
    margin-left: auto;
    display: flex;
    gap: var(--space-3);
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
}

.day-summary-item {
    display: flex;
    align-items: center;
    gap: var(--space-1);
}

.day-summary-item.pickup { color: var(--color-primary-600); }
.day-summary-item.delivery { color: var(--color-success); }
.day-summary-item.offline { color: var(--color-gray-500); }

/* Timeline Event */
.timeline-event {
    display: flex;
    gap: var(--space-3);
    padding: var(--space-3);
    background: var(--color-white);
    border: 1px solid var(--color-gray-100);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-2);
    transition: all var(--transition-fast);
    cursor: pointer;
    position: relative;
}

.timeline-event:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--color-gray-200);
    transform: translateX(4px);
}

.timeline-event::before {
    content: '';
    position: absolute;
    left: -1px;
    top: 0;
    bottom: 0;
    width: 3px;
    border-radius: var(--radius-lg) 0 0 var(--radius-lg);
}

.timeline-event.pickup::before { background: var(--color-primary-500); }
.timeline-event.delivery::before { background: var(--color-success); }
.timeline-event.offline::before { background: var(--color-gray-400); }
.timeline-event.break::before { background: var(--color-warning); }
.timeline-event.maintenance::before { background: var(--color-danger); }

.event-time {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 70px;
    padding: var(--space-2);
    background: var(--color-gray-50);
    border-radius: var(--radius-base);
}

.event-time-start {
    font-weight: var(--font-weight-bold);
    font-size: var(--font-size-base);
    color: var(--color-gray-900);
}

.event-time-end {
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
}

.event-content {
    flex: 1;
    min-width: 0;
}

.event-type {
    display: inline-flex;
    align-items: center;
    gap: var(--space-1);
    padding: var(--space-1) var(--space-2);
    border-radius: var(--radius-full);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-semibold);
    margin-bottom: var(--space-2);
}

.event-type.pickup { background: var(--color-primary-100); color: var(--color-primary-700); }
.event-type.delivery { background: var(--color-success-light); color: var(--color-success-dark); }
.event-type.offline { background: var(--color-gray-100); color: var(--color-gray-600); }
.event-type.break { background: var(--color-warning-light); color: var(--color-warning-dark); }
.event-type.maintenance { background: var(--color-danger-light); color: var(--color-danger-dark); }

.event-title {
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
    margin-bottom: var(--space-1);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.event-details {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-3);
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
}

.event-detail {
    display: flex;
    align-items: center;
    gap: var(--space-1);
}

.event-earnings {
    font-weight: var(--font-weight-bold);
    color: var(--color-success);
    font-size: var(--font-size-sm);
}

.event-actions {
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
    align-items: flex-end;
}

.event-btn {
    padding: var(--space-1) var(--space-3);
    border: 1px solid var(--color-gray-200);
    background: var(--color-white);
    border-radius: var(--radius-base);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    color: var(--color-gray-600);
    cursor: pointer;
    transition: all var(--transition-fast);
    display: flex;
    align-items: center;
    gap: var(--space-1);
}

.event-btn:hover {
    border-color: var(--color-primary-300);
    color: var(--color-primary-600);
    background: var(--color-primary-50);
}

.event-btn.primary {
    background: var(--color-primary-500);
    border-color: var(--color-primary-500);
    color: white;
}

.event-btn.primary:hover {
    background: var(--color-primary-600);
}

/* Weekly View */
.week-view {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: var(--space-3);
    padding: var(--space-4);
}

.week-day {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.week-day.today {
    border-color: var(--color-primary-500);
    box-shadow: 0 0 0 2px var(--color-primary-100);
}

.week-day-header {
    padding: var(--space-3);
    background: var(--color-gray-50);
    border-bottom: 1px solid var(--color-gray-100);
    text-align: center;
}

.week-day.today .week-day-header {
    background: var(--color-primary-50);
    border-color: var(--color-primary-100);
}

.week-day-name {
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
    font-size: var(--font-size-sm);
}

.week-day-date {
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
    margin-top: var(--space-1);
}

.week-day.today .week-day-date {
    color: var(--color-primary-600);
}

.week-day-events {
    flex: 1;
    padding: var(--space-2);
    overflow-y: auto;
    max-height: 400px;
}

.week-event {
    padding: var(--space-2);
    border-radius: var(--radius-base);
    margin-bottom: var(--space-2);
    font-size: var(--font-size-xs);
    cursor: pointer;
    transition: all var(--transition-fast);
}

.week-event:hover {
    transform: translateX(2px);
}

.week-event.pickup { background: var(--color-primary-50); border-left: 3px solid var(--color-primary-500); }
.week-event.delivery { background: var(--color-success-light); border-left: 3px solid var(--color-success); }
.week-event.offline { background: var(--color-gray-50); border-left: 3px solid var(--color-gray-400); }

.week-event-time {
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
    margin-bottom: var(--space-1);
}

.week-event-title {
    color: var(--color-gray-700);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Availability Form */
.availability-form {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    margin-top: var(--space-5);
}

.availability-form-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--space-4);
    padding-bottom: var(--space-4);
    border-bottom: 1px solid var(--card-border);
}

.availability-form-title {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
}

.day-availability {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--space-3);
}

.day-avail-item {
    padding: var(--space-3);
    background: var(--color-gray-50);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-gray-100);
    transition: all var(--transition-fast);
}

.day-avail-item:hover {
    background: var(--color-white);
    box-shadow: var(--shadow-sm);
}

.day-avail-item.active {
    border-color: var(--color-primary-300);
    background: var(--color-primary-50);
}

.day-avail-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--space-2);
}

.day-avail-name {
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
}

.day-avail-toggle {
    position: relative;
    width: 44px;
    height: 24px;
}

.day-avail-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}

.day-avail-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: var(--color-gray-300);
    transition: var(--transition-base);
    border-radius: 24px;
}

.day-avail-slider:before {
    position: absolute;
    content: "";
    height: 18px; width: 18px;
    left: 3px; bottom: 3px;
    background-color: white;
    transition: var(--transition-base);
    border-radius: 50%;
    box-shadow: var(--shadow-sm);
}

.day-avail-toggle input:checked + .day-avail-slider {
    background-color: var(--color-success);
}

.day-avail-toggle input:checked + .day-avail-slider:before {
    transform: translateX(20px);
}

.day-avail-times {
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
}

.time-input-group {
    display: flex;
    align-items: center;
    gap: var(--space-2);
}

.time-input-group label {
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
    width: 50px;
    flex-shrink: 0;
}

.time-input-group input {
    flex: 1;
    padding: var(--space-1) var(--space-2);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--radius-base);
    font-size: var(--font-size-sm);
    background: var(--color-white);
}

.time-input-group input:disabled {
    background: var(--color-gray-100);
    color: var(--color-gray-400);
}

/* Empty States */
.schedule-empty {
    text-align: center;
    padding: var(--space-12) var(--space-6);
    color: var(--color-gray-500);
}

.schedule-empty i {
    font-size: 4rem;
    color: var(--color-gray-300);
    margin-bottom: var(--space-4);
}

.schedule-empty h3 {
    color: var(--color-gray-600);
    margin-bottom: var(--space-2);
}

/* Mobile Responsive */
@media (max-width: 992px) {
    #calendar {
        height: 500px;
    }
    .timeline-list {
        max-height: 500px;
    }
}

@media (max-width: 768px) {
    .schedule-header {
        padding: var(--space-4);
    }
    .schedule-title {
        font-size: var(--font-size-xl);
    }
    .schedule-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    .calendar-header, .timeline-header {
        flex-direction: column;
        align-items: flex-start;
    }
    .calendar-nav, .timeline-filters {
        width: 100%;
        justify-content: space-between;
    }
    #calendar {
        height: 450px;
        padding: var(--space-2);
    }
    .timeline-event {
        flex-direction: column;
    }
    .event-time {
        flex-direction: row;
        justify-content: space-between;
        min-width: auto;
        width: 100%;
    }
    .event-actions {
        flex-direction: row;
        justify-content: flex-end;
        width: 100%;
    }
    .week-view {
        grid-template-columns: 1fr;
    }
    .day-availability {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .schedule-stats {
        grid-template-columns: 1fr;
    }
    .fc-toolbar {
        flex-wrap: wrap !important;
    }
    .fc-toolbar-chunk {
        width: 100% !important;
        justify-content: center !important;
        margin-bottom: var(--space-2) !important;
    }
    .timeline-event {
        padding: var(--space-2);
    }
    .event-detail {
        flex: 1 1 50%;
    }
}

/* Print Styles */
@media print {
    .view-toggle, .calendar-nav, .timeline-filters, .availability-form, .schedule-header, .sidebar, .navbar-top {
        display: none !important;
    }
    .calendar-container, .timeline-container {
        box-shadow: none;
        border: 1px solid #ddd;
    }
    #calendar {
        height: auto !important;
    }
    .timeline-list {
        max-height: none;
    }
    .fc-button {
        display: none !important;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="schedule-header" data-aos="fade-down">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
            <div>
                <h1 class="schedule-title"><i class="fas fa-calendar-alt me-2"></i>My Schedule</h1>
                <p class="schedule-subtitle mb-0">Manage your availability and track upcoming orders</p>
            </div>
            <div class="d-flex flex-wrap gap-3">
                <div class="view-toggle" role="group" aria-label="Schedule view">
                    <button type="button" class="view-btn active" data-view="calendar" onclick="switchView('calendar')">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="d-none d-sm-inline">Month</span>
                    </button>
                    <button type="button" class="view-btn" data-view="week" onclick="switchView('week')">
                        <i class="fas fa-calendar-week"></i>
                        <span class="d-none d-sm-inline">Week</span>
                    </button>
                    <button type="button" class="view-btn" data-view="timeline" onclick="switchView('timeline')">
                        <i class="fas fa-list"></i>
                        <span class="d-none d-sm-inline">Timeline</span>
                    </button>
                </div>
                <button class="btn btn-light btn-sm" onclick="goToToday()">
                    <i class="fas fa-calendar-day me-1"></i>Today
                </button>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#availabilityModal">
                    <i class="fas fa-clock me-1"></i>Set Availability
                </button>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="schedule-stats" data-aos="fade-up">
        <div class="schedule-stat">
            <div class="stat-icon-wrapper primary"><i class="fas fa-box"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['pickups_this_week'] ?? 0 }}</div>
                <div class="stat-label">Pickups This Week</div>
            </div>
        </div>
        <div class="schedule-stat">
            <div class="stat-icon-wrapper success"><i class="fas fa-flag-checkered"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['deliveries_this_week'] ?? 0 }}</div>
                <div class="stat-label">Deliveries This Week</div>
            </div>
        </div>
        <div class="schedule-stat">
            <div class="stat-icon-wrapper warning"><i class="fas fa-clock"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['scheduled_hours'] ?? 0 }}h</div>
                <div class="stat-label">Scheduled Hours</div>
            </div>
        </div>
        <div class="schedule-stat">
            <div class="stat-icon-wrapper info"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-content">
                <div class="stat-value">K{{ number_format($stats['estimated_earnings'] ?? 0, 0) }}</div>
                <div class="stat-label">Est. Earnings</div>
            </div>
        </div>
    </div>

    <!-- Calendar View -->
    <div id="calendarView" class="calendar-container" data-aos="fade-up">
        <div class="calendar-header">
            <h2 class="calendar-title">
                <i class="fas fa-calendar-alt me-2"></i>
                <span id="calendarMonth"></span>
            </h2>
            <div class="calendar-nav">
                <button class="calendar-nav-btn" onclick="calendarPrev()" aria-label="Previous month">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="calendar-nav-btn" onclick="calendarNext()" aria-label="Next month">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        <div id="calendar"></div>
    </div>

    <!-- Week View -->
    <div id="weekView" class="calendar-container d-none" data-aos="fade-up">
        <div class="calendar-header">
            <h2 class="calendar-title">
                <i class="fas fa-calendar-week me-2"></i>
                <span id="weekRange"></span>
            </h2>
            <div class="calendar-nav">
                <button class="calendar-nav-btn" onclick="weekPrev()" aria-label="Previous week">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="calendar-nav-btn" onclick="weekNext()" aria-label="Next week">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        <div id="weekCalendar" class="week-view"></div>
    </div>

    <!-- Timeline View -->
    <div id="timelineView" class="timeline-container d-none" data-aos="fade-up">
        <div class="timeline-header">
            <h2 class="timeline-date">
                <i class="fas fa-list me-2"></i>
                Upcoming Schedule
            </h2>
            <div class="timeline-filters" role="group" aria-label="Filter events">
                <button type="button" class="filter-chip active" data-filter="all" onclick="filterTimeline('all')">All</button>
                <button type="button" class="filter-chip" data-filter="pickup" onclick="filterTimeline('pickup')"><i class="fas fa-box me-1"></i>Pickups</button>
                <button type="button" class="filter-chip" data-filter="delivery" onclick="filterTimeline('delivery')"><i class="fas fa-flag-checkered me-1"></i>Deliveries</button>
                <button type="button" class="filter-chip" data-filter="availability" onclick="filterTimeline('availability')"><i class="fas fa-clock me-1"></i>Availability</button>
            </div>
        </div>
        <div class="timeline-list" id="timelineList">
            @if($timelineEvents->isNotEmpty())
                @foreach($timelineEvents->groupBy('date') as $date => $events)
                    <div class="timeline-day" data-date="{{ $date }}">
                        <div class="day-header">
                            <div>
                                <div class="day-name">{{ \Carbon\Carbon::parse($date)->format('l') }}</div>
                                <div class="day-date">{{ \Carbon\Carbon::parse($date)->format('M j, Y') }}</div>
                            </div>
                            <div class="day-summary">
                                <span class="day-summary-item pickup"><i class="fas fa-box"></i>{{ $events->where('type', 'pickup')->count() }}</span>
                                <span class="day-summary-item delivery"><i class="fas fa-flag-checkered"></i>{{ $events->where('type', 'delivery')->count() }}</span>
                                <span class="day-summary-item offline"><i class="fas fa-moon"></i>{{ $events->where('type', 'offline')->count() }}</span>
                            </div>
                        </div>
                        @foreach($events as $event)
                            <div class="timeline-event {{ $event['type'] }}" data-event-id="{{ $event['id'] }}" onclick="openEventModal({{ $event['id'] }})">
                                <div class="event-time">
                                    <span class="event-time-start">{{ \Carbon\Carbon::parse($event['start_time'])->format('g:i A') }}</span>
                                    <span class="event-time-end">{{ \Carbon\Carbon::parse($event['end_time'])->format('g:i A') }}</span>
                                </div>
                                <div class="event-content">
                                    <span class="event-type {{ $event['type'] }}">
                                        <i class="fas fa-{{ $event['icon'] ?? ($event['type'] === 'pickup' ? 'box' : ($event['type'] === 'delivery' ? 'flag-checkered' : 'clock')) }}"></i>
                                        {{ ucfirst($event['type']) }}
                                    </span>
                                    <div class="event-title">{{ $event['title'] }}</div>
                                    <div class="event-details">
                                        @if(isset($event['order_number']))
                                            <span class="event-detail"><i class="fas fa-hashtag"></i>#{{ $event['order_number'] }}</span>
                                        @endif
                                        @if(isset($event['client_name']))
                                            <span class="event-detail"><i class="fas fa-user"></i>{{ $event['client_name'] }}</span>
                                        @endif
                                        @if(isset($event['location']))
                                            <span class="event-detail"><i class="fas fa-map-marker-alt"></i>{{ $event['location'] }}</span>
                                        @endif
                                        @if(isset($event['earnings']))
                                            <span class="event-detail event-earnings"><i class="fas fa-money-bill-wave"></i>K{{ number_format($event['earnings'], 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="event-actions">
                                    @if($event['type'] === 'pickup' || $event['type'] === 'delivery')
                                        <button class="event-btn primary" onclick="event.stopPropagation(); navigateToOrder({{ $event['id'] }})">
                                            <i class="fas fa-external-link-alt"></i>View
                                        </button>
                                    @endif
                                    @if($event['type'] === 'availability')
                                        <button class="event-btn" onclick="event.stopPropagation(); editAvailability({{ $event['id'] }})">
                                            <i class="fas fa-edit"></i>Edit
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="schedule-empty">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No Scheduled Events</h3>
                    <p>Your schedule is clear. Set your availability or wait for order assignments.</p>
                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#availabilityModal">
                        <i class="fas fa-plus me-2"></i>Set Availability
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Availability Form -->
    <div class="availability-form" data-aos="fade-up" data-aos-delay="200">
        <div class="availability-form-header">
            <h3 class="availability-form-title"><i class="fas fa-clock me-2"></i>Weekly Availability</h3>
            <button type="button" class="btn btn-primary" onclick="saveAvailability()">
                <i class="fas fa-save me-1"></i>Save Schedule
            </button>
        </div>
        <form id="availabilityForm">
            <div class="day-availability">
                @foreach(['Monday' => 'monday', 'Tuesday' => 'tuesday', 'Wednesday' => 'wednesday', 'Thursday' => 'thursday', 'Friday' => 'friday', 'Saturday' => 'saturday', 'Sunday' => 'sunday'] as $dayName => $dayKey)
                    @php
                        $avail = $availability[$dayKey] ?? ['enabled' => false, 'start' => '08:00', 'end' => '18:00'];
                    @endphp
                    <div class="day-avail-item {{ $avail['enabled'] ? 'active' : '' }}" data-day="{{ $dayKey }}">
                        <div class="day-avail-header">
                            <span class="day-avail-name">{{ $dayName }}</span>
                            <label class="day-avail-toggle">
                                <input type="checkbox" name="days[{{ $dayKey }}][enabled]" {{ $avail['enabled'] ? 'checked' : '' }} onchange="toggleDayAvailability(this, '{{ $dayKey }}')">
                                <span class="day-avail-slider"></span>
                            </label>
                        </div>
                        <div class="day-avail-times {{ !$avail['enabled'] ? 'd-none' : '' }}" id="times_{{ $dayKey }}">
                            <div class="time-input-group">
                                <label>Start</label>
                                <input type="time" name="days[{{ $dayKey }}][start]" value="{{ $avail['start'] }}" {{ !$avail['enabled'] ? 'disabled' : '' }}>
                            </div>
                            <div class="time-input-group">
                                <label>End</label>
                                <input type="time" name="days[{{ $dayKey }}][end]" value="{{ $avail['end'] }}" {{ !$avail['enabled'] ? 'disabled' : '' }}>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
    </div>
</div>

<!-- Availability Modal -->
<div class="modal fade" id="availabilityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="modalAvailabilityForm">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-clock me-2"></i>Set Weekly Availability</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach(['Monday' => 'monday', 'Tuesday' => 'tuesday', 'Wednesday' => 'wednesday', 'Thursday' => 'thursday', 'Friday' => 'friday', 'Saturday' => 'saturday', 'Sunday' => 'sunday'] as $dayName => $dayKey)
                            @php
                                $avail = $availability[$dayKey] ?? ['enabled' => false, 'start' => '08:00', 'end' => '18:00'];
                            @endphp
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <h6 class="mb-0">{{ $dayName }}</h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="days[{{ $dayKey }}][enabled]" id="modal_{{ $dayKey }}_enabled" {{ $avail['enabled'] ? 'checked' : '' }} onchange="toggleModalDay('{{ $dayKey }}')">
                                            </div>
                                        </div>
                                        <div id="modal_times_{{ $dayKey }}" class="{{ !$avail['enabled'] ? 'd-none' : '' }}">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="form-label form-label-sm">Start</label>
                                                    <input type="time" class="form-control form-control-sm" name="days[{{ $dayKey }}][start]" value="{{ $avail['start'] }}" {{ !$avail['enabled'] ? 'disabled' : '' }}>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label form-label-sm">End</label>
                                                    <input type="time" class="form-control form-control-sm" name="days[{{ $dayKey }}][end]" value="{{ $avail['end'] }}" {{ !$avail['enabled'] ? 'disabled' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        When you're available, you'll receive order requests during those hours. Orders outside your availability won't be offered to you.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Availability</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Event Detail Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalTitle">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="eventModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
            <div class="modal-footer" id="eventModalFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
// Schedule Data
const scheduleEvents = @json($calendarEvents ?? []);
const timelineEvents = @json($timelineEvents ?? []);
const driverAvailability = @json($availability ?? {});

// State
let currentView = 'calendar';
let calendar = null;
let currentDate = new Date();
let currentWeekStart = getWeekStart(new Date());

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    initCalendar();
    initWeekView();
    updateCalendarHeader();
    updateWeekHeader();
    renderTimeline();
});

// View Switching
function switchView(view) {
    currentView = view;
    
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.view === view);
    });
    
    document.getElementById('calendarView').classList.toggle('d-none', view !== 'calendar');
    document.getElementById('weekView').classList.toggle('d-none', view !== 'week');
    document.getElementById('timelineView').classList.toggle('d-none', view !== 'timeline');
    
    if (view === 'calendar') {
        calendar?.render();
    } else if (view === 'week') {
        renderWeekView();
    } else if (view === 'timeline') {
        renderTimeline();
    }
}

// Calendar View
function initCalendar() {
    const calendarEl = document.getElementById('calendar');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: false,
        height: 'auto',
        events: scheduleEvents.map(e => ({
            id: e.id,
            title: e.title,
            start: e.start_time,
            end: e.end_time,
            classNames: ['fc-event', e.type],
            extendedProps: e
        })),
        eventClick: function(info) {
            openEventModal(info.event.id);
        },
        eventDidMount: function(info) {
            info.el.setAttribute('data-event-id', info.event.id);
        },
        dayCellDidMount: function(info) {
            if (info.date.toDateString() === new Date().toDateString()) {
                info.el.classList.add('fc-day-today');
            }
        },
        datesSet: function(info) {
            currentDate = info.start;
            updateCalendarHeader();
        }
    });
    
    calendar.render();
}

function updateCalendarHeader() {
    const monthName = currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    document.getElementById('calendarMonth').textContent = monthName;
}

function calendarPrev() {
    calendar.prev();
}

function calendarNext() {
    calendar.next();
}

function goToToday() {
    currentDate = new Date();
    currentWeekStart = getWeekStart(new Date());
    calendar?.gotoDate(currentDate);
    updateCalendarHeader();
    updateWeekHeader();
    renderWeekView();
    renderTimeline();
}

// Week View
function getWeekStart(date) {
    const d = new Date(date);
    const day = d.getDay();
    const diff = d.getDate() - day + (day === 0 ? -6 : 1); // Monday start
    d.setDate(diff);
    d.setHours(0, 0, 0, 0);
    return d;
}

function getWeekEnd(weekStart) {
    const d = new Date(weekStart);
    d.setDate(d.getDate() + 6);
    d.setHours(23, 59, 59, 999);
    return d;
}

function initWeekView() {
    renderWeekView();
}

function updateWeekHeader() {
    const weekEnd = getWeekEnd(currentWeekStart);
    const startStr = currentWeekStart.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    const endStr = weekEnd.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    document.getElementById('weekRange').textContent = `${startStr} - ${endStr}`;
}

function renderWeekView() {
    const container = document.getElementById('weekCalendar');
    const weekEnd = getWeekEnd(currentWeekStart);
    
    let html = '';
    
    for (let i = 0; i < 7; i++) {
        const day = new Date(currentWeekStart);
        day.setDate(day.getDate() + i);
        
        const isToday = day.toDateString() === new Date().toDateString();
        const dayName = day.toLocaleDateString('en-US', { weekday: 'short' });
        const dayDate = day.getDate();
        
        // Filter events for this day
        const dayEvents = scheduleEvents.filter(e => {
            const eventStart = new Date(e.start_time);
            return eventStart.toDateString() === day.toDateString();
        });
        
        html += `
            <div class="week-day ${isToday ? 'today' : ''}" data-date="${day.toISOString().split('T')[0]}">
                <div class="week-day-header">
                    <div class="week-day-name">${dayName}</div>
                    <div class="week-day-date">${dayDate}</div>
                </div>
                <div class="week-day-events">
        `;
        
        if (dayEvents.length > 0) {
            dayEvents.forEach(event => {
                const startTime = new Date(event.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                const endTime = new Date(event.end_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                html += `
                    <div class="week-event ${event.type}" onclick="openEventModal(${event.id})">
                        <div class="week-event-time">${startTime} - ${endTime}</div>
                        <div class="week-event-title">${event.title}</div>
                    </div>
                `;
            });
        } else {
            html += '<div class="text-center text-muted small py-4">No events</div>';
        }
        
        html += '</div></div>';
    }
    
    container.innerHTML = html;
}

function weekPrev() {
    currentWeekStart.setDate(currentWeekStart.getDate() - 7);
    updateWeekHeader();
    renderWeekView();
}

function weekNext() {
    currentWeekStart.setDate(currentWeekStart.getDate() + 7);
    updateWeekHeader();
    renderWeekView();
}

// Timeline View
function renderTimeline() {
    const container = document.getElementById('timelineList');
    const filter = document.querySelector('.filter-chip.active')?.dataset.filter || 'all';
    
    let filteredEvents = timelineEvents;
    if (filter !== 'all') {
        filteredEvents = timelineEvents.filter(e => e.type === filter);
    }
    
    if (filteredEvents.length === 0) {
        container.innerHTML = `
            <div class="schedule-empty">
                <i class="fas fa-calendar-times"></i>
                <h3>No Events Found</h3>
                <p>No events match the current filter.</p>
            </div>
        `;
        return;
    }
    
    // Group by date
    const grouped = {};
    filteredEvents.forEach(event => {
        const date = event.date;
        if (!grouped[date]) grouped[date] = [];
        grouped[date].push(event);
    });
    
    let html = '';
    Object.keys(grouped).sort().forEach(date => {
        const events = grouped[date];
        const dayDate = new Date(date);
        
        html += `
            <div class="timeline-day" data-date="${date}">
                <div class="day-header">
                    <div>
                        <div class="day-name">${dayDate.toLocaleDateString('en-US', { weekday: 'long' })}</div>
                        <div class="day-date">${dayDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</div>
                    </div>
                    <div class="day-summary">
                        <span class="day-summary-item pickup"><i class="fas fa-box"></i>${events.filter(e => e.type === 'pickup').length}</span>
                        <span class="day-summary-item delivery"><i class="fas fa-flag-checkered"></i>${events.filter(e => e.type === 'delivery').length}</span>
                        <span class="day-summary-item offline"><i class="fas fa-moon"></i>${events.filter(e => e.type === 'offline').length}</span>
                    </div>
                </div>
        `;
        
        events.forEach(event => {
            html += `
                <div class="timeline-event ${event.type}" data-event-id="${event.id}" onclick="openEventModal(${event.id})">
                    <div class="event-time">
                        <span class="event-time-start">${event.start_time}</span>
                        <span class="event-time-end">${event.end_time}</span>
                    </div>
                    <div class="event-content">
                        <span class="event-type ${event.type}">
                            <i class="fas fa-${event.icon || (event.type === 'pickup' ? 'box' : (event.type === 'delivery' ? 'flag-checkered' : 'clock'))}"></i>
                            ${event.type.charAt(0).toUpperCase() + event.type.slice(1)}
                        </span>
                        <div class="event-title">${event.title}</div>
                        <div class="event-details">
                            ${event.order_number ? `<span class="event-detail"><i class="fas fa-hashtag"></i>#${event.order_number}</span>` : ''}
                            ${event.client_name ? `<span class="event-detail"><i class="fas fa-user"></i>${event.client_name}</span>` : ''}
                            ${event.location ? `<span class="event-detail"><i class="fas fa-map-marker-alt"></i>${event.location}</span>` : ''}
                            ${event.earnings ? `<span class="event-detail event-earnings"><i class="fas fa-money-bill-wave"></i>K${event.earnings.toFixed(2)}</span>` : ''}
                        </div>
                    </div>
                    <div class="event-actions">
                        ${(event.type === 'pickup' || event.type === 'delivery') ? `
                            <button class="event-btn primary" onclick="event.stopPropagation(); navigateToOrder(${event.id})">
                                <i class="fas fa-external-link-alt"></i>View
                            </button>
                        ` : ''}
                        ${event.type === 'availability' ? `
                            <button class="event-btn" onclick="event.stopPropagation(); editAvailability(${event.id})">
                                <i class="fas fa-edit"></i>Edit
                            </button>
                        ` : ''}
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
    });
    
    container.innerHTML = html;
}

function filterTimeline(filter) {
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.classList.toggle('active', chip.dataset.filter === filter);
    });
    renderTimeline();
}

// Event Modal
function openEventModal(eventId) {
    const event = [...scheduleEvents, ...timelineEvents].find(e => e.id == eventId);
    if (!event) return;
    
    const modal = new bootstrap.Modal(document.getElementById('eventModal'));
    document.getElementById('eventModalTitle').textContent = event.title;
    
    let bodyHtml = `
        <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-gray-50 rounded-lg">
            <div class="event-type ${event.type}" style="font-size: var(--font-size-sm); padding: var(--space-2) var(--space-3);">
                <i class="fas fa-${event.icon || (event.type === 'pickup' ? 'box' : (event.type === 'delivery' ? 'flag-checkered' : 'clock'))}"></i>
                ${event.type.charAt(0).toUpperCase() + event.type.slice(1)}
            </div>
        </div>
        <div class="row g-3">
            <div class="col-6">
                <small class="text-muted d-block">Start Time</small>
                <strong>${new Date(event.start_time).toLocaleString()}</strong>
            </div>
            <div class="col-6">
                <small class="text-muted d-block">End Time</small>
                <strong>${new Date(event.end_time).toLocaleString()}</strong>
            </div>
    `;
    
    if (event.order_number) {
        bodyHtml += `
            <div class="col-6">
                <small class="text-muted d-block">Order</small>
                <strong>#${event.order_number}</strong>
            </div>
        `;
    }
    
    if (event.client_name) {
        bodyHtml += `
            <div class="col-6">
                <small class="text-muted d-block">Client</small>
                <strong>${event.client_name}</strong>
            </div>
        `;
    }
    
    if (event.location) {
        bodyHtml += `
            <div class="col-12">
                <small class="text-muted d-block">Location</small>
                <strong>${event.location}</strong>
            </div>
        `;
    }
    
    if (event.earnings) {
        bodyHtml += `
            <div class="col-6">
                <small class="text-muted d-block">Earnings</small>
                <strong class="text-success">K${event.earnings.toFixed(2)}</strong>
            </div>
        `;
    }
    
    if (event.distance_km) {
        bodyHtml += `
            <div class="col-6">
                <small class="text-muted d-block">Distance</small>
                <strong>${event.distance_km} km</strong>
            </div>
        `;
    }
    
    if (event.notes) {
        bodyHtml += `
            <div class="col-12">
                <small class="text-muted d-block">Notes</small>
                <p class="mb-0">${event.notes}</p>
            </div>
        `;
    }
    
    bodyHtml += '</div>';
    
    document.getElementById('eventModalBody').innerHTML = bodyHtml;
    
    // Footer actions
    let footerHtml = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
    
    if (event.type === 'pickup' || event.type === 'delivery') {
        footerHtml = `
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="navigateToOrder(${event.id}); bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();">
                <i class="fas fa-external-link-alt me-1"></i>View Order
            </button>
            <button type="button" class="btn btn-outline-primary" onclick="openMaps('${event.location}')">
                <i class="fas fa-map-marker-alt me-1"></i>Navigate
            </button>
        `;
    }
    
    document.getElementById('eventModalFooter').innerHTML = footerHtml;
    
    modal.show();
}

function navigateToOrder(eventId) {
    const event = [...scheduleEvents, ...timelineEvents].find(e => e.id == eventId);
    if (event && event.order_id) {
        window.location.href = '{{ route('driver.orders.show', ':id') }}'.replace(':id', event.order_id);
    }
}

function editAvailability(eventId) {
    const event = timelineEvents.find(e => e.id == eventId);
    if (event) {
        bootstrap.Modal.getInstance(document.getElementById('eventModal'))?.hide();
        const modal = new bootstrap.Modal(document.getElementById('availabilityModal'));
        modal.show();
    }
}

function openMaps(address) {
    const encoded = encodeURIComponent(address);
    if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
        window.open('maps://?q=' + encoded);
        setTimeout(() => window.open('https://www.google.com/maps/search/?api=1&query=' + encoded, '_blank'), 500);
    } else {
        window.open('geo:0,0?q=' + encoded);
    }
}

// Availability Form
function toggleDayAvailability(checkbox, day) {
    const timesDiv = document.getElementById('times_' + day);
    if (timesDiv) {
        timesDiv.classList.toggle('d-none', !checkbox.checked);
        timesDiv.querySelectorAll('input').forEach(input => input.disabled = !checkbox.checked);
    }
    
    const dayItem = checkbox.closest('.day-avail-item');
    if (dayItem) {
        dayItem.classList.toggle('active', checkbox.checked);
    }
}

function toggleModalDay(day) {
    const checkbox = document.getElementById('modal_' + day + '_enabled');
    const timesDiv = document.getElementById('modal_times_' + day);
    if (timesDiv && checkbox) {
        timesDiv.classList.toggle('d-none', !checkbox.checked);
        timesDiv.querySelectorAll('input').forEach(input => input.disabled = !checkbox.checked);
    }
}

function saveAvailability() {
    const form = document.getElementById('availabilityForm');
    const formData = new FormData(form);
    const data = {};
    
    formData.forEach((value, key) => {
        data[key] = value;
    });
    
    // Convert to structured format
    const availability = {};
    ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(day => {
        availability[day] = {
            enabled: !!data[`days[${day}][enabled]`],
            start: data[`days[${day}][start]`] || '08:00',
            end: data[`days[${day}][end]`] || '18:00'
        };
    });
    
    fetch('{{ route('driver.availability.update') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ availability })
    })
    .then(r => r.json())
    .then(response => {
        if (response.success) {
            window.toastManager.success('Availability saved!');
            bootstrap.Modal.getInstance(document.getElementById('availabilityModal'))?.hide();
            setTimeout(() => location.reload(), 500);
        } else {
            window.toastManager.error(response.message || 'Failed to save');
        }
    })
    .catch(() => window.toastManager.error('Failed to save'));
}

// Modal form submit
document.getElementById('modalAvailabilityForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    saveAvailability();
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Arrow keys for navigation
    if (e.key === 'ArrowLeft' && !e.ctrlKey && !e.metaKey) {
        if (currentView === 'calendar') calendarPrev();
        else if (currentView === 'week') weekPrev();
    }
    if (e.key === 'ArrowRight' && !e.ctrlKey && !e.metaKey) {
        if (currentView === 'calendar') calendarNext();
        else if (currentView === 'week') weekNext();
    }
    // T for today
    if (e.key === 't' && !e.ctrlKey && !e.metaKey && document.activeElement.tagName !== 'INPUT') {
        goToToday();
    }
    // V for view cycle
    if (e.key === 'v' && !e.ctrlKey && !e.metaKey && document.activeElement.tagName !== 'INPUT') {
        const views = ['calendar', 'week', 'timeline'];
        const currentIndex = views.indexOf(currentView);
        const nextView = views[(currentIndex + 1) % views.length];
        switchView(nextView);
    }
    // A for availability
    if (e.key === 'a' && !e.ctrlKey && !e.metaKey && document.activeElement.tagName !== 'INPUT') {
        const modal = new bootstrap.Modal(document.getElementById('availabilityModal'));
        modal.show();
    }
});

// Touch swipe for calendar/week
let touchStartX = 0;
document.getElementById('calendar')?.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
document.getElementById('calendar')?.addEventListener('touchend', e => {
    const diff = e.changedTouches[0].screenX - touchStartX;
    if (Math.abs(diff) > 50) {
        diff > 0 ? calendarPrev() : calendarNext();
    }
}, { passive: true });

document.getElementById('weekCalendar')?.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
document.getElementById('weekCalendar')?.addEventListener('touchend', e => {
    const diff = e.changedTouches[0].screenX - touchStartX;
    if (Math.abs(diff) > 50) {
        diff > 0 ? weekPrev() : weekNext();
    }
}, { passive: true });

// Offline support - cache schedule data
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.ready.then(reg => {
        // Schedule data is cached for offline viewing
    });
}

// Auto-refresh timeline every 2 minutes
setInterval(() => {
    if (currentView === 'timeline' && document.visibilityState === 'visible') {
        fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTimeline = doc.getElementById('timelineList')?.innerHTML;
                if (newTimeline) {
                    document.getElementById('timelineList').innerHTML = newTimeline;
                }
            })
            .catch(console.error);
    }
}, 120000);
</script>
@endpush
@endsection