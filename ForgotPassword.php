<?php
require_once 'db.php';

$message='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email=filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    $email=$pdo->prepare("SELECT id from users WHERE email=?");
    $stmt->execute([$email]);
    $user=$stmt->fetch();

    if($user){

    $token=bin2hex(random_bytes(32));
    $expires=date("Y-m-d H:i:s",strtotime("+1 hour"));

    $update_stmt=$pdo->prepare("UPDATE users SET reset_token = ?,reset_expires = ? WHERE email = ?");
    $update_stmt->execute([$token,$expires,$email]);

    $reset_link = "http://localhost/hotel_system/reset_password.php?token=" . $token;
        $message = "Password reset link generated: <a href='$reset_link'>$reset_link</a>";
    } else {
        $message = "If that email exists, a reset link has been sent.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Grand Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 col-md-5">
    <div class="card shadow p-4">
        <h3>Reset Password</h3>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?= $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Enter Your Account Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
        </form>
        <div class="mt-3 text-center">
            <a href="login.php">Back to Login</a>
        </div>
    </div>
</div>
</body>
</html>

