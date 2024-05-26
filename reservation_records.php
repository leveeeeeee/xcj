<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>预约记录</title>
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

$conn = new mysqli("localhost", "root", "", "library", 3308);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$reservation_sql = "SELECT * FROM reservation WHERE user_id = (SELECT user_id FROM user WHERE username = '$username')";
$reservation_result = $conn->query($reservation_sql);

if ($reservation_result->num_rows > 0) {
    echo "预约记录：<br>";
    while ($reservation = $reservation_result->fetch_assoc()) {
        // 显示预约记录
        echo "预约编号: " . $reservation["reservation_id"] . "<br>";
        echo "书号: " . $reservation["book_id"] . "<br>";
        echo "预约日期: " . $reservation["reservationdate"] . "<br>";
        echo "预约情况: " . ($reservation["status_id"] == 23005 ? "书未到馆" : "书已入馆") . "<br>";
    }
} else {
    echo "当前没有预约记录。<br><br>";
}
// 获取书籍的作者信息
function getAuthorsForBook($bookId) {
    global $conn;

    $authors = array();

    $author_sql = "SELECT author.name FROM book_author
                   JOIN author ON book_author.author_id = author.author_id
                   WHERE book_author.book_id = $bookId";

    $author_result = $conn->query($author_sql);

    if ($author_result->num_rows > 0) {
        while ($row = $author_result->fetch_assoc()) {
            $authors[] = $row["name"];  
        }
    }

    return implode(", ", $authors);
}


// 获取书籍的出版社信息
function getPublisherForBook($bookId) {
    global $conn;

    $publisher_sql = "SELECT publisher.publishername FROM book
                      JOIN publisher ON book.publisher_id = publisher.publisher_id
                      WHERE book.book_id = $bookId";

    $publisher_result = $conn->query($publisher_sql);

    if ($publisher_result->num_rows > 0) {
        $row = $publisher_result->fetch_assoc();
        return $row["publishername"];
    }

    return "未知出版社"; // 处理没有出版社信息的情况
}

$conn->close();
?>
