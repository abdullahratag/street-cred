<?php
session_start();
include(dirname(__DIR__) . "/database/database.php");

$success_message = "";
$error_message = "";

$first_name = "";
$last_name = "";
$email = "";
$phone_number = "";
$barangay = "";
$role = "citizen";
$lgu_department = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim(mysqli_real_escape_string($conn, $_POST['first_name']));
    $last_name = trim(mysqli_real_escape_string($conn, $_POST['last_name']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $phone_number = trim(mysqli_real_escape_string($conn, $_POST['phone_number']));
    $barangay = trim(mysqli_real_escape_string($conn, $_POST['barangay']));
    $role = trim(mysqli_real_escape_string($conn, $_POST['role']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Capture LGU Department selection only if role is 'lgu_admin'
    if ($role === 'lgu_admin') {
        $lgu_department = trim(mysqli_real_escape_string($conn, $_POST['lgu_department']));
    } else {
        $lgu_department = null;
    }

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } elseif ($role === 'lgu_admin' && empty($lgu_department)) {
        $error_message = "Please select your assigned LGU department.";
    } elseif (empty($barangay)) {
        $error_message = "Please select your assigned or residential Barangay.";
    } else {
        $check_query = "SELECT ID FROM users WHERE email = '$email' LIMIT 1";
        $check_result = mysqli_query($conn, $check_query);

        if ($check_result && mysqli_num_rows($check_result) > 0) {
            $error_message = "Email address is already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert_query = "INSERT INTO users (first_name, last_name, phone_number, email, role, password_hash, barangay, lgu_department)
                             VALUES ('$first_name', '$last_name', '$phone_number', '$email', '$role', '$hashed_password', '$barangay', " . 
                             ($lgu_department ? "'$lgu_department'" : "NULL") . ")";

            if (mysqli_query($conn, $insert_query)) {
                $success_message = "Registration successful! You may now log in.";

                // Reset forms on success
                $first_name = "";
                $last_name = "";
                $email = "";
                $phone_number = "";
                $barangay = "";
                $role = "citizen";
                $lgu_department = "";
            } else {
                $error_message = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StreetCred Registration</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<style>
    .role-selection-wrapper {
        margin-bottom: 18px;
        text-align: left;
    }
    .role-label {
        font-size: 0.9rem;
        color: #475569;
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
    }
    .role-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 12px;
    }
    .role-card {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 6px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .role-card i {
        display: block;
        font-size: 1.3rem;
        margin-bottom: 6px;
        color: #64748b;
    }
    .role-card span {
        display: block;
        font-size: 0.78rem;
        font-weight: 600;
        color: #475569;
        line-height: 1.2;
    }
    .role-card.active {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    .role-card.active i, .role-card.active span {
        color: #2563eb;
    }
    select.form-dropdown {
        width: 100%;
        padding: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.95rem;
        background-color: #fff;
        margin-bottom: 16px;
        color: #334155;
    }
    .hidden-field {
        display: none;
        animation: fadeIn 0.3s ease-in-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
<body>

<div class="top-bar"></div>

<div class="container">
    <div class="register-box">
        <h1>StreetCred</h1>
        <p>Create your infrastructure account</p>

        <?php if (!empty($error_message)): ?>
            <div class="message-banner error-banner">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error_message); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="message-banner success-banner">
                <i class="fas fa-check-circle"></i>
                <span><?php echo htmlspecialchars($success_message); ?></span>
            </div>
        <?php endif; ?>

        <form action="registration.php" method="POST" id="regForm">
            
            <div class="role-selection-wrapper">
                <label class="role-label"><i class="fas fa-user-shield"></i> Account Type</label>
                <div class="role-options">
                    <div class="role-card <?php echo ($role === 'citizen') ? 'active' : ''; ?>" onclick="selectRole('citizen')">
                        <i class="fas fa-user"></i>
                        <span>Citizen</span>
                    </div>
                    <div class="role-card <?php echo ($role === 'barangay_admin') ? 'active' : ''; ?>" onclick="selectRole('barangay_admin')">
                        <i class="fas fa-gavel"></i>
                        <span>Barangay Admin</span>
                    </div>
                    <div class="role-card <?php echo ($role === 'lgu_admin') ? 'active' : ''; ?>" onclick="selectRole('lgu_admin')">
                        <i class="fas fa-building-shield"></i> <span>LGU Admin</span>
                    </div>
                </div>
                <input type="hidden" name="role" id="user_role" value="<?php echo htmlspecialchars($role); ?>">
            </div>

            <div id="department-wrapper" class="hidden-field" <?php echo ($role === 'lgu_admin') ? 'style="display:block;"' : ''; ?>>
                <label class="role-label"><i class="fas fa-briefcase"></i> Assigned LGU Department Specialty</label>
                <select name="lgu_department" id="lgu_department" class="form-dropdown">
                    <option value="">Select department assignment</option>
                    
                    <option value="City Engineering Office" <?php if($lgu_department == 'City Engineering Office') echo 'selected'; ?>>
                        City Engineering Office (Drainage, Roads & Infra)
                    </option>
                    
                    <option value="City Environment & Natural Resources Office" <?php if($lgu_department == 'City Environment & Natural Resources Office') echo 'selected'; ?>>
                        City Environment & Natural Resources Office (Waste Management)
                    </option>
                    
                    <option value="City General Services Office" <?php if($lgu_department == 'City General Services Office') echo 'selected'; ?>>
                        City General Services Office (Streetlights & Utilities)
                    </option>
                    
                    <option value="City Traffic Operations Management" <?php if($lgu_department == 'City Traffic Operations Management') echo 'selected'; ?>>
                        City Traffic Operations Management (Traffic Concerns)
                    </option>
                    
                    <option value="Public Order and Safety Office" <?php if($lgu_department == 'Public Order and Safety Office') echo 'selected'; ?>>
                        Public Order and Safety Office (Local Security Issues)
                    </option>
                    
                    <option value="Other Local Governance Dept." <?php if($lgu_department == 'Other Local Governance Dept.') echo 'selected'; ?>>
                        Other Local Governance Dept.
                    </option>
                </select>
            </div>

            <div class="input-group">
                <input type="text" name="first_name" placeholder="First Name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                <input type="text" name="last_name" placeholder="Last Name" value="<?php echo htmlspecialchars($last_name); ?>" required>
            </div>

            <input type="email" name="email" placeholder="Email Address" value="<?php echo htmlspecialchars($email); ?>" required>

            <input type="text" name="phone_number" placeholder="Phone Number" value="<?php echo htmlspecialchars($phone_number); ?>" required>

            <label class="role-label" style="text-align: left; margin-top: 4px;"><i class="fas fa-map-marker-alt"></i> Barangay Jurisdiction</label>
            <select name="barangay" class="form-dropdown" required>
                <option value="">Select your Barangay</option>
                <option value="Arena Blanco" <?php if($barangay == 'Arena Blanco') echo 'selected'; ?>>Arena Blanco</option>
                <option value="Ayala" <?php if($barangay == 'Ayala') echo 'selected'; ?>>Ayala</option>
                <option value="Baliwasan" <?php if($barangay == 'Baliwasan') echo 'selected'; ?>>Baliwasan</option>
                <option value="Boalan" <?php if($barangay == 'Boalan') echo 'selected'; ?>>Boalan</option>
                <option value="Canelar" <?php if($barangay == 'Canelar') echo 'selected'; ?>>Canelar</option>
                <option value="Divisoria" <?php if($barangay == 'Divisoria') echo 'selected'; ?>>Divisoria</option>
                <option value="Guiwan" <?php if($barangay == 'Guiwan') echo 'selected'; ?>>Guiwan</option>
                <option value="La Paz" <?php if($barangay == 'La Paz') echo 'selected'; ?>>La Paz</option>
                <option value="Mampang" <?php if($barangay == 'Mampang') echo 'selected'; ?>>Mampang</option>
                <option value="Pasonanca" <?php if($barangay == 'Pasonanca') echo 'selected'; ?>>Pasonanca</option>
                <option value="Putik" <?php if($barangay == 'Putik') echo 'selected'; ?>>Putik</option>
                <option value="San Roque" <?php if($barangay == 'San Roque') echo 'selected'; ?>>San Roque</option>
                <option value="Santa Catalina" <?php if($barangay == 'Santa Catalina') echo 'selected'; ?>>Santa Catalina</option>
                <option value="Santa Maria" <?php if($barangay == 'Santa Maria') echo 'selected'; ?>>Santa Maria</option>
                <option value="Tetuan" <?php if($barangay == 'Tetuan') echo 'selected'; ?>>Tetuan</option>
                <option value="Zambowood" <?php if($barangay == 'Zambowood') echo 'selected'; ?>>Zambowood</option>
            </select>

            <input type="password" name="password" placeholder="Password" required>

            <input type="password" name="confirm_password" placeholder="Confirm Password" required>

            <button type="submit">Create Account</button>
        </form>

        <div class="login-text">
            Already have an account? <a href="../login-page/LoginPage.php">Login here</a>
        </div>
    </div>
</div>

<script>
    function selectRole(roleType) {
        document.getElementById('user_role').value = roleType;
        
        document.querySelectorAll('.role-card').forEach(card => card.classList.remove('active'));
        
        let targetIndex = 0;
        if (roleType === 'barangay_admin') targetIndex = 1;
        if (roleType === 'lgu_admin') targetIndex = 2;
        
        document.querySelectorAll('.role-card')[targetIndex].classList.add('active');
        
        const deptWrapper = document.getElementById('department-wrapper');
        const deptSelect = document.getElementById('lgu_department');
        
        if (roleType === 'lgu_admin') {
            deptWrapper.style.display = 'block';
            deptSelect.setAttribute('required', 'required');
        } else {
            deptWrapper.style.display = 'none';
            deptSelect.removeAttribute('required');
            deptSelect.value = ""; 
        }
    }
</script>
</body>
</html>