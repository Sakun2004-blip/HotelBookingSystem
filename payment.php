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
