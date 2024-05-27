<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>搜索书籍</title>
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $search_query = $_POST["search"];
    $search_sql = "SELECT * FROM book 
                   WHERE title LIKE '%$search_query%' 
                      OR book_id IN (SELECT book_author.book_id FROM book_author JOIN author ON book_author.author_id = author.author_id WHERE author.name LIKE '%$search_query%')
                      OR book_id IN (SELECT book_id FROM book WHERE publisher_id IN (SELECT publisher_id FROM publisher WHERE publishername LIKE '%$search_query%'))
                      OR isbn LIKE '%$search_query%'";
    $search_result = $conn->query($search_sql);

    if ($search_result->num_rows > 0) {
        echo "检索结果：<br>";
        while ($book = $search_result->fetch_assoc()) {
            // 显示书籍详情
            echo "书名: " . $book["title"] . "<br>";
            echo "作者: " . getAuthorsForBook($book["book_id"]) . "<br>";
            echo "出版社: " . getPublisherForBook($book["book_id"]) . "<br>";
            echo "简介: " . $book["description"] . "<br>";
            echo "ISBN号: " . $book["isbn"] . "<br>";
            echo "当前库存: " . $book["availability"] . "<br>";
            echo "<br>";
        }
    } else {
        echo "未找到匹配的书籍。<br><br>";
    }
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

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="search">输入搜索关键字：</label>
    <input type="text" id="search" name="search" required>
    <button type="submit">搜索</button>
</form>

</body>
</html>
