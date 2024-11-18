<?php

include('db_connection.php');


$data = json_decode(file_get_contents('php://input'), true);

$receiver_account_number = $data['receiver_account_number'];
$amount = $data['amount'];
$total_amount = $data['total_amount'];


$commission = 3.00;


session_start();
if (!isset($_SESSION['account_number'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit();
}
$sender_account_number = $_SESSION['account_number'];

$sender_query = "SELECT u.user_id, a.balance FROM users u JOIN accounts a ON u.user_id = a.user_id WHERE u.account_number = ?";
$stmt = $conn->prepare($sender_query);
$stmt->bind_param("s", $sender_account_number);
$stmt->execute();
$sender_result = $stmt->get_result();

if ($sender_result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Sender not found.']);
    exit();
}

$sender = $sender_result->fetch_assoc();
$sender_id = $sender['user_id'];
$sender_balance = $sender['balance'];


if ($sender_balance < $total_amount) {
    echo json_encode(['success' => false, 'error' => 'Not enough funds.']);
    exit();
}

$receiver_query = "SELECT u.user_id, a.balance FROM users u JOIN accounts a ON u.user_id = a.user_id WHERE u.account_number = ?";
$stmt = $conn->prepare($receiver_query);
$stmt->bind_param("s", $receiver_account_number);
$stmt->execute();
$receiver_result = $stmt->get_result();

if ($receiver_result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Receiver not found.']);
    exit();
}

$receiver = $receiver_result->fetch_assoc();
$receiver_id = $receiver['user_id'];
$receiver_balance = $receiver['balance'];


$conn->begin_transaction();

try {
    $new_sender_balance = $sender_balance - $total_amount;
    $update_sender_sql = "UPDATE accounts SET balance = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_sender_sql);
    $stmt->bind_param("di", $new_sender_balance, $sender_id);
    if (!$stmt->execute()) {
        throw new Exception("Error updating sender's balance.");
    }

    $amount_to_receive = $amount;
    $new_receiver_balance = $receiver_balance + $amount_to_receive;
    $update_receiver_sql = "UPDATE accounts SET balance = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_receiver_sql);
    $stmt->bind_param("di", $new_receiver_balance, $receiver_id);
    if (!$stmt->execute()) {
        throw new Exception("Error updating receiver's balance.");
    }

    $transaction_description = "Sent $$amount to account number $receiver_account_number, commission: $$commission";
    $transaction_type = 'transfer';
    $transaction_sender_sql = "INSERT INTO transactions (sender_id, receiver_id, amount, transaction_type, description, username)
                                VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($transaction_sender_sql);
    $stmt->bind_param("iissss", $sender_id, $receiver_id, $amount, $transaction_type, $transaction_description, $sender_account_number);
    if (!$stmt->execute()) {
        throw new Exception("Error inserting transaction record for sender.");
    }

    // Record the transaction in the Transactions table (receiver's entry)
    $transaction_receiver_sql = "INSERT INTO transactions (sender_id, receiver_id, amount, transaction_type, description, username)
                                 VALUES (?, ?, ?, ?, ?, ?)";
    $receiver_description = "Received $$amount from account number $sender_account_number";
    $stmt = $conn->prepare($transaction_receiver_sql);
    $stmt->bind_param("iissss", $sender_id, $receiver_id, $amount, $transaction_type, $receiver_description, $receiver_account_number);
    if (!$stmt->execute()) {
        throw new Exception("Error inserting transaction record for receiver.");
    }

    // Record the commission as an expense for the sender in the Expenses table
    $expense_description = "Commission for sending $$amount to account number $receiver_account_number";
    $expense_sql = "INSERT INTO expenses (user_id, amount, description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($expense_sql);
    $stmt->bind_param("ids", $sender_id, $commission, $expense_description);
    if (!$stmt->execute()) {
        throw new Exception("Error inserting expense record.");
    }

    // Commit the transaction if all queries succeeded
    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback the transaction in case of error
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
