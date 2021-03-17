<?php
/**
 * Created by PhpStorm.
 * User: noza
 * Date: 16.03.2021
 * Time: 23:07
 */

$conn = mysqli_connect("localhost", "root", "", "friend_with_contacts");

if (isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $query = "DELETE FROM user_phone WHERE id = '$id' limit 1";
    mysqli_query($conn, $query);

}
if (isset($_GET['update'])){
    $id = (int)$_GET['update'];
    $query = "select * FROM user_phone WHERE id ='$id' limit 1";

    $result = mysqli_query($conn, $query);
    $one = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $one = reset($one);
}

if (!empty($_POST)){
    $id = $_POST['id'];
    $phone = $_POST['phone'];
    $contact_id = $_POST['contact_id'];

    if ($id>0){
        $query = "UPDATE user_phone SET 
                    phone = '$phone', 
                    contact_id = '$contact_id'
                    where id = '$id' limit 1
";
    } else {
        $query = "INSERT INTO user_phone VALUES (
                    null, 
                    '$phone', 
                    '$contact_id'
)";
    }

    mysqli_query($conn, $query);
}
$result = mysqli_query($conn, "SELECT * FROM user_phone ORDER BY id desc");
$all = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<html>
<head>
    <title>Phones</title>
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
<h1>Add phone</h1>

<form action="phones.php" method="post" enctype="multipart/form-data">
    <input value="<?=$one['id'] ?? "" ?>" type = "hidden" name ="id">
    <div class="form-group">
        <label>Phone</label>
        <input value="<?=$one['phone'] ?? "" ?>" type ="text" name = "phone" class="form-control">
    </div>
    <div class="form-group">
        <label>Contact_id</label>
        <input value="<?=$one['contact_id'] ?? "" ?>" type ="text" name = "contact_id" class="form-control">
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
            <th scope="col">Phone</th>
            <th scope="col">Contact_id</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($all as $one): ?>
            <tr>
                <td><?=$one['id']?></td>
                <td><?=$one['phone']?></td>
                <td><?=$one['contact_id']?></td>
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

