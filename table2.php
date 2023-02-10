<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" >
</head>
<body>
<?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dtbase = "demo";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dtbase);

        // Check connection
        if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        }
        //echo "Connected successfully";
    ?>
    <?php
        if(isset($_GET['Detail'])){
            $uid = $_GET['Detail'];
            $query = "SELECT * FROM `user` WHERE user_id = $uid";
            $result = mysqli_query($conn, $query);
            if(mysqli_num_rows($result)> 0){
                while($row = mysqli_fetch_assoc($result)){
                    $useid = $row['user_id'];
                    $un  = $row['username'];
                    $sex = $row['sex'];
                    $pos = $row['position'];
                }
        }
    }
    ?>
<div class = "container">
    <a href="sqlWaitTable.php" class="btn btn-success mt-3 mb-3">Back</a>
    <div class="card" style="width: 18rem;">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><?php echo $uid ?></li>
            <li class="list-group-item"><?php echo $un ?></li>
            <li class="list-group-item"><?php echo $sex ?></li>
            <li class = "list-group-item"><?php echo $pos ?></li>
        </ul>
    </div>
</div>

</body>
</html>