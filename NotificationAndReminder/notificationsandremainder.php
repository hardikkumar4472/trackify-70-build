<?php
session_start();

if (!isset($_SESSION['data'])) {
    $_SESSION['data'] = [
        'reminders' => [],
        'metrics' => [],
        'goals' => [],
        'completedReminders' => []
    ];
}

$data = &$_SESSION['data'];

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : null;

// Handle API requests
if ($action) {
    header('Content-Type: application/json');

    if ($method === 'GET') {
        if ($action === 'getReminders') {
            echo json_encode($data['reminders']);
        } elseif ($action === 'getMetrics') {
            echo json_encode($data['metrics']);
        } elseif ($action === 'getGoals') {
            echo json_encode($data['goals']);
        } elseif ($action === 'getCompletedReminders') {
            echo json_encode($data['completedReminders']);
        } else {
            echo json_encode(['error' => 'Invalid action']);
        }
        exit;
    }

    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        if ($action === 'addReminder') {
            $newReminder = [
                'id' => uniqid(),
                'habitName' => $input['habitName'] ?? 'Untitled',
                'reminderTime' => $input['reminderTime'] ?? '00:00 AM',
                'frequency' => $input['frequency'] ?? 'daily',
                'selectedDays' => $input['selectedDays'] ?? [],
                'category' => $input['category'] ?? 'other',
                'notes' => $input['notes'] ?? '',
                'priority' => $input['priority'] ?? 'low',
                'createdAt' => date('Y-m-d H:i:s'),
                'active' => true
            ];
            $data['reminders'][] = $newReminder;
            echo json_encode(['message' => 'Reminder added successfully', 'reminder' => $newReminder]);
        } elseif ($action === 'addMetric') {
            $newMetric = [
                'id' => uniqid(),
                'date' => $input['date'] ?? date('Y-m-d'),
                'weight' => $input['weight'] ?? null,
                'steps' => $input['steps'] ?? null,
                'water' => $input['water'] ?? null,
                'sleepHours' => $input['sleepHours'] ?? null,
                'sleepMinutes' => $input['sleepMinutes'] ?? null,
                'mood' => $input['mood'] ?? null,
                'notes' => $input['notes'] ?? ''
            ];
            $data['metrics'][] = $newMetric;
            echo json_encode(['message' => 'Metric added successfully', 'metric' => $newMetric]);
        } elseif ($action === 'addGoal') {
            $newGoal = [
                'id' => uniqid(),
                'type' => $input['type'] ?? 'general',
                'target' => $input['target'] ?? null,
                'deadline' => $input['deadline'] ?? null,
                'notes' => $input['notes'] ?? '',
                'createdAt' => date('Y-m-d H:i:s')
            ];
            $data['goals'][] = $newGoal;
            echo json_encode(['message' => 'Goal added successfully', 'goal' => $newGoal]);
        } else {
            echo json_encode(['error' => 'Invalid action']);
        }
        exit;
    }

    if ($method === 'DELETE') {
        parse_str(file_get_contents('php://input'), $input);

        if ($action === 'deleteReminder') {
            $id = $input['id'] ?? null;
            if ($id) {
                $data['reminders'] = array_filter($data['reminders'], function ($reminder) use ($id) {
                    return $reminder['id'] !== $id;
                });
                echo json_encode(['message' => 'Reminder deleted successfully']);
            } else {
                echo json_encode(['error' => 'Reminder ID is required']);
            }
        } elseif ($action === 'deleteMetric') {
            $id = $input['id'] ?? null;
            if ($id) {
                $data['metrics'] = array_filter($data['metrics'], function ($metric) use ($id) {
                    return $metric['id'] !== $id;
                });
                echo json_encode(['message' => 'Metric deleted successfully']);
            } else {
                echo json_encode(['error' => 'Metric ID is required']);
            }
        } else {
            echo json_encode(['error' => 'Invalid action']);
        }
        exit;
    }

    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Health Habit Tracker</title>
  <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">
  <style>
    /* Base Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
    }
    
    body {
      background-color: #111827; 
      min-height: 100vh;
      padding: 2rem 1rem;
      color: #f97316; 
      line-height: 1.5;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr;
      gap: 2rem;
    }
    
    @media (min-width: 768px) {
      .container {
        grid-template-columns: 1fr 1fr;
      }
    }
    
    .card {
      background-color: #1f2937; /* Darker background for cards */
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      padding: 1.5rem;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
    }
    
    /* Header Styles */
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    
    h1, h2, h3, h4, h5, h6 {
      font-family: 'Abril Fatface', cursive;
      color: #22c55e; /* Tailwind green-500 */
    }
    
    h1 {
      font-size: 1.5rem;
    }
    
    h2 {
      font-size: 1.25rem;
      margin-bottom: 1rem;
    }
    
    /* Button Styles */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      border: none;
      outline: none;
      color: #fff;
    }
    
    .btn-primary {
      background-color: #22c55e; /* Green accent */
      box-shadow: 0 4px 6px rgba(34, 197, 94, 0.25);
    }
    
    .btn-primary:hover {
      background-color: #16a34a;
      box-shadow: 0 6px 10px rgba(34, 197, 94, 0.3);
    }
    
    .btn-secondary {
      background-color: #f97316; /* Orange accent */
      box-shadow: 0 4px 6px rgba(249, 115, 22, 0.25);
    }
    
    .btn-secondary:hover {
      background-color: #ea580c;
      box-shadow: 0 6px 10px rgba(249, 115, 22, 0.3);
    }
    
    .btn-danger {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white;
    }
    
    .btn-danger:hover {
      background: linear-gradient(135deg, #dc2626, #b91c1c);
    }
    
    .btn-success {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
    }
    
    .btn-success:hover {
      background: linear-gradient(135deg, #059669, #047857);
    }
    
    .btn-3d {
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06);
      transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .btn-3d:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15), 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .btn-3d:active {
      transform: translateY(0);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06);
    }
    
    .btn-icon {
      width: 2.5rem;
      height: 2.5rem;
      padding: 0;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    
    .btn-full {
      width: 100%;
      padding: 0.75rem;
    }
    
    /* Form Styles */
    .form-group {
      margin-bottom: 1.25rem;
    }
    
    label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: #f97316; /* Orange accent */
    }
    
    input[type="text"],
    input[type="number"],
    select,
    textarea {
      width: 100%;
      padding: 0.75rem;
      background-color: #374151;
      border: 1px solid #4b5563;
      border-radius: 0.5rem;
      font-family: inherit;
      font-size: 0.875rem;
      color: #f97316;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    
    input[type="text"]:focus,
    input[type="number"]:focus,
    select:focus,
    textarea:focus {
      outline: none;
      border-color: #22c55e;
      box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
    }
    
    /* Rotary Time Picker */
    .rotary-picker {
      display: flex;
      gap: 1rem;
      justify-content: center;
      align-items: center;
      margin: 1rem 0;
    }
    
    .picker-column {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    
    .picker-button {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: linear-gradient(135deg, #3b82f6, #6366f1);
      color: white;
      font-size: 16px;
      font-weight: bold;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06);
      transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .picker-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15), 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .picker-button:active {
      transform: translateY(0);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06);
    }
    
    .picker-button.selected {
      background: linear-gradient(135deg, #ec4899, #f43f5e);
      box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15), 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .picker-column span {
      margin-top: 0.5rem;
      font-size: 14px;
      font-weight: 500;
      color: #6b7280;
    }
    
    /* Custom Days Selection */
    .days-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 0.5rem;
      margin-top: 0.5rem;
    }
    
    .day-checkbox {
      display: none;
    }
    
    .day-label {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0.5rem;
      background-color: #f3f4f6;
      border-radius: 0.5rem;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 0.875rem;
    }
    
    .day-checkbox:checked + .day-label {
      background: linear-gradient(135deg, #3b82f6, #6366f1);
      color: white;
      box-shadow: 0 4px 6px rgba(59, 130, 246, 0.25);
    }
    
    /* Reminder List */
    .reminder-list {
      list-style: none;
      margin-top: 1rem;
    }
    
    .reminder-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem;
      background-color: #f9fafb;
      border-radius: 0.5rem;
      margin-bottom: 0.5rem;
      transition: background-color 0.3s ease;
    }
    
    .reminder-item:hover {
      background-color: #f3f4f6;
    }
    
    .reminder-info {
      flex: 1;
    }
    
    .reminder-name {
      font-weight: 600;
      color: #1f2937;
    }
    
    .reminder-time {
      font-size: 0.875rem;
      color: #6b7280;
    }
    
    .reminder-actions {
      display: flex;
      gap: 0.5rem;
    }
    
    /* Notification */
    .notification {
      background-color: #1f2937;
      border-radius: 0.5rem;
      padding: 1rem;
      margin-bottom: 0.75rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      display: flex;
      justify-content: space-between;
      align-items: center;
      animation: fadeIn 0.5s ease-in-out;
      border-left: 4px solid #22c55e;
    }
    
    .notification-content {
      flex: 1;
    }
    
    .notification-title {
      font-weight: 600;
      color: #f97316;
    }
    
    .notification-time {
      font-size: 0.75rem;
      color: #9ca3af;
    }
    
    .notification-actions {
      display: flex;
      gap: 0.5rem;
    }
    
    /* Tabs */
    .tabs {
      display: flex;
      border-bottom: 1px solid #e5e7eb;
      margin-bottom: 1.5rem;
    }
    
    .tab {
      padding: 0.75rem 1rem;
      cursor: pointer;
      border-bottom: 2px solid transparent;
      transition: all 0.3s ease;
      font-weight: 500;
    }
    
    .tab.active {
      border-bottom-color: #6366f1;
      color: #6366f1;
    }
    
    .tab-content {
      display: none;
    }
    
    .tab-content.active {
      display: block;
    }
    
    /* Progress Bar */
    .progress-container {
      margin: 1.5rem 0;
    }
    
    .progress-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.5rem;
    }
    
    .progress-bar {
      height: 0.5rem;
      background-color: #374151;
      border-radius: 9999px;
      overflow: hidden;
    }
    
    .progress-fill {
      height: 100%;
      background-color: #22c55e;
      border-radius: 9999px;
      transition: width 0.5s ease;
    }
    
    /* Stats */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
      margin-bottom: 1.5rem;
    }
    
    .stat-card {
      background-color: #374151;
      border-radius: 0.5rem;
      padding: 1rem;
      text-align: center;
      color: #f97316;
    }
    
    .stat-value {
      font-size: 1.5rem;
      font-weight: 700;
    }
    
    .stat-label {
      font-size: 0.875rem;
      color: #9ca3af;
    }
    
    /* Calendar */
    .calendar {
      margin-bottom: 1.5rem;
    }
    
    .calendar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.75rem;
    }
    
    .calendar-title {
      font-weight: 600;
    }
    
    .calendar-nav {
      display: flex;
      gap: 0.5rem;
    }
    
    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 0.25rem;
    }
    
    .calendar-day-header {
      text-align: center;
      font-weight: 500;
      font-size: 0.75rem;
      color: #6b7280;
      padding: 0.25rem;
    }
    
    .calendar-day {
      aspect-ratio: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 0.25rem;
      cursor: pointer;
      position: relative;
      font-size: 0.875rem;
      color: #f97316;
      background-color: #374151;
    }
    
    .calendar-day:hover {
      background-color: #4b5563;
    }
    
    .calendar-day.today {
      background-color: #22c55e;
      color: white;
    }
    
    .calendar-day.has-reminder::after {
      content: '';
      position: absolute;
      bottom: 0.25rem;
      width: 0.25rem;
      height: 0.25rem;
      background-color: #6366f1;
      border-radius: 50%;
    }
    
    .calendar-day.selected {
      background-color: #f97316;
      color: white;
    }
    
    .calendar-day.other-month {
      color: #6b7280;
    }
    
    /* Settings */
    .settings-section {
      margin-bottom: 1.5rem;
    }
    
    .settings-option {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem 0;
      border-bottom: 1px solid #e5e7eb;
    }
    
    .settings-option:last-child {
      border-bottom: none;
    }
    
    .settings-label {
      font-weight: 500;
    }
    
    .settings-description {
      font-size: 0.875rem;
      color: #6b7280;
      margin-top: 0.25rem;
    }
    
    /* Toggle Switch */
    .toggle-switch {
      position: relative;
      display: inline-block;
      width: 3rem;
      height: 1.5rem;
    }
    
    .toggle-switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }
    
    .toggle-slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #e5e7eb;
      transition: .4s;
      border-radius: 1.5rem;
    }
    
    .toggle-slider:before {
      position: absolute;
      content: "";
      height: 1.25rem;
      width: 1.25rem;
      left: 0.125rem;
      bottom: 0.125rem;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
    }
    
    input:checked + .toggle-slider {
      background-color: #6366f1;
    }
    
    input:checked + .toggle-slider:before {
      transform: translateX(1.5rem);
    }
    
    /* Category Pills */
    .category-pills {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }
    
    .category-pill {
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      background-color: #374151;
      color: #f97316;
    }
    
    .category-pill.active {
      background-color: #22c55e;
      color: white;
    }
    
    /* Health Metrics */
    .metrics-form {
      margin-top: 1rem;
    }
    
    .metric-row {
      display: flex;
      gap: 0.5rem;
      margin-bottom: 0.75rem;
    }
    
    .metric-input {
      flex: 1;
    }
    
    /* Charts */
    .chart-container {
      height: 200px;
      margin: 1rem 0;
      position: relative;
    }
    
    .chart-bar {
      position: absolute;
      bottom: 0;
      width: 2rem;
      background: linear-gradient(to top, #3b82f6, #6366f1);
      border-radius: 0.25rem 0.25rem 0 0;
      transition: height 0.5s ease;
    }
    
    .chart-label {
      position: absolute;
      bottom: -1.5rem;
      width: 2rem;
      text-align: center;
      font-size: 0.75rem;
      color: #6b7280;
    }
    
    /* Modal */
    .modal-backdrop {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    
    .modal-backdrop.active {
      opacity: 1;
      visibility: visible;
    }
    
    .modal {
      background-color: #1f2937;
      color: #f97316;
      border-radius: 0.75rem;
      width: 90%;
      max-width: 500px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      transform: scale(0.9);
      opacity: 0;
      transition: transform 0.3s ease, opacity 0.3s ease;
    }
    
    .modal-backdrop.active .modal {
      transform: scale(1);
      opacity: 1;
    }
    
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #374151;
    }
    
    .modal-title {
      font-weight: 600;
      font-size: 1.25rem;
    }
    
    .modal-close {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: #9ca3af;
    }
    
    .modal-body {
      padding: 1.5rem;
    }
    
    .modal-footer {
      display: flex;
      justify-content: flex-end;
      gap: 0.75rem;
      padding: 1rem 1.5rem;
      border-top: 1px solid #374151;
    }
    
    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeOut {
      from { opacity: 1; transform: translateY(0); }
      to { opacity: 0; transform: translateY(10px); }
    }
    
    .fade-in {
      animation: fadeIn 0.5s ease-in-out;
    }
    
    .fade-out {
      animation: fadeOut 0.5s ease-in-out;
    }
    
    /* Responsive */
    @media (max-width: 640px) {
      .days-grid {
        grid-template-columns: repeat(3, 1fr);
      }
      
      .stats-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Left Column -->
    <div class="card">
      <header>
        <h1>Health Habit Tracker</h1>
        <button id="settingsButton" class="btn btn-icon btn-3d">
          <svg xmlns="http://www.w3.org/2000/svg" style="color:#111827" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3"></circle>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
          </svg>
        </button>
      </header>
      
      <!-- Tabs -->
      <div class="tabs">
        <div class="tab active" data-tab="reminders">Reminders</div>
        <div class="tab" data-tab="metrics">Health Metrics</div>
        <div class="tab" data-tab="progress">Progress</div>
      </div>
      
      <!-- Reminders Tab -->
      <div id="remindersTab" class="tab-content active">
        <form id="reminderForm" class="form-group">
          <h2>Set a Health Habit Reminder</h2>
          
          <!-- Category Selection -->
          <div class="form-group">
            <label>Category:</label>
            <div class="category-pills">
              <div class="category-pill active" data-category="exercise" style="background-color: #e0f2fe; color: #0369a1;">Exercise</div>
              <div class="category-pill" data-category="nutrition" style="background-color: #dcfce7; color: #15803d;">Nutrition</div>
              <div class="category-pill" data-category="meditation" style="background-color: #f3e8ff; color: #7e22ce;">Meditation</div>
              <div class="category-pill" data-category="water" style="background-color: #dbeafe; color: #1d4ed8;">Water</div>
              <div class="category-pill" data-category="sleep" style="background-color: #fef3c7; color: #b45309;">Sleep</div>
              <div class="category-pill" data-category="medication" style="background-color: #fee2e2; color: #b91c1c;">Medication</div>
              <div class="category-pill" data-category="other" style="background-color: #f3f4f6; color: #4b5563;">Other</div>
            </div>
          </div>
          
          <!-- Habit Name -->
          <div class="form-group">
            <label for="habitName">Habit Name:</label>
            <input type="text" id="habitName" placeholder="Enter habit name" required>
          </div>
          
          <!-- Reminder Time -->
          <div class="form-group">
            <label>Reminder Time:</label>
            <div class="rotary-picker">
              <div class="picker-column">
                <div id="hourPicker" class="picker-button">12</div>
                <span>Hours</span>
              </div>
              <div class="picker-column">
                <div id="minutePicker" class="picker-button">00</div>
                <span>Minutes</span>
              </div>
              <div class="picker-column">
                <div id="ampmPicker" class="picker-button">AM</div>
                <span>AM/PM</span>
              </div>
            </div>
          </div>
          
          <!-- Frequency -->
          <div class="form-group">
            <label for="frequency">Frequency:</label>
            <select id="frequency" required>
              <option value="daily">Daily</option>
              <option value="weekly">Weekly</option>
              <option value="custom">Custom Days</option>
            </select>
          </div>
          
          <!-- Custom Days -->
          <div id="customDays" class="form-group" style="display: none;">
            <label>Select Days:</label>
            <div class="days-grid">
              <div>
                <input type="checkbox" id="monday" class="day-checkbox" value="Monday">
                <label for="monday" class="day-label">Mon</label>
              </div>
              <div>
                <input type="checkbox" id="tuesday" class="day-checkbox" value="Tuesday">
                <label for="tuesday" class="day-label">Tue</label>
              </div>
              <div>
                <input type="checkbox" id="wednesday" class="day-checkbox" value="Wednesday">
                <label for="wednesday" class="day-label">Wed</label>
              </div>
              <div>
                <input type="checkbox" id="thursday" class="day-checkbox" value="Thursday">
                <label for="thursday" class="day-label">Thu</label>
              </div>
              <div>
                <input type="checkbox" id="friday" class="day-checkbox" value="Friday">
                <label for="friday" class="day-label">Fri</label>
              </div>
              <div>
                <input type="checkbox" id="saturday" class="day-checkbox" value="Saturday">
                <label for="saturday" class="day-label">Sat</label>
              </div>
              <div>
                <input type="checkbox" id="sunday" class="day-checkbox" value="Sunday">
                <label for="sunday" class="day-label">Sun</label>
              </div>
            </div>
          </div>
          
          <!-- Reminder Notes -->
          <div class="form-group">
            <label for="reminderNotes">Notes (Optional):</label>
            <textarea id="reminderNotes" rows="2" placeholder="Add any additional details"></textarea>
          </div>
          
          <!-- Priority Level -->
          <div class="form-group">
            <label>Priority Level:</label>
            <div class="days-grid" style="grid-template-columns: repeat(3, 1fr);">
              <div>
                <input type="radio" id="lowPriority" name="priority" class="day-checkbox" value="low" checked>
                <label for="lowPriority" class="day-label" style="background-color: #dcfce7; color: #15803d;">Low</label>
              </div>
              <div>
                <input type="radio" id="mediumPriority" name="priority" class="day-checkbox" value="medium">
                <label for="mediumPriority" class="day-label" style="background-color: #fef3c7; color: #b45309;">Medium</label>
              </div>
              <div>
                <input type="radio" id="highPriority" name="priority" class="day-checkbox" value="high">
                <label for="highPriority" class="day-label" style="background-color: #fee2e2; color: #b91c1c;">High</label>
              </div>
            </div>
          </div>
          
          <!-- Submit Button -->
          <button type="submit" class="btn btn-primary btn-full btn-3d">
            Set Reminder
          </button>
        </form>
      </div>
      
      <!-- Health Metrics Tab -->
      <div id="metricsTab" class="tab-content">
        <h2>Track Your Health Metrics</h2>
        
        <div class="calendar">
          <div class="calendar-header">
            <div class="calendar-title" id="calendarTitle">May 2023</div>
            <div class="calendar-nav">
              <button id="prevMonth" class="btn btn-icon btn-3d">
                <svg xmlns="http://www.w3.org/2000/svg" style="color:#111827" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
              </button>
              <button id="nextMonth" class="btn btn-icon btn-3d">
                <svg xmlns="http://www.w3.org/2000/svg" style="color:#111827" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
              </button>
            </div>
          </div>
          
          <div class="calendar-grid" id="calendarGrid">
            <!-- Calendar will be generated here -->
          </div>
        </div>
        
        <form id="metricsForm" class="metrics-form">
          <div class="form-group">
            <label for="metricDate">Date:</label>
            <input type="date" id="metricDate" required>
          </div>
          
          <div class="form-group">
            <label>Weight:</label>
            <div class="metric-row">
              <input type="number" id="weightValue" placeholder="Enter weight" step="0.1" class="metric-input">
              <select id="weightUnit">
                <option value="kg">kg</option>
                <option value="lbs">lbs</option>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label>Steps:</label>
            <input type="number" id="stepsValue" placeholder="Enter steps">
          </div>
          
          <div class="form-group">
            <label>Water Intake:</label>
            <div class="metric-row">
              <input type="number" id="waterValue" placeholder="Enter amount" class="metric-input">
              <select id="waterUnit">
                <option value="ml">ml</option>
                <option value="oz">oz</option>
                <option value="cups">cups</option>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label>Sleep Duration:</label>
            <div class="metric-row">
              <input type="number" id="sleepHours" placeholder="Hours" min="0" max="24" class="metric-input">
              <input type="number" id="sleepMinutes" placeholder="Minutes" min="0" max="59" class="metric-input">
            </div>
          </div>
          
          <div class="form-group">
            <label>Mood:</label>
            <select id="moodValue">
              <option value="5">Excellent</option>
              <option value="4">Good</option>
              <option value="3">Average</option>
              <option value="2">Poor</option>
              <option value="1">Terrible</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="metricNotes">Notes:</label>
            <textarea id="metricNotes" rows="2" placeholder="Add any additional notes"></textarea>
          </div>
          
          <button type="submit" class="btn btn-primary btn-full btn-3d">
            Save Metrics
          </button>
        </form>
      </div>
      
      <!-- Progress Tab -->
      <div id="progressTab" class="tab-content">
        <h2>Your Health Progress</h2>
        
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-value" id="activeReminders">0</div>
            <div class="stat-label">Active Reminders</div>
          </div>
          <div class="stat-card">
            <div class="stat-value" id="completedReminders">0</div>
            <div class="stat-label">Completed</div>
          </div>
          <div class="stat-card">
            <div class="stat-value" id="streakCount">0</div>
            <div class="stat-label">Current Streak</div>
          </div>
          <div class="stat-card">
            <div class="stat-value" id="adherenceRate">0%</div>
            <div class="stat-label">Adherence Rate</div>
          </div>
        </div>
        
        <div class="form-group">
          <label>Select Metric:</label>
          <select id="chartMetric">
            <option value="weight">Weight</option>
            <option value="steps">Steps</option>
            <option value="water">Water Intake</option>
            <option value="sleep">Sleep Duration</option>
            <option value="mood">Mood</option>
          </select>
        </div>
        
        <div class="chart-container" id="metricsChart">
          <!-- Chart will be generated here -->
        </div>
        
        <div class="progress-container">
          <div class="progress-header">
            <span>Weekly Goal Progress</span>
            <span id="goalPercentage">0%</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" id="goalProgressFill" style="width: 0%"></div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Right Column -->
    <div class="card">
      <header>
        <h1>Upcoming Reminders</h1>
        <button id="addGoalButton" class="btn btn-icon btn-3d">
          <svg xmlns="http://www.w3.org/2000/svg" style="color:#111827" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
        </button>
      </header>
      
      <!-- Today's Date -->
      <div class="form-group">
        <div id="todayDate" class="text-center text-lg font-semibold"></div>
      </div>
      
      <!-- Reminders List -->
      <div class="form-group">
        <h2>Today's Reminders</h2>
        <ul id="todayReminders" class="reminder-list">
          <!-- Today's reminders will be added here -->
          <li class="text-center text-gray-500">No reminders for today.</li>
        </ul>
      </div>
      
      <div class="form-group">
        <h2>Upcoming Reminders</h2>
        <ul id="upcomingReminders" class="reminder-list">
          <!-- Upcoming reminders will be added here -->
          <li class="text-center text-gray-500">No upcoming reminders.</li>
        </ul>
      </div>
      
      <!-- Notifications Section -->
      <div class="form-group">
        <h2>Notifications</h2>
        <div id="notifications">
          <!-- Notifications will be added here -->
        </div>
      </div>
    </div>
  </div>
  
  <!-- Settings Modal -->
  <div id="settingsModal" class="modal-backdrop">
    <div class="modal">
      <div class="modal-header">
        <h2 class="modal-title">Settings</h2>
        <button class="modal-close" id="closeSettingsModal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="settings-section">
          <h3>Notification Settings</h3>
          
          <div class="settings-option">
            <div>
              <div class="settings-label">Enable Notifications</div>
              <div class="settings-description">Receive reminders for your habits</div>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" id="enableNotifications" checked>
              <span class="toggle-slider"></span>
            </label>
          </div>
          
          <div class="settings-option">
            <div>
              <div class="settings-label">Sound Alerts</div>
              <div class="settings-description">Play sound when notifications appear</div>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" id="enableSoundAlerts" checked>
              <span class="toggle-slider"></span>
            </label>
          </div>
          
          <div class="settings-option">
            <div>
              <div class="settings-label">Notification Time</div>
              <div class="settings-description">Minutes before scheduled time</div>
            </div>
            <select id="notificationTime">
              <option value="0">At scheduled time</option>
              <option value="5">5 minutes before</option>
              <option value="10">10 minutes before</option>
              <option value="15">15 minutes before</option>
              <option value="30">30 minutes before</option>
            </select>
          </div>
        </div>
        
        <div class="settings-section">
          <h3>Data Management</h3>
          
          <div class="settings-option">
            <div>
              <div class="settings-label">Export Data</div>
              <div class="settings-description">Download your health data</div>
            </div>
            <button id="exportDataBtn" class="btn btn-primary btn-3d">Export</button>
          </div>
          
          <div class="settings-option">
            <div>
              <div class="settings-label">Import Data</div>
              <div class="settings-description">Upload previously exported data</div>
            </div>
            <input type="file" id="importDataInput" style="display: none;" accept=".json">
            <button id="importDataBtn" class="btn btn-primary btn-3d">Import</button>
          </div>
          
          <div class="settings-option">
            <div>
              <div class="settings-label">Clear All Data</div>
              <div class="settings-description">Delete all reminders and metrics</div>
            </div>
            <button id="clearDataBtn" class="btn btn-danger btn-3d">Clear</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Add Goal Modal -->
  <div id="goalModal" class="modal-backdrop">
    <div class="modal">
      <div class="modal-header">
        <h2 class="modal-title">Set Health Goal</h2>
        <button class="modal-close" id="closeGoalModal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="goalForm">
          <div class="form-group">
            <label for="goalType">Goal Type:</label>
            <select id="goalType" required>
              <option value="weight">Weight</option>
              <option value="steps">Daily Steps</option>
              <option value="water">Water Intake</option>
              <option value="sleep">Sleep Duration</option>
              <option value="exercise">Exercise Frequency</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="goalTarget">Target Value:</label>
            <input type="number" id="goalTarget" required>
          </div>
          
          <div class="form-group">
            <label for="goalDeadline">Target Date:</label>
            <input type="date" id="goalDeadline" required>
          </div>
          
          <div class="form-group">
            <label for="goalNotes">Notes:</label>
            <textarea id="goalNotes" rows="2"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button id="cancelGoalBtn" class="btn btn-3d">Cancel</button>
        <button id="saveGoalBtn" class="btn btn-primary btn-3d">Save Goal</button>
      </div>
    </div>
  </div>
  
  <!-- Confirmation Modal -->
  <div id="confirmationModal" class="modal-backdrop">
    <div class="modal">
      <div class="modal-header">
        <h2 class="modal-title">Confirmation</h2>
        <button class="modal-close" id="closeConfirmationModal">&times;</button>
      </div>
      <div class="modal-body">
        <p id="confirmationMessage">Are you sure you want to proceed?</p>
      </div>
      <div class="modal-footer">
        <button id="cancelConfirmationBtn" class="btn btn-3d">Cancel</button>
        <button id="confirmActionBtn" class="btn btn-danger btn-3d">Confirm</button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // State Management
      let reminders = JSON.parse(localStorage.getItem('reminders')) || [];
      let metrics = JSON.parse(localStorage.getItem('metrics')) || [];
      let goals = JSON.parse(localStorage.getItem('goals')) || [];
      let completedReminders = JSON.parse(localStorage.getItem('completedReminders')) || [];
      
      // Settings
      let settings = JSON.parse(localStorage.getItem('settings')) || {
        enableNotifications: true,
        enableSoundAlerts: true,
        notificationTime: 0
      };
      
      // Time Picker State
      let selectedHour = '12';
      let selectedMinute = '00';
      let selectedAmPm = 'AM';
      
      // Calendar State
      let currentDate = new Date();
      let selectedDate = new Date();
      let currentMonth = currentDate.getMonth();
      let currentYear = currentDate.getFullYear();
      
      // Selected Category
      let selectedCategory = 'exercise';
      
      // DOM Elements
      const reminderForm = document.getElementById('reminderForm');
      const frequencySelect = document.getElementById('frequency');
      const customDaysDiv = document.getElementById('customDays');
      const todayRemindersEl = document.getElementById('todayReminders');
      const upcomingRemindersEl = document.getElementById('upcomingReminders');
      const notificationsDiv = document.getElementById('notifications');
      const todayDateEl = document.getElementById('todayDate');
      
      // Rotary Time Picker Elements
      const hourPicker = document.getElementById('hourPicker');
      const minutePicker = document.getElementById('minutePicker');
      const ampmPicker = document.getElementById('ampmPicker');
      
      // Tab Elements
      const tabs = document.querySelectorAll('.tab');
      const tabContents = document.querySelectorAll('.tab-content');
      
      // Calendar Elements
      const calendarTitle = document.getElementById('calendarTitle');
      const calendarGrid = document.getElementById('calendarGrid');
      const prevMonthBtn = document.getElementById('prevMonth');
      const nextMonthBtn = document.getElementById('nextMonth');
      
      // Stats Elements
      const activeRemindersEl = document.getElementById('activeReminders');
      const completedRemindersEl = document.getElementById('completedReminders');
      const streakCountEl = document.getElementById('streakCount');
      const adherenceRateEl = document.getElementById('adherenceRate');
      const goalPercentageEl = document.getElementById('goalPercentage');
      const goalProgressFillEl = document.getElementById('goalProgressFill');
      
      // Modal Elements
      const settingsModal = document.getElementById('settingsModal');
      const settingsButton = document.getElementById('settingsButton');
      const closeSettingsModal = document.getElementById('closeSettingsModal');
      
      const goalModal = document.getElementById('goalModal');
      const addGoalButton = document.getElementById('addGoalButton');
      const closeGoalModal = document.getElementById('closeGoalModal');
      const saveGoalBtn = document.getElementById('saveGoalBtn');
      const cancelGoalBtn = document.getElementById('cancelGoalBtn');
      
      const confirmationModal = document.getElementById('confirmationModal');
      const closeConfirmationModal = document.getElementById('closeConfirmationModal');
      const confirmationMessage = document.getElementById('confirmationMessage');
      const confirmActionBtn = document.getElementById('confirmActionBtn');
      const cancelConfirmationBtn = document.getElementById('cancelConfirmationBtn');
      
      // Settings Elements
      const enableNotificationsToggle = document.getElementById('enableNotifications');
      const enableSoundAlertsToggle = document.getElementById('enableSoundAlerts');
      const notificationTimeSelect = document.getElementById('notificationTime');
      const exportDataBtn = document.getElementById('exportDataBtn');
      const importDataBtn = document.getElementById('importDataBtn');
      const importDataInput = document.getElementById('importDataInput');
      const clearDataBtn = document.getElementById('clearDataBtn');
      
      // Category Pills
      const categoryPills = document.querySelectorAll('.category-pill');
      
      // Chart Elements
      const chartMetricSelect = document.getElementById('chartMetric');
      const metricsChart = document.getElementById('metricsChart');
      
      // Initialize the app
      function init() {
        // Set today's date
        updateTodayDate();
        
        // Load settings
        loadSettings();
        
        // Render reminders
        renderReminders();
        
        // Generate calendar
        generateCalendar();
        
        // Update stats
        updateStats();
        
        // Generate chart
        generateChart();
        
        // Schedule reminders
        scheduleReminders();
        
        // Check for due reminders
        checkDueReminders();
        
        // Set interval to check reminders every minute
        setInterval(checkDueReminders, 60000);
      }
      
      // Update today's date display
      function updateTodayDate() {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        todayDateEl.textContent = new Date().toLocaleDateString('en-US', options);
      }
      
      // Load settings
      function loadSettings() {
        enableNotificationsToggle.checked = settings.enableNotifications;
        enableSoundAlertsToggle.checked = settings.enableSoundAlerts;
        notificationTimeSelect.value = settings.notificationTime;
      }
      
      // Save settings
      function saveSettings() {
        settings.enableNotifications = enableNotificationsToggle.checked;
        settings.enableSoundAlerts = enableSoundAlertsToggle.checked;
        settings.notificationTime = notificationTimeSelect.value;
        
        localStorage.setItem('settings', JSON.stringify(settings));
      }
      
      // Rotary Time Picker Logic
      function updatePicker(button, value) {
        button.textContent = value;
        button.classList.add('selected');
        setTimeout(() => button.classList.remove('selected'), 200);
      }
      
      // Hour Picker
      hourPicker.addEventListener('click', () => {
        const hours = ['12', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11'];
        const currentIndex = hours.indexOf(selectedHour);
        selectedHour = hours[(currentIndex + 1) % hours.length];
        updatePicker(hourPicker, selectedHour);
      });
      
      // Minute Picker
      minutePicker.addEventListener('click', () => {
        const minutes = ['00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55'];
        const currentIndex = minutes.indexOf(selectedMinute);
        selectedMinute = minutes[(currentIndex + 1) % minutes.length];
        updatePicker(minutePicker, selectedMinute);
      });
      
      // AM/PM Picker
      ampmPicker.addEventListener('click', () => {
        selectedAmPm = selectedAmPm === 'AM' ? 'PM' : 'AM';
        updatePicker(ampmPicker, selectedAmPm);
      });
      
      // Show/hide custom days based on frequency selection
      frequencySelect.addEventListener('change', () => {
        if (frequencySelect.value === 'custom') {
          customDaysDiv.style.display = 'block';
        } else {
          customDaysDiv.style.display = 'none';
        }
      });
      
      // Tab switching
      tabs.forEach(tab => {
        tab.addEventListener('click', () => {
          tabs.forEach(t => t.classList.remove('active'));
          tabContents.forEach(tc => tc.classList.remove('active'));
          
          tab.classList.add('active');
          document.getElementById(`${tab.dataset.tab}Tab`).classList.add('active');
          
          // Update chart if switching to progress tab
          if (tab.dataset.tab === 'progress') {
            updateStats();
            generateChart();
          }
          
          // Update calendar if switching to metrics tab
          if (tab.dataset.tab === 'metrics') {
            generateCalendar();
          }
        });
      });
      
      // Category selection
      categoryPills.forEach(pill => {
        pill.addEventListener('click', () => {
          categoryPills.forEach(p => p.classList.remove('active'));
          pill.classList.add('active');
          selectedCategory = pill.dataset.category;
        });
      });
      
      // Handle form submission
      reminderForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const habitName = document.getElementById('habitName').value;
        const reminderTime = `${selectedHour}:${selectedMinute} ${selectedAmPm}`;
        const frequency = frequencySelect.value;
        const notes = document.getElementById('reminderNotes').value;
        
        // Get selected days for custom frequency
        let selectedDays = [];
        if (frequency === 'custom') {
          selectedDays = Array.from(document.querySelectorAll('#customDays input[type="checkbox"]:checked'))
            .map(checkbox => checkbox.value);
            
          if (selectedDays.length === 0) {
            alert('Please select at least one day for custom frequency.');
            return;
          }
        }
        
        // Get priority
        const priority = document.querySelector('input[name="priority"]:checked').value;
        
        // Create reminder object
        const reminder = {
          id: Date.now(),
          habitName,
          reminderTime,
          frequency,
          selectedDays,
          category: selectedCategory,
          notes,
          priority,
          createdAt: new Date().toISOString(),
          active: true
        };
        
        // Add to reminders array
        reminders.push(reminder);
        
        // Save to localStorage
        localStorage.setItem('reminders', JSON.stringify(reminders));
        
        // Update UI
        renderReminders();
        updateStats();
        
        // Schedule the reminder
        scheduleReminder(reminder);
        
        // Reset form
        reminderForm.reset();
        showNotification(`Reminder set for ${habitName}`);
      });
      
      // Render reminders
      function renderReminders() {
        // Clear existing reminders
        todayRemindersEl.innerHTML = '';
        upcomingRemindersEl.innerHTML = '';
        
        // Get today's date
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        // Filter reminders for today
        const todayRems = reminders.filter(reminder => {
          if (!reminder.active) return false;
          
          if (reminder.frequency === 'daily') {
            return true;
          } else if (reminder.frequency === 'weekly') {
            const dayOfWeek = today.toLocaleDateString('en-US', { weekday: 'long' });
            return dayOfWeek === 'Monday'; // Assuming weekly reminders are on Monday
          } else if (reminder.frequency === 'custom') {
            const dayOfWeek = today.toLocaleDateString('en-US', { weekday: 'long' });
            return reminder.selectedDays.includes(dayOfWeek);
          }
          
          return false;
        });
        
        // Render today's reminders
        if (todayRems.length === 0) {
          todayRemindersEl.innerHTML = '<li class="text-center text-gray-500">No reminders for today.</li>';
        } else {
          todayRems.forEach(reminder => {
            const reminderItem = createReminderElement(reminder);
            todayRemindersEl.appendChild(reminderItem);
          });
        }
        
        // Filter upcoming reminders (not today)
        const upcomingRems = reminders.filter(reminder => {
          if (!reminder.active) return false;
          
          if (reminder.frequency === 'daily') {
            return false; // Already shown in today's reminders
          } else if (reminder.frequency === 'weekly') {
            const dayOfWeek = today.toLocaleDateString('en-US', { weekday: 'long' });
            return dayOfWeek !== 'Monday';
          } else if (reminder.frequency === 'custom') {
            const dayOfWeek = today.toLocaleDateString('en-US', { weekday: 'long' });
            return !reminder.selectedDays.includes(dayOfWeek);
          }
          
          return false;
        });
        
        // Render upcoming reminders
        if (upcomingRems.length === 0) {
          upcomingRemindersEl.innerHTML = '<li class="text-center text-gray-500">No upcoming reminders.</li>';
        } else {
          upcomingRems.forEach(reminder => {
            const reminderItem = createReminderElement(reminder);
            upcomingRemindersEl.appendChild(reminderItem);
          });
        }
      }
      
      // Create reminder element
      function createReminderElement(reminder) {
        const li = document.createElement('li');
        li.className = 'reminder-item';
        li.dataset.id = reminder.id;
        
        // Set background color based on category
        const categoryColors = {
          exercise: '#e0f2fe',
          nutrition: '#dcfce7',
          meditation: '#f3e8ff',
          water: '#dbeafe',
          sleep: '#fef3c7',
          medication: '#fee2e2',
          other: '#f3f4f6'
        };
        
        li.style.backgroundColor = categoryColors[reminder.category] || '#f3f4f6';
        
        // Set border color based on priority
        const priorityColors = {
          low: '#15803d',
          medium: '#b45309',
          high: '#b91c1c'
        };
        
        li.style.borderLeft = `4px solid ${priorityColors[reminder.priority]}`;
        
        li.innerHTML = `
          <div class="reminder-info">
            <div class="reminder-name">${reminder.habitName}</div>
            <div class="reminder-time">
              <span>${reminder.reminderTime}</span>
              ${reminder.notes ? `<span> • ${reminder.notes}</span>` : ''}
            </div>
          </div>
          <div class="reminder-actions">
            <button class="btn btn-icon btn-3d complete-btn" title="Mark as Complete">
              <svg xmlns="http://www.w3.org/2000/svg" style="color:#111827" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
            </button>
            <button class="btn btn-icon btn-3d edit-btn" title="Edit Reminder">
              <svg xmlns="http://www.w3.org/2000/svg"style="color:#111827" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
              </svg>
            </button>
            <button class="btn btn-icon btn-3d delete-btn" title="Delete Reminder">
              <svg xmlns="http://www.w3.org/2000/svg" style="color:#111827" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
              </svg>
            </button>
          </div>
        `;
        
        // Add event listeners
        const completeBtn = li.querySelector('.complete-btn');
        completeBtn.addEventListener('click', () => {
          markReminderComplete(reminder.id);
        });
        
        const editBtn = li.querySelector('.edit-btn');
        editBtn.addEventListener('click', () => {
          editReminder(reminder.id);
        });
        
        const deleteBtn = li.querySelector('.delete-btn');
        deleteBtn.addEventListener('click', () => {
          deleteReminder(reminder.id);
        });
        
        return li;
      }
      
      // Mark reminder as complete
      function markReminderComplete(id) {
        const reminder = reminders.find(r => r.id === id);
        if (!reminder) return;
        
        // Add to completed reminders
        completedReminders.push({
          id: Date.now(),
          reminderId: id,
          habitName: reminder.habitName,
          completedAt: new Date().toISOString()
        });
        
        // Save to localStorage
        localStorage.setItem('completedReminders', JSON.stringify(completedReminders));
        
        // Update UI
        renderReminders();
        updateStats();
        showNotification(`${reminder.habitName} marked as complete!`);
      }
      
      // Edit reminder
      function editReminder(id) {
        const reminder = reminders.find(r => r.id === id);
        if (!reminder) return;
        
        // Fill form with reminder data
        document.getElementById('habitName').value = reminder.habitName;
        document.getElementById('reminderNotes').value = reminder.notes || '';
        
        // Set time picker
        const [time, ampm] = reminder.reminderTime.split(' ');
        const [hour, minute] = time.split(':');
        selectedHour = hour;
        selectedMinute = minute;
        selectedAmPm = ampm;
        updatePicker(hourPicker, selectedHour);
        updatePicker(minutePicker, selectedMinute);
        updatePicker(ampmPicker, selectedAmPm);
        
        // Set frequency
        frequencySelect.value = reminder.frequency;
        if (reminder.frequency === 'custom') {
          customDaysDiv.style.display = 'block';
          
          // Uncheck all days first
          document.querySelectorAll('#customDays input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
          });
          
          // Check selected days
          reminder.selectedDays.forEach(day => {
            const checkbox = document.getElementById(day.toLowerCase());
            if (checkbox) checkbox.checked = true;
          });
        } else {
          customDaysDiv.style.display = 'none';
        }
        
        // Set category
        categoryPills.forEach(pill => {
          pill.classList.remove('active');
          if (pill.dataset.category === reminder.category) {
            pill.classList.add('active');
          }
        });
        selectedCategory = reminder.category;
        
        // Set priority
        document.querySelector(`input[value="${reminder.priority}"]`).checked = true;
        
        // Remove the reminder
        reminders = reminders.filter(r => r.id !== id);
        localStorage.setItem('reminders', JSON.stringify(reminders));
        
        // Update UI
        renderReminders();
        
        // Switch to reminders tab
        tabs.forEach(t => t.classList.remove('active'));
        tabContents.forEach(tc => tc.classList.remove('active'));
        document.querySelector('.tab[data-tab="reminders"]').classList.add('active');
        document.getElementById('remindersTab').classList.add('active');
        
        showNotification('Editing reminder. Submit to save changes.');
      }
      
      // Delete reminder
      function deleteReminder(id) {
        const reminder = reminders.find(r => r.id === id);
        if (!reminder) return;
        
        // Show confirmation
        showConfirmation(
          `Are you sure you want to delete the reminder "${reminder.habitName}"?`,
          () => {
            // Remove the reminder
            reminders = reminders.filter(r => r.id !== id);
            localStorage.setItem('reminders', JSON.stringify(reminders));
            
            // Update UI
            renderReminders();
            updateStats();
            showNotification('Reminder deleted successfully');
          }
        );
      }
      
      // Schedule reminders
      function scheduleReminders() {
        reminders.forEach(reminder => {
          if (reminder.active) {
            scheduleReminder(reminder);
          }
        });
      }
      
      // Schedule a single reminder
      function scheduleReminder(reminder) {
        const now = new Date();
        const [time, ampm] = reminder.reminderTime.split(' ');
        const [hours, minutes] = time.split(':');
        
        // Convert to 24-hour format
        let hour24 = parseInt(hours);
        if (ampm === 'PM' && hour24 < 12) hour24 += 12;
        if (ampm === 'AM' && hour24 === 12) hour24 = 0;
        
        // Create reminder date
        const reminderDate = new Date();
        reminderDate.setHours(hour24, parseInt(minutes), 0, 0);
        
        // If the time has already passed today, schedule for the next occurrence
        if (now > reminderDate) {
          if (reminder.frequency === 'daily') {
            // Schedule for tomorrow
            reminderDate.setDate(reminderDate.getDate() + 1);
          } else if (reminder.frequency === 'weekly') {
            // Schedule for next week
            reminderDate.setDate(reminderDate.getDate() + 7);
          } else if (reminder.frequency === 'custom') {
            // Find the next day in the selected days
            const today = now.toLocaleDateString('en-US', { weekday: 'long' });
            const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const todayIndex = daysOfWeek.indexOf(today);
            
            let nextDayIndex = -1;
            for (let i = 1; i <= 7; i++) {
              const checkIndex = (todayIndex + i) % 7;
              if (reminder.selectedDays.includes(daysOfWeek[checkIndex])) {
                nextDayIndex = checkIndex;
                break;
              }
            }
            
            if (nextDayIndex !== -1) {
              const daysToAdd = (nextDayIndex - todayIndex + 7) % 7;
              reminderDate.setDate(reminderDate.getDate() + daysToAdd);
            }
          }
        }
        
        // Calculate time until reminder
        const timeUntilReminder = reminderDate - now;
        
        // Adjust for notification time setting
        const notificationOffset = parseInt(settings.notificationTime) * 60 * 1000;
        const adjustedTime = timeUntilReminder - notificationOffset;
        
        // Schedule the reminder if it's in the future
        if (adjustedTime > 0) {
          setTimeout(() => {
            showReminderNotification(reminder);
          }, adjustedTime);
        }
      }
      
      // Check for due reminders
      function checkDueReminders() {
        const now = new Date();
        const currentHour = now.getHours();
        const currentMinute = now.getMinutes();
        
        reminders.forEach(reminder => {
          if (!reminder.active) return;
          
          // Check if this reminder should trigger today
          let shouldTriggerToday = false;
          
          if (reminder.frequency === 'daily') {
            shouldTriggerToday = true;
          } else if (reminder.frequency === 'weekly') {
            const dayOfWeek = now.toLocaleDateString('en-US', { weekday: 'long' });
            shouldTriggerToday = dayOfWeek === 'Monday'; // Assuming weekly reminders are on Monday
          } else if (reminder.frequency === 'custom') {
            const dayOfWeek = now.toLocaleDateString('en-US', { weekday: 'long' });
            shouldTriggerToday = reminder.selectedDays.includes(dayOfWeek);
          }
          
          if (shouldTriggerToday) {
            // Parse reminder time
            const [time, ampm] = reminder.reminderTime.split(' ');
            const [hours, minutes] = time.split(':');
            
            // Convert to 24-hour format
            let hour24 = parseInt(hours);
            if (ampm === 'PM' && hour24 < 12) hour24 += 12;
            if (ampm === 'AM' && hour24 === 12) hour24 = 0;
            
            // Apply notification time offset
            const notificationOffset = parseInt(settings.notificationTime);
            let adjustedHour = hour24;
            let adjustedMinute = parseInt(minutes) - notificationOffset;
            
            // Handle minute underflow
            while (adjustedMinute < 0) {
              adjustedMinute += 60;
              adjustedHour -= 1;
            }
            
            // Handle hour underflow
            if (adjustedHour < 0) {
              adjustedHour += 24;
            }
            
            // Check if it's time for the reminder
            if (currentHour === adjustedHour && currentMinute === adjustedMinute) {
              showReminderNotification(reminder);
            }
          }
        });
      }
      
      // Show reminder notification
      function showReminderNotification(reminder) {
        if (!settings.enableNotifications) return;
        
        // Try to use browser notifications
        if (Notification.permission === 'granted') {
          const notification = new Notification(`Reminder: ${reminder.habitName}`, {
            body: reminder.notes || "It's time for your health habit!",
            icon: 'https://cdn-icons-png.flaticon.com/512/2382/2382461.png'
          });
          
          // Play sound if enabled
          if (settings.enableSoundAlerts) {
            playNotificationSound();
          }
          
          // Close notification after 10 seconds
          setTimeout(() => notification.close(), 10000);
        } else if (Notification.permission !== 'denied') {
          // Request permission
          Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
              showReminderNotification(reminder);
            } else {
              // Fall back to in-app notification
              showInAppNotification(reminder);
            }
          });
        } else {
          // Fall back to in-app notification
          showInAppNotification(reminder);
        }
      }
      
      // Show in-app notification
      function showInAppNotification(reminder) {
        const notificationEl = document.createElement('div');
        notificationEl.className = 'notification fade-in';
        
        // Set background color based on priority
        const priorityColors = {
          low: '#dcfce7',
          medium: '#fef3c7',
          high: '#fee2e2'
        };
        
        notificationEl.style.backgroundColor = priorityColors[reminder.priority] || '#f9fafb';
        
        notificationEl.innerHTML = `
          <div class="notification-content">
            <div class="notification-title">${reminder.habitName}</div>
            <div class="notification-time">${reminder.reminderTime} ${reminder.notes ? `• ${reminder.notes}` : ''}</div>
          </div>
          <div class="notification-actions">
            <button class="btn btn-success btn-3d complete-btn">Complete</button>
            <button class="btn btn-secondary btn-3d snooze-btn">Snooze</button>
            <button class="btn btn-danger btn-3d dismiss-btn">Dismiss</button>
          </div>
        `;
        
        // Add event listeners
        const completeBtn = notificationEl.querySelector('.complete-btn');
        completeBtn.addEventListener('click', () => {
          markReminderComplete(reminder.id);
          removeNotification(notificationEl);
        });
        
        const snoozeBtn = notificationEl.querySelector('.snooze-btn');
        snoozeBtn.addEventListener('click', () => {
          snoozeReminder(reminder.id);
          removeNotification(notificationEl);
        });
        
        const dismissBtn = notificationEl.querySelector('.dismiss-btn');
        dismissBtn.addEventListener('click', () => {
          removeNotification(notificationEl);
        });
        
        // Add to notifications container
        notificationsDiv.appendChild(notificationEl);
        
        // Play sound if enabled
        if (settings.enableSoundAlerts) {
          playNotificationSound();
        }
      }
      
      // Remove notification
      function removeNotification(notificationEl) {
        notificationEl.classList.remove('fade-in');
        notificationEl.classList.add('fade-out');
        
        setTimeout(() => {
          notificationEl.remove();
        }, 500);
      }
      
      // Snooze reminder
      function snoozeReminder(id) {
        const reminder = reminders.find(r => r.id === id);
        if (!reminder) return;
        
        // Schedule reminder for 5 minutes later
        setTimeout(() => {
          showReminderNotification(reminder);
        }, 5 * 60 * 1000);
        
        showNotification(`Snoozed "${reminder.habitName}" for 5 minutes`);
      }
      
      // Play notification sound
      function playNotificationSound() {
        try {
          const audio = new Audio('/files/ringtone.mp3');
          audio.volume = 0.5;
          audio.play();
        } catch (e) {
          console.log('Audio play failed:', e);
        }
      }
      
      // Show notification
      function showNotification(message) {
        const notificationEl = document.createElement('div');
        notificationEl.className = 'notification fade-in';
        notificationEl.innerHTML = `
          <div class="notification-content">
            <div class="notification-title">${message}</div>
          </div>
          <button class="btn btn-icon btn-3d dismiss-btn">
            <svg xmlns="http://www.w3.org/2000/svg" style="color:#111827" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        `;
        
        // Add event listener
        const dismissBtn = notificationEl.querySelector('.dismiss-btn');
        dismissBtn.addEventListener('click', () => {
          removeNotification(notificationEl);
        });
        
        // Add to notifications container
        notificationsDiv.appendChild(notificationEl);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
          if (notificationEl.parentNode) {
            removeNotification(notificationEl);
          }
        }, 5000);
      }
      
      // Generate calendar
      function generateCalendar() {
        // Update calendar title
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        calendarTitle.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        
        // Clear calendar grid
        calendarGrid.innerHTML = '';
        
        // Add day headers
        const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        dayNames.forEach(day => {
          const dayHeader = document.createElement('div');
          dayHeader.className = 'calendar-day-header';
          dayHeader.textContent = day;
          calendarGrid.appendChild(dayHeader);
        });
        
        // Get first day of month and total days
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        
        // Get days from previous month
        const daysInPrevMonth = new Date(currentYear, currentMonth, 0).getDate();
        
        // Add days from previous month
        for (let i = firstDay - 1; i >= 0; i--) {
          const day = daysInPrevMonth - i;
          const dayEl = document.createElement('div');
          dayEl.className = 'calendar-day other-month';
          dayEl.textContent = day;
          calendarGrid.appendChild(dayEl);
        }
        
        // Add days of current month
        const today = new Date();
        for (let i = 1; i <= daysInMonth; i++) {
          const dayEl = document.createElement('div');
          dayEl.className = 'calendar-day';
          dayEl.textContent = i;
          
          // Check if this is today
          if (currentYear === today.getFullYear() && currentMonth === today.getMonth() && i === today.getDate()) {
            dayEl.classList.add('today');
          }
          
          // Check if this day has metrics
          const hasMetrics = metrics.some(metric => {
            const metricDate = new Date(metric.date);
            return metricDate.getFullYear() === currentYear && metricDate.getMonth() === currentMonth && metricDate.getDate() === i;
          });
          
          if (hasMetrics) {
            dayEl.classList.add('has-reminder');
          }
          
          // Check if this is the selected date
          if (currentYear === selectedDate.getFullYear() && currentMonth === selectedDate.getMonth() && i === selectedDate.getDate()) {
            dayEl.classList.add('selected');
          }
          
          // Add click event
          dayEl.addEventListener('click', () => {
            // Remove selected class from all days
            document.querySelectorAll('.calendar-day').forEach(day => {
              day.classList.remove('selected');
            });
            
            // Add selected class to clicked day
            dayEl.classList.add('selected');
            
            // Update selected date
            selectedDate = new Date(currentYear, currentMonth, i);
            
            // Update metrics form date
            document.getElementById('metricDate').valueAsDate = selectedDate;
            
            // Load metrics for this date
            loadMetricsForDate(selectedDate);
          });
          
          calendarGrid.appendChild(dayEl);
        }
        
        // Add days from next month to fill the grid
        const totalCells = 42; // 6 rows of 7 days
        const remainingCells = totalCells - (firstDay + daysInMonth);
        
        for (let i = 1; i <= remainingCells; i++) {
          const dayEl = document.createElement('div');
          dayEl.className = 'calendar-day other-month';
          dayEl.textContent = i;
          calendarGrid.appendChild(dayEl);
        }
        
        // Set metrics form date to selected date
        document.getElementById('metricDate').valueAsDate = selectedDate;
        
        // Load metrics for selected date
        loadMetricsForDate(selectedDate);
      }
      
      // Load metrics for a specific date
      function loadMetricsForDate(date) {
        const metric = metrics.find(m => {
          const metricDate = new Date(m.date);
          return metricDate.getFullYear() === date.getFullYear() && 
                 metricDate.getMonth() === date.getMonth() && 
                 metricDate.getDate() === date.getDate();
        });
        
        if (metric) {
          // Fill form with metric data
          document.getElementById('weightValue').value = metric.weight || '';
          document.getElementById('weightUnit').value = metric.weightUnit || 'kg';
          document.getElementById('stepsValue').value = metric.steps || '';
          document.getElementById('waterValue').value = metric.water || '';
          document.getElementById('waterUnit').value = metric.waterUnit || 'ml';
          document.getElementById('sleepHours').value = metric.sleepHours || '';
          document.getElementById('sleepMinutes').value = metric.sleepMinutes || '';
          document.getElementById('moodValue').value = metric.mood || '3';
          document.getElementById('metricNotes').value = metric.notes || '';
        } else {
          // Clear form
          document.getElementById('weightValue').value = '';
          document.getElementById('stepsValue').value = '';
          document.getElementById('waterValue').value = '';
          document.getElementById('sleepHours').value = '';
          document.getElementById('sleepMinutes').value = '';
          document.getElementById('moodValue').value = '3';
          document.getElementById('metricNotes').value = '';
        }
      }
      
      // Update stats
      function updateStats() {
        // Count active reminders
        const activeCount = reminders.filter(r => r.active).length;
        activeRemindersEl.textContent = activeCount;
        
        // Count completed reminders
        const completedCount = completedReminders.length;
        completedRemindersEl.textContent = completedCount;
        
        // Calculate streak
        const streak = calculateStreak();
        streakCountEl.textContent = streak;
        
        // Calculate adherence rate
        const adherenceRate = calculateAdherenceRate();
        adherenceRateEl.textContent = `${adherenceRate}%`;
        
        // Calculate goal progress
        const goalProgress = calculateGoalProgress();
        goalPercentageEl.textContent = `${goalProgress}%`;
        goalProgressFillEl.style.width = `${goalProgress}%`;
      }
      
      // Calculate streak
      function calculateStreak() {
        if (completedReminders.length === 0) return 0;
        
        // Sort completed reminders by date
        const sortedCompletions = [...completedReminders].sort((a, b) => {
          return new Date(b.completedAt) - new Date(a.completedAt);
        });
        
        // Get today's date
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        // Check if there's a completion today
        const latestCompletion = new Date(sortedCompletions[0].completedAt);
        latestCompletion.setHours(0, 0, 0, 0);
        
        if (latestCompletion.getTime() !== today.getTime()) {
          // No completion today, check if there was one yesterday
          const yesterday = new Date(today);
          yesterday.setDate(yesterday.getDate() - 1);
          
          const yesterdayCompletion = sortedCompletions.find(c => {
            const completionDate = new Date(c.completedAt);
            completionDate.setHours(0, 0, 0, 0);
            return completionDate.getTime() === yesterday.getTime();
          });
          
          if (!yesterdayCompletion) {
            // No completion yesterday, streak is broken
            return 0;
          }
        }
        
        // Calculate streak
        let streak = 1;
        let currentDate = new Date(today);
        
        while (true) {
          currentDate.setDate(currentDate.getDate() - 1);
          
          const completionOnDate = sortedCompletions.find(c => {
            const completionDate = new Date(c.completedAt);
            completionDate.setHours(0, 0, 0, 0);
            return completionDate.getTime() === currentDate.getTime();
          });
          
          if (completionOnDate) {
            streak++;
          } else {
            break;
          }
        }
        
        return streak;
      }
      
      // Calculate adherence rate
      function calculateAdherenceRate() {
        if (completedReminders.length === 0) return 0;
        
        // Get the earliest reminder creation date
        const earliestReminder = [...reminders].sort((a, b) => {
          return new Date(a.createdAt) - new Date(b.createdAt);
        })[0];
        
        if (!earliestReminder) return 0;
        
        // Calculate days since first reminder
        const firstReminderDate = new Date(earliestReminder.createdAt);
        const today = new Date();
        const daysSinceFirst = Math.floor((today - firstReminderDate) / (1000 * 60 * 60 * 24)) + 1;
        
        // Calculate expected completions
        const expectedCompletions = daysSinceFirst * reminders.length;
        
        // Calculate adherence rate
        const adherenceRate = Math.round((completedReminders.length / expectedCompletions) * 100);
        
        return Math.min(adherenceRate, 100);
      }
      
      // Calculate goal progress
      function calculateGoalProgress() {
        if (goals.length === 0) return 0;
        
        // Get active goals
        const activeGoals = goals.filter(goal => {
          const deadline = new Date(goal.deadline);
          return deadline >= new Date();
        });
        
        if (activeGoals.length === 0) return 0;
        
        // Calculate progress for each goal
        let totalProgress = 0;
        
        activeGoals.forEach(goal => {
          let progress = 0;
          
          switch (goal.type) {
            case 'weight':
              // Get latest weight
              const latestWeightMetric = [...metrics].sort((a, b) => {
                return new Date(b.date) - new Date(a.date);
              }).find(m => m.weight);
              
              if (latestWeightMetric) {
                const currentWeight = parseFloat(latestWeightMetric.weight);
                const targetWeight = parseFloat(goal.target);
                const startWeight = parseFloat(goal.startValue);
                
                if (startWeight > targetWeight) {
                  // Weight loss goal
                  progress = Math.min(100, Math.max(0, ((startWeight - currentWeight) / (startWeight - targetWeight)) * 100));
                } else {
                  // Weight gain goal
                  progress = Math.min(100, Math.max(0, ((currentWeight - startWeight) / (targetWeight - startWeight)) * 100));
                }
              }
              break;
              
            case 'steps':
              // Calculate average steps
              const stepsMetrics = metrics.filter(m => m.steps);
              if (stepsMetrics.length > 0) {
                const totalSteps = stepsMetrics.reduce((sum, m) => sum + parseInt(m.steps), 0);
                const avgSteps = totalSteps / stepsMetrics.length;
                progress = Math.min(100, Math.max(0, (avgSteps / parseInt(goal.target)) * 100));
              }
              break;
              
            case 'water':
              // Calculate average water intake
              const waterMetrics = metrics.filter(m => m.water);
              if (waterMetrics.length > 0) {
                const totalWater = waterMetrics.reduce((sum, m) => sum + parseInt(m.water), 0);
                const avgWater = totalWater / waterMetrics.length;
                progress = Math.min(100, Math.max(0, (avgWater / parseInt(goal.target)) * 100));
              }
              break;
              
            case 'sleep':
              // Calculate average sleep duration
              const sleepMetrics = metrics.filter(m => m.sleepHours);
              if (sleepMetrics.length > 0) {
                const totalSleep = sleepMetrics.reduce((sum, m) => {
                  return sum + (parseInt(m.sleepHours) * 60 + parseInt(m.sleepMinutes || 0));
                }, 0);
                const avgSleep = totalSleep / sleepMetrics.length / 60; // Convert to hours
                progress = Math.min(100, Math.max(0, (avgSleep / parseFloat(goal.target)) * 100));
              }
              break;
              
            case 'exercise':
              // Calculate exercise frequency
              const exerciseReminders = completedReminders.filter(c => {
                const reminder = reminders.find(r => r.id === c.reminderId);
                return reminder && reminder.category === 'exercise';
              });
              
              if (exerciseReminders.length > 0) {
                // Calculate days since goal creation
                const goalCreationDate = new Date(goal.createdAt);
                const today = new Date();
                const daysSinceCreation = Math.floor((today - goalCreationDate) / (1000 * 60 * 60 * 24)) + 1;
                
                // Calculate weeks
                const weeksSinceCreation = Math.ceil(daysSinceCreation / 7);
                
                // Calculate target exercise sessions
                const targetSessions = parseInt(goal.target) * weeksSinceCreation;
                
                progress = Math.min(100, Math.max(0, (exerciseReminders.length / targetSessions) * 100));
              }
              break;
          }
          
          totalProgress += progress;
        });
        
        // Calculate average progress
        return Math.round(totalProgress / activeGoals.length);
      }
      
      // Generate chart
      function generateChart() {
        // Clear chart
        metricsChart.innerHTML = '';
        
        // Get selected metric
        const metricType = chartMetricSelect.value;
        
        // Filter metrics with the selected type
        let filteredMetrics = [];
        
        switch (metricType) {
          case 'weight':
            filteredMetrics = metrics.filter(m => m.weight);
            break;
          case 'steps':
            filteredMetrics = metrics.filter(m => m.steps);
            break;
          case 'water':
            filteredMetrics = metrics.filter(m => m.water);
            break;
          case 'sleep':
            filteredMetrics = metrics.filter(m => m.sleepHours);
            break;
          case 'mood':
            filteredMetrics = metrics.filter(m => m.mood);
            break;
        }
        
        // Sort metrics by date
        filteredMetrics.sort((a, b) => new Date(a.date) - new Date(b.date));
        
        // Take the last 7 metrics
        const chartMetrics = filteredMetrics.slice(-7);
        
        if (chartMetrics.length === 0) {
          metricsChart.innerHTML = '<div class="text-center text-gray-500">No data available</div>';
          return;
        }
        
        // Find min and max values
        let minValue = Infinity;
        let maxValue = -Infinity;
        
        chartMetrics.forEach(metric => {
          let value;
          
          switch (metricType) {
            case 'weight':
              value = parseFloat(metric.weight);
              break;
            case 'steps':
              value = parseInt(metric.steps);
              break;
            case 'water':
              value = parseInt(metric.water);
              break;
            case 'sleep':
              value = parseInt(metric.sleepHours) * 60 + parseInt(metric.sleepMinutes || 0);
              break;
            case 'mood':
              value = parseInt(metric.mood);
              break;
          }
          
          minValue = Math.min(minValue, value);
          maxValue = Math.max(maxValue, value);
        });
        
        // Calculate range and scale
        const range = maxValue - minValue;
        const scale = range > 0 ? 180 / range : 1; // Max height is 180px
        
        // Create chart bars
        chartMetrics.forEach((metric, index) => {
          let value;
          let label;
          
          switch (metricType) {
            case 'weight':
              value = parseFloat(metric.weight);
              label = `${value}${metric.weightUnit || 'kg'}`;
              break;
            case 'steps':
              value = parseInt(metric.steps);
              label = value.toLocaleString();
              break;
            case 'water':
              value = parseInt(metric.water);
              label = `${value}${metric.waterUnit || 'ml'}`;
              break;
            case 'sleep':
              value = parseInt(metric.sleepHours) * 60 + parseInt(metric.sleepMinutes || 0);
              label = `${metric.sleepHours}h ${metric.sleepMinutes || 0}m`;
              break;
            case 'mood':
              value = parseInt(metric.mood);
              const moodLabels = ['', 'Terrible', 'Poor', 'Average', 'Good', 'Excellent'];
              label = moodLabels[value] || '';
              break;
          }
          
          // Calculate bar height
          const height = (value - minValue) * scale + 20; // Add 20px minimum height
          
          // Create bar
          const bar = document.createElement('div');
          bar.className = 'chart-bar';
          bar.style.height = `${height}px`;
          bar.style.left = `${index * (100 / (chartMetrics.length - 1 || 1))}%`;
          bar.style.transform = 'translateX(-50%)';
          
          // Add tooltip
          bar.title = `${new Date(metric.date).toLocaleDateString()}: ${label}`;
          
          // Create label
          const labelEl = document.createElement('div');
          labelEl.className = 'chart-label';
          labelEl.textContent = new Date(metric.date).getDate();
          labelEl.style.left = `${index * (100 / (chartMetrics.length - 1 || 1))}%`;
          labelEl.style.transform = 'translateX(-50%)';
          
          metricsChart.appendChild(bar);
          metricsChart.appendChild(labelEl);
        });
      }
      
      // Show confirmation modal
      function showConfirmation(message, onConfirm) {
        confirmationMessage.textContent = message;
        confirmationModal.classList.add('active');
        
        // Set confirm action
        confirmActionBtn.onclick = () => {
          onConfirm();
          confirmationModal.classList.remove('active');
        };
      }
      
      // Handle metrics form submission
      document.getElementById('metricsForm').addEventListener('submit', (e) => {
        e.preventDefault();
        
        const date = document.getElementById('metricDate').value;
        const weight = document.getElementById('weightValue').value;
        const weightUnit = document.getElementById('weightUnit').value;
        const steps = document.getElementById('stepsValue').value;
        const water = document.getElementById('waterValue').value;
        const waterUnit = document.getElementById('waterUnit').value;
        const sleepHours = document.getElementById('sleepHours').value;
        const sleepMinutes = document.getElementById('sleepMinutes').value;
        const mood = document.getElementById('moodValue').value;
        const notes = document.getElementById('metricNotes').value;
        
        // Check if metric for this date already exists
        const existingMetricIndex = metrics.findIndex(m => m.date === date);
        
        if (existingMetricIndex !== -1) {
          // Update existing metric
          metrics[existingMetricIndex] = {
            ...metrics[existingMetricIndex],
            weight,
            weightUnit,
            steps,
            water,
            waterUnit,
            sleepHours,
            sleepMinutes,
            mood,
            notes
          };
        } else {
          // Add new metric
          metrics.push({
            date,
            weight,
            weightUnit,
            steps,
            water,
            waterUnit,
            sleepHours,
            sleepMinutes,
            mood,
            notes
          });
        }
        
        // Save to localStorage
        localStorage.setItem('metrics', JSON.stringify(metrics));
        
        // Update UI
        generateCalendar();
        generateChart();
        updateStats();
        showNotification('Health metrics saved successfully');
      });
      
      // Handle goal form submission
      document.getElementById('goalForm').addEventListener('submit', (e) => {
        e.preventDefault();
        
        const type = document.getElementById('goalType').value;
        const target = document.getElementById('goalTarget').value;
        const deadline = document.getElementById('goalDeadline').value;
        const notes = document.getElementById('goalNotes').value;
        
        // Get start value from latest metric
        let startValue = '';
        
        switch (type) {
          case 'weight':
            const latestWeightMetric = [...metrics].sort((a, b) => {
              return new Date(b.date) - new Date(a.date);
            }).find(m => m.weight);
            
            if (latestWeightMetric) {
              startValue = latestWeightMetric.weight;
            }
            break;
            
          case 'steps':
          case 'water':
          case 'sleep':
          case 'exercise':
            // These don't need start values
            break;
        }
        
        // Create goal object
        const goal = {
          id: Date.now(),
          type,
          target,
          startValue,
          deadline,
          notes,
          createdAt: new Date().toISOString()
        };
        
        // Add to goals array
        goals.push(goal);
        
        // Save to localStorage
        localStorage.setItem('goals', JSON.stringify(goals));
        
        // Update UI
        updateStats();
        goalModal.classList.remove('active');
        showNotification('Health goal set successfully');
      });
      
      // Calendar navigation
      prevMonthBtn.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
          currentMonth = 11;
          currentYear--;
        }
        generateCalendar();
      });
      
      nextMonthBtn.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
          currentMonth = 0;
          currentYear++;
        }
        generateCalendar();
      });
      
      // Chart metric selection
      chartMetricSelect.addEventListener('change', generateChart);
      
      // Settings modal
      settingsButton.addEventListener('click', () => {
        settingsModal.classList.add('active');
      });
      
      closeSettingsModal.addEventListener('click', () => {
        settingsModal.classList.remove('active');
        saveSettings();
      });
      
      // Goal modal
      addGoalButton.addEventListener('click', () => {
        goalModal.classList.add('active');
      });
      
      closeGoalModal.addEventListener('click', () => {
        goalModal.classList.remove('active');
      });
      
      cancelGoalBtn.addEventListener('click', () => {
        goalModal.classList.remove('active');
      });
      
      // Confirmation modal
      closeConfirmationModal.addEventListener('click', () => {
        confirmationModal.classList.remove('active');
      });
      
      cancelConfirmationBtn.addEventListener('click', () => {
        confirmationModal.classList.remove('active');
      });
      
      // Export data
      exportDataBtn.addEventListener('click', () => {
        const data = {
          reminders,
          metrics,
          goals,
          completedReminders,
          settings,
          exportDate: new Date().toISOString()
        };
        
        const dataStr = JSON.stringify(data, null, 2);
        const dataUri = `data:application/json;charset=utf-8,${encodeURIComponent(dataStr)}`;
        
        const exportFileDefaultName = `health-tracker-export-${new Date().toISOString().slice(0, 10)}.json`;
        
        const linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', exportFileDefaultName);
        linkElement.click();
        
        showNotification('Data exported successfully');
      });
      
      // Import data
      importDataBtn.addEventListener('click', () => {
        importDataInput.click();
      });
      
      importDataInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = (event) => {
          try {
            const data = JSON.parse(event.target.result);
            
            // Validate data
            if (!data.reminders || !data.metrics || !data.goals || !data.completedReminders || !data.settings) {
              throw new Error('Invalid data format');
            }
            
            // Show confirmation
            showConfirmation(
              'This will replace all your current data. Are you sure you want to proceed?',
              () => {
                // Import data
                reminders = data.reminders;
                metrics = data.metrics;
                goals = data.goals;
                completedReminders = data.completedReminders;
                settings = data.settings;
                
                // Save to localStorage
                localStorage.setItem('reminders', JSON.stringify(reminders));
                localStorage.setItem('metrics', JSON.stringify(metrics));
                localStorage.setItem('goals', JSON.stringify(goals));
                localStorage.setItem('completedReminders', JSON.stringify(completedReminders));
                localStorage.setItem('settings', JSON.stringify(settings));
                
                // Update UI
                loadSettings();
                renderReminders();
                generateCalendar();
                updateStats();
                generateChart();
                
                showNotification('Data imported successfully');
              }
            );
          } catch (error) {
            showNotification('Error importing data: Invalid format');
            console.error(error);
          }
        };
        
        reader.readAsText(file);
      });
      
      // Clear data
      clearDataBtn.addEventListener('click', () => {
        showConfirmation(
          'This will delete all your reminders, metrics, and goals. This action cannot be undone. Are you sure?',
          () => {
            // Clear data
            reminders = [];
            metrics = [];
            goals = [];
            completedReminders = [];
            
            // Save to localStorage
            localStorage.setItem('reminders', JSON.stringify(reminders));
            localStorage.setItem('metrics', JSON.stringify(metrics));
            localStorage.setItem('goals', JSON.stringify(goals));
            localStorage.setItem('completedReminders', JSON.stringify(completedReminders));
            
            // Update UI
            renderReminders();
            generateCalendar();
            updateStats();
            generateChart();
            
            showNotification('All data cleared successfully');
          }
        );
      });
      
      // Request notification permission
      if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
        Notification.requestPermission();
      }
      
      // Initialize the app
      init();
    });
  </script>
</body>
</html>