<?php
//данные о хосте, пользователе и базе данных
$host = 'localhost';
$user = 'user_for_auth_page';
$pass = 'Oppressed-Unwilling-Sloped4';
$dbname = 'auth_page';
// подключаемся и выбираем бд, которую указали выше
$link = mysqli_connect($host, $user, $pass, $dbname);
if (!$link)
	die('не удалось подключиться к серверу mysql!');
elseif (!mysqli_select_db($link, $dbname))
	die('не удалось выбрать бд!');
?>
