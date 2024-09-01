<?php
if (isset($_POST['username']) && isset($_POST['password'])) {
    $password = $_POST['password'];
    $username = $_POST['username'];
    if ($username === 'admin' && $password === 'admin') {
        session_name('magiaDental');
        session_start();
        $_SESSION['loggedin'] = true;
        header('Location: ./index.php');
    } else {
        header('Location: ./login.php?error=1');
    }
}
