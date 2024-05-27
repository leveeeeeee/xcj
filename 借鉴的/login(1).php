<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>图书馆管理系统</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>图书馆管理系统</h1>

    <?php
    session_start();

    // 如果用户已登录，重定向到 dashboard
    if (isset($_SESSION['user_info'])) {
        header("Location: dashboard.php");
        exit();
    }

    // 处理用户提交的登录表单
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
        $conn = new mysqli("localhost", "root", "", "library", 3308);

        // 连接数据库失败时输出错误信息
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
        }

        $username = $_POST["username"];
        $password = $_POST["password"];

        // 使用准备语句，防止 SQL 注入
        $login_sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $conn->prepare($login_sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // 查询数据库失败时输出错误信息
        if (!$result) {
            die("查询失败: " . $conn->error);
        }

        // 验证用户身份
        if ($result->num_rows > 0) {
            $user_info = $result->fetch_assoc();
            if (password_verify($password, $user_info['password'])) {
                // 登录成功，将用户信息存入 session 并重定向到 dashboard
                $_SESSION['user_info'] = $user_info;
                header("Location: dashboard.php");
                exit();
            } else {
                echo "用户名或密码错误";
            }
        } else {
            echo "用户名或密码错误";
        }

        $stmt->close();
        $conn->close();
    }
    ?>

    <!-- 登录表单 -->
    <form method="post" action="login.php">
        <label for="username">用户名：</label>
        <input type="text" name="username" required><br>
        <label for="password">密码：</label>
        <input type="password" name="password" required><br>
        <input type="submit" value="登录">
    </form>
</body>
</html>
