<?php
session_start();

include("../database/database.php");
/*
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
*/
// Dummy user data
$user_name = "Juan Dela Cruz";
$user_barangay = "Tetuan";

// Form submission success flag (for demo)
$submission_success = false;
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
                    <a href="../home-page/homepage.php" class="btn-nav">Home</a>
                    <a href="submit.php" class="btn-nav active">Submit Report</a>
                    <a href="../user/profile.php" class="btn-nav">Profile</a>
                </nav>

                <div class="user-profile">
                    <strong><?php echo $user_name; ?></strong><br>
                    <small><?php echo $user_barangay; ?></small>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Header -->
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

    <!-- Main Content -->
    <section class="submit-content">
        <div class="container">
            <div class="submit-grid">
                <!-- Left Column - Form -->
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

                            <form id="submit-report-form" class="report-form" enctype="multipart/form-data">
                                <!-- Report Title -->
                                <div class="form-group">
                                    <label for="report_title">
                                        <i class="fas fa-heading"></i> Report Title *
                                    </label>
                                    <input 
                                        type="text" 
                                        id="report_title" 
                                        name="report_title" 
                                        required
                                        placeholder="e.g., Clogged Drainage on Main Street"
                                        maxlength="100"
                                    >
                                    <small class="form-hint">Give your report a clear, descriptive title</small>
                                </div>

                                <!-- Category -->
                                <div class="form-group">
                                    <label for="category">
                                        <i class="fas fa-tag"></i> Category *
                                    </label>
                                    <select id="category" name="category" required>
                                        <option value="">Select a category</option>
                                        <option value="drainage"> Drainage Issues</option>
                                        <option value="waste"> Waste Management</option>
                                        <option value="pothole"> Road/Pothole</option>
                                        <option value="streetlight"> Streetlight</option>
                                        <option value="traffic"> Traffic Concern</option>
                                        <option value="security"> Security Issue</option>
                                        <option value="infrastructure"> Infrastructure</option>
                                        <option value="other"> Other</option>
                                    </select>
                                    <small class="form-hint">Choose the category that best fits your report</small>
                                </div>

                                <!-- Location -->
                                <div class="form-group">
                                    <label for="location">
                                        <i class="fas fa-map-marker-alt"></i> Location *
                                    </label>
                                    <input 
                                        type="text" 
                                        id="location" 
                                        name="location" 
                                        required
                                        placeholder="e.g., Don Alfaro St, Tetuan or Near Barangay Hall"
                                        list="zamboanga-locations"
                                    >
                                    <datalist id="zamboanga-locations">
                                        <option value="Arena Blanco, Zamboanga City">
                                        <option value="Ayala, Zamboanga City">
                                        <option value="Baliwasan, Zamboanga City">
                                        <option value="Boalan, Zamboanga City">
                                        <option value="Canelar, Zamboanga City">
                                        <option value="Divisoria, Zamboanga City">
                                        <option value="Guiwan, Zamboanga City">
                                        <option value="La Paz, Zamboanga City">
                                        <option value="Mampang, Zamboanga City">
                                        <option value="Pasonanca, Zamboanga City">
                                        <option value="Putik, Zamboanga City">
                                        <option value="San Roque, Zamboanga City">
                                        <option value="Santa Catalina, Zamboanga City">
                                        <option value="Santa Maria, Zamboanga City">
                                        <option value="Tetuan, Zamboanga City">
                                        <option value="Zambowood, Zamboanga City">
                                    </datalist>
                                    <small class="form-hint">Specify the exact location of the issue</small>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description">
                                        <i class="fas fa-align-left"></i> Description *
                                    </label>
                                    <textarea 
                                        id="description" 
                                        name="description" 
                                        required
                                        placeholder="Describe the issue in detail. Include when you noticed it, how it affects you, and any other relevant information..."
                                        rows="6"
                                        maxlength="500"
                                    ></textarea>
                                    <div class="char-counter">
                                        <span id="char-count">0</span> / 500 characters
                                    </div>
                                </div>

                                <!-- Photo Upload -->
                                <div class="form-group">
                                    <label for="report_image">
                                        <i class="fas fa-camera"></i> Photo Evidence *
                                    </label>
                                    <div class="file-upload-wrapper">
                                        <input 
                                            type="file" 
                                            id="report_image" 
                                            name="report_image" 
                                            accept="image/jpeg,image/png,image/jpg"
                                            required
                                        >
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

                                <!-- Submit Button -->
                                <button type="submit" class="btn-primary btn-full">
                                    <i class="fas fa-paper-plane"></i> Submit Report
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Guidelines -->
                <div class="info-column">
                    <!-- Guidelines Card -->
                    <div class="submit-card">
                        <div class="card-header">
                            <h3><i class="fas fa-info-circle"></i> Submission Guidelines</h3>
                        </div>
                        <div class="card-body">
                            <div class="guideline-list">
                                <div class="guideline-item">
                                    <div class="guideline-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="guideline-content">
                                        <h4>Be Specific</h4>
                                        <p>Provide clear details about the location and nature of the issue</p>
                                    </div>
                                </div>

                                <div class="guideline-item">
                                    <div class="guideline-icon">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                    <div class="guideline-content">
                                        <h4>Include Photos</h4>
                                        <p>Clear images help officials understand and prioritize your report</p>
                                    </div>
                                </div>

                                <div class="guideline-item">
                                    <div class="guideline-icon">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="guideline-content">
                                        <h4>Be Respectful</h4>
                                        <p>Use appropriate language when describing issues</p>
                                    </div>
                                </div>

                                <div class="guideline-item">
                                    <div class="guideline-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="guideline-content">
                                        <h4>Response Time</h4>
                                        <p>Reports are typically reviewed within 24-48 hours</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Process Card -->
                    <div class="submit-card">
                        <div class="card-header">
                            <h3><i class="fas fa-tasks"></i> What Happens Next?</h3>
                        </div>
                        <div class="card-body">
                            <div class="process-steps">
                                <div class="process-step">
                                    <div class="step-number">1</div>
                                    <div class="step-content">
                                        <h4>Submission</h4>
                                        <p>Your report is submitted and logged in the system</p>
                                    </div>
                                </div>

                                <div class="process-step">
                                    <div class="step-number">2</div>
                                    <div class="step-content">
                                        <h4>Review</h4>
                                        <p>Barangay officials review and verify your report</p>
                                    </div>
                                </div>

                                <div class="process-step">
                                    <div class="step-number">3</div>
                                    <div class="step-content">
                                        <h4>Action</h4>
                                        <p>Appropriate action is taken to address the issue</p>
                                    </div>
                                </div>

                                <div class="process-step">
                                    <div class="step-number">4</div>
                                    <div class="step-content">
                                        <h4>Resolution</h4>
                                        <p>You'll be notified when the issue is resolved</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact Card -->
                    <div class="submit-card emergency-card">
                        <div class="card-header">
                            <h3><i class="fas fa-exclamation-triangle"></i> Emergency?</h3>
                        </div>
                        <div class="card-body">
                            <p class="emergency-text">
                                For urgent matters requiring immediate attention, please contact:
                            </p>
                            <div class="emergency-contacts">
                                <a href="tel:911" class="emergency-btn">
                                    <i class="fas fa-phone-alt"></i>
                                    Emergency Hotline: 911
                                </a>
                                <a href="tel:0966-731-6242" class="emergency-btn">
                                    <i class="fas fa-building"></i>
                                    Emergency Operations Center: 0966-731-6242
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Character counter for description
        const descriptionTextarea = document.getElementById('description');
        const charCount = document.getElementById('char-count');

        descriptionTextarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            if (count >= 450) {
                charCount.style.color = '#ef4444';
            } else if (count >= 400) {
                charCount.style.color = '#f59e0b';
            } else {
                charCount.style.color = '#10b981';
            }
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
                // Validate file type
                if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
                    alert('Please upload only JPG or PNG images');
                    this.value = '';
                    return;
                }
                
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB');
                    this.value = '';
                    return;
                }
                
                // Show preview
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

        // Form submission (Demo)
        document.getElementById('submit-report-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate all required fields
            const title = document.getElementById('report_title').value;
            const category = document.getElementById('category').value;
            const location = document.getElementById('location').value;
            const description = document.getElementById('description').value;
            const image = document.getElementById('report_image').files[0];
            
            if (!title || !category || !location || !description || !image) {
                alert('Please fill in all required fields');
                return;
            }
            
            // Simulate successful submission
            alert('Report submitted successfully!\n\nTitle: ' + title + '\nCategory: ' + category + '\nLocation: ' + location);
            
            // Redirect with success message
            window.location.href = 'submit.php?success=1';
        });
    </script>
</body>
</html>