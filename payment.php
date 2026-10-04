<?php
session_start();
require_once 'dp.php';

if(!isset($_SESSION['user_id']) || !isset($_GET['booking_id'])){
    header("Location: login.php");
    exit();
}

$booking_id=$_GET['booking_id'];

//Fetch booking details securely
$stmt=$pdo->prepare("SELECT b.*, r.room_name FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.id = ?");
$stmt->execute([$booking_id]);
$booking=$stmt->fetch();

if($_SERVER['REQUEST_METHOD']==='POST'){
  $card_number=$_POST['card_number'];
  //Security : Only store last 4 digits of card number
  $card_last_four=substr(preg_replace('/\D/', '', $card_number), -4);
  $amount=$booking['total_price'];

  //Insert payment record securely
  $pay_stmt=$pdo->prepare("INSERT INTO payments(booking_id,amount,card_last_four,payment_status)VALUES(?,?,?,'Completed')");
  $pay_stmt->execute([$booking_id,$amount,$card_last_four]);

  //Update room status
  $upd_room=$pdo->prepare("UPDATE room SET status='Booked' WHERE id=?");
  $upd_room->execute([$booking['room_id']]);

  echo "<script>alert('Payment Successful! Booking Confirmed.'); window.location='dashboard.php';</script>";
  exit();

}
?>

<html lang="en">
<head>
    <title>Secure Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 col-md-6">
    <div class="card shadow-p4">
        <h3>Secure Checkout</h3>    
        <p>Room: <strong><? =htmlspecialchars($booking['room_name']); ?></strong></p>
        <p>Total Amount: <strong>$<? =htmlspecialchars($booking['total_price']); ?></strong></p>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Cardholder Name</label>
                <input type="text" class="form-control" required placeholder="John Doe">
            </div>
            <div class="mb-3">
                <label class="form-label">Card Number (Mock Secure Input)</label>
                <input type="text" name="card_number" class="form-control" maxlength="16" required placeholder="4111222233334444">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Expiry Date</label>
                    <input type="text" class="form-control" placeholder="MM/YY" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">CVV</label>
                    <input type="password" class="form-control" maxlength="3" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100">Pay $<?= $booking['total_price']; ?></button>
        </form>
    </div>
</div>
</body>
</html>



