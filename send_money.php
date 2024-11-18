<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('config.php');




function initiate_transaction($sender_account_number, $sender_balance, $reciever_account_number, $reciever_balance, $amount_payment) 
{
    global $conn;

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Ensure sender has enough balance and balance is non-negative
        if ($sender_balance >= 0 AND $sender_balance >= $amount_payment) {
            // Calculate new balances
            $new_receiver_balance = $reciever_balance + $amount_payment;
            $new_sender_balance = $sender_balance - $amount_payment;
            
            // Use prepared statements to update balances safely
            $update_receiver_stmt = mysqli_prepare($conn, "UPDATE accounts SET balance = ? WHERE account_id = ?");
            mysqli_stmt_bind_param($update_receiver_stmt, 'ds', $new_receiver_balance, $reciever_account_number);
            $update_sender_stmt = mysqli_prepare($conn, "UPDATE accounts SET balance = ? WHERE account_id = ?");
            mysqli_stmt_bind_param($update_sender_stmt, 'ds', $new_sender_balance, $sender_account_number);

            // Execute the queries
            $update_recivere = mysqli_stmt_execute($update_receiver_stmt);
            $update_sender = mysqli_stmt_execute($update_sender_stmt);

            // Check if both queries succeeded
            if ($update_recivere && $update_sender) {
                // Commit the transaction
                mysqli_commit($conn);
                return 1; // Transaction successful
            } else {
                // If any query fails, rollback
                mysqli_rollback($conn);
                return 0; // Transaction failed, unable to update balances
            }

        } else {
            // Insufficient funds or invalid balance
            return 2; // Insufficient funds or invalid balance
        }
    } catch (Exception $e) {
        // Rollback transaction in case of exception
        mysqli_rollback($conn);
        return 0; // Transaction failed due to exception
    }
}



if(isset($_POST['makepayment'])){
    $receiver_account = mysqli_real_escape_string($conn, $_POST['receiver_account_number']);

    $amount_payment =  (float)  mysqli_real_escape_string($conn, $_POST['amount_payment']);

    $get_sender_detail = mysqli_query($conn, "SELECT * FROM accounts WHERE account_id = '$account_number'");
    if (mysqli_num_rows($get_sender_detail) > 0) {
        while ($sender_detail = mysqli_fetch_array($get_sender_detail)) {
            $sender_account_number = $sender_detail['account_id'];
            $sender_balance = (float) $sender_detail['balance'];
        }
    }else{
        echo "sender account not fund";
        die();
    }


    // confirm the reciver account number
    $get_sender_detail = mysqli_query($conn, "SELECT * FROM accounts WHERE account_id = '$receiver_account'");
    if (mysqli_num_rows($get_sender_detail) > 0) {
        while ($sender_detail = mysqli_fetch_array($get_sender_detail)) {
            $reciever_account_number = $sender_detail['account_id'];
            $reciever_balance =  (float) $sender_detail['balance'];
        }
    }else{
        echo "receiver account not fund";
        die();
    }
    // fund reciever

    $initiate = initiate_transaction($sender_account_number, $sender_balance, $reciever_account_number, $reciever_balance, $amount_payment);


    echo $initiate;
}


// // Get data from POST request
// $data = json_decode(file_get_contents('php://input'), true);

// if (!$data) {
//     echo json_encode(['success' => false, 'error' => 'Invalid input data.']);
//     exit();
// }

// // Extract data from the request
// $receiver_account_number = $data['receiver_account_number'];
// $amount = $data['amount'];
// $total_amount = $data['total_amount'];
// $commission = 3.00;

// // Ensure user is logged in
// $sender_account_number = $_SESSION['account_number'] ?? null;
// if (!$sender_account_number) {
//     echo json_encode(['success' => false, 'error' => 'User not logged in.']);
//     exit();
// }

// try {
//     // Fetch sender account details
//     $stmt = $conn->prepare("SELECT u.user_id, a.balance FROM users u JOIN accounts a ON u.user_id = a.user_id WHERE u.account_number = ?");
//     $stmt->bind_param("s", $sender_account_number);
//     $stmt->execute();
//     $sender_result = $stmt->get_result();
//     if ($sender_result->num_rows === 0) {
//         throw new Exception("Sender not found.");
//     }
//     $sender = $sender_result->fetch_assoc();
//     $sender_id = $sender['user_id'];
//     $sender_balance = $sender['balance'];

//     // Check if sender has enough balance
//     if ($sender_balance < $total_amount) {
//         throw new Exception("Not enough funds.");
//     }

//     // Fetch receiver account details
//     $stmt = $conn->prepare("SELECT u.user_id, a.balance FROM users u JOIN accounts a ON u.user_id = a.user_id WHERE u.account_number = ?");
//     $stmt->bind_param("s", $receiver_account_number);
//     $stmt->execute();
//     $receiver_result = $stmt->get_result();
//     if ($receiver_result->num_rows === 0) {
//         throw new Exception("Receiver not found.");
//     }
//     $receiver = $receiver_result->fetch_assoc();
//     $receiver_id = $receiver['user_id'];
//     $receiver_balance = $receiver['balance'];

//     // Start transaction
//     $conn->begin_transaction();

//     // Deduct total amount from sender
//     $new_sender_balance = $sender_balance - $total_amount;
//     $stmt = $conn->prepare("UPDATE accounts SET balance = ? WHERE user_id = ?");
//     $stmt->bind_param("di", $new_sender_balance, $sender_id);
//     $stmt->execute();

//     // Add amount to receiver
//     $new_receiver_balance = $receiver_balance + $amount;
//     $stmt = $conn->prepare("UPDATE accounts SET balance = ? WHERE user_id = ?");
//     $stmt->bind_param("di", $new_receiver_balance, $receiver_id);
//     $stmt->execute();

//     // Record transaction for sender
//     $transaction_description = "Sent $$amount to account number $receiver_account_number, commission: $$commission";
//     $stmt = $conn->prepare("INSERT INTO transactions (sender_id, receiver_id, amount, transaction_type, description, username) VALUES (?, ?, ?, 'transfer', ?, ?)");
//     $stmt->bind_param("iisss", $sender_id, $receiver_id, $amount, $transaction_description, $sender_account_number);
//     $stmt->execute();

//     // Record transaction for receiver
//     $receiver_description = "Received $$amount from account number $sender_account_number";
//     $stmt = $conn->prepare("INSERT INTO transactions (sender_id, receiver_id, amount, transaction_type, description, username) VALUES (?, ?, ?, 'transfer', ?, ?)");
//     $stmt->bind_param("iisss", $sender_id, $receiver_id, $amount, $receiver_description, $receiver_account_number);
//     $stmt->execute();

//     // Record commission as expense
//     $expense_description = "Commission for sending $$amount to account number $receiver_account_number";
//     $stmt = $conn->prepare("INSERT INTO expenses (user_id, amount, description) VALUES (?, ?, ?)");
//     $stmt->bind_param("ids", $sender_id, $commission, $expense_description);
//     $stmt->execute();

//     // Commit transaction
//     $conn->commit();
//     echo json_encode(['success' => true]);
// } catch (Exception $e) {
//     // Rollback transaction in case of error
//     $conn->rollback();
//     echo json_encode(['success' => false, 'error' => $e->getMessage()]);
// }
?>
