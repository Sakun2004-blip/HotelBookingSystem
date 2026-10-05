<?php
session_start();
require_once 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // prepared statement to fetch user record securely
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Verify hashed password
    if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
        // prevent session fixation attacks
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<html lang="en">
<head>
    <title>Login-Grand Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Grand Hotel Portal</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="dashboard.php">My Bookings</a>
            <a class="nav-link" href="booking.php">Book Room</a>
            <a class="btn btn-outline-light btn-sm ms-3" href="logout.php">Logout</a>
        </div>
    </div>
</nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="text-center mb-4">User Login</h3>
                        <?php if(!empty($error)): ?>
                            <div class="alert alert-danger"><?= $error; ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Login</button>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="forgot_password.php">Forgot Password?</a> | <a href="register.php">Register</a>
                         </div>   
                     </div>
                  </div>
               </div>
            </div>
        </div>                    
</body>
</html>