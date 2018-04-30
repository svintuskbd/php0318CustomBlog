<?php
function viewTitle()
{
    $arr = explode('.', $_SERVER['REQUEST_URI']);
    $str = substr($arr[0], 1);
    if ($str) {
        echo 'Custom Blog - '. ucfirst($str);
    } else {
        echo 'Custom Blog';
    }

//    $arr = [
//        '/' => 'Custom Blog',
//        '/about.php' => 'Custom Blog - About',
//        '/post.php' => 'Custom Blog - Post',
//        '/contact.php' => 'Custom Blog - Contact',
//    ];
//
//    if (isset($arr[$_SERVER['REQUEST_URI']])) {
//        echo $arr[$_SERVER['REQUEST_URI']];
//    } else {
//        echo 'Custom Blog';
//    }




//    if ($_SERVER['REQUEST_URI'] === '/') {
//        echo 'Custom Blog';
//    } elseif (strpos($_SERVER['REQUEST_URI'], 'about')) {
//        echo 'Custom Blog - About';
//    } elseif (strpos($_SERVER['REQUEST_URI'], 'post')) {
//        echo 'Custom Blog - Post';
//    } elseif (strpos($_SERVER['REQUEST_URI'], 'contact')) {
//        echo 'Custom Blog - Contact';
//    } else {
//        echo 'Custom Blog';
//    }
}

function connectDb()
{
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=custom_blog', 'root', '19888813');

        return $dbh;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";

        return false;
    }
}

function getArticles()
{
    $db = connectDb();
    if ($db) {
        $sql = "SELECT *
                FROM articles
                ";

        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


//    if ($db) {
//        $sql = "SELECT articles.*, user.name
//                FROM article
//                LEFT JOIN users ON users.id=author";
//
//        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
//    }

    return false;
}

function getAuthor($id)
{
    $db = connectDb();
    if ($db) {
        $sql = "SELECT *
                FROM users
                WHERE id=$id
                ";

        return $db->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    return false;
}

function insertArticle($title, $text)
{
    $db = connectDb();
    if ($db) {
        $sql = "INSERT INTO article(title, content) VALUES ( :title,  :content)";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $text, PDO::PARAM_STR);

        $stmt->execute();
    }
}

function updateArticle($article, $id)
{
    $db = connectDb();
    if ($db) {
        $sql = "UPDATE article 
              SET title = '" . $article['title'] . "', content = '" . $article['content'] . "' 
              WHERE id = $id";

        return $db->prepare($sql)->execute();
    }

    return false;
}

function deleteArticle($id)
{
    $db = connectDb();
    if ($db) {
        $sql = "DELETE FROM article WHERE id=$id";

        return $db->prepare($sql)->execute();
    }

    return false;
}