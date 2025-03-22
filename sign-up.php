<?php
session_start();
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmpass = trim($_POST['confirmpass']);

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirmpass)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if ($password !== $confirmpass) {
        die("Passwords do not match.");
    }

    $email = mysqli_real_escape_string($conn, $email);
    $username = mysqli_real_escape_string($conn, $username);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    if (!$stmt) {
        die("Error preparing query: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Email already registered. Please use a different email.");
    }
    $stmt->close();

    // Insert new user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, verified) VALUES (?, ?, ?, 0)");
    if (!$stmt) {
        die("Error preparing insert query: " . $conn->error);
    }
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        // Generate a 4-digit OTP
        $otp = rand(1000, 9999);
        $_SESSION['otp'] = $otp;  // Store OTP in session
        $_SESSION['email'] = $email;  // Store email in session

        // Alert OTP using JavaScript and redirect to OTP page
        echo "<script>
                alert('Your OTP Code: $otp');
                window.location.href = 'otp.html';
              </script>";
        exit(); // Stop further execution
    } else {
        die("Could not sign up: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method.");
}
?>
