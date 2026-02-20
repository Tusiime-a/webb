<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit('Invalid');


// CSRF check
if (!isset($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) exit('CSRF failure');


$cause_id = (int)($_POST['cause_id'] ?? 0);
$donor_name = trim($_POST['donor_name'] ?? '');
$donor_email = trim($_POST['donor_email'] ?? '');
$amount = (float)($_POST['amount'] ?? 0);
$currency = trim($_POST['currency'] ?? 'USD');
$user_id = $_SESSION['user']['id'] ?? null;


$errors = [];
if (!$cause_id || !$donor_name || !filter_var($donor_email, FILTER_VALIDATE_EMAIL) || $amount <= 0) {
$errors[] = 'Invalid input.';
}
if ($errors) { foreach ($errors as $e) echo e($e)."<br>"; exit; }


// Record donation with status pending
$stmt = $pdo->prepare('INSERT INTO donations (user_id, cause_id, donor_name, donor_email, amount, currency, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?)');
$stmt->execute([$user_id, $cause_id, $donor_name, $donor_email, $amount, $currency, 'pending']);
$donation_id = $pdo->lastInsertId();


// MOCK PAYMENT PROCESS
// In production: redirect to payment gateway (Stripe Checkout / PayPal), then handle webhook to confirm payment.
$mock_success = true; // simulate success
$transaction_id = 'MOCK-'.bin2hex(random_bytes(8));


if ($mock_success) {
$stmt = $pdo->prepare('UPDATE donations SET payment_status = ?, transaction_id = ? WHERE id = ?');
$stmt->execute(['completed', $transaction_id, $donation_id]);
echo "Thank you, your donation was successful. Transaction ID: ".e($transaction_id);
} else {
$stmt = $pdo->prepare('UPDATE donations SET payment_status = ? WHERE id = ?');
$stmt->execute(['failed', $donation_id]);
echo "Payment failed.";