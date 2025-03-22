<?php
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        die("Email and Password are required.");
    }

    $email = mysqli_real_escape_string($conn, $email);

    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                header("refresh:2; url=TODO.html"); // Redirect to dashboard or homepage
                exit();
            } else {
                echo "Invalid email or password.";
                header("refresh:1; url=index.html");
            }
        } else {
            echo "Invalid email or password.";
            header("refresh:1; url=index.html");
        }

        $stmt->close();
    } else {
        echo "Error in preparing statement: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}