<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>借阅记录</title>
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

$borrowing_sql = "SELECT * FROM borrowing WHERE user_id = (SELECT user_id FROM user WHERE username = '$username')";
$borrowing_result = $conn->query($borrowing_sql);

if ($borrowing_result->num_rows > 0) {
    echo "借阅记录：<br>";
    while ($borrowing = $borrowing_result->fetch_assoc()) {
        // 显示借阅记录
        echo "编号: " . $borrowing["borrowing_id"] . "<br>";
        echo "书号: " . $borrowing["book_id"] . "<br>";
        echo "借阅日期: " . $borrowing["borrow_date"] . "<br>";
        echo "归还日期: " . $borrowing["return_date"] . "<br>";
        echo "借阅情况: " . ($borrowing["status_id"] == 23001 ? "借阅中" : "已归还") . "<br>";

        // 根据借阅记录检索相关详情
        $book_details_sql = "SELECT * FROM book WHERE book_id = " . $borrowing["book_id"];
        $book_details_result = $conn->query($book_details_sql);

        if ($book_details_result->num_rows > 0) {
            $book_details = $book_details_result->fetch_assoc();
            // 显示书本详情
            echo "书名: " . $book_details["title"] . "<br>";
            echo "作者: " . getAuthorsForBook($book_details["book_id"]) . "<br>";
            echo "出版社: " . getPublisherForBook($book_details["book_id"]) . "<br>";
            echo "简介: " . $book_details["description"] . "<br>";
            echo "ISBN号: " . $book_details["isbn"] . "<br>";
            echo "当前库存: " . $book_details["availability"] . "<br>";
        }

        echo "<br>";
    }
} else {
    echo "暂无借阅记录。<br><br>";
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
