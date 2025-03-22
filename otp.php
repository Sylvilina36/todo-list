<?php
session_start();
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = trim($_POST['otp']);
    
    if (!isset($_SESSION['otp']) || !isset($_SESSION['email'])) {
        die("Session expired. Please sign up again.");
    }

    if ($entered_otp == $_SESSION['otp']) {
        // Mark user as verified in the database
        $email = $_SESSION['email'];
        $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();

        // Unset session variables
        unset($_SESSION['otp']);
        unset($_SESSION['email']);
        
        echo "OTP Verified Successfully! Redirecting to login...";
        header("refresh:2; url=index.html");
        exit();
    } else {
        echo "Invalid OTP. Please try again.";
        header("refresh:2; url=otp.html");
    }
}
?>
