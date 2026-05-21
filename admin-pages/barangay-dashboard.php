<?php
session_start();

include("../database/database.php");

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'barangay_admin' && $_SESSION['user_role'] !== 'lgu_admin')) {
    $barangay_name = "Pitipiwpiw"; 
    $admin_name = "Demo Name";
} else {
    $barangay_name = $_SESSION['user_barangay']; 
    $admin_name = $_SESSION['first_name'];
}

/** @var mysqli $conn */ 

// --- START STATUS UPDATE ACTION HANDLER ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_update_status'])) {
    $report_id = intval($_POST['report_id']);
    $target_status = mysqli_real_escape_string($conn, $_POST['target_status']);
    
    // Process update query securely 
    $update_sql = "UPDATE reports SET status = '$target_status' WHERE ID = $report_id AND barangay = '$barangay_name'";
    mysqli_query($conn, $update_sql);
    
    // Refresh to prevent form duplicate submissions on page reload
    header("Location: " . $_SERVER['PHP_SELF'] . (isset($_GET['category']) ? "?category=" . urlencode($_GET['category']) : ""));
    
    // IF SENDING TO LGU / CHANGING STATUS
    if (isset($_POST['action_update_status'])) {
        $report_id = intval($_POST['report_id']);
        $target_status = mysqli_real_escape_string($conn, $_POST['target_status']);
        
        // Process update query securely 
        $update_sql = "UPDATE reports SET status = '$target_status' WHERE ID = $report_id AND barangay = '$barangay_name'";
        mysqli_query($conn, $update_sql);
        
        // Refresh to prevent form duplicate submissions on page reload
        header("Location: " . $_SERVER['PHP_SELF'] . (isset($_GET['category']) ? "?category=" . urlencode($_GET['category']) : ""));
        exit();
    }
    
    // CASE 2: IF CANCELING AND DELETING THE TICKET COMPLETELY
    if (isset($_POST['action_delete_report'])) {
        $report_id = intval($_POST['report_id']);
        
        // Securely delete from your local community scope jurisdiction
        $delete_sql = "DELETE FROM reports WHERE ID = $report_id AND barangay = '$barangay_name'";
        mysqli_query($conn, $delete_sql);
        
        // Redirect back to dashboard (removes the ?review_id from the URL cleanly)
        header("Location: " . $_SERVER['PHP_SELF'] . (isset($_GET['category']) ? "?category=" . urlencode($_GET['category']) : ""));
        exit();
    }
    
    exit();
}
// --- END STATUS UPDATE ACTION HANDLER ---


// --- START FILTERING LOGIC ---
$selected_category = isset($_GET['category']) ? $_GET['category'] : 'All Categories';
$category_filter_sql = "";

if ($selected_category !== 'All Categories') {
    $safe_category = mysqli_real_escape_string($conn, $selected_category);
    $category_filter_sql = " AND category = '$safe_category'";
}
// --- END FILTERING LOGIC ---


// --- DATA EXTRACTION: PIPELINE QUEUES ---

// Pipeline 1: New Reports (Standby only)
$sql_standby = "SELECT * FROM reports WHERE barangay = '$barangay_name' AND status = 'standby' $category_filter_sql ORDER BY date_submitted DESC";
$run_standby = mysqli_query($conn, $sql_standby);
$total_standby = mysqli_num_rows($run_standby);

// Pipeline 2: Active Reports Queue (In progress, Reported to LGU)
$sql_active = "SELECT * FROM reports WHERE barangay = '$barangay_name' AND status IN ('In progress', 'Reported to LGU') $category_filter_sql ORDER BY date_submitted DESC";
$run_active = mysqli_query($conn, $sql_active);
$total_active = mysqli_num_rows($run_active);

// Pipeline 3: Resolved Reports Archetype
$sql_resolved = "SELECT * FROM reports WHERE barangay = '$barangay_name' AND status = 'Resolved' $category_filter_sql ORDER BY date_submitted DESC";
$run_resolved = mysqli_query($conn, $sql_resolved);
$total_resolved = mysqli_num_rows($run_resolved);


// --- DETAILED ENTRY LOOKUP (FOR REVIEW MODE) ---
$review_mode = false;
$review_report = null;
$user = null;

if (isset($_GET['review_id'])) {
    $review_id = intval($_GET['review_id']);
    
    // 1. Get the report first
    $sql_review = "SELECT * FROM reports WHERE ID = $review_id AND barangay = '$barangay_name' LIMIT 1";
    $run_review = mysqli_query($conn, $sql_review);

    if (mysqli_num_rows($run_review) > 0) {
        $review_mode = true;
        $review_report = mysqli_fetch_assoc($run_review);
        
        // 2. Now check if the report has a user_id linked to it
        if (!empty($review_report['user_id'])) {
            $reporter_id = intval($review_report['user_id']); // Safe integer conversion
            
            // 3. Query the users table using the actual reporter's ID
            $sql_get_user = "SELECT * FROM users WHERE ID = $reporter_id LIMIT 1";
            $run_get_user = mysqli_query($conn, $sql_get_user);
            
            if (mysqli_num_rows($run_get_user) > 0) {
                // 4. Fetch from the CORRECT result pointer ($run_get_user)
                $user = mysqli_fetch_assoc($run_get_user);
            }
        }
    }
}

// Generate link persistent strings for smooth routing transitions
$category_query_param = (isset($_GET['category'])) ? "&category=" . urlencode($_GET['category']) : "";
$back_to_dashboard_url = $_SERVER['PHP_SELF'] . (isset($_GET['category']) ? "?category=" . urlencode($_GET['category']) : "");
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
            --accent-lgu: #9333ea;
            --danger: #ef4444;
            --success: #166534;
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

        /* Header Right Layout */
        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-badge {
            background: var(--white);
            padding: 10px 20px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Logout Button Styles */
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

        .btn-logout:hover {
            color: var(--danger);
            background: #fef2f2;
            border-color: #fee2e2;
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

        /* Container & Grid layout for sections */
        .table-container {
            background: var(--white);
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            overflow-x: auto;
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
            padding: 15px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            vertical-align: middle;
        }

        /* Description constraints */
        .desc-text-box {
            max-width: 250px;
            white-space: normal;
            word-wrap: break-word;
            color: #334155;
        }

        /* Image reference design */
        .img-box-container img {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            display: block;
        }

        .no-img-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-style: italic;
        }

        /* Badges & Button Formatting */
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .badge.date-badge {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .btn-update {
            border: none;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 600;
            text-transform: capitalize;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action-trigger {
            background: var(--admin-blue);
            color: var(--white);
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.82rem;
            cursor: pointer;
            transition: 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 4px rgba(26, 66, 115, 0.2);
        }

        .btn-action-trigger:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* Status Colors Mapping */
        .status-standby { background: #cbd5e1; color: #1e293b; }
        .status-inprogress { background: #dbeafe; color: #1e40af; }
        .status-reportedtolgu { background: #fee2e2; color: #991b1b; }
        .status-resolved { background: #dcfce7; color: #166534; }

        /* --- NEW REVIEW BLOCK STYLES --- */
        .review-card-container {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            position: relative;
            padding: 35px;
            margin-bottom: 40px;
        }
        .review-close-btn {
            position: absolute;
            top: 25px;
            right: 25px;
            font-size: 1.5rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: 0.2s;
        }
        .review-close-btn:hover {
            color: var(--danger);
        }
        .review-grid-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            margin-top: 20px;
        }
        .review-meta-item {
            margin-bottom: 20px;
        }
        .review-meta-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .review-meta-value {
            font-size: 1.05rem;
            color: var(--text-main);
        }
        .review-large-img {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            background: #f1f5f9;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
        }
        .review-action-footer {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }
        .btn-cancel {
            background: #f1f5f9;
            color: var(--text-main);
            border: 1px solid #cbd5e1;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
        }
        .btn-cancel:hover {
            background: #e2e8f0;
        }
        .btn-send-lgu {
            background: var(--accent-lgu);
            color: var(--white);
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 4px 6px -1px rgba(147, 51, 234, 0.2);
        }
        .btn-send-lgu:hover {
            opacity: 0.95;
        }

        @media (max-width: 1024px) {
            .sidebar { width: 80px; padding: 15px; }
            .sidebar-brand span, .current-scope, .nav-item span { display: none; }
            .stats-grid { grid-template-columns: 1fr; }
            .review-grid-layout { grid-template-columns: 1fr; }
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
            <a href="barangay-dashboard.php" class="nav-item active"><i class="fas fa-map-marker-alt"></i> <span>Reports</span></a>
            <a href="resolution-logs.php" class="nav-item "><i class="fas fa-history"></i> <span>Resolution Logs</span></a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="content-header">
            <div class="header-left">
                <h1>Infrastructure Live Dashboard</h1>
                <p>Monitoring workflows for <strong>Barangay <?php echo $barangay_name; ?></strong></p>
            </div>
            <div class="header-right">
                <div class="user-badge">
                    <i class="fas fa-user-circle"></i>
                    <span>Official: <strong><?php echo $admin_name; ?></strong></span>
                    <button name="logout-btn" class="btn-logout" onclick="logout()">
                        <i class="fas fa-sign-out-alt">Logout</i> 
                    </button>
                </div>
            </div>
        </header>

        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-label">New Submissions (Standby)</span>
                <span class="stat-value" style="color: #64748b;"><?php echo $total_standby; ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-label">In Progress / Active Queue</span>
                <span class="stat-value" style="color: var(--admin-blue);"><?php echo $total_active; ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Total Completed History</span>
                <span class="stat-value" style="color: #166534;"><?php echo $total_resolved; ?></span>
            </div>
        </div>

        <?php if (!$review_mode): ?>
            <div style="display: flex; justify-content: flex-end; margin-bottom: 25px;">
                <div class="filter-group">
                    <label style="font-weight: 600; font-size: 0.9rem; margin-right: 10px; color: var(--text-muted);">Sort Category View:</label>
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
        <?php endif; ?>

        <?php if ($review_mode): ?>
            
            <div class="review-card-container" style="border-top: 4px solid var(--accent-lgu);">
                <a href="<?php echo $back_to_dashboard_url; ?>" class="review-close-btn" title="Back to Queues">
                    <i class="fas fa-times-circle"></i>
                </a>

                <h2><i class="fas fa-clipboard-check" style="color: var(--accent-lgu); margin-right: 8px;"></i> Report Review Space</h2>
                <p style="color: var(--text-muted); margin-bottom: 10px;">Inspecting details for Ticket <strong>#<?php echo $review_report['ID']; ?></strong></p>

                <div class="review-grid-layout">
                    <div>
                        <div class="review-meta-item">
                            <span class="review-meta-label">Report ID</span>
                            <div class="review-meta-value"><strong>#<?php echo $review_report['ID']; ?></strong></div>
                        </div>

                        <div class="review-meta-item">
                            <span class="review-meta-label">Reporter Name / Ownership</span>
                            <div class="review-meta-value">
                                <?php 
                                if (!empty($review_report['user_id']) && !empty($user)) {
                                    echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); 
                                } else {
                                    echo "<strong>Anonymous Submitter</strong>"; 
                                }
                                ?>
                            </div>
                        </div>

                        <div class="review-meta-item">
                            <span class="review-meta-label">Issue Category Type</span>
                            <div class="review-meta-value" style="font-weight: 600; color: var(--admin-blue);">
                                <?php echo htmlspecialchars($review_report['category']); ?>
                            </div>
                        </div>

                        <div class="review-meta-item">
                            <span class="review-meta-label">Detailed Problem Description</span>
                            <div class="review-meta-value" style="background: #f1f5f9; padding: 15px; border-radius: 6px; line-height: 1.6; color: #334155;">
                                <?php echo nl2br(htmlspecialchars($review_report['description'])); ?>
                            </div>
                        </div>

                        <div class="review-meta-item">
                            <span class="review-meta-label">Date and Time Received</span>
                            <div class="review-meta-value">
                                <span class="badge date-badge"><i class="far fa-clock"></i> <?php echo date("F d, Y @ g:i A", strtotime($review_report['date_submitted'])); ?></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <span class="review-meta-label">Image Attachment Blueprint</span>
                        <?php if(!empty($review_report['image_path'])): ?>
                            <img src="../<?php echo htmlspecialchars($review_report['image_path']); ?>" class="review-large-img" alt="Detailed Attachment visual window">
                        <?php else: ?>
                            <div style="background: #f1f5f9; padding: 40px 20px; text-align: center; border-radius: 8px; color: var(--text-muted); font-style: italic; border: 1px dashed #cbd5e1;">
                                <i class="fas fa-image" style="font-size: 2.5rem; margin-bottom: 10px; display: block; color: #cbd5e1;"></i>
                                No Visual Media Attached
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="review-action-footer">

                    <form method="POST" onsubmit="return confirm('Are you absolutely sure you want to cancel and permanently delete this report? This cannot be undone.');" style="display: inline-block;">
                        <input type="hidden" name="report_id" value="<?php echo $review_report['ID']; ?>">
                        <button type="submit" name="action_delete_report" class="btn-cancel" style="background: var(--danger); color: white; border: none;">
                            <i class="fas fa-trash-alt" style="margin-right: 6px;"></i> Cancel & Delete Report
                        </button>
                    </form>

                    <form method="POST" style="display: inline-block;">
                        <input type="hidden" name="report_id" value="<?php echo $review_report['ID']; ?>">
                        <input type="hidden" name="target_status" value="Reported to LGU">
                        <button type="submit" name="action_update_status" class="btn-send-lgu">
                            <i class="fas fa-paper-plane" style="margin-right: 6px;"></i> Send to LGU
                        </button>
                    </form>
                </div>
            </div>
            <?php else: ?>
            
            <div class="table-container" style="border-top: 4px solid #cbd5e1;">
                <div class="table-header-actions">
                    <h2><i class="fas fa-bell" style="color: #64748b; margin-right: 8px;"></i> New Reports Queue <span style="font-size: 1.1rem; color: var(--text-muted);"> (Standby Status)</span></h2>
                </div>
                
                <table class="reports-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Reporter ID</th>
                            <th>Issue Category</th>
                            <th>Problem Description</th>
                            <th>Image Attachment</th>
                            <th>Date Submitted</th>
                            <th>Workflow Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($total_standby > 0) {
                            while ($row = mysqli_fetch_array($run_standby)){
                                ?>
                                <tr>
                                    <td><strong>#<?php echo $row['ID']; ?></strong></td>
                                    <td><?php echo (!empty($row['user_id'])) ? "UID-" . htmlspecialchars($row['user_id']) : "<span style='color:var(--text-muted); font-style:italic;'>Anonymous</span>"; ?></td>
                                    <td><span style="font-weight: 600;"><?php echo htmlspecialchars($row['category']); ?></span></td>
                                    <td><div class="desc-text-box"><?php echo htmlspecialchars($row['description']); ?></div></td>
                                    <td>
                                        <div class="img-box-container">
                                            <?php if(!empty($row['image_path'])): ?>
                                                <img src="../<?php echo htmlspecialchars($row['image_path']); ?>" alt="Attachment Visual">
                                            <?php else: ?>
                                                <span class="no-img-label">No Image Attached</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><span class="badge date-badge"><?php echo date("M d, Y g:i A", strtotime($row['date_submitted'])); ?></span></td>
                                    <td>
                                        <a href="?review_id=<?php echo $row['ID'] . $category_query_param; ?>" class="btn-action-trigger">
                                            <i class="fas fa-search"></i> Review
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px; font-style: italic;">No new standby reports found for this folder filter view.</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="table-container" style="border-top: 4px solid var(--admin-blue);">
                <div class="table-header-actions">
                    <h2><i class="fas fa-tools" style="color: var(--admin-blue); margin-right: 8px;"></i> Recent Report Queue <span style="font-size: 1.1rem; color: var(--text-muted);"> (In progress / Reported to LGU)</span></h2>
                </div>
                
                <table class="reports-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Reporter ID</th>
                            <th>Issue Category</th>
                            <th>Problem Description</th>
                            <th>Image Attachment</th>
                            <th>Date Submitted</th>
                            <th>Current Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($total_active > 0) {
                            while ($row = mysqli_fetch_array($run_active)){
                                $status_class = "status-" . strtolower(str_replace(' ', '', $row['status']));
                                ?>
                                <tr>
                                    <td><strong>#<?php echo $row['ID']; ?></strong></td>
                                    <td><?php echo (!empty($row['user_id'])) ? "UID-" . htmlspecialchars($row['user_id']) : "<span style='color:var(--text-muted); font-style:italic;'>Anonymous</span>"; ?></td>
                                    <td><span style="font-weight: 600; color: var(--admin-blue);"><?php echo htmlspecialchars($row['category']); ?></span></td>
                                    <td><div class="desc-text-box"><?php echo htmlspecialchars($row['description']); ?></div></td>
                                    <td>
                                        <div class="img-box-container">
                                            <?php if(!empty($row['image_path'])): ?>
                                                <img src="../<?php echo htmlspecialchars($row['image_path']); ?>" alt="Attachment Visual">
                                            <?php else: ?>
                                                <span class="no-img-label">No Image Attached</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><span class="badge date-badge"><?php echo date("M d, Y g:i A", strtotime($row['date_submitted'])); ?></span></td>
                                    <td>
                                        <button class="btn-update <?php echo $status_class; ?>">
                                            <i class="fas <?php echo ($row['status'] == 'In progress') ? 'fa-spinner fa-spin' : 'fa-building'; ?>"></i> 
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px; font-style: italic;">No current processing entries under active statuses.</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="table-container" style="border-top: 4px solid #166534;">
                <div class="table-header-actions">
                    <h2><i class="fas fa-check-circle" style="color: #166534; margin-right: 8px;"></i> Resolved History Archive <span style="font-size: 1.1rem; color: var(--text-muted);"> (Closed Tickets)</span></h2>
                </div>
                
                <table class="reports-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Reporter ID</th>
                            <th>Issue Category</th>
                            <th>Problem Description</th>
                            <th>Image Attachment</th>
                            <th>Date Submitted</th>
                            <th>Resolution Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($total_resolved > 0) {
                            while ($row = mysqli_fetch_array($run_resolved)){
                                ?>
                                <tr>
                                    <td><strong>#<?php echo $row['ID']; ?></strong></td>
                                    <td><?php echo (!empty($row['user_id'])) ? "UID-" . htmlspecialchars($row['user_id']) : "<span style='color:var(--text-muted); font-style:italic;'>Anonymous</span>"; ?></td>
                                    <td><span style="font-weight: 600; color: #166534;"><?php echo htmlspecialchars($row['category']); ?></span></td>
                                    <td><div class="desc-text-box" style="color: var(--text-muted);"><?php echo htmlspecialchars($row['description']); ?></div></td>
                                    <td>
                                        <div class="img-box-container" style="opacity: 0.75;">
                                            <?php if(!empty($row['image_path'])): ?>
                                                <img src="../<?php echo htmlspecialchars($row['image_path']); ?>" alt="Attachment Visual">
                                            <?php else: ?>
                                                <span class="no-img-label">No Image Attached</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><span class="badge date-badge"><?php echo date("M d, Y g:i A", strtotime($row['date_submitted'])); ?></span></td>
                                    <td>
                                        <span class="badge status-resolved">
                                            <i class="fas fa-check-double"></i> <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px; font-style: italic;">Archive is completely empty. No resolved reports found.</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </main>
</div>

</body>
<script src="../scripts/script.js"></script>
</html>