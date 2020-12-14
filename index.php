<?php
include ('connectdb.php'); // подключение к серверу MySql и выбор БД
$userinfo = '';
$state = '0';
if ((isset($_COOKIE['login'])) & (isset($_COOKIE['pass']))) { // если в куках лежит логин и зашифрованый пароль
    if (!isset($_GET['exit'])) { // если кнопка выход не была нажата
        if($_COOKIE['login'])
            $login = $_COOKIE['login'];
        if($_COOKIE['pass'])
            $pass = $_COOKIE['pass'];
        // проверяем наличие пользователя в БД и достаём оттуда пароль
        $sql = "SELECT id, pass FROM users WHERE login='%s'";
        $query = sprintf($sql,
                         mysqli_real_escape_string($link, $login));
        $res = mysqli_query($link, $query);
        if (mysqli_num_rows($res) > 0) { // если пользователь есть в БД
            $userinfo = mysqli_fetch_array($res); // в этой переменной лежит пароль из БД
            if (strcmp($pass, md5($userinfo['pass'])) == 0) { //проверяем схожесть пароля из БД с паролем из куков
                // достаём все данные из БД
                $sql = "SELECT * FROM users WHERE login='%s'";
                $query = sprintf($sql,
                                mysqli_real_escape_string($link, $login));
                $res = mysqli_query($link, $query);
                $userinfo = mysqli_fetch_array($res); // в этой переменной будет лежать вся информация о пользователе из БД
                $time = time();
                // устанавливаем куки для запоминания статуса пользователя
                setcookie("login", $login, $time + 1800);
                setcookie("pass", $pass, $time + 1800);
                $state = 1; // статус, если 1, тогда пользователь авторизован
            }
        }
    } else {
        //обнуляем куки, если была нажата кнопка выход
        setcookie("login");
        setcookie("pass");
    }
}
if ($state != 1) { // если после проверки куков, оказалось, что пользователь не авторизован, то идем дальше
    if ((isset($_POST['login'])) & (isset($_POST['pass']))) { // если пользователь ввёл логин и пароль
        $login = $_POST['login'];
        // проверяем наличие пользователя в БД и достаём оттуда пароль
        $sql = "SELECT id, pass FROM users WHERE login='%s'";
        $query = sprintf($sql,
                         mysqli_real_escape_string($link, $login));
        $res = mysqli_query($link, $query);
        if (mysqli_num_rows($res) > 0) { // если пользователь есть в БД
            $userinfo = mysqli_fetch_array($res); // в этой переменной лежит пароль из БД и номер пользователя
            $pass = $_POST['pass'];
            if (strcmp($pass, $userinfo['pass']) == 0) {
                // достаём все данные из БД
                $sql = "SELECT * FROM users WHERE login='%s'";
                $query = sprintf($sql,
                                 mysqli_real_escape_string($link, $login));
                $res = mysqli_query($link, $query);
                $userinfo = mysqli_fetch_array($res); // в этой переменной будет лежать вся информация о пользователе из БД
                $time = time();
                // устанавливаем куки для запоминания статуса пользователя, пароль шифруем
                setcookie("login", $login, $time + 1800);
                setcookie("pass", md5($pass), $time + 1800);
                $state = 1; // статус, если 1, тогда пользователь авторизован
            }
        }
        else {
            echo '
                <script type="text/JavaScript">
                    alert("Пользователя с таким логином не существует!!!");
                </script>';
        }
    }
}
if ($state != 1) {
?>
<head>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<form method="post" action="/auth_page/index.php">
    Логин: <input type="text" size="30" name="login"/><br />
    Пароль: <input type="password" name="pass" size="30"/><br />
    <input type="submit" value="Войти"/>
</form>
<br /><a href="/auth_page/register.php">Регистрация</a>
<?php
} else {
    echo '
<body style="background-image: url(img/fb1.gif); text-align: center">
<div style="background-color: white; width: 20%; margin: auto; text-align: center">
    Вы вошли на сайт!<br />
    Ваш Логин: ' . $userinfo["login"] . '<br />
    Ваш E-mail: ' . $userinfo["email"] . '<br />
    <a href="/auth_page/index.php?exit=y"> Выход</a>
</div>
</body>';
}
?>
