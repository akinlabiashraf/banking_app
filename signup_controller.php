<?php
session_start();
require 'db_connection.php'; // Update with your DB connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $username = htmlspecialchars(trim($_POST['username']));
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $repeat_password = htmlspecialchars(trim($_POST['repeat_password']));

    // Validate required fields
    if (empty($fullname) || empty($username) || empty($email) || empty($password) || empty($repeat_password)) {
        echo "All fields are required.";
        exit();
    }

    // Check if passwords match
    if ($password !== $repeat_password) {
        echo "Passwords do not match.";
        exit();
    }

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Generate a unique account number
    $account_number = 'ACC' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

    // Check for existing email or account number
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR account_number = ?");
    $stmt->bind_param("ss", $email, $account_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email or account number already exists.";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (account_number, username, email, password, full_name) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $account_number, $username, $email, $hashed_password, $fullname);

        if (!$stmt->execute()) {
            throw new Exception("Failed to insert into users table.");
        }

        // Get the user_id of the newly inserted user
        $user_id = $stmt->insert_id;

        // Insert account details into the accounts table
        $initial_balance = 0.00; // Set initial balance to 0.00
        $stmt = $conn->prepare("INSERT INTO accounts (user_id, account_id, balance) VALUES (?, ?, ?)");
        $stmt->bind_param("isd", $user_id, $account_number, $initial_balance);

        if (!$stmt->execute()) {
            throw new Exception("Failed to insert into accounts table.");
        }

        // Commit the transaction
        $conn->commit();

        // Registration success
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['account_number'] = $account_number;

        header("Location: user_dashboard.php"); // Redirect to user dashboard
        exit();
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        echo "Registration failed: " . $e->getMessage();
        exit();
    }
}
?>
