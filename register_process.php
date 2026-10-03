<?php
//Include database connection
require_once 'db.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
    $username=trim($_POST['username']);
    $email=trim($_POST['email']);
    $raw_password=trim($_POST['password']);

    if(empty($username) || empty($email) || empty($raw_password)){
        die("All fields are required.");
    }

    //Security: Use Strong Password Hashing
    $hashed_password=password_hash($raw_password,PASSWORD_BCRYPT);

    try{
        $stmt=$pdo->prepare("INSERT INTO users(username,email,password) VALUES(?,?,?)");
        $stmt->execute([$username,$email,$hashed_password]);

        header("Location: login.php?register=success");
        exit();
    }catch(PDOException $e){
        if($e->getCode() ==23000){
            echo "Error: Username or Email already exists.";
        }else{
            echo "Registration failed: ".$e->getMessage();
        }
    }
}
?>
