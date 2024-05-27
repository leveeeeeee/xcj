<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户信息</title>
</head>
<body>
<?php
session_start();

if (!isset($_SESSION['user_info'])) {
    header("Location: login.php");
    exit();
}

$user_info = $_SESSION['user_info'];
$username = $user_info["username"];
$usertype = $user_info["usertype_id"];

$conn = new mysqli("localhost", "root", "", "library", 3308);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$user_info_sql = "SELECT * FROM user WHERE username = '$username' AND usertype_id = $usertype";
$user_info_result = $conn->query($user_info_sql);

if ($user_info_result->num_rows > 0) {
    $user_info = $user_info_result->fetch_assoc();
    
    // 显示用户信息
    echo "用户信息：<br>";
    echo "学号/职工号: " . $user_info["user_id"] . "<br>";
    echo "用户类型: " . ($usertype == 1 ? "学生" : "老师") . "<br>";
    echo "用户名: " . $user_info["username"] . "<br>";
    echo "姓氏: " . $user_info["firstname"] . "<br>";
    echo "姓名: " . $user_info["lastname"] . "<br>";
    echo "邮箱: " . $user_info["email"] . "<br>";
    echo "电话号码: " . $user_info["phone"] . "<br><br>";
} else {
    echo "未找到用户信息。<br><br>";
}

$conn->close();
?>
