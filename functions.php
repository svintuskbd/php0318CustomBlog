<?php

use Classes\Article;
use Classes\ConnectDb;

session_start();

spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'Classes\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/Classes/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});


$articleManager = new Article(ConnectDb::getConnect());







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







function insertArticle($userData)
{
    $db = connectDb();
    if ($db) {
        $authorId = null;
        if ($_SESSION['user_login']) {
            $authorId = getAuthor($_SESSION['user_login']);
        } else {
            return;
        }

        $sql = "INSERT INTO articles(title, sub_title, content, created_at, author, url)
                  VALUES ( :title, :subTitle,  :content, :createdAt, :authorId, :url)";

        $stmt = $db->prepare($sql);

        $datetime = new DateTime();
        $createdAt = $datetime->format('Y-m-d H:i:s');



        $url = getUrl($userData['title']);

        $stmt->bindParam(':title', $userData['title'], PDO::PARAM_STR);
        $stmt->bindParam(':subTitle', $userData['sub_title'], PDO::PARAM_STR);
        $stmt->bindParam(':content', $userData['content'], PDO::PARAM_STR);
        $stmt->bindParam(':createdAt', $createdAt, PDO::PARAM_STR);
        $stmt->bindParam(':authorId', $authorId, PDO::PARAM_STR);
        $stmt->bindParam(':url', $url, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header('Location: /admin/articles.php');
        }
    }
}

function getUrl($str)
{
    $articleUrl = str_replace(' ', '-', $str);
    $articleUrl = transliteration($articleUrl);
    $articleIsset = getArticleByUrl($articleUrl);
    if (!$articleIsset) {
        return $articleUrl;
    } else {
        $url = $articleIsset['url'];
        $exUrl = explode('-', $url);
        if ($exUrl){
            $temp = (int)end($exUrl);
            $newUrl = $exUrl[0] . '-'. ++$temp;
        } else {
            $temp = 0;
            $newUrl = $articleUrl . '-'. ++$temp;
        }

        return getUrl($newUrl);
    }
}

function transliteration($str)
{
    $st = strtr($str,
        array(
            'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d',
            'е'=>'e','ё'=>'e','ж'=>'zh','з'=>'z','и'=>'i',
            'к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o',
            'п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u',
            'ф'=>'ph','х'=>'h','ы'=>'y','э'=>'e','ь'=>'',
            'ъ'=>'','й'=>'y','ц'=>'c','ч'=>'ch', 'ш'=>'sh',
            'щ'=>'sh','ю'=>'yu','я'=>'ya',' '=>'_', '<'=>'_',
            '>'=>'_', '?'=>'_', '"'=>'_', '='=>'_', '/'=>'_',
            '|'=>'_'
        )
    );
    $st2 = strtr($st,
        array(
            'А'=>'a','Б'=>'b','В'=>'v','Г'=>'g','Д'=>'d',
            'Е'=>'e','Ё'=>'e','Ж'=>'zh','З'=>'z','И'=>'i',
            'К'=>'k','Л'=>'l','М'=>'m','Н'=>'n','О'=>'o',
            'П'=>'p','Р'=>'r','С'=>'s','Т'=>'t','У'=>'u',
            'Ф'=>'ph','Х'=>'h','Ы'=>'y','Э'=>'e','Ь'=>'',
            'Ъ'=>'','Й'=>'y','Ц'=>'c','Ч'=>'ch', 'Ш'=>'sh',
            'Щ'=>'sh','Ю'=>'yu','Я'=>'ya'
        )
    );
    $translit = $st2;

    return $translit;
}


function getArticleByUrl($str)
{
    $db = connectDb();
    if ($db) {
        $sql = "SELECT *
                FROM articles
                WHERE url='$str'
                ";

        return $db->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    return false;
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

function insertUser($userData)
{
    $db = connectDb();
    if ($db) {
        $password = md5($userData['password']);
        $sql = "INSERT INTO users(name, last_name, login, email, password)
              VALUES ( :name,  :lastName, :login, :email, :password)";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':name', $userData['firstName'], PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $userData['lastName'], PDO::PARAM_STR);
        $stmt->bindParam(':login', $userData['login'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $userData['email'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);

        return $stmt->execute();
    }
}

function registerUser(array $userData)
{
    if ($userData['password'] !== $userData['passwordConfirm']) {
        $_SESSION['error_message'] = 'Inputted passwords not confirm!';
        return;
    }

    if (!isset($userData['login']) || empty($userData['login'])) {
        $_SESSION['error_message'] = 'Login can not be empty!';
        return;
    }

    if (!isset($userData['email']) || empty($userData['email'])) {
        $_SESSION['error_message'] = 'Email can not be empty!';
        return;
    }

    //TODO validation data before send to DB

    if (insertUser($userData)) {
        $_SESSION['error_message'] = false;
    } else {
        $_SESSION['error_message'] = 'Register user not complete';
    }
}

function getErrorMessage()
{
    return isset($_SESSION['error_message']) ? $_SESSION['error_message'] : false;
}