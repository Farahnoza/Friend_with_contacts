<?php

$conn = mysqli_connect("localhost", "root", "", "friend_with_contacts");

if (isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $query = "DELETE FROM contact WHERE id = '$id' limit 1";
    mysqli_query($conn, $query);

}
if (isset($_GET['update'])){
    $id = (int)$_GET['update'];
    $query = "select * FROM contact WHERE id ='$id' limit 1";

    $result = mysqli_query($conn, $query);
    $one = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $one = reset($one);
}

if (!empty($_POST)){
    if (!empty($_FILES['picture']['tmp_name'])){
        $fileName = md5(rand(10000,99999).microtime(time())).$_FILES['picture']['name'];
        copy($_FILES['picture']['tmp_name'],__DIR__.'/uploads/'.$fileName);
    }
    $id = $_POST['id'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $photo = $fileName ?? "";

    if ($id>0){
        $query = "UPDATE contact SET 
                    'name' = '$name', 
                    surname = '$surname', 
                    picture = '$photo',
                    where id = '$id' limit 1
";
    } else {
        $query = "INSERT INTO contact VALUES (
                    null, 
                    '$name',
                    '$surname',
                    '$photo'
)";
    }

    mysqli_query($conn, $query);
}
$result = mysqli_query($conn, "SELECT * FROM contact ORDER BY id desc");
$all = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<html>
<head>
    <title>Contacts</title>
    <meta charset="utf8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/js/bootstrap.min.js" integrity="sha384-5h4UG+6GOuV9qXh6HqOLwZMY4mnLPraeTrjT5v07o347pj6IkfuoASuGBhfDsp3d" crossorigin="anonymous"></script>
    <style>
        .form-group {
            width: 450px;
            margin: 20px auto;
        }
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
<h1>Add contact</h1>

<form action="contacts.php" method="post" enctype="multipart/form-data">
    <input value="<?=$one['id'] ?? "" ?>" type = "hidden" name ="id">
    <div class="form-group">
        <label>Name</label>
        <input value="<?=$one['name'] ?? "" ?>" type ="text" name = "name" class="form-control">
    </div>
    <div class="form-group">
        <label>Surname</label>
        <input value="<?=$one['surname'] ?? "" ?>" type ="text" name = "surname" class="form-control">
    </div>
    <div class="form-group">
        <label>Picture</label>
        <input type ="file" name = "picture" class="form-control">
    </div>
    <div class="form-group">
        <input type ="submit" name = "submit" class="btn btn-success">
    </div>
</form>
<div>
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Surname</th>
            <th scope="col">Picture</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($all as $one): ?>
            <tr>
                <td><?=$one['id']?></td>
                <td><?=$one['name']?></td>
                <td><?=$one['surname']?></td>
                <td><img src = <?=__DIR__ . "/uploads/".$one['photo']?>></td>
                <td style ="width: 200px;">
                    <a href="?update=<?=$one['id']?>" class="btn btn-warning">Update</a>
                    <a href="?delete=<?=$one['id']?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

