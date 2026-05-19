<?php
session_start();

// FIX: Fixed include path to correctly escape admin-pages folder layout
include("../database/database.php");

$barangay_name = "Tetuan"; 
$admin_name = "MARC LAURENCE";

/** @var mysqli $conn */ 
// --- START FILTERING LOGIC ---
// 1. Check if a specific category filter was submitted via GET, otherwise default to All
$selected_category = isset($_GET['category']) ? $_GET['category'] : 'All Categories';

// 2. Adjust the SQL Query dynamically based on the filter selection AND include all updated statuses
if ($selected_category == 'All Categories') {
    // Fetches all matching statuses for this barangay
    $sql = "SELECT * FROM reports 
            WHERE barangay = '$barangay_name' 
            AND status IN ('standby', 'In progress', 'Reported to LGU', 'Resolved') 
            ORDER BY date_submitted DESC";
} else {
    // Filter by both the category selection AND all matching statuses
    $safe_category = mysqli_real_escape_string($conn, $selected_category);
    $sql = "SELECT * FROM reports 
            WHERE barangay = '$barangay_name' 
            AND category = '$safe_category' 
            AND status IN ('standby', 'In progress', 'Reported to LGU', 'Resolved') 
            ORDER BY date_submitted DESC";
}
// --- END FILTERING LOGIC ---

$run = mysqli_query($conn, $sql);

// Calculate total records matching your active dashboard filters
$total_active = mysqli_num_rows($run);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Street Cred</title>
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

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
            border: 1px solid transparent;
            transition: all 0.2s ease-in-out;
            background: #f1f5f9;
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
            overflow-x: auto; /* Ensures large tables scroll horizontally instead of breaking layout */
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
            cursor: pointer;
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
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .report-desc-cell {
            max-width: 220px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Image preview styles */
        .img-preview {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            display: block;
        }
        

        .no-img-text {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-style: italic;
        }

        /* Status Badges */
        .btn-update {
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            transition: 0.2s;
            white-space: nowrap;
        }

        .btn-update:hover {
            opacity: 0.85;
        }

        /* Dynamic Status Color Rules */
        .status-standby { background: #cbd5e1; color: #1e293b; }
        .status-inprogress { background: #dbeafe; color: #1e40af; }
        .status-reportedtolgu { background: #fee2e2; color: #991b1b; }
        .status-resolved { background: #dcfce7; color: #166534; }

        @media (max-width: 1024px) {
            .sidebar { width: 80px; padding: 15px; }
            .sidebar-brand span, .current-scope, .nav-item span { display: none; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-city"></i> <span>Street Cred</span>
        </div>
        
        <div class="current-scope">
            <small>Jurisdiction</small>
            <p><strong>Barangay <?php echo $barangay_name; ?></strong></p> 
        </div>

        <nav class="sidebar-nav">
            <a href="../home-page/homepage.php" class="nav-item "><i class="fas fa-home"></i> <span>Home</span></a>
            <a href="barangay-dashboard.php" class="nav-item "><i class="fas fa-map-marker-alt"></i> <span>Reports</span></a>
            <a href="#" class="nav-item active"><i class="fas fa-history"></i> <span>Resolution Logs</span></a>
        </nav>
        </nav>
    </aside>

    <main class="main-content">
        <header class="content-header">
            <div class="header-left">
                <h1>All reports</h1>
                <p>Monitoring submitted infrastructure logs for <strong><?php echo $barangay_name; ?></strong></p>
            </div>
           <div class="header-right">
                <div class="user-badge">
                    <i class="fas fa-user-circle"></i>
                    <span>Official: <strong><?php echo $admin_name; ?></strong></span>
                    <a href="" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </header>

        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-label">Total Barangay Reports</span>
                <span class="stat-value"><?php echo $total_active; ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Current Scope Filter</span>
                <span class="stat-value" style="font-size: 1.2rem; margin-top: 5px; display: inline-block;">
                    <?php echo htmlspecialchars($selected_category); ?>
                </span>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header-actions">
                <h2>All Database Attributes View</h2>
                <div class="filter-group">
                    <select class="filter-dropdown" onchange="location = this.value;">
                        <option value="?category=All Categories" <?php if($selected_category == 'All Categories') echo 'selected'; ?>>All Categories</option>
                        <option value="?category=Drainage Issues" <?php if($selected_category == 'Drainage Issues') echo 'selected'; ?>>Drainage Issues</option>
                        <option value="?category=Waste Management" <?php if($selected_category == 'Waste Management') echo 'selected'; ?>>Waste Management</option>
                        <option value="?category=Road/Pothole" <?php if($selected_category == 'Road/Pothole') echo 'selected'; ?>>Road/Pothole</option>
                        <option value="?category=Streetlight" <?php if($selected_category == 'Streetlight') echo 'selected'; ?>>Streetlight</option>
                        <option value="?category=Traffic Concern" <?php if($selected_category == 'Traffic Concern') echo 'selected'; ?>>Traffic Concern</option>
                        <option value="?category=Security Issue" <?php if($selected_category == 'Security Issue') echo 'selected'; ?>>Security Issue</option>
                        <option value="?category=Infrastructure" <?php if($selected_category == 'Infrastructure') echo 'selected'; ?>>Infrastructure</option>
                        <option value="?category=Other" <?php if($selected_category == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                </div>
            </div>
            
            <table class="reports-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Barangay</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Image Reference</th>
                        <th>Date Submitted</th>
                        <th>Status Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($total_active > 0) {
                        while ($row = mysqli_fetch_array($run)){
                            // Format status text into CSS selector keys safely (e.g., "Reported to LGU" -> "reportedtolgu")
                            $status_class = "status-" . strtolower(str_replace(' ', '', $row['status']));
                            ?>
                            <tr>
                                <td><strong>#<?php echo $row['ID']; ?></strong></td>
                                
                                <td>
                                    <?php 
                                    echo ($row['user_id'] !== NULL && $row['user_id'] != 0) 
                                        ? "User ID: " . htmlspecialchars($row['user_id']) 
                                        : "<span style='color: var(--text-muted); font-style: italic;'>Anonymous</span>"; 
                                    ?>
                                </td>
                                
                                <td><?php echo htmlspecialchars($row['barangay']); ?></td>
                                
                                <td><span style="font-weight: 600; color: var(--admin-blue);"><?php echo htmlspecialchars($row['category']); ?></span></td>
                                
                                <td class="report-desc-cell" title="<?php echo htmlspecialchars($row['description']); ?>">
                                    <?php echo htmlspecialchars($row['description']); ?>
                                </td>
                                
                                <td>
                                    <?php if(!empty($row['image_path'])): ?>
                                        <img src="../<?php echo htmlspecialchars($row['image_path']); ?>" class="img-preview" alt="Issue Attachment">
                                    <?php else: ?>
                                        <span class="no-img-text">No image uploaded</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td>
                                    <span class="badge date-badge" style="padding: 6px 12px; border-radius: 4px; font-size: 0.85rem;">
                                        <?php 
                                        // Uses the table row's exact timestamp or defaults safely if null
                                        $date_val = !empty($row['date_submitted']) ? $row['date_submitted'] : 'now';
                                        echo date("M d, Y g:i A", strtotime($date_val)); 
                                        ?>
                                    </span>
                                </td>
                                
                                <td>
                                    <button class="btn-update <?php echo $status_class; ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px; font-style: italic;">
                                No records found matching category: "<?php echo htmlspecialchars($selected_category); ?>"
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>