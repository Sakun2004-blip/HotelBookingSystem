<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT b.*, r.room_name, r.room_type FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ?");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - Grand Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Grand Hotel Portal</a>
        <div class="d-flex">
            <span class="navbar-text me-3 text-white">Welcome, <?= htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Your Active Bookings</h2>
        <a href="booking.php" class="btn btn-primary">+ Book New Room</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Booking ID</th>
                    <th>Room Name</th>
                    <th>Type</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Total Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($bookings) > 0): ?>
                    <?php foreach($bookings as $b): ?>
                        <tr>
                            <td>#<?= $b['id']; ?></td>
                            <td><?= htmlspecialchars($b['room_name']); ?></td>
                            <td><?= htmlspecialchars($b['room_type']); ?></td>
                            <td><?= $b['check_in']; ?></td>
                            <td><?= $b['check_out']; ?></td>
                            <td>$<?= $b['total_price']; ?></td>
                            <td><span class="badge bg-success"><?= $b['booking_status']; ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No bookings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>