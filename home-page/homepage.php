<?php
// Activate session tracking to pull real logged-in user data
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../database/database.php");

// Set up fallbacks for display data if a session isn't running yet
$display_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : "Tester!";
$display_barangay = isset($_SESSION['user_barangay']) ? $_SESSION['user_barangay'] : "Dummy ";

// Capture the user's role from the session (Defaults to civilian)
$user_role = isset($_SESSION['user_role']) ? strtolower($_SESSION['user_role']) : 'civilian';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Street Cred - Zamboanga City Program</title>
    <link rel="stylesheet" href="../home-page/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

        <header>
            <div class="container">
                <div class="nav-wrapper">
                    
                    <a href="../index.php" class="logo" style="text-decoration: none; color: inherit;">
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
                        <a href="#" class="btn-nav active">Home</a>
                        <a href="#features" class="btn-nav">Features</a>
                        <a href="#recent-reports" class="btn-nav">Recent Report</a>
                        <a href="#contact-section" class="btn-nav">Contact</a>

                        <?php if ($user_role === 'citizen' || $user_role === 'civilian'): ?>
                            <a href="../submit-page/submit.php" class="btn-nav">Submit Report</a>
                            <a href="../userprofile-page/userprofile.php" class="btn-nav">Profile</a>
                        <?php endif; ?>

                        <?php if ($user_role === 'barangay_admin'): ?>
                            <a href="../admin-pages/barangay-dashboard.php" class="btn-nav">
                                <i class="fas fa-chart-pie"></i> Brgy Dashboard
                            </a>
                        <?php elseif ($user_role === 'lgu_admin'): ?>
                            <a href="../lgu-dashboard/dashboard.php" class="btn-nav">
                                <i class="fas fa-city"></i> LGU Dashboard
                            </a>
                        <?php endif; ?> 

                        <?php if (!isset($_SESSION['user_role'])): ?>
                            <a href="../login-page/LoginPage.php" class="btn-nav">
                                <i class="fas fa-sign-out-alt"></i> Log In
                            </a>
                        <?php endif; ?> 
                    </nav>

                    <?php if (isset($_SESSION['user_role'])): ?>
                        <div class="user-profile">
                            <strong><?php echo htmlspecialchars($display_name); ?></strong><br>
                            <small>Barangay <?php echo htmlspecialchars($display_barangay); ?></small>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_role'])): ?>        
                        <button onclick="logout()" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                        </button>
                    <?php endif; ?>

                </div>
            </div>
        </header>

    <section class="hero">
        <div class="container hero-grid">
            <div class="hero-content">
                <p class="overline">DIGITAL CIVIC REPORTING</p>
                <h1>Modernizing Citizen-Government Feedback</h1>
                <p>Street Cred transforms infrastructure reporting in Zamboanga City. Report issues, track progress, and help build a better community.</p>
                <?php if (!isset($_SESSION['user_role'])): ?>
                    <button class="btn-primary">Register Now!</button>
                <?php endif; ?>
            </div>
            <div class="hero-image">
                <img src="Images/DowntownZamboanga.jpg" alt="Downtown Zamboanga">
            </div>
        </div>
    </section>

    <section class="features" id="features">
        <div class="container">
            <h2>Key platform features</h2>
            <p class="section-desc">Street Cred is built to help citizens report concerns quickly while giving officials clear tools for review, updates, and resolution.</p>
            
            <div class="feature-grid">
                <div class="card">
                    <div class="card-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h3>Report Issues Easily</h3>
                    <p>Citizens can submit infrastructure concerns with location details and photo evidence in a clear guided flow.</p>
                </div>
                <div class="card">
                    <div class="card-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Track Real-Time Status</h3>
                    <p>Transparent updates help residents understand when a report is submitted, under review, or resolved.</p>
                </div>
                <div class="card">
                    <div class="card-icon"><i class="fas fa-building"></i></div>
                    <h3>Efficient Reporting</h3>
                    <p>Officials get practical tools to review, prioritize, and communicate progress back to the public.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="resolved-news" id="recent-reports">
        <div class="container">
            <h2>Recent Reports</h2>
            <p class="section-desc">Stay informed on the latest infrastructure updates across Zamboanga City. From waste collection in Tetuan to utility repairs in Santa Maria, track how our community is working together to resolve local issues.</p>
            
            <div class="news-grid">
                <div class="report-card">
                    <div class="report-img">
                        <img src="Images/Trash.jpg" alt="Uncollected Waste in Tetuan">
                    </div>
                    <div class="report-content">
                        <div class="report-header">
                            <h3>Uncollected Waste in Tetuan</h3>
                            <span class="status-badge resolved">Resolved</span>
                        </div>
                        <p class="category">Waste</p>
                        <p class="description">Garbage has been collected at the side of the highway going Talon-Talon. The area has been cleared to ensure public hygiene.</p>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> Don Alfaro St, Tetuan, Zamboanga City</p>
                    </div>
                </div>

                <div class="report-card">
                    <div class="report-img">
                        <img src="Images/BrokenStreetLight.jpg" alt="Broken Streetlight in Santa Maria">
                    </div>
                    <div class="report-content">
                        <div class="report-header">
                            <h3>Broken Streetlight in Santa Maria</h3>
                            <span class="status-badge resolved">Resolved</span>
                        </div>
                        <p class="category">Electricity</p>
                        <p class="description">The streetlight at the corner of Gov. Ramos Ave and MCLL Highway has been flickering and finally went out.</p>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> Gov. Ramos Ave, Santa Maria, Zamboanga City</p>
                    </div>
                </div>

                <div class="report-card">
                    <div class="report-img">
                        <img src="Images/PotholeSanRoque.jpg" alt="Severe Road Damage in San Roque">
                    </div>
                    <div class="report-content">
                        <div class="report-header">
                            <h3>Deep Potholes and Crevices</h3>
                            <span class="status-badge resolved">Resolved</span>
                        </div>
                        <p class="category">Roads & Maintenance</p>
                        <p class="description">Large hazardous potholes causing severe traffic slows along the primary lane have been completely filled and patched with fresh asphalt concrete layout.</p>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> San Roque Main Road, San Roque, Zamboanga City</p>
                    </div>
                </div>

                <div class="report-card">
                    <div class="report-img">
                        <img src="Images/DrainageTumaga.jpg" alt="Clogged Drainage System in Tumaga">
                    </div>
                    <div class="report-content">
                        <div class="report-header">
                            <h3>Clogged Canal Overflow</h3>
                            <span class="status-badge resolved">Resolved</span>
                        </div>
                        <p class="category">Drainage System</p>
                        <p class="description">Barangay response team successfully cleared accumulated silt, plastic trash barriers, and debris causing immediate street water backup during heavy downpours.</p>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> Tumaga Lizada Road, Tumaga, Zamboanga City</p>
                    </div>
                </div>

                <div class="report-card">
                    <div class="report-img">
                        <img src="Images/WaterPipePasonanca.jpg" alt="Main Water Line Leak in Pasonanca">
                    </div>
                    <div class="report-content">
                        <div class="report-header">
                            <h3>Main Water Pipe Leakage</h3>
                            <span class="status-badge resolved">Resolved</span>
                        </div>
                        <p class="category">Water Supply</p>
                        <p class="description">ZCWD maintenance crew quickly isolated the underground pressure leak, replaced the cracked main distribution link, and safely restored normal community line pressure.</p>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> Pasonanca Park Road, Pasonanca, Zamboanga City</p>
                    </div>
                </div>

                <div class="report-card">
                    <div class="report-img">
                        <img src="Images/FallenTreeBaliwasan.jpg" alt="Fallen Tree Blocking Road in Baliwasan">
                    </div>
                    <div class="report-content">
                        <div class="report-header">
                            <h3>Fallen Tree Obstruction</h3>
                            <span class="status-badge resolved">Resolved</span>
                        </div>
                        <p class="category">Public Safety</p>
                        <p class="description">Emergency response teams chopped and removed a large fallen Acacia branch that completely blocked a side lane, successfully eliminating a major safety risk.</p>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> Baliwasan Chico Road, Baliwasan, Zamboanga City</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="how-it-works">
        <div class="container">
            <h2>How it works</h2>
            <p class="section-desc">Parallel guidance helps both residents and city teams understand the reporting flow.</p>

            <div class="workflow-box citizen-box">
                <h3><i class="fas fa-check-circle" style="color: var(--primary-blue);"></i> For Citizens</h3>
                <div class="step-grid">
                    <div class="step-card">
                        <span>Step 1</span>
                        <p>Choose the issue type and describe the problem clearly.</p>
                    </div>
                    <div class="step-card">
                        <span>Step 2</span>
                        <p>Add a landmark or address with a supporting photo.</p>
                    </div>
                    <div class="step-card">
                        <span>Step 3</span>
                        <p>Submit the report and receive a status update trail.</p>
                    </div>
                    <div class="step-card">
                        <span>Step 4</span>
                        <p>Follow official comments until the issue is resolved.</p>
                    </div>
                </div>
            </div>

            <div class="workflow-box official-box">
                <h3><i class="fas fa-city" style="color: #8bb4e6;"></i> For Officials</h3>
                <div class="step-grid">
                    <div class="step-card dark">
                        <span>Step 1</span>
                        <p>Review incoming reports by category, urgency, and location.</p>
                    </div>
                    <div class="step-card dark">
                        <span>Step 2</span>
                        <p>Assign the issue for verification and field response.</p>
                    </div>
                    <div class="step-card dark">
                        <span>Step 3</span>
                        <p>Post updates so residents can monitor progress.</p>
                    </div>
                    <div class="step-card dark">
                        <span>Step 4</span>
                        <p>Resolve the case and document the final action taken.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-section" id="contact-section">
        <div class="container contact-grid">
            <div class="contact-info">
                <h2>Contact Street Cred</h2>
                <p>Ask questions about services, reporting guidance, or department coordination. Messages are recorded for follow-up.</p>
            </div>
            <div class="contact-form">
                <div class="form-group">
                    <label>Full name</label>
                    <input type="text">
                </div>
                <div class="form-group">
                    <label>Email address</label>
                    <input type="email">
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text">
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <textarea rows="6"></textarea>
                </div>
            </div>
        </div>
    </section>

</body>

<script src="../scripts/script.js"></script>

</html>