<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>图书馆管理系统 - 注册</title>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #2980b9;
        }

        a {
            text-decoration: none;
            color: #3498db;
        }

        a:hover {
            color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>注册</h2>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="user_id">学号或职工号:</label>
                <input type="text" id="user_id" name="user_id" required>
            </div>
            <div class="form-group">
                <label for="username">用户名:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">密码:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="usertype_id">用户类型:</label>
                <select id="usertype_id" name="usertype_id" required>
                    <option value="2">老师</option>
                    <option value="1">学生</option>
                </select>
            </div>
            <div class="form-group">
                <label for="firstname">名字:</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="lastname">姓氏:</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="email">邮箱:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">电话号码:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <button type="submit">注册</button>
        </form>
        <p>已有账户？<a href="login.php">返回登录</a></p>
    </div>
</body>
</html>

<?php
// 处理用户注册逻辑
// 处理用户注册逻辑
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 从表单获取用户注册信息
    $user_id = $_POST["user_id"];
	$usertype_id = $_POST["usertype_id"]; 
    $username = $_POST["username"];
    $password = $_POST["password"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    $conn = new mysqli("localhost", "root", "", "library", 3308);

    if ($conn->connect_error) {
        die("连接失败：" . $conn->connect_error);
    }

    $insertQuery = "INSERT INTO user (user_id, usertype_id, username, password, firstname, lastname, email, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);

    if ($stmt === false) {
        die("预处理失败：" . $conn->error);
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $bindResult = $stmt->bind_param("sssssss", $user_id, $usertype_id, $username, $hashedPassword,  $firstname, $lastname, $email, $phone);

    if ($bindResult === false) {
        die("绑定参数失败：" . $stmt->error);
    }

    $executeResult = $stmt->execute();

    if ($executeResult === false) {
        die("执行查询失败：" . $stmt->error);
    }

    if ($stmt->affected_rows > 0) {
        echo "注册成功！";
    } else {
        echo "注册失败，请重试。";
    }

    $stmt->close();
    $conn->close();
}

?>