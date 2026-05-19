<?php
session_start();
// Mock data for display - in your real app, these would come from MySQL
$barangay_name = "Tetuan"; 
$admin_name = "MARC LAURENCE";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Street Cred</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --admin-dark: #0f2a4a;
            --admin-blue: #1a4273;
            --admin-light: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--admin-light);
            color: var(--text-main);
        }

        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: var(--admin-dark);
            color: var(--white);
            padding: 25px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 35px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .current-scope {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #8bb4e6;
        }

        .current-scope small {
            display: block;
            color: #94a3b8;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #cbd5e1;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            transition: 0.2s;
            font-size: 0.95rem;
        }

        .nav-item:hover, .nav-item.active {
            background: var(--admin-blue);
            color: var(--white);
        }

        .admin-divider {
            font-size: 0.75rem;
            color: #475569;
            margin: 25px 0 10px 10px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
        }

        .header-left h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .header-left p {
            color: var(--text-muted);
        }

        .user-badge {
            background: var(--white);
            padding: 10px 20px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--white);
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .stat-label {
            display: block;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--admin-blue);
        }

        /* Table Styles */
        .table-container {
            background: var(--white);
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .table-header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .filter-dropdown {
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: var(--admin-light);
            font-size: 0.9rem;
            outline: none;
        }

        .reports-table {
            width: 100%;
            border-collapse: collapse;
        }

        .reports-table th {
            text-align: left;
            padding: 15px;
            background: #f1f5f9;
            color: var(--text-muted);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .reports-table td {
            padding: 20px 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.95rem;
        }

        /* Status Badges */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge.progress {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-update {
            background: #e2e8f0;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-update:hover {
            background: #cbd5e1;
        }

        @media (max-width: 1024px) {
            .sidebar { width: 80px; padding: 15px; }
            .sidebar-brand span, .current-scope, .nav-item span, .admin-divider { display: none; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-city"></i> <span>Street Cred</span>
        </div>
        
        <div class="current-scope">
            <small>Jurisdiction</small>
            <p><strong>Barangay <?php echo $barangay_name; ?></strong></p> 
        </div>

        <nav class="sidebar-nav">
            <a href="#" class="nav-item"><i class="fas fa-map-marker-alt"></i> <span>Reports</span></a>
            <a href="#" class="nav-item"><i class="fas fa-history"></i> <span>Resolution Logs</span></a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="content-header">
            <div class="header-left">
                <h1>Barangay Management</h1>
                <p>Handling infrastructure reports for <strong><?php echo $barangay_name; ?> District</strong></p>
            </div>
            <div class="header-right">
                <div class="user-badge">
                    <i class="fas fa-user-circle"></i>
                    <span>Official: <strong><?php echo $admin_name; ?></strong></span>
                </div>
            </div>
        </header>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-label">Active in <?php echo $barangay_name; ?></span>
                <span class="stat-value">12</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Response Rate</span>
                <span class="stat-value">92%</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Last Resolved</span>
                <span class="stat-value">2h ago</span>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            <div class="table-header-actions">
                <h2>Local Report Queue</h2>
                <div class="filter-group">
                    <select class="filter-dropdown">
                        <option>All Categories</option>
                        <option>Waste Management</option>
                        <option>Roads & Potholes</option>
                    </select>
                </div>
            </div>
            
            <table class="reports-table">
                <thead>
                    <tr>
                        <th>Problem</th>
                        <th>Specific Landmark</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Uncollected Waste</strong></td>
                        <td>Near Tetuan Parish Church</td>
                        <td><span class="badge progress">In Progress</span></td>
                        <td><button class="btn-update">Update Log</button></td>
                    </tr>
                    <!-- Database loop starts here in your real PHP version -->
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>