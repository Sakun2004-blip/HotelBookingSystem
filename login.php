<?php
session_start();
require_once 'dp.php';

$error='';
if($_SERVER['REQUEST_METHOD']=='POST'){
     $username=trim($_POST['username']);
     $password=trim($_POST['password']);

     //prepared statement to fetch user record securely
     $stmt=$pdo->prepare("SELECT * from users WHERE username=?");
     $stmt->execute([$username]);
     $user=$stmt->fetch():

     //Verify hashed password
     if($user && password_verify($password,$user['password'])){
        //prevent session fixation attacks
        session_regenerate_id(true)
        $_SESSION['user_id']=$user['id'];
        $_SESSION['username']=$user['username'];

        header("Location: dashboard.php");
        exit();
        }else{
            $error="Invalid username or password.";
        }

}
?>