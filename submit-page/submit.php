<?php
session_start();

include("../database/database.php"); // Uses your working $conn MySQLi object

$user_ID = $_SESSION['user_id'];
$user_name = $_SESSION['first_name'];
$user_barangay = $_SESSION['user_barangay'];

$submission_success = false;
$error_message = "";

// 2. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Catch text inputs and sanitize basic spacing
    $category    = trim($_POST['category']);
    $location    = trim($_POST['location']); 
    $description = trim($_POST['description']);
    
    // File upload settings
    $target_dir = "../uploads/reports/";
    
    // Create directory dynamically if it doesn't exist yet
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (isset($_FILES['report_image']) && $_FILES['report_image']['error'] == 0) {
        $file = $_FILES['report_image'];
        
        // Validate File Size (5MB limit)
        if ($file['size'] > 5 * 1024 * 1024) {
            $error_message = "File size exceeds the 5MB limit.";
        } else {
            // Validate File Type securely using MIME type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
            
            if (!in_array($mime_type, $allowed_types)) {
                $error_message = "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
            } else {
                // Generate a completely unique filename to avoid overriding existing images
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = "report_" . time() . "_" . bin2hex(random_bytes(8)) . "." . $extension;
                $target_file = $target_dir . $new_filename;
                
                // Move temporary file to your storage folder
                if (move_uploaded_file($file['tmp_name'], $target_file)) {
                    
                    // 3. FIXED: Correctly implemented MySQLi Prepared Statements with '?' placeholders
                    $query = "INSERT INTO reports (user_id, category, barangay, description, image_path, date_submitted) 
                            VALUES (?, ?, ?, ?, ?, NOW())";
                    
                    if ($stmt = $conn->prepare($query)) {
                        // "issss" -> integer, string, string, string, string
                        $stmt->bind_param("issss", $user_ID, $category, $location, $description, $target_file);
                        
                        if ($stmt->execute()) {
                            $stmt->close();
                            // Redirect to prevent form resubmission on page refresh
                            header("Location: submit.php?success=1");
                            exit();
                        } else {
                            $error_message = "Database execution error. Please try again.";
                        }
                        $stmt->close();
                    } else {
                        $error_message = "Database preparation error. Please try again.";
                    }
                    
                } else {
                    $error_message = "Failed to move uploaded file. Check folder permissions.";
                }
            }
        }
    } else {
        $error_message = "Photo evidence is required.";
    }
}

// Form submission success flag check via URL tracking
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $submission_success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Report - Street Cred</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="submit.css">
    <style>
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
        .alert-danger {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            color: #ef4444;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
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
                    <a href="submit.php" class="btn-nav active">Submit Report</a>
                    <a href="../userprofile-page/userprofile.php" class="btn-nav">Profile</a>
                </nav>

                <div class="user-profile">
                    <strong><?php echo htmlspecialchars($user_name); ?></strong><br>
                    <small><?php echo htmlspecialchars($user_barangay); ?></small>
                </div>

                <a href="../login-page/logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </div>
        </div>
    </header>

    <section class="page-header">
        <div class="container">
            <div class="header-content">
                <div class="header-icon">
                    <i class="fas fa-file-medical"></i>
                </div>
                <div class="header-text">
                    <h1>Submit New Report</h1>
                    <p>Report infrastructure issues, concerns, or problems in your barangay. Help us make your community better.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="submit-content">
        <div class="container">
            <div class="submit-grid">
                <div class="form-column">
                    <div class="submit-card">
                        <div class="card-header">
                            <h3><i class="fas fa-edit"></i> Report Details</h3>
                            <span class="required-note">* Required fields</span>
                        </div>
                        <div class="card-body">
                            
                            <?php if ($submission_success): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Success!</strong> Your report has been submitted successfully.
                                    <br>
                                    <small>You will be notified once it's reviewed by officials.</small>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i>
                                <div>
                                    <strong>Submission Failed:</strong> <?php echo htmlspecialchars($error_message); ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <form id="submit-report-form" class="report-form" action="submit.php" method="POST" enctype="multipart/form-data">
                                
                                <div class="form-group">
                                    <label for="category"><i class="fas fa-tag"></i> Category *</label>
                                    <select id="category" name="category" required>
                                        <option value="">Select a category</option>
                                        <option value="Drainage Issues"> Drainage Issues</option>
                                        <option value="Waste Management"> Waste Management</option>
                                        <option value="Road/Pothole"> Road/Pothole</option>
                                        <option value="Streetlight"> Streetlight</option>
                                        <option value="Traffic Concern"> Traffic Concern</option>
                                        <option value="Security Issue"> Security Issue</option>
                                        <option value="Infrastructure"> Infrastructure</option>
                                        <option value="other"> Other</option>
                                    </select>
                                    <small class="form-hint">Choose the category that best fits your report</small>
                                </div>

                                <div class="form-group">
                                    <label for="location"><i class="fas fa-map-marker-alt"></i> Location *</label>
                                    <input type="text" id="location" name="location" required placeholder="e.g., Don Alfaro St, Tetuan or Near Barangay Hall" list="zamboanga-locations">
                                    <datalist id="zamboanga-locations">
                                        <option value="Arena Blanco">
                                            <option value="Ayala">
                                                <option value="Baliwasan">
                                                    <option value="Boalan">
                                                        <option value="Canelar">
                                                            <option value="Divisoria">
                                                                <option value="Guiwan">
                                                                    <option value="La Paz">
                                                                        <option value="Mampang">
                                                                            <option value="Pasonanca">
                                                                                <option value="Putik">
                                                                                    <option value="San Roque">
                                                                                        <option value="Santa Catalina">
                                                                                            <option value="Santa Maria">
                                                                                                <option value="Tetuan">
                                                                                                    <option value="Zambowood">
                                    </datalist>
                                    <small class="form-hint">Specify the exact location of the issue</small>
                                </div>

                                <div class="form-group">
                                    <label for="description"><i class="fas fa-align-left"></i> Description *</label>
                                    <textarea id="description" name="description" required placeholder="Describe the issue in detail..." rows="6" maxlength="500"></textarea>
                                    <div class="char-counter">
                                        <span id="char-count">0</span> / 500 characters
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="report_image"><i class="fas fa-camera"></i> Photo Evidence *</label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" id="report_image" name="report_image" accept="image/jpeg,image/png,image/jpg" required>
                                        <label for="report_image" class="file-upload-label">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span class="file-text">Click to upload or drag and drop</span>
                                            <span class="file-hint">JPG, PNG (Max 5MB)</span>
                                        </label>
                                        <div class="file-preview" id="file-preview" style="display: none;">
                                            <img id="preview-image" src="" alt="Preview">
                                            <button type="button" class="remove-file" id="remove-file">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-hint">Clear photos help officials address issues faster</small>
                                </div>

                                <button type="submit" class="btn-primary btn-full">
                                    <i class="fas fa-paper-plane"></i> Submit Report
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="info-column">
                    <div class="submit-card">
                        <div class="card-header">
                            <h3><i class="fas fa-info-circle"></i> Submission Guidelines</h3>
                        </div>
                        <div class="card-body">
                            <div class="guideline-list">
                                <div class="guideline-item">
                                    <div class="guideline-icon"><i class="fas fa-check-circle"></i></div>
                                    <div class="guideline-content">
                                        <h4>Be Specific</h4>
                                        <p>Provide clear details about the location and nature of the issue</p>
                                    </div>
                                </div>
                                <div class="guideline-item">
                                    <div class="guideline-icon"><i class="fas fa-camera"></i></div>
                                    <div class="guideline-content">
                                        <h4>Include Photos</h4>
                                        <p>Clear images help officials understand and prioritize your report</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Character counter
        const descriptionTextarea = document.getElementById('description');
        const charCount = document.getElementById('char-count');

        descriptionTextarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            if (count >= 450) { charCount.style.color = '#ef4444'; } 
            else if (count >= 400) { charCount.style.color = '#f59e0b'; } 
            else { charCount.style.color = '#10b981'; }
        });

        // File upload preview
        const fileInput = document.getElementById('report_image');
        const filePreview = document.getElementById('file-preview');
        const previewImage = document.getElementById('preview-image');
        const fileLabel = document.querySelector('.file-upload-label');
        const removeFileBtn = document.getElementById('remove-file');
        const fileText = document.querySelector('.file-text');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg')) {
                    alert('Please upload only JPG or PNG images');
                    this.value = '';
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    filePreview.style.display = 'block';
                    fileLabel.style.display = 'none';
                };
                reader.readAsDataURL(file);
                fileText.textContent = file.name;
            }
        });

        removeFileBtn.addEventListener('click', function() {
            fileInput.value = '';
            filePreview.style.display = 'none';
            fileLabel.style.display = 'flex';
            fileText.textContent = 'Click to upload or drag and drop';
        });

        // FIXED: Removed JavaScript intercept e.preventDefault() so the actual HTML form can submit natively to PHP.
    </script>
</body>
</html>