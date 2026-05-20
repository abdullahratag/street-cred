<?php
session_start(); 

include("../database/database.php");

/** @var mysqli $conn */ 

// Initialize variables to prevent undefined notices
$email = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = trim($_POST['password']); 

    // Fetch user details from the database
    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Native comparison against the bcrypt string
        if (password_verify($password, trim($user['password_hash']))) {
            
            // --- SAVE USER INFO TO SESSION ---
            $_SESSION['user_id']       = $user['ID'];
            $_SESSION['first_name']    = $user['first_name'];
            $_SESSION['last_name']     = $user['last_name'];
            $_SESSION['user_role']     = $user['role'];
            $_SESSION['user_barangay'] = $user['barangay']; 
            
            // Send logged-in user to landing homepage
            header("Location: ../home-page/homepage.php");
            exit();

        } else {
            // Professional & Secure: Do not reveal whether the email or password was the specific point of failure
            $error_message = "Incorrect email address or password. Please try again.";
        }
    } else {
        $error_message = "Incorrect email address or password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StreetCred Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Modern Error Banner Styling */
        .error-banner {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #fef2f2;
            border: 1px solid #fca5a5;
            color: #b91c1c;
            padding: 12px;
            border-radius: 6px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            text-align: left;
            line-height: 1.4;
        }
        .error-banner i {
            font-size: 1.1rem;
            flex-shrink: 0;
        }
    </style>
</head>
<body>

<div class="top-bar"></div>

<div class="container">
    <div class="login-box">
        <h1>StreetCred</h1>
        <p>Citizen Infrastructure Reporting System (Zamboanga City)</p>

        <?php if (!empty($error_message)): ?>
            <div class="error-banner">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error_message); ?></span>
            </div>
        <?php endif; ?>

        <form action="LoginPage.php" method="POST">
            <input name="email" type="text" id="username" placeholder="Username or Email" value="<?php echo htmlspecialchars($email); ?>" required>
            <input name="password" type="password" id="password" placeholder="Password" autocomplete="current-password" required>

            <button type="submit">Login</button>
        </form>

        <div class="register-text">
            Not yet registered? <a href="#">Register now</a>
        </div>

        <div class="footer-text">
            <a href="#">Forgot Password?</a>
        </div>
    </div>
</div>

<script src="../scripts/script.js"></script>

</body>
</html>