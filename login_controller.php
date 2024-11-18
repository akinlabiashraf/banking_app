<?php
session_start(); // Start a session

include 'db_connection.php'; // Replace with your database connection file

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare SQL query
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set up the session
            $_SESSION['account_number'] = $user['account_number'];
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $user['user_id'];

            // Redirect to a dashboard or home page
            header("Location: user_dashboard.php");

            exit();
        } else {
            $err_msg = '<center><p style="color:red;">You just provided invalid credentials. Please try again...</p></center>';
            $_SESSION['invalid_data'] = $err_msg;
            header('location:login.php');
            exit();
        }
    } else {
        // User not found
        echo "Invalid email or password.";
    }
}
