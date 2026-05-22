<?php
session_start();

include("../database/database.php");

// Fetch active identity variables from session tracking
$user_name = ($_SESSION['first_name'] ?? 'Admin') . ' ' . ($_SESSION['last_name'] ?? '');
$admin_barangay = $_SESSION['user_barangay'] ?? 'Zamboanga City';
$lgu_department = $_SESSION['lgu_department'] ?? 'General Infrastructure';

$status_message = "";
$error_message = "";

// 2. Process Post Actions using standard, normal SQL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_trigger'])) {
    $report_id = intval($_POST['report_id']);
    $target_status = mysqli_real_escape_string($conn, trim($_POST['status_value'])); // Standard escaping

    if ($report_id > 0 && ($target_status === 'resolved' || $target_status === 'cancelled')) {
        // Normal direct SQL Update execution
        $update_query = "UPDATE reports SET status = '$target_status' WHERE ID = $report_id";
        
        if (mysqli_query($conn, $update_query)) {
            $status_message = "Report ID #$report_id has been successfully marked as " . htmlspecialchars($target_status) . ".";
        } else {
            $error_message = "Failed to update report status: " . mysqli_error($conn);
        }
    }
}

// 3. Fetch Active Reports matching this LGU Department using Normal SQL
$lgu_department = $_SESSION['lgu_department'] ?? '';

// Map the logged-in department to the raw categories used in your reports table
$report_categories = [];
switch ($lgu_department) {
    case 'City Engineering Office':
        $report_categories = ["'Drainage Issues'", "'Road/Pothole'", "'Infrastructure'"];
        break;
    case 'City Environment & Natural Resources Office':
        $report_categories = ["'Waste Management'"];
        break;
    case 'City General Services Office':
        $report_categories = ["'Streetlight'"];
        break;
    case 'City Traffic Operations Management':
        $report_categories = ["'Traffic Concern'"];
        break;
    case 'Public Order and Safety Office':
        $report_categories = ["'Security Issue'"];
        break;
    default:
        $report_categories = ["'other'"];
        break;
}

// Convert the categories array into a format SQL understand: ('Waste Management') or ('Road/Pothole', 'Infrastructure')
$category_list = implode(",", $report_categories);

// Fetch Active Reports matching this LGU Admin's official department handling scope
$fetch_query = "SELECT * FROM reports 
                WHERE category IN ($category_list) 
                AND status NOT IN ('resolved', 'cancelled') 
                ORDER BY ID DESC"; 

$result = mysqli_query($conn, $fetch_query);
$active_reports = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $active_reports[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGU Department Dashboard - Street Cred</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary-lgu: #1e3a8a;
            --accent-lgu: #f59e0b;
            --success-lgu: #10b981;
            --danger-lgu: #ef4444;
            --bg-light: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        body {
            background-color: var(--bg-light);
            margin: 0;
            font-family: system-ui, -apple-system, sans-serif;
            color: var(--text-main);
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            box-sizing: border-box;
        }

        /* Top Bar Identity Block */
        .top-bar-identity {
            background: var(--primary-lgu);
            color: #fff;
            padding: 12px 20px;
            font-size: 0.88rem;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--accent-lgu);
            letter-spacing: 0.5px;
        }

        .identity-badge {
            background: rgba(255, 255, 255, 0.15);
            padding: 4px 10px;
            border-radius: 4px;
            color: var(--accent-lgu);
        }

        /* Main Header Portal Styling Navigation Rules */
        header {
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid var(--border-color);
            padding: 15px 0;
        }

        .nav-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            font-size: 2rem;
        }

        .brand {
            display: block;
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary-lgu);
            letter-spacing: -0.5px;
        }

        .subtext {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
        }

        .btn-nav-lgu {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            color: #475569;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-nav-lgu.active {
            background: #eff6ff;
            color: #2563eb;
        }

        .btn-nav-lgu:hover:not(.active) {
            background: #f1f5f9;
            color: #0f172a;
        }

        .user-profile {
            text-align: right;
            line-height: 1.3;
        }

        .user-profile strong {
            color: #0f172a;
            font-size: 0.95rem;
        }

        .user-profile small {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .btn-logout {
            background: #f1f5f9;
            padding: 8px 16px;
            border-radius: 20px;
            color: #64748b;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            color: var(--danger-lgu);
            background: #fef2f2;
        }

        /* Incidents Card Container Styles */
        .incident-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .incident-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        }

        .img-container {
            width: 130px;
            height: 130px;
            border-radius: 8px;
            overflow: hidden;
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
            flex-shrink: 0;
        }

        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .action-container {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex-shrink: 0;
            width: 110px;
        }

        .btn-action {
            border: none;
            padding: 10px 14px;
            border-radius: 6px;
            color: white;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            justify-content: center;
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-action:active {
            transform: scale(0.98);
        }

        .btn-action:hover {
            opacity: 0.9;
        }

        .btn-action.done { background-color: var(--success-lgu); }
        .btn-action.cancel { background-color: var(--danger-lgu); }

        .alert-banner {
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
        }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    </style>
</head>
<body>

    <div class="top-bar-identity">
        <div>
            <i class="fas fa-building-shield"></i> LGU DEPARTMENT: 
            <span class="identity-badge"><?php echo htmlspecialchars($lgu_department); ?></span>
        </div>
        <div>
            <i class="fas fa-map-marker-alt"></i> REGIONAL AREA/BARANGAY: 
            <span style="color: #60a5fa; text-transform: uppercase;"><?php echo htmlspecialchars($admin_barangay); ?></span>
        </div>
    </div>

    <header>
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <div class="logo-icon">🏛️</div>
                    <div>
                        <span class="brand">Street Cred</span>
                        <span class="subtext">Department Portal</span>
                    </div>
                </div>

                <nav style="display: flex; gap: 5px;">
                    <a href="LGU_DASHBOARD.php" class="btn-nav-lgu active"><i class="fas fa-folder-open"></i> Active Incidents</a>
                    <a href="../home-page/homepage.php" class="btn-nav-lgu"><i class="fas fa-home"></i> Home</a>
                </nav>

                <div class="user-profile">
                    <strong><?php echo htmlspecialchars($user_name); ?></strong><br>
                    <small><i class="fas fa-user-tie"></i> Assigned Specialist</small>
                </div>

                <a href="../login-page/logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <section class="submit-content" style="padding-top: 30px;">
        <div class="container">
            
            <?php if(!empty($status_message)): ?>
                <div class="alert-banner alert-success"><i class="fas fa-check-circle"></i> <?php echo $status_message; ?></div>
            <?php endif; ?>
            <?php if(!empty($error_message)): ?>
                <div class="alert-banner alert-error"><i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?></div>
            <?php endif; ?>

            <div style="margin-bottom: 25px;">
                <h2 style="margin: 0; color: #0f172a;"><i class="fas fa-stream"></i> Pending Infrastructure Incidents (<?php echo count($active_reports); ?>)</h2>
                <p style="color: var(--text-muted); margin: 6px 0 0 0; font-size: 0.95rem;">Reviewing public assignments submitted directly under your jurisdiction parameters.</p>
            </div>

            <?php if (count($active_reports) === 0): ?>
                <div style="text-align: center; padding: 60px; background: #fff; border-radius: 12px; border: 1px solid var(--border-color); color: var(--text-muted);">
                    <i class="fas fa-clipboard-check fa-3x" style="color: #cbd5e1; margin-bottom: 12px;"></i>
                    <h3>Clear Workspace!</h3>
                    <p>No active incidents require processing attention in the <?php echo htmlspecialchars($lgu_department); ?> queue right now.</p>
                </div>
            <?php else: ?>
                <?php foreach ($active_reports as $report): ?>
                    <div class="incident-card">
                        <div style="display: flex; gap: 20px; align-items: flex-start;">
                            
                            <div class="img-container">
                                <?php if (!empty($report['image_path']) && file_exists($report['image_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($report['image_path']); ?>" alt="Report Evidence Grid">
                                <?php else: ?>
                                    <div style="display:flex; align-items:center; justify-content:center; height:100%; color:#94a3b8; background:#f1f5f9;">
                                        <i class="fas fa-image fa-2x"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div style="flex-grow: 1;">
                                <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 6px;">
                                    <span style="background: #eff6ff; color: #2563eb; font-size: 0.75rem; font-weight: 700; padding: 3px 8px; border-radius: 4px; text-transform: uppercase;">
                                        ID #<?php echo $report['ID']; ?>
                                    </span>
                                </div>
                                <h3 style="margin: 0 0 6px 0; color: #1e293b; font-size: 1.15rem;"><?php echo htmlspecialchars($report['category']); ?></h3>
                                <p style="margin: 0 0 12px 0; font-size: 0.9rem; color: #475569; font-weight: 500;">
                                    <i class="fas fa-location-dot" style="color: var(--danger-lgu);"></i> Spot Location: Barangay <?php echo htmlspecialchars($report['barangay']); ?>
                                </p>
                                <div style="background: #f8fafc; padding: 12px; border-radius: 6px; border-left: 3px solid #cbd5e1; font-size: 0.92rem; color: #334155; line-height: 1.5;">
                                    <?php echo htmlspecialchars($report['description']); ?>
                                </div>
                            </div>

                            <div class="action-container">
                                <form action="LGU_DASHBOARD.php" method="POST">
                                    <input type="hidden" name="action_trigger" value="1">
                                    <input type="hidden" name="report_id" value="<?php echo $report['ID']; ?>">
                                    <input type="hidden" name="status_value" value="resolved">
                                    <button type="submit" class="btn-action done" style="width: 100%;">
                                        <i class="fas fa-circle-check"></i> Done
                                    </button>
                                </form>

                                <form action="LGU_DASHBOARD.php" method="POST" onsubmit="return confirm('Are you sure you want to drop or cancel processing this incident update?');">
                                    <input type="hidden" name="action_trigger" value="1">
                                    <input type="hidden" name="report_id" value="<?php echo $report['ID']; ?>">
                                    <input type="hidden" name="status_value" value="cancelled">
                                    <button type="submit" class="btn-action cancel" style="width: 100%;">
                                        <i class="fas fa-ban"></i> Cancel
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </section>

</body>
</html>