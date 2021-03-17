<?php
/**
 * Created by PhpStorm.
 * User: noza
 * Date: 16.03.2021
 * Time: 23:07
 */

$conn = mysqli_connect("localhost", "root", "", "friend_with_contacts");


$result = mysqli_query($conn, "SELECT c.name as name, c.photo as photo, p.phone as phone, em.email as email 
                                    FROM contact as c join user_phone as p  
                                    on c.id = p.contact_id
                                     join user_email as em on c.id=em.contact_id ");
$all = mysqli_fetch_all($result, MYSQLI_ASSOC);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'friend_with_contacts');
$link =mysqli_connect(DB_HOST, DB_USER, DB_PASS);
if (!mysqli_connect(DB_HOST, DB_USER, DB_PASS)) {
    exit('Cannot connect to server');
}
if (!mysqli_select_db($link, DB_NAME)) {
    exit('Cannot select database');
}

mysqli_query($link, "SELECT c.name as name, c.photo as photo, p.phone as phone, em.email as email 
                                    FROM contact as c join user_phone as p  
                                    on c.id = p.contact_id
                                     join user_email as em on c.id=em.contact_id ");

function search ($query)
{
    $query = trim($query);
    $query = mysql_real_escape_string($query);
    $query = htmlspecialchars($query);

    if (!empty($query))
    {
        if (strlen($query) < 3) {
            $text = '<p>Слишком короткий поисковый запрос.</p>';
        } else if (strlen($query) > 128) {
            $text = '<p>Слишком длинный поисковый запрос.</p>';
        } else {
            $q = "SELECT c.id as id, c.name as name, p.phone as phone, em.email as email 
                                    FROM contact as c join user_phone as p  
                                    on c.id = p.contact_id
                                     join user_email as em on c.id=em.contact_id  WHERE `name` LIKE '%$query%'
                  OR `phone` LIKE '%$query%' OR `email` LIKE '%$query%'";

            $result = mysql_query($q);

            if (mysql_affected_rows() > 0) {
                $row = mysql_fetch_assoc($result);
                $num = mysql_num_rows($result);

                $text = '<p>По запросу <b>'.$query.'</b> найдено совпадений: '.$num.'</p>';

                do {
                    // Делаем запрос, получающий ссылки на статьи
                    $q1 = "SELECT `name` FROM `contact` WHERE `id` = '$row[id]'";
                    $result1 = mysql_query($q1);

                    if (mysql_affected_rows() > 0) {
                        $row1 = mysql_fetch_assoc($result1);
                    }

                    $text .= '<p>$row[name].",".$row[phone].",".$row[email]</p>';

                } while ($row = mysql_fetch_assoc($result));
            } else {
                $text = '<p>По вашему запросу ничего не найдено.</p>';
            }
        }
    } else {
        $text = '<p>Задан пустой поисковый запрос.</p>';
    }

    return $text;
}
?>

<html>
<head>
    <title>Friend with contacts</title>
    <meta charset="utf8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/js/bootstrap.min.js" integrity="sha384-5h4UG+6GOuV9qXh6HqOLwZMY4mnLPraeTrjT5v07o347pj6IkfuoASuGBhfDsp3d" crossorigin="anonymous"></script>
    <style>
        h1 {
            text-align: center;
        }
        img {
            width: 100px;
            height: auto;
        }


    </style>


</head>
<body>
<h1>Friend with contacts</h1>

<form name="search" method="post" action="index.php">
    <input type="search" name="query" placeholder="Поиск">
    <button type="submit">Найти</button>
</form>
<div>
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Picture</th>
            <th scope="col">Phone</th>
            <th scope="col">E-mail</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($all as $one): ?>
            <tr>

                <td><?=$one['name']?></td>
                <td><?=$one['photo']?></td>
                <td><?=$one['phone']?></td>
                <td><?=$one['email']?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

