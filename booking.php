<?php
session_start();
require_once 'dp.php';

//check authentication
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$success_msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
     $user_id=$_SESSION['user_id'];
     $room_id=$_POST['room_id'];
     $check_in=$_POST['check_in'];
     $check_out=$_POST['check_out'];

     //Calculate total price using DateTime difference
     $in=new DateTime($check_in);
     $out=new DateTime($check_out);
     $interval=$in->diff($out);
     $days=$interval->days;

     if($days<=0){
        $error_msg="Check-out date must be after check-in date.";
     }else{
        //Fetch room price
        $stmt=$pdo->prepare("SELECT price_per_night FROM rooms WHERE id=?");
        $stmt->execute([$room_id]);
        $room=$stmt->fetch();

        $total_price=$days * $room['price_per_night'];

        //Insert booking record securely
        $book_stmt=$pdo->prepare("INSERT INTO bookings(user_id,room_id,check_in,check_out,total_price) VALUES(?,?,?,?,?)");
        $book_stmt->execute([$user_id,$room_id,$check_in,$check_out,$total_price]);

        $booking_id=$pdo->lastInsertId();
        header("Location: payment.php?booking_id=".$booking_id);
        exit();
     }
}

//Fetch available rooms
$rooms=$pdo->query("SELECT * FROM rooms WHERE status='Available'")->fetchAll();
?>

<html lang="en">
<head>
    <title>Book a Room-Grand Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Book Your Stay,<?= htmlspecialchars($_SESSION['username']) ?></h2>
        <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <div class="card shadow">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Select Room</label>
                    <select name="room_id" class="form-select" required>
                        <?php foreach($rooms as $room): ?>
                            <option value="<?= $room['id']; ?>">
                                <?= htmlspecialchars($room['room_name']); ?> ($<?=$room['price_per_night']; ?>/night)
                            </option>
                            <?php endforeach; ?>
                        </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Check-in Date</label>
                    <input type="date" name="check_in" class="form-control" required min="<?=date(Y-m-d);?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Check-out Date</label>
                    <input type="date" name="check_out" class="form-control" required min="<?=date(Y-m-d);?>">
                 </div>
                <button type="submit" class="btn btn-primary">Proceeed to Payment</button> 
            </form>
        </div>
      </div>
</body>
</html>     
