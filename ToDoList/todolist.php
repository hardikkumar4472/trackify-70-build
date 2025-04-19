<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

$tasks = $_SESSION['tasks'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['api'])) {
    header('Content-Type: application/json');
    echo json_encode($tasks);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newTask = [
        'id' => count($tasks) + 1,
        'name' => $_POST['name'] ?? 'Untitled Task',
        'priority' => $_POST['priority'] ?? 'low',
        'completed' => false,
    ];
    $tasks[] = $newTask;
    $_SESSION['tasks'] = $tasks;

    header('Content-Type: application/json');
    echo json_encode(['message' => 'Task added successfully', 'task' => $newTask]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $taskId = $data['id'] ?? null;

    if ($taskId) {
        $tasks = array_filter($tasks, function ($task) use ($taskId) {
            return $task['id'] != $taskId;
        });
        $_SESSION['tasks'] = array_values($tasks);

        header('Content-Type: application/json');
        echo json_encode(['message' => 'Task deleted successfully']);
    } else {
        header('Content-Type: application/json', true, 400);
        echo json_encode(['error' => 'Task ID is required']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Task Manager</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
  }

  @import url('https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap');

  body {
    background-color: #111827; /* Tailwind bg-gray-900 */
    min-height: 100vh;
    color: #f97316; 
  }

  h1, h2, h3, h4, h5, h6 {
    font-family: 'Abril Fatface', cursive;
    color: #22c55e; 
  }

  .container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 1rem;
  }

  @media (min-width: 768px) {
    .container {
      flex-direction: row;
      gap: 1rem;
    }
  }


  .card {
    background-color: #1f2937; 
    border-radius: 1.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
    width: 100%;
    padding: 1.25rem;
    margin-bottom: 1rem;
    transition: transform 0.3s, box-shadow 0.3s;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
  }

  body.dark .card {
    background-color: #1f2937;
    color: #fff;
  }

  @media (min-width: 768px) {
    .card {
      width: 40%;
      margin-bottom: 0;
    }
  }

  /* Header Styles */
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
  }

  .header i {
    color: #f97316; /* Orange accent for icons */
    cursor: pointer;
  }

  body.dark .header i {
    color: #d1d5db;
  }

  .date-badge {
    background-color: #22c55e; /* Green accent */
    color: white;
    border-radius: 9999px;
    padding: 0.375rem 0.875rem;
  }

  /* Blue Card Styles */
  .blue-card {
    background-color: #1e40af; /* Darker blue background */
    color: white;
    border-radius: 1.5rem;
    padding: 1.25rem;
    margin-bottom: 1.25rem;
  }

  .blue-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .blue-card h1 {
    font-size: 1.5rem;
    font-weight: 600;
  }

  .blue-card p {
    margin-top: 0.375rem;
  }

  .add-btn {
    background-color: white;
    color: #3b82f6;
    border: none;
    border-radius: 9999px;
    padding: 0.375rem 0.875rem;
    cursor: pointer;
    font-weight: 600;
    transition: transform 0.3s, box-shadow 0.3s;
  }

  .add-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
  }

  /* Progress Bar */
  .progress-container {
    margin-top: 1rem;
  }

  .progress-header {
    display: flex;
    justify-content: space-between;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
  }

  .progress-bar {
    width: 100%;
    background-color: #374151;
    border-radius: 9999px;
    height: 0.625rem;
  }

  .progress-fill {
    background-color: #22c55e;
    height: 100%;
    border-radius: 9999px;
    transition: width 0.3s ease;
  }

  /* Filter Buttons */
  .filter-container {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
  }

  .filter-btn {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    white-space: nowrap;
    background-color: #374151;
    color: #9ca3af;
  }

  .filter-btn.active {
    background-color: #22c55e;
    color: white;
  }

  .filter-btn:not(.active) {
    background-color: #e5e7eb;
    color: #4b5563;
  }

  body.dark .filter-btn:not(.active) {
    background-color: #374151;
    color: #d1d5db;
  }

  /* Task List */
  .task-list {
    display: flex;
    flex-direction: column;
    gap: 0.875rem;
  }

  .task-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #1f2937;
    color: #f97316;
    border-radius: 1.5rem;
    padding: 0.875rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s, box-shadow 0.3s;
  }

  .task-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
  }

  .task-item.completed {
    background-color: #374151;
    color: #9ca3af;
  }

  body.dark .task-item.completed {
    background-color: #374151;
    color: #9ca3af;
  }

  .task-left {
    display: flex;
    align-items: center;
    gap: 0.875rem;
  }

  .task-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 50%;
    cursor: pointer;
  }

  .task-info p:first-child {
    font-weight: 500;
  }

  .task-info p:first-child.completed {
    text-decoration: line-through;
  }

  .task-info p:last-child {
    font-size: 0.875rem;
  }

  .task-actions {
    display: flex;
    gap: 0.5rem;
  }

  .task-actions button {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
  }

  .task-item.completed .task-actions button {
    color: #4b5563;
  }

  body.dark .task-item.completed .task-actions button {
    color: #d1d5db;
  }

  .priority-dot {
    display: inline-block;
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 50%;
    margin-left: 0.5rem;
  }

  .priority-low {
    background-color: #10b981;
  }

  .priority-medium {
    background-color: #f59e0b;
  }

  .priority-high {
    background-color: #ef4444;
  }

  .task-time {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
  }

  .empty-tasks {
    text-align: center;
    padding: 2rem 0;
    color: #f97316;
  }

  body.dark .empty-tasks {
    color: #9ca3af;
  }

  /* Date Circles */
  .dates-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
  }

  .date-circle {
    width: 40px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    background-color: white;
    color: #111827;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .date-circle:hover {
    transform: scale(1.1);
  }

  .date-circle.selected {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
  }

  .date-circle.completed {
    background-color: #3b82f6;
    color: white;
  }

  body.dark .date-circle {
    background-color: #374151;
    color: white;
  }

  /* Category Items */
  .category-list {
    display: flex;
    flex-direction: column;
    gap: 0.875rem;
  }

  .category-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #1f2937;
    border-radius: 1.5rem;
    padding: 0.875rem;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
  }

  .category-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
  }

  body.dark .category-item {
    background-color: #374151;
  }

  .category-left {
    display: flex;
    align-items: center;
    gap: 0.875rem;
  }

  .category-icon {
    color: #22c55e;
  }

  .category-info p:first-child {
    color: #f97316;
    font-weight: 500;
  }

  .category-info p:last-child {
    color: #9ca3af;
    font-size: 0.875rem;
  }

  body.dark .category-info p:first-child {
    color: #f9fafb;
  }

  body.dark .category-info p:last-child {
    color: #9ca3af;
  }

  .category-right {
    color: #6b7280;
  }

  body.dark .category-right {
    color: #9ca3af;
  }

  /* Add Button */
  .floating-btn {
    
    margin-left: 85%; 
    margin-top: 5%;
    bottom: 1.25rem;
    right: 1.25rem;
    background-color: #3b82f6;
    color: white;
    border: none;
    border-radius: 50%;
    width: 3rem;
    height: 3rem;
    /* display: flex; */
    justify-content: right;
    align-items: right;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
  }

  .floating-btn:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
  }

  /* Modal Styles */
  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s;
  }

  .modal-content {
    background-color: #1f2937;
    color: #f97316;
    margin: 10% auto;
    width: 90%;
    max-width: 500px;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    animation: slideIn 0.3s;
  }

  body.dark .modal-content {
    background-color: #1f2937;
    color: white;
  }

  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #374151;
  }

  body.dark .modal-header {
    border-bottom: 1px solid #374151;
  }

  .modal-header h2 {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
  }

  .modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #9ca3af;
  }

  body.dark .modal-close {
    color: #9ca3af;
  }

  .modal-body {
    padding: 1rem;
  }

  .form-group {
    margin-bottom: 1rem;
  }

  .form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #f97316;
  }

  body.dark .form-group label {
    color: #d1d5db;
  }

  .form-group input,
  .form-group select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    background-color: #374151;
    border-color: #4b5563;
    color: #f97316;
  }

  body.dark .form-group input,
  body.dark .form-group select {
    background-color: #374151;
    border-color: #4b5563;
    color: white;
  }

  .priority-buttons {
    display: flex;
    gap: 0.5rem;
  }

  .priority-btn {
    flex: 1;
    padding: 0.5rem;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
  }

  .priority-btn.low {
    background-color: #e5e7eb;
    color: #111827;
  }

  .priority-btn.low.active {
    background-color: #10b981;
    color: white;
  }

  .priority-btn.medium {
    background-color: #e5e7eb;
    color: #111827;
  }

  .priority-btn.medium.active {
    background-color: #f59e0b;
    color: white;
  }

  .priority-btn.high {
    background-color: #e5e7eb;
    color: #111827;
  }

  .priority-btn.high.active {
    background-color: #ef4444;
    color: white;
  }

  body.dark .priority-btn:not(.active) {
    background-color: #374151;
    color: #d1d5db;
  }

  .modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    padding: 1rem;
    border-top: 1px solid #e5e7eb;
  }

  body.dark .modal-footer {
    border-top: 1px solid #374151;
  }

  .btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
  }

  .btn-cancel {
    background-color: #374151;
    color: #9ca3af;
  }

  body.dark .btn-cancel {
    background-color: #374151;
    color: #9ca3af;
  }

  .btn-primary {
    background-color: #22c55e;
    color: white;
  }

  /* Search Bar */
  .search-container {
    margin-bottom: 1rem;
    display: none;
  }

  .search-input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
  }

  body.dark .search-input {
    background-color: #374151;
    border-color: #4b5563;
    color: white;
  }

  /* Stats View */
  .stats-container {
    background-color: #1f2937;
    padding: 1rem;
    border-radius: 0.75rem;
    margin-bottom: 1.25rem;
    display: none;
  }

  body.dark .stats-container {
    background-color: #374151;
  }

  .stats-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
  }

  .stat-card {
    background-color: #374151;
    padding: 0.75rem;
    border-radius: 0.5rem;
  }

  body.dark .stat-card {
    background-color: #1f2937;
  }

  .stat-label {
    font-size: 0.875rem;
    color: #9ca3af;
  }

  body.dark .stat-label {
    color: #9ca3af;
  }

  .stat-value {
    font-size: 1.25rem;
    font-weight: 700;
  }

  /* Notification */
  .notification {
    position: fixed;
    bottom: 1rem;
    right: 1rem;
    background-color: #1f2937;
    color: #f97316;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 1001;
    animation: fadeInUp 0.3s;
    display: none;
  }

  /* Animations */
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  @keyframes slideIn {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
  }

  @keyframes fadeInUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
  }
  </style>
</head>
<body>
  <!-- Your HTML content here -->
  <div class="container">
    <!-- Left Panel -->
    <div class="card" id="left-panel">
      <div class="header">
        <i class="fas fa-search" id="search-toggle"></i>
        <span class="date-badge" id="current-date">Loading...</span>
      </div>

      <div class="search-container" id="search-container">
        <input type="text" class="search-input" id="search-input" placeholder="Search tasks...">
      </div>

      <div class="blue-card">
        <div class="blue-card-header">
          <h1>Today</h1>
          <button class="add-btn" id="add-task-btn">Add New</button>
        </div>
        <p>Tasks for today</p>

        <!-- Progress bar -->
        <div class="progress-container">
          <div class="progress-header">
            <span>Progress</span>
            <span id="progress-percentage">0%</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" id="progress-fill" style="width: 0%"></div>
          </div>
        </div>
      </div>

      <!-- Task filters -->
      <div class="filter-container" id="category-filters">
        <button class="filter-btn active" data-category="all">All</button>
        <!-- Category filters will be added dynamically -->
      </div>

      <!-- Task list -->
      <div class="task-list" id="task-list">
        <!-- Tasks will be added dynamically -->
        <div class="empty-tasks" id="empty-tasks">
          No tasks found. Add a new task to get started!
        </div>
      </div>
    </div>

    <!-- Right Panel -->
    <div class="card" id="right-panel">
      <div class="header">
        <i class="fas fa-chart-bar" id="stats-toggle"></i>
        <span>Task Categories</span>
        <i class="fas fa-plus" id="add-category-btn"></i>
      </div>

      <!-- Stats view -->
      <div class="stats-container" id="stats-container">
        <div class="stats-title">Task Statistics</div>
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-label">Total Tasks</div>
            <div class="stat-value" id="stat-total">0</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Completed</div>
            <div class="stat-value" id="stat-completed">0</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Today's Tasks</div>
            <div class="stat-value" id="stat-today">0</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Completion Rate</div>
            <div class="stat-value" id="stat-rate">0%</div>
          </div>
        </div>
      </div>

      <div class="dates-container" id="dynamic-dates">
        <!-- Dynamic dates will be added here -->
      </div>

      <div class="category-list" id="category-list">
        <!-- Categories will be added dynamically -->
      </div>

      <button class="floating-btn" id="floating-add-btn">
        <i class="fas fa-plus"></i>
      </button>
    </div>
  </div>

  <!-- Add/Edit Task Modal -->
  <div class="modal" id="task-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="task-modal-title">Add New Task</h2>
        <button class="modal-close" id="task-modal-close">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="task-name-input">Task Name:</label>
          <input type="text" id="task-name-input" placeholder="Enter task name" required>
        </div>
        <div class="form-group">
          <label for="task-category-select">Category:</label>
          <select id="task-category-select">
            <!-- Categories will be added dynamically -->
          </select>
        </div>
        <div class="form-group">
          <label>Priority:</label>
          <div class="priority-buttons">
            <button class="priority-btn low" data-priority="low">Low</button>
            <button class="priority-btn medium active" data-priority="medium">Medium</button>
            <button class="priority-btn high" data-priority="high">High</button>
          </div>
        </div>
        <div class="form-group">
          <label for="task-reminder-input">Reminder (optional):</label>
          <input type="time" id="task-reminder-input">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-cancel" id="task-modal-cancel">Cancel</button>
        <button class="btn btn-primary" id="task-modal-save">Add</button>
      </div>
    </div>
  </div>

  <!-- Add Category Modal -->
  <div class="modal" id="category-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Add New Category</h2>
        <button class="modal-close" id="category-modal-close">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="category-name-input">Category Name:</label>
          <input type="text" id="category-name-input" placeholder="Enter category name" required>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-cancel" id="category-modal-cancel">Cancel</button>
        <button class="btn btn-primary" id="category-modal-save">Add</button>
      </div>
    </div>
  </div>

  <!-- Reminder Modal -->
  <div class="modal" id="reminder-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Set Reminder</h2>
        <button class="modal-close" id="reminder-modal-close">&times;</button>
      </div>
      <div class="modal-body">
        <p id="reminder-task-name" class="mb-4">Set a reminder for: <strong>Task Name</strong></p>
        <div class="form-group">
          <label for="reminder-time-input">Time:</label>
          <input type="time" id="reminder-time-input" required>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-cancel" id="reminder-modal-cancel">Cancel</button>
        <button class="btn btn-primary" id="reminder-modal-save">Save</button>
      </div>
    </div>
  </div>

  
  <div class="notification" id="notification"></div>
  <script>
    
     let tasks = [];
    let categories = [
      { id: 1, name: "Idea", icon: "lightbulb", color: "blue", taskCount: 0 },
      { id: 2, name: "Food", icon: "utensils", color: "blue", taskCount: 0 },
      { id: 3, name: "Work", icon: "briefcase", color: "blue", taskCount: 0 },
      { id: 4, name: "Sport", icon: "dumbbell", color: "blue", taskCount: 0 },
      { id: 5, name: "Music", icon: "music", color: "blue", taskCount: 0 }
    ];
    let reminders = [];
    let selectedDate = new Date().getDate();
    let selectedCategory = null;
    let editingTaskId = null;
    let reminderTaskId = null;
    let selectedPriority = "medium";

    // DOM Elements
    const currentDateEl = document.getElementById('current-date');
    const taskListEl = document.getElementById('task-list');
    const emptyTasksEl = document.getElementById('empty-tasks');
    const categoryListEl = document.getElementById('category-list');
    const dynamicDatesEl = document.getElementById('dynamic-dates');
    const categoryFiltersEl = document.getElementById('category-filters');
    const progressFillEl = document.getElementById('progress-fill');
    const progressPercentageEl = document.getElementById('progress-percentage');
    const searchContainerEl = document.getElementById('search-container');
    const searchInputEl = document.getElementById('search-input');
    const statsContainerEl = document.getElementById('stats-container');
    const statTotalEl = document.getElementById('stat-total');
    const statCompletedEl = document.getElementById('stat-completed');
    const statTodayEl = document.getElementById('stat-today');
    const statRateEl = document.getElementById('stat-rate');
    const notificationEl = document.getElementById('notification');

    // Task Modal Elements
    const taskModalEl = document.getElementById('task-modal');
    const taskModalTitleEl = document.getElementById('task-modal-title');
    const taskNameInputEl = document.getElementById('task-name-input');
    const taskCategorySelectEl = document.getElementById('task-category-select');
    const taskReminderInputEl = document.getElementById('task-reminder-input');
    const taskModalSaveEl = document.getElementById('task-modal-save');

    // Category Modal Elements
    const categoryModalEl = document.getElementById('category-modal');
    const categoryNameInputEl = document.getElementById('category-name-input');

    // Reminder Modal Elements
    const reminderModalEl = document.getElementById('reminder-modal');
    const reminderTaskNameEl = document.getElementById('reminder-task-name');
    const reminderTimeInputEl = document.getElementById('reminder-time-input');

    // Initialize the app
    function init() {
      // Set current date
      updateCurrentDate();

      // Load data from localStorage
      loadData();

      // Generate dynamic dates
      generateDynamicDates();

      // Render tasks and categories
      renderTasks();
      renderCategories();
      renderCategoryFilters();
      updateCategoryTaskCounts();
      updateTaskStats();

      // Check for reminders every minute
      setInterval(checkReminders, 60000);
    }

    // Load data from localStorage
    function loadData() {
      const savedTasks = localStorage.getItem('tasks');
      if (savedTasks) {
        tasks = JSON.parse(savedTasks);
      }

      const savedCategories = localStorage.getItem('categories');
      if (savedCategories) {
        categories = JSON.parse(savedCategories);
      }

      const savedReminders = localStorage.getItem('reminders');
      if (savedReminders) {
        reminders = JSON.parse(savedReminders);
      }
    }

    // Save data to localStorage
    function saveData() {
      localStorage.setItem('tasks', JSON.stringify(tasks));
      localStorage.setItem('categories', JSON.stringify(categories));
      localStorage.setItem('reminders', JSON.stringify(reminders));
    }

    // Update current date
    function updateCurrentDate() {
      const today = new Date();
      const options = { day: 'numeric', month: 'short' };
      currentDateEl.textContent = today.toLocaleDateString('en-US', options);
    }

    // Generate dynamic dates
    function generateDynamicDates() {
      const today = new Date();
      dynamicDatesEl.innerHTML = '';

      for (let i = 0; i < 6; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() + i);
        const day = date.getDate();

        // Check if all tasks for this date are completed
        const tasksForDate = tasks.filter(task => task.date === day);
        const allCompleted = tasksForDate.length > 0 && 
                            tasksForDate.every(task => task.completed);

        const dateCircle = document.createElement('div');
        dateCircle.className = `date-circle ${day === selectedDate ? 'selected' : ''} ${allCompleted ? 'completed' : ''}`;
        dateCircle.textContent = day;
        dateCircle.dataset.date = day;
        dateCircle.addEventListener('click', () => {
          document.querySelectorAll('.date-circle').forEach(el => el.classList.remove('selected'));
          dateCircle.classList.add('selected');
          selectedDate = parseInt(dateCircle.dataset.date);
          renderTasks();
        });

        dynamicDatesEl.appendChild(dateCircle);
      }
    }

    // Render tasks
    function renderTasks() {
      // Filter tasks based on search, selected date, and category
      const searchTerm = searchInputEl.value.toLowerCase();
      const filteredTasks = tasks.filter(task => {
        const matchesSearch = task.name.toLowerCase().includes(searchTerm);
        const matchesDate = task.date === selectedDate;
        const matchesCategory = selectedCategory === null || task.categoryId === selectedCategory;
        
        return matchesSearch && matchesDate && matchesCategory;
      });

      // Clear task list
      while (taskListEl.firstChild && taskListEl.firstChild !== emptyTasksEl) {
        taskListEl.removeChild(taskListEl.firstChild);
      }

      // Show or hide empty message
      if (filteredTasks.length === 0) {
        emptyTasksEl.style.display = 'block';
      } else {
        emptyTasksEl.style.display = 'none';

        // Add tasks to the list
        filteredTasks.forEach(task => {
          const taskItem = document.createElement('div');
          taskItem.className = `task-item ${task.completed ? 'completed' : ''}`;
          taskItem.dataset.id = task.id;

          const category = categories.find(c => c.id === task.categoryId);
          const categoryName = category ? category.name : 'Uncategorized';

          taskItem.innerHTML = `
            <div class="task-left">
              <input type="checkbox" class="task-checkbox" ${task.completed ? 'checked' : ''}>
              <div class="task-info">
                <p class="${task.completed ? 'completed' : ''}">
                  ${task.name}
                  <span class="priority-dot priority-${task.priority}"></span>
                </p>
                <p class="task-meta">
                  ${task.dueTime ? `<span class="task-time"><i class="fas fa-clock"></i> ${task.dueTime}</span>` : ''}
                  ${categoryName}
                </p>
              </div>
            </div>
            <div class="task-actions">
              <button class="reminder-btn"><i class="fas fa-bell"></i></button>
              <button class="edit-btn"><i class="fas fa-edit"></i></button>
              <button class="delete-btn"><i class="fas fa-trash"></i></button>
            </div>
          `;

          // Add event listeners
          const checkbox = taskItem.querySelector('.task-checkbox');
          checkbox.addEventListener('change', () => {
            toggleTaskCompletion(task.id);
          });

          const reminderBtn = taskItem.querySelector('.reminder-btn');
          reminderBtn.addEventListener('click', () => {
            openReminderModal(task.id);
          });

          const editBtn = taskItem.querySelector('.edit-btn');
          editBtn.addEventListener('click', () => {
            openTaskModal('edit', task.id);
          });

          const deleteBtn = taskItem.querySelector('.delete-btn');
          deleteBtn.addEventListener('click', () => {
            deleteTask(task.id);
          });

          taskListEl.insertBefore(taskItem, emptyTasksEl);
        });
      }

      // Update progress
      updateProgress();
    }

    // Render categories
    function renderCategories() {
      categoryListEl.innerHTML = '';

      categories.forEach(category => {
        const categoryItem = document.createElement('div');
        categoryItem.className = 'category-item';
        categoryItem.dataset.id = category.id;

        categoryItem.innerHTML = `
          <div class="category-left">
            <i class="fas fa-${category.icon} category-icon"></i>
            <div class="category-info">
              <p>${category.name}</p>
              <p>${category.taskCount} tasks this week</p>
            </div>
          </div>
          <div class="category-right">
            <i class="fas fa-chevron-right"></i>
          </div>
        `;

        categoryItem.addEventListener('click', () => {
          if (selectedCategory === category.id) {
            selectedCategory = null;
          } else {
            selectedCategory = category.id;
          }
          updateCategoryFilters();
          renderTasks();
        });

        categoryListEl.appendChild(categoryItem);
      });
    }

    // Render category filters
    function renderCategoryFilters() {
      // Clear existing filters except "All"
      while (categoryFiltersEl.childElementCount > 1) {
        categoryFiltersEl.removeChild(categoryFiltersEl.lastChild);
      }

      // Add category filters
      categories.forEach(category => {
        const filterBtn = document.createElement('button');
        filterBtn.className = `filter-btn ${selectedCategory === category.id ? 'active' : ''}`;
        filterBtn.textContent = category.name;
        filterBtn.dataset.category = category.id;

        filterBtn.addEventListener('click', () => {
          selectedCategory = parseInt(filterBtn.dataset.category);
          updateCategoryFilters();
          renderTasks();
        });

        categoryFiltersEl.appendChild(filterBtn);
      });
    }

    // Update category filters active state
    function updateCategoryFilters() {
      const allFilterBtn = categoryFiltersEl.querySelector('[data-category="all"]');
      const categoryFilterBtns = categoryFiltersEl.querySelectorAll('[data-category]:not([data-category="all"])');

      if (selectedCategory === null) {
        allFilterBtn.classList.add('active');
        categoryFilterBtns.forEach(btn => btn.classList.remove('active'));
      } else {
        allFilterBtn.classList.remove('active');
        categoryFilterBtns.forEach(btn => {
          if (parseInt(btn.dataset.category) === selectedCategory) {
            btn.classList.add('active');
          } else {
            btn.classList.remove('active');
          }
        });
      }
    }

    // Update category task counts
    function updateCategoryTaskCounts() {
      // Reset all counts
      categories.forEach(category => {
        category.taskCount = 0;
      });

      // Count tasks for each category
      tasks.forEach(task => {
        const category = categories.find(c => c.id === task.categoryId);
        if (category) {
          category.taskCount++;
        }
      });

      // Update UI
      renderCategories();
    }

    // Update progress bar
    function updateProgress() {
      const todayTasks = tasks.filter(task => task.date === new Date().getDate());
      const completedTasks = todayTasks.filter(task => task.completed);
      const percentage = todayTasks.length > 0 
        ? Math.round((completedTasks.length / todayTasks.length) * 100) 
        : 0;

      progressFillEl.style.width = `${percentage}%`;
      progressPercentageEl.textContent = `${percentage}%`;
    }

    // Update task statistics
    function updateTaskStats() {
      const totalTasks = tasks.length;
      const completedTasks = tasks.filter(task => task.completed).length;
      const todayTasks = tasks.filter(task => task.date === new Date().getDate()).length;
      const completionRate = totalTasks > 0 
        ? Math.round((completedTasks / totalTasks) * 100) 
        : 0;

      statTotalEl.textContent = totalTasks;
      statCompletedEl.textContent = completedTasks;
      statTodayEl.textContent = todayTasks;
      statRateEl.textContent = `${completionRate}%`;
    }

    // Toggle task completion
    function toggleTaskCompletion(taskId) {
      const taskIndex = tasks.findIndex(task => task.id === taskId);
      if (taskIndex !== -1) {
        tasks[taskIndex].completed = !tasks[taskIndex].completed;
        saveData();
        renderTasks();
        generateDynamicDates();
        updateTaskStats();
        showNotification(`Task ${tasks[taskIndex].completed ? 'completed' : 'uncompleted'}`);
      }
    }

    // Delete task
    function deleteTask(taskId) {
      tasks = tasks.filter(task => task.id !== taskId);
      reminders = reminders.filter(reminder => reminder.taskId !== taskId);
      saveData();
      renderTasks();
      updateCategoryTaskCounts();
      generateDynamicDates();
      updateTaskStats();
      showNotification('Task deleted successfully');
    }

    // Open task modal
    function openTaskModal(mode, taskId = null) {
      // Reset form
      taskNameInputEl.value = '';
      taskReminderInputEl.value = '';
      selectedPriority = 'medium';
      updatePriorityButtons();

      // Set modal title and button text
      if (mode === 'add') {
        taskModalTitleEl.textContent = 'Add New Task';
        taskModalSaveEl.textContent = 'Add';
        editingTaskId = null;
      } else {
        taskModalTitleEl.textContent = 'Edit Task';
        taskModalSaveEl.textContent = 'Save';
        editingTaskId = taskId;

        // Fill form with task data
        const task = tasks.find(t => t.id === taskId);
        if (task) {
          taskNameInputEl.value = task.name;
          taskCategorySelectEl.value = task.categoryId;
          selectedPriority = task.priority;
          updatePriorityButtons();
          
          if (task.dueTime) {
            taskReminderInputEl.value = task.dueTime;
          }
        }
      }

      // Fill category select
      taskCategorySelectEl.innerHTML = '';
      categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        taskCategorySelectEl.appendChild(option);
      });

      // Set selected category
      if (selectedCategory !== null) {
        taskCategorySelectEl.value = selectedCategory;
      }

      // Show modal
      taskModalEl.style.display = 'block';
      taskNameInputEl.focus();
    }

    // Open category modal
    function openCategoryModal() {
      categoryNameInputEl.value = '';
      categoryModalEl.style.display = 'block';
      categoryNameInputEl.focus();
    }

    // Open reminder modal
    function openReminderModal(taskId) {
      const task = tasks.find(t => t.id === taskId);
      if (!task) return;

      reminderTaskId = taskId;
      reminderTaskNameEl.innerHTML = `Set a reminder for: <strong>${task.name}</strong>`;
      
      // Check if task already has a reminder
      const existingReminder = reminders.find(r => r.taskId === taskId);
      if (existingReminder) {
        reminderTimeInputEl.value = existingReminder.time;
      } else {
        reminderTimeInputEl.value = '';
      }

      reminderModalEl.style.display = 'block';
      reminderTimeInputEl.focus();
    }

    // Close modals
    function closeTaskModal() {
      taskModalEl.style.display = 'none';
    }

    function closeCategoryModal() {
      categoryModalEl.style.display = 'none';
    }

    function closeReminderModal() {
      reminderModalEl.style.display = 'none';
      reminderTaskId = null;
    }

    function saveTask() {
      const taskName = taskNameInputEl.value.trim();
      if (!taskName) {
        showNotification('Please enter a task name');
        return;
      }

      const categoryId = parseInt(taskCategorySelectEl.value);
      const reminderTime = taskReminderInputEl.value;

      if (editingTaskId === null) {
        const newTask = {
          id: Date.now(),
          name: taskName,
          completed: false,
          date: selectedDate,
          categoryId: categoryId,
          priority: selectedPriority,
          createdAt: new Date(),
          dueTime: reminderTime || undefined
        };

        tasks.push(newTask);

        if (reminderTime) {
          const newReminder = {
            id: Date.now(),
            taskId: newTask.id,
            time: reminderTime,
            triggered: false
          };
          reminders.push(newReminder);
        }

        showNotification('Task added successfully');
      } else {
        const taskIndex = tasks.findIndex(task => task.id === editingTaskId);
        if (taskIndex !== -1) {
          tasks[taskIndex].name = taskName;
          tasks[taskIndex].categoryId = categoryId;
          tasks[taskIndex].priority = selectedPriority;
          tasks[taskIndex].dueTime = reminderTime || tasks[taskIndex].dueTime;

          if (reminderTime) {
            const existingReminderIndex = reminders.findIndex(r => r.taskId === editingTaskId);
            
            if (existingReminderIndex >= 0) {
              reminders[existingReminderIndex].time = reminderTime;
              reminders[existingReminderIndex].triggered = false;
            } else {
              const newReminder = {
                id: Date.now(),
                taskId: editingTaskId,
                time: reminderTime,
                triggered: false
              };
              reminders.push(newReminder);
            }
          }

          showNotification('Task updated successfully');
        }
      }

      saveData();
      renderTasks();
      updateCategoryTaskCounts();
      updateTaskStats();
      closeTaskModal();
    }

    function saveCategory() {
      const categoryName = categoryNameInputEl.value.trim();
      if (!categoryName) {
        showNotification('Please enter a category name');
        return;
      }

      const newCategory = {
        id: Date.now(),
        name: categoryName,
        icon: 'briefcase', // Default icon
        color: 'blue',
        taskCount: 0
      };

      categories.push(newCategory);
      saveData();
      renderCategories();
      renderCategoryFilters();
      closeCategoryModal();
      showNotification('Category added successfully');
    }

    function saveReminder() {
      if (!reminderTaskId) return;
      
      const reminderTime = reminderTimeInputEl.value;
      if (!reminderTime) {
        showNotification('Please select a time');
        return;
      }

      const existingReminderIndex = reminders.findIndex(r => r.taskId === reminderTaskId);
      
      if (existingReminderIndex >= 0) {
        reminders[existingReminderIndex].time = reminderTime;
        reminders[existingReminderIndex].triggered = false;
      } else {
        const newReminder = {
          id: Date.now(),
          taskId: reminderTaskId,
          time: reminderTime,
          triggered: false
        };
        reminders.push(newReminder);
      }

      const taskIndex = tasks.findIndex(task => task.id === reminderTaskId);
      if (taskIndex !== -1) {
        tasks[taskIndex].dueTime = reminderTime;
      }

      saveData();
      renderTasks();
      closeReminderModal();
      showNotification('Reminder set successfully');
    }

    function checkReminders() {
      const now = new Date();
      const currentHour = now.getHours();
      const currentMinute = now.getMinutes();
      const currentTimeString = `${currentHour.toString().padStart(2, '0')}:${currentMinute.toString().padStart(2, '0')}`;
      
      reminders.forEach(reminder => {
        if (!reminder.triggered && reminder.time === currentTimeString) {
          const task = tasks.find(t => t.id === reminder.taskId);
          if (task) {
            showNotification(`Reminder: ${task.name}`, 10000);
            
            reminder.triggered = true;
            saveData();
            
            try {
              const audio = new Audio('/files/ringtone.mp3');
              audio.play();
            } catch (e) {
              console.log('Audio play failed:', e);
            }
          }
        }
      });
    }

    function updatePriorityButtons() {
      const priorityBtns = document.querySelectorAll('.priority-btn');
      priorityBtns.forEach(btn => {
        if (btn.dataset.priority === selectedPriority) {
          btn.classList.add('active');
        } else {
          btn.classList.remove('active');
        }
      });
    }

    function showNotification(message, duration = 3000) {
      notificationEl.textContent = message;
      notificationEl.style.display = 'block';
      
      setTimeout(() => {
        notificationEl.style.display = 'none';
      }, duration);
    }

    function toggleSearch() {
      const isVisible = searchContainerEl.style.display === 'block';
      searchContainerEl.style.display = isVisible ? 'none' : 'block';
      
      if (!isVisible) {
        searchInputEl.focus();
      } else {
        searchInputEl.value = '';
        renderTasks();
      }
    }

    // Toggle stats
    function toggleStats() {
      const isVisible = statsContainerEl.style.display === 'block';
      statsContainerEl.style.display = isVisible ? 'none' : 'block';
      
      if (!isVisible) {
        updateTaskStats();
      }
    }

  
    document.getElementById('add-task-btn').addEventListener('click', () => openTaskModal('add'));
    document.getElementById('floating-add-btn').addEventListener('click', () => openTaskModal('add'));
    document.getElementById('add-category-btn').addEventListener('click', openCategoryModal);
    document.getElementById('search-toggle').addEventListener('click', toggleSearch);
    document.getElementById('stats-toggle').addEventListener('click', toggleStats);

    document.getElementById('task-modal-close').addEventListener('click', closeTaskModal);
    document.getElementById('task-modal-cancel').addEventListener('click', closeTaskModal);
    document.getElementById('task-modal-save').addEventListener('click', saveTask);

    document.getElementById('category-modal-close').addEventListener('click', closeCategoryModal);
    document.getElementById('category-modal-cancel').addEventListener('click', closeCategoryModal);
    document.getElementById('category-modal-save').addEventListener('click', saveCategory);

    document.getElementById('reminder-modal-close').addEventListener('click', closeReminderModal);
    document.getElementById('reminder-modal-cancel').addEventListener('click', closeReminderModal);
    document.getElementById('reminder-modal-save').addEventListener('click', saveReminder);

    document.querySelectorAll('.priority-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        selectedPriority = btn.dataset.priority;
        updatePriorityButtons();
      });
    });

   
    searchInputEl.addEventListener('input', renderTasks);

   
    document.querySelector('.filter-btn[data-category="all"]').addEventListener('click', () => {
      selectedCategory = null;
      updateCategoryFilters();
      renderTasks();
    });

    
    init();
 
  </script>

</body>
</html>