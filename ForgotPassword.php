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

