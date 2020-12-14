<?php
include ('connectdb.php'); // подключение к серверу MySql и выбор БД
if (isset($_POST['login']) || isset($_POST['pass1']) || isset($_POST['pass2']) || isset($_POST['email'])) {
    // если все данные для регистрации введены, то продолжаем
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    if (strcmp($pass1, $pass2) == 0) { // если пароли совпадают, то продолжаем
        $login = $_POST['login'];
        $email = $_POST['email'];
        //проверяем наличие в БД пользователя с логином $login
        $sql = 'SELECT * FROM users WHERE login= "%s"'; // скрипт для поиска по логину в таблице users
        $query = sprintf($sql, mysqli_real_escape_string($link, $login));
        if (!($res = mysqli_query($link, $query)) || (mysqli_num_rows($res) == 0)) {
            // если количество найденых записей ноль, то продолжаем
            // sql-скрипт для добавления даных в таблицу
            $sql = 'INSERT INTO users(login, pass, email) VALUES("%s", "%s", "%s")';
            $query = sprintf($sql,
                            mysqli_real_escape_string($link, $login),
                            mysqli_real_escape_string($link, $pass1),
                            mysqli_real_escape_string($link, $email));
            if (mysqli_query($link, $query)) { // выполняем скрипт
                echo 'Пользователь ' . $_POST['login'] . ' успешно зарегистрирован!  <a href="/auth_page/index.php">Форма для входа.';
            } else {
                echo 'При регистрации произошла ошибка, <a href="/auth_page/register.php"> повторите попытку</a>.';
            }
        } else echo 'Пользователь с таким логином уже зарегистрирован!';
    } else echo 'Введенные пароли не совпадают, <a href="/auth_page/register.php"> повторите попытку</a>.';
} else {
	echo "
<head>
    <link rel='stylesheet' href='stylesheet.css'>
</head>
<form method='post' action='/auth_page/register.php'> Введите Логин: <input type='text' size='30' name='login' /><br />
	Введите e-mail: <input type=text size=30 name='email' /><br /> Пароль: <input type='password' name='pass1' size='30' /><br />
	Повторите пароль: <input type='password' name='pass2' size='30' /><br /> <input type='submit' value='Регистрация' /> </form>
    <br /><a href='/auth_page/index.php'>Вход</a>";
}
?>
