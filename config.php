<?php
require 'db_connection.php';


if (!$_SESSION['email']) {
  header('location:login.php');
}

$user_email = $_SESSION['email'];

$user_details = mysqli_query($conn, "SELECT * FROM users WHERE email = '$user_email'");
while($user_detail_result = mysqli_fetch_array($user_details)) {
  $username = $user_detail_result['username'];
  $account_number = $user_detail_result['account_number'];
}





if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Enable error reporting for mysqli
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
      $user_id = $_SESSION['user_id'];

      $balanceQuery = $conn->prepare("SELECT `balance` FROM `accounts` WHERE `user_id` = ?");
      $balanceQuery->bind_param("i", $user_id); 
      $balanceQuery->execute();
      $balanceResult = $balanceQuery->get_result();

      $balance = 0;
      if ($balanceResult->num_rows > 0) {
        $row = $balanceResult->fetch_assoc();
        $balance = $row['balance'];
      }
      $balanceQuery->close();

      $incomeQuery = $conn->prepare("SELECT SUM(`amount`) AS total_income FROM `transactions` WHERE `transaction_type` = 'income' AND `receiver_id` = ?");
      $incomeQuery->bind_param("i", $user_id);
      $incomeQuery->execute();
      $incomeResult = $incomeQuery->get_result();

      $totalIncome = 0; 
      if ($incomeResult->num_rows > 0) {
        $incomeData = $incomeResult->fetch_assoc();
        $totalIncome = $incomeData['total_income'] ?? 0;
      }
      $incomeQuery->close();

      $expenseQuery = $conn->prepare("SELECT SUM(`amount`) AS total_expense FROM `transactions` WHERE `transaction_type` = 'expense' AND `sender_id` = ?");
      $expenseQuery->bind_param("i", $user_id);
      $expenseQuery->execute();
      $expenseResult = $expenseQuery->get_result();

      $totalExpense = 0; 
      if ($expenseResult->num_rows > 0) {
        $expenseData = $expenseResult->fetch_assoc();
        $totalExpense = $expenseData['total_expense'] ?? 0;
      }
      $expenseQuery->close();
    } catch (Exception $e) {
      
      echo "Error: " . $e->getMessage();
    } 

    
    ?>