<?php
session_start();
 /*
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
*/
// Dummy PHP variables
$name = "Juan Dela Cruz";
$email = "juan@email.com";
$barangay = "Tetuan";
$member_since = "January 2024";

// Hardcoded stats
$total_reports = 12;
$pending_reports = 3;
$resolved_reports = 8;
$rejected_reports = 1;

// Dummy recent reports
$recent_reports = [
    [
        'id' => '#REP-001',
        'title' => 'Broken Streetlight',
        'category' => 'Infrastructure',
        'status' => 'resolved',
        'date' => '2024-01-15'
    ],
    [
        'id' => '#REP-002',
        'title' => 'Garbage Collection Issue',
        'category' => 'Sanitation',
        'status' => 'pending',
        'date' => '2024-01-18'
    ],
    [
        'id' => '#REP-003',
        'title' => 'Road Repair Needed',
        'category' => 'Infrastructure',
        'status' => 'in-progress',
        'date' => '2024-01-20'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Street Cred</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="userprofile.css">
</head>
<body>
    <!-- Header -->
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
                    <a href="../index.php" class="btn-nav">Home</a>
                    <a href="dashboard.php" class="btn-nav">Dashboard</a>
                    <a href="profile.php" class="btn-nav active">Profile</a>
                </nav>

                <div class="user-profile">
                    <strong><?php echo $name; ?></strong><br>
                    <small><?php echo $barangay; ?></small>
                </div>
            </div>
        </div>
    </header>

    <!-- Profile Header -->
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
                    <h1><?php echo $name; ?></h1>
                    <p><i class="fas fa-envelope"></i> <?php echo $email; ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> Barangay <?php echo $barangay; ?></p>
                    <p class="member-since"><i class="fas fa-calendar"></i> Member since <?php echo $member_since; ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Profile Content -->
    <section class="profile-content">
        <div class="container">
            <div class="profile-grid">
                <!-- Left Column -->
                <div class="left-column">
                    <!-- Profile Info Card -->
                    <div class="profile-card">
                        <div class="card-header">
                            <h3><i class="fas fa-user-circle"></i> Profile Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-group">
                                <label>Full Name</label>
                                <div class="info-value"><?php echo $name; ?></div>
                            </div>
                            <div class="info-group">
                                <label>Email Address</label>
                                <div class="info-value"><?php echo $email; ?></div>
                            </div>
                            <div class="info-group">
                                <label>Barangay</label>
                                <div class="info-value"><?php echo $barangay; ?></div>
                            </div>
                            <div class="info-group">
                                <label>Member Since</label>
                                <div class="info-value"><?php echo $member_since; ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Report Statistics Card -->
                    <div class="profile-card">
                        <div class="card-header">
                            <h3><i class="fas fa-chart-bar"></i> Report Statistics</h3>
                        </div>
                        <div class="card-body">
                            <div class="stats-grid">
                                <div class="stat-box stat-total">
                                    <div class="stat-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number"><?php echo $total_reports; ?></div>
                                        <div class="stat-label">Total Reports</div>
                                    </div>
                                </div>

                                <div class="stat-box stat-pending">
                                    <div class="stat-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number"><?php echo $pending_reports; ?></div>
                                        <div class="stat-label">Pending</div>
                                    </div>
                                </div>

                                <div class="stat-box stat-resolved">
                                    <div class="stat-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number"><?php echo $resolved_reports; ?></div>
                                        <div class="stat-label">Resolved</div>
                                    </div>
                                </div>

                                <div class="stat-box stat-rejected">
                                    <div class="stat-icon">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number"><?php echo $rejected_reports; ?></div>
                                        <div class="stat-label">Rejected</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Reports Card -->
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
                                            <h4><?php echo $report['title']; ?></h4>
                                            <span class="report-id"><?php echo $report['id']; ?></span>
                                        </div>
                                        <span class="status-badge <?php echo $report['status']; ?>">
                                            <?php echo ucfirst(str_replace('-', ' ', $report['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="report-item-meta">
                                        <span class="report-category">
                                            <i class="fas fa-tag"></i> <?php echo $report['category']; ?>
                                        </span>
                                        <span class="report-date">
                                            <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($report['date'])); ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="right-column">
                    <!-- Edit Profile Form -->
                    <div class="profile-card">
                        <div class="card-header">
                            <h3><i class="fas fa-edit"></i> Edit Profile</h3>
                        </div>
                        <div class="card-body">
                            <!-- Success Message Placeholder -->
                            <div class="alert alert-success" style="display: none;" id="profile-success">
                                <i class="fas fa-check-circle"></i>
                                Profile updated successfully!
                            </div>

                            <form id="edit-profile-form" class="profile-form">
                                <div class="form-group">
                                    <label for="full_name">
                                        <i class="fas fa-user"></i> Full Name
                                    </label>
                                    <input 
                                        type="text" 
                                        id="full_name" 
                                        name="full_name" 
                                        value="<?php echo $name; ?>" 
                                        required
                                        placeholder="Enter your full name"
                                    >
                                </div>

                                <div class="form-group">
                                    <label for="email">
                                        <i class="fas fa-envelope"></i> Email Address
                                    </label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="<?php echo $email; ?>" 
                                        required
                                        placeholder="Enter your email"
                                    >
                                </div>

                                <div class="form-group">
                                    <label for="barangay">
                                        <i class="fas fa-map-marker-alt"></i> Barangay
                                    </label>
                                    <select id="barangay" name="barangay" required>
                                        <option value="">Select Barangay</option>
                                        <option value="Tetuan" <?php echo $barangay == 'Tetuan' ? 'selected' : ''; ?>>Tetuan</option>
                                        <option value="Canelar">Canelar</option>
                                        <option value="Pasonanca">Pasonanca</option>
                                        <option value="San Roque">San Roque</option>
                                        <option value="Sta. Maria">Sta. Maria</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn-primary btn-full">
                                    <i class="fas fa-save"></i> Update Profile
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Change Password Form -->
                    <div class="profile-card">
                        <div class="card-header">
                            <h3><i class="fas fa-lock"></i> Change Password</h3>
                        </div>
                        <div class="card-body">
                            <!-- Success/Error Message Placeholders -->
                            <div class="alert alert-success" style="display: none;" id="password-success">
                                <i class="fas fa-check-circle"></i>
                                Password changed successfully!
                            </div>
                            <div class="alert alert-error" style="display: none;" id="password-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <span id="password-error-message">An error occurred. Please try again.</span>
                            </div>

                            <form id="change-password-form" class="profile-form">
                                <div class="form-group">
                                    <label for="current_password">
                                        <i class="fas fa-key"></i> Current Password
                                    </label>
                                    <div class="password-input-wrapper">
                                        <input 
                                            type="password" 
                                            id="current_password" 
                                            name="current_password" 
                                            required
                                            placeholder="Enter current password"
                                        >
                                        <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="new_password">
                                        <i class="fas fa-lock"></i> New Password
                                    </label>
                                    <div class="password-input-wrapper">
                                        <input 
                                            type="password" 
                                            id="new_password" 
                                            name="new_password" 
                                            required
                                            placeholder="Enter new password"
                                            minlength="8"
                                        >
                                        <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="form-hint">Minimum 8 characters</small>
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password">
                                        <i class="fas fa-lock"></i> Confirm New Password
                                    </label>
                                    <div class="password-input-wrapper">
                                        <input 
                                            type="password" 
                                            id="confirm_password" 
                                            name="confirm_password" 
                                            required
                                            placeholder="Confirm new password"
                                        >
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

                    <!-- Account Actions -->
                    <div class="profile-card">
                        <div class="card-header">
                            <h3><i class="fas fa-cog"></i> Account Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="action-buttons">
                                <a href="../auth/logout.php" class="btn-action btn-logout">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                                <button class="btn-action btn-delete" onclick="confirmDelete()">
                                    <i class="fas fa-trash-alt"></i> Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.parentElement.querySelector('.toggle-password i');
            
            if (input.type === 'password') {
                input.type = 'text';
                button.classList.remove('fa-eye');
                button.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                button.classList.remove('fa-eye-slash');
                button.classList.add('fa-eye');
            }
        }

        // Edit Profile Form Handler (Demo)
        document.getElementById('edit-profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const successMsg = document.getElementById('profile-success');
            successMsg.style.display = 'block';
            setTimeout(() => {
                successMsg.style.display = 'none';
            }, 3000);
        });

        // Change Password Form Handler (Demo)
        document.getElementById('change-password-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const errorMsg = document.getElementById('password-error');
            const errorText = document.getElementById('password-error-message');
            const successMsg = document.getElementById('password-success');
            
            // Hide all messages
            errorMsg.style.display = 'none';
            successMsg.style.display = 'none';
            
            // Validate passwords match
            if (newPassword !== confirmPassword) {
                errorText.textContent = 'New passwords do not match!';
                errorMsg.style.display = 'block';
                return;
            }
            
            // Success simulation
            successMsg.style.display = 'block';
            this.reset();
            setTimeout(() => {
                successMsg.style.display = 'none';
            }, 3000);
        });

        // Delete account confirmation
        function confirmDelete() {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                alert('Account deletion feature will be implemented with database logic.');
            }
        }
    </script>
</body>
</html>