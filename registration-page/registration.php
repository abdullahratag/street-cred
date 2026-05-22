<?php
session_start();
include("../database/database.php");

$success_message = "";
$error_message = "";

$first_name = "";
$last_name = "";
$email = "";
$phone_number = "";
$barangay = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim(mysqli_real_escape_string($conn, $_POST['first_name']));
    $last_name = trim(mysqli_real_escape_string($conn, $_POST['last_name']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $phone_number = trim(mysqli_real_escape_string($conn, $_POST['phone_number']));
    $barangay = trim(mysqli_real_escape_string($conn, $_POST['barangay']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } else {
        $check_query = "SELECT ID FROM users WHERE email = '$email' LIMIT 1";
        $check_result = mysqli_query($conn, $check_query);

        if ($check_result && mysqli_num_rows($check_result) > 0) {
            $error_message = "Email address is already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = "citizen";

            $insert_query = "INSERT INTO users (first_name, last_name, phone_number, email, role, password_hash, barangay)
                             VALUES ('$first_name', '$last_name', '$phone_number', '$email', '$role', '$hashed_password', '$barangay')";

            if (mysqli_query($conn, $insert_query)) {
                $success_message = "Registration successful! You may now log in.";

                $first_name = "";
                $last_name = "";
                $email = "";
                $phone_number = "";
                $barangay = "";
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
<body>

<div class="top-bar"></div>

<div class="container">
    <div class="register-box">
        <h1>StreetCred</h1>
        <p>Create your citizen account</p>

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

        <form action="registration.php" method="POST">
            <div class="input-group">
                <input type="text" name="first_name" placeholder="First Name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                <input type="text" name="last_name" placeholder="Last Name" value="<?php echo htmlspecialchars($last_name); ?>" required>
            </div>

            <input type="email" name="email" placeholder="Email Address" value="<?php echo htmlspecialchars($email); ?>" required>

            <input type="text" name="phone_number" placeholder="Phone Number" value="<?php echo htmlspecialchars($phone_number); ?>" required>

            <input type="text" name="barangay" placeholder="Barangay" value="<?php echo htmlspecialchars($barangay); ?>" required>

            <input type="password" name="password" placeholder="Password" required>

            <input type="password" name="confirm_password" placeholder="Confirm Password" required>

            <button type="submit">Create Account</button>
        </form>

        <div class="login-text">
            Already have an account? <a href="../login-page/LoginPage.php">Login here</a>
        </div>
    </div>
</div>

</body>
</html>
