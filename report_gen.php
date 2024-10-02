<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch data based on selected period
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Query to fetch data based on the selected date range
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? AND date BETWEEN ? AND ?");
    $stmt->execute([$_SESSION['user_id'], $start_date, $end_date]);
    $transactions = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Generation</title>
    <link rel="stylesheet" href="CSS/report_gen.css">
</head>
<body>
    <h1>Generate Reports</h1>
    <form method="POST">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" required>
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" required>
        <button type="submit">Generate</button>
    </form>

    <!-- Report results -->
    <?php if (isset($transactions) && count($transactions) > 0): ?>
        <div class="report-section">
            <h2>Transaction Report</h2>
            <?php foreach ($transactions as $transaction): ?>
                <p><?php echo $transaction['date']; ?> - <?php echo ucfirst($transaction['type']); ?>: $<?php echo $transaction['amount']; ?></p>
            <?php endforeach; ?>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <div class="report-section">
            <h2>No Transactions Found</h2>
            <p>No transactions were found in the selected date range.</p>
        </div>
    <?php endif; ?>
</body>
</html>
