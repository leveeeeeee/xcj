<?php
session_start();

// 检查用户是否登录，如果没有登录则重定向到登录页面
if (!isset($_SESSION['user_info'])) {
    header("Location: login.php");
    exit();
}

// 获取用户信息
$user_info = $_SESSION['user_info'];
$username = $user_info["username"];
$usertype = $user_info["usertype_id"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>图书馆管理系统</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>欢迎登录图书馆管理系统, <?php echo $username; ?>!</h1>

    <nav>
        <ul>
            <li><a href="user_info.php">用户信息</a></li>
            <li><a href="borrowing_records.php">借阅记录</a></li>
            <li><a href="reservation_records.php">预约记录</a></li>
            <li><a href="search.php">搜索书籍</a></li>
            <li><a href="logout.php">退出登录</a></li>
        </ul>
    </nav>

    <div class="content">
        <?php
        // 根据不同的导航链接加载不同的模块
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (isset($_GET["module"])) {
                $module = $_GET["module"];
                switch ($module) {
                    case "user_info":
                        include "user_info.php";
                        break;
                    case "borrowing_records":
                        include "borrowing_records.php";
                        break;
                    case "reservation_records":
                        include "reservation_records.php";
                        break;
                    case "search_books":
                        include "search_books.php";
                        break;
                    default:
                        echo "Invalid module specified.";
                }
            } else {
                echo "图书馆管理系统";
            }
        }
        ?>
    </div>
</body>
</html>
