<?php
session_name('magiaDental');
session_start();
session_destroy();

header('Location: ./login.php');
