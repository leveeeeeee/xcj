<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册页面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 98vh; /* 可以根据需要调整高度 */
            background-color: rgb(159, 214, 235);
        }
        form {
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            position: absolute;
            background-color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input {
            padding: 10px;
            margin: 10px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            width: 100px;;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div>
        <label for="username">用户名:</label>
        <input type="text" id="username" name="username" required placeholder="请输入用户名">
    </div>
    <div>
        <label for="user_id">账号:</label>
        <input type="text" id="user_id" name="user_id" required>
    </div>
        <div>
            <label for="password">密码:</label>
            <input type="password" id="password" name="password" required placeholder="请输入密码">
        </div>
        <div>
            <input type="submit" value="注册并登录" >
        </div>
</form> 
</body>
</html>
<?php
// 在用户点击注册并登录按钮时执行以下代码
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 连接数据库
    $servername = "localhost";
    $username = "root";
    $password = "123456";
    $dbname = "math";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $username = $_POST['username'];
        $user_id = $_POST['user_id'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("INSERT INTO user (User_Id, Username, Password) VALUES (:user_id, :username, :password)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            // 注册并登录成功
            header("Location: home1.php"); // 跳转到成功页面
            exit();
        } else {
            // 注册并登录失败
            echo "注册并登录失败，请重试";
        }
    } catch (PDOException $e) {
        // 发生异常错误
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}
?>

