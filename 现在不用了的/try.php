<?php
// 连接数据库
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "math";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // 设置 PDO 错误模式为异常
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 在用户点击注册并登录按钮时执行以下代码
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $user_id = $_POST['user_id'];
        $password = $_POST['password'];

        // 使用预处理语句插入数据到数据库
        $stmt = $conn->prepare("INSERT INTO user (User_Id, Username, Password) VALUES (:user_id, :username, :password)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        
        // 执行预处理语句
        if ($stmt->execute()) {
            // 注册并登录成功
            header("Location: home1.php"); // 跳转到成功页面
            exit();
        } else {
            // 注册并登录失败
            echo "注册并登录失败，请重试";
        }
    }
}
catch(PDOException $e) {
    // 发生异常错误
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>
