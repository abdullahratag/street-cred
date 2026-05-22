<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(dirname(__DIR__) . "/database/database.php");

$user_ID = $_SESSION['user_id'];
$name = $_SESSION['first_name'];
$email = $_SESSION['user_email'];
$barangay = $_SESSION['user_barangay'];
$member_since = $_SESSION['membersince'];

// Status counters
// 1. Total Reports (Fixed: Removed barangay filter so it counts ALL user reports)
$sql_all_reports = "SELECT * FROM reports WHERE user_id = $user_ID";
$run_all = mysqli_query($conn, $sql_all_reports);
$total_reports = mysqli_num_rows($run_all);

// 2. Standby Reports (Fixed: Renamed variable to avoid conflicts)
$sql_standby = "SELECT * FROM reports WHERE status = 'standby' AND user_id = $user_ID";
$run_standby = mysqli_query($conn, $sql_standby);
$total_standby = mysqli_num_rows($run_standby);

$query_reported_to_lgu = "SELECT * FROM reports WHERE status = 'Reported to LGU' AND user_id = $user_ID";
$result = mysqli_query($conn, $query_reported_to_lgu);
$total_reported_to_lgu = mysqli_num_rows($result);

$query_resolved = "SELECT * FROM reports WHERE status = 'Resolved' AND user_id = $user_ID";
$result = mysqli_query($conn, $query_resolved);
$total_resolved = mysqli_num_rows($result);

// ✨ FIX: Pulling the actual 3 most recent reports from the database for this specific user
$query_recent = "SELECT * FROM reports 
                 WHERE user_id = $user_ID 
                 ORDER BY ID DESC 
                 LIMIT 3";
$result_recent = mysqli_query($conn, $query_recent);

$recent_reports = [];
if ($result_recent) {
    while ($row = mysqli_fetch_assoc($result_recent)) {
        $recent_reports[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Street Cred</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="userprofile.css">
    <script src="../scripts/script.js"></script>
</head>
<body>
    <header>
        <div class="container">
            <div class="nav-wrapper">
                <a href="../index.php" class="logo">
                    <div class="logo-icon">🏛️</div>
                    <div>
                        <span class="brand">Street Cred</span>
                        <span class="subtext">Zamboanga City</span>
                    </div>
                </a>

                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search reports, documents...">
                </div>

                <nav>
                    <a href="../home-page/homepage.php" class="btn-nav">Home</a>
                    <a href="../submit-page/submit.php" class="btn-nav">Submit Report</a>
                    <a href="#" class="btn-nav active">Profile</a>
                </nav>

                <div class="user-profile">
                    <strong><?php echo htmlspecialchars($name); ?></strong><br>
                    <small><?php echo htmlspecialchars($barangay); ?></small>
                </div>
            </div>
        </div>
    </header>

    <section class="profile-header">
        <div class="container">
            <div class="profile-header-content">
                <div class="profile-avatar">
                    <div class="avatar-circle">
                        <i class="fas fa-user"></i>
                    </div>
                    <button class="change-photo-btn">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>
                <div class="profile-header-info">
                    <h1><?php echo htmlspecialchars($name); ?></h1>
                    <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($email); ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> Barangay <?php echo htmlspecialchars($barangay); ?></p>
                    <p class="member-since"><i class="fas fa-calendar"></i> Member since <?php echo htmlspecialchars($member_since); ?></p>
                </div>
            </div>
        </div>
    </section>

    <main class="container profile-main-layout">
        
        <section class="top-row-insights">
            <div class="profile-card">
                <div class="card-header">
                    <h3><i class="fas fa-chart-bar"></i> Report Statistics</h3>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-box stat-total">
                            <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                            <div class="stat-info">
                                <div class="stat-number"><?php echo $total_reports; ?></div>
                                <div class="stat-label">Total Reports</div>
                            </div>
                        </div>

                        <div class="stat-box stat-pending">
                            <div class="stat-icon"><i class="fas fa-clock"></i></div>
                            <div class="stat-info">
                                <div class="stat-number"><?php echo $total_standby; ?></div>
                                <div class="stat-label">Standby</div>
                            </div>
                        </div>

                        <div class="stat-box stat-lgu">
                            <div class="stat-icon"><i class="fas fa-city"></i></div>
                            <div class="stat-info">
                                <div class="stat-number"><?php echo $total_reported_to_lgu; ?></div>
                                <div class="stat-label">Reported to LGU</div>
                            </div>
                        </div>

                        <div class="stat-box stat-resolved">
                            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                            <div class="stat-info">
                                <div class="stat-number"><?php echo $total_resolved; ?></div>
                                <div class="stat-label">Resolved</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-card">
                <div class="card-header">
                    <h3><i class="fas fa-history"></i> Recent Reports</h3>
                    <a href="my-reports.php" class="view-all-link">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    <div class="recent-reports-list">
                        <?php foreach ($recent_reports as $report): ?>
                        <div class="recent-report-item">
                            <div class="report-item-header">
                                <div>
                                    <h4><?php echo htmlspecialchars($report['category']); ?></h4>
                                    <span class="report-id"><?php echo htmlspecialchars($report['description']); ?></span>
                                </div>
                                <span class="status-badge <?php echo htmlspecialchars($report['status']); ?>">
                                    <?php echo ucfirst(str_replace('-', ' ', $report['status'])); ?>
                                </span>
                            </div>
                            <div class="report-item-meta">
                                <span class="report-category">
                                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars($report['barangay']); ?>
                                </span>
                                <span class="report-date">
                                    <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($report['created_at'])); ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="middle-row-forms">
            <div class="profile-card">
                <div class="card-header">
                    <h3><i class="fas fa-edit"></i> Edit Profile</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-success" style="display: none;" id="profile-success">
                        <i class="fas fa-check-circle"></i> Profile updated successfully!
                    </div>

                    <form id="edit-profile-form" class="profile-form">
                        <div class="form-group">
                            <label for="full_name"><i class="fas fa-user"></i> Full Name</label>
                            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($name); ?>" required placeholder="Enter your full name">
                        </div>

                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="Enter your email">
                        </div>

                        <div class="form-group">
                            <label for="barangay"><i class="fas fa-map-marker-alt"></i> Barangay</label>
                            <select id="barangay" name="barangay" required>
                                <option value="">Select Barangay</option>
                                <option value="Tetuan" <?php echo $barangay == 'Tetuan' ? 'selected' : ''; ?>>Tetuan</option>
                                <option value="Canelar" <?php echo $barangay == 'Canelar' ? 'selected' : ''; ?>>Canelar</option>
                                <option value="Pasonanca" <?php echo $barangay == 'Pasonanca' ? 'selected' : ''; ?>>Pasonanca</option>
                                <option value="San Roque" <?php echo $barangay == 'San Roque' ? 'selected' : ''; ?>>San Roque</option>
                                <option value="Sta. Maria" <?php echo $barangay == 'Sta. Maria' ? 'selected' : ''; ?>>Sta. Maria</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-primary btn-full">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </form>
                </div>
            </div>

            <div class="profile-card">
                <div class="card-header">
                    <h3><i class="fas fa-lock"></i> Change Password</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-success" style="display: none;" id="password-success">
                        <i class="fas fa-check-circle"></i> Password changed successfully!
                    </div>
                    <div class="alert alert-error" style="display: none;" id="password-error">
                        <i class="fas fa-exclamation-circle"></i> <span id="password-error-message">An error occurred. Please try again.</span>
                    </div>

                    <form id="change-password-form" class="profile-form">
                        <div class="form-group">
                            <label for="current_password"><i class="fas fa-key"></i> Current Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="current_password" name="current_password" required placeholder="Enter current password">
                                <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new_password"><i class="fas fa-lock"></i> New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="new_password" name="new_password" required placeholder="Enter new password" minlength="8">
                                <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-hint">Minimum 8 characters</small>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password"><i class="fas fa-lock"></i> Confirm New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm new password">
                                <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary btn-full btn-danger">
                            <i class="fas fa-shield-alt"></i> Change Password
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section class="bottom-row-actions">
            <div class="centered-action-container">
                <a href="../login-page/logout.php" class="btn-action-panel btn-logout-action">
                    <i class="fas fa-sign-out-alt"></i> Logout of Account
                </a>
                <button class="btn-action-panel btn-delete-action" onclick="confirmDelete()">
                    <i class="fas fa-trash-alt"></i> Delete Account
                </button>
            </div>
        </section>
        
    </main>

    
</body>
</html>