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