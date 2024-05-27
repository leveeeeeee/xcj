<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录页面</title>
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
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: yellow;
            border: 2px solid #000;
            z-index: 9999;
        }
    </style>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div>
            <label for="userid">账号:</label>
            <input type="text" id="userid" name="userid" required placeholder="请输入账号">
        </div>
        <div>
            <label for="password">密码:</label>
            <input type="password" id="password" name="password" required placeholder="请输入密码">
        </div>
        <div>
            <input type="submit" value="登录">
        </div>
    <p >还没有账号？<a href="register.html">点击这里注册</a></p>
    </form>
    <div id="loginFailedPopup" class="popup">
        <p>登录失败，请检查您的账号和密码是否正确。</p>
    </div>

    <script>
        // 在页面加载时检查 URL 是否包含 loginFailed 参数，如果包含则显示弹窗并设置定时器自动关闭
        window.onload = function() {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('loginFailed')) {
                var popup = document.getElementById('loginFailedPopup');
                popup.style.display = 'block';
                setTimeout(function(){
                    popup.style.display = 'none';
                }, 3000); // 3秒后关闭弹窗
            }
        };
    </script>
</body>
</body>
</html>
<?php  
session_start();
// 1. 数据库连接配置  
$servername = "localhost";  
$username = "root";  
$password = "123456";  
$dbname = "math";  
  
// 2. 创建连接  
$conn = new mysqli($servername, $username, $password, $dbname);  
  
// 检查连接  
if ($conn->connect_error) {  
    die("连接失败: " . $conn->connect_error);  
}  
  
// 3. 接收用户输入  
if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    // 收集值并赋值给变量  
    $userid = $_POST['userid'];  
    $password = $_POST['password'];  
  
    // 4. 使用预处理语句防止SQL注入  
    // 注意：这里我们直接比较明文密码，这是不安全的！  
    $stmt = $conn->prepare("SELECT * FROM user WHERE User_id = ? AND Password = ?");  
    $stmt->bind_param("ss", $userid, $password);  
  
    $stmt->execute();  
    $result = $stmt->get_result();  
  
    if ($result->num_rows > 0) {  
        // 登录成功，跳转到home2.html 
        $_SESSION['userid'] = 'ID'; 
        header("Location: home2.html");  
        exit();  
    } else {  
        // 未查询到该用户或密码错误  
        header("Location: login.php?loginFailed=true");
exit();
    }  
  
    // 关闭预处理语句  
    $stmt->close();  
}  
  
// 关闭数据库连接  
$conn->close();  
?>
