<?php ob_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.usebootstrap.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dtbase = "demo";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dtbase);
    
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

        if(isset($_GET['limit'])){
            $lim = $_GET['limit'];
        }else{
            $lim = 5;
        }

        if(isset($_GET['page'])){
            $page = $_GET['page'];
        }else{
            $page = 1;
        }

        $offset = ceil($page * $lim) - $lim;


        if(isset($_GET['name'])){
            $name = $_GET['name'];
        }else{
            $name = "";
        }

        if(isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }else{
            $sort = "asc";
        }
    ?>
<div class="container">
    <div class="top d-flex mt-3 mb-3">
        <div class="input-group mb-3">
            <input type="text" class="form-control mr-3" placeholder="Username" aria-label="Username" id="search_box">
            <button type="submit" class="btn btn-primary mr-3" id="btn-submit">Submit</button>
            <select class="form-select" id = "sort"aria-label="Default select example">
                <option selected id="sort"></option>
                <!-- <option value=""></option> -->
                <option value="desc">desc</option>
                <option value="asc">asc</option>
            </select>
        </div>
    </div>
    <table class="table table-success table-striped">
        <thead>
            <tr>
                <th>user_id</th>
                <th>username</th>
                <th>sex</th>
                <th>position</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $query = "SELECT * FROM `user` WHERE username like lower('%$name%') ORDER BY user_id $sort LIMIT $lim OFFSET $offset";
                $result = mysqli_query($conn, $query);
                $queryCount = "SELECT * FROM `user` WHERE username like lower('%$name%')";
                $resultCount = mysqli_query($conn, $queryCount);
                $numm = mysqli_num_rows($resultCount);
                $countPage = ceil($numm /$lim);
                if(mysqli_num_rows($result)>0){
                    while($row = mysqli_fetch_assoc($result)){
                        ?>
                            <tr>
                                <th><?php echo $row['user_id'] ?></th>
                                <th><?php echo $row['username'] ?></th>
                                <th><?php echo $row['sex'] ?></th>
                                <th><?php echo $row['position'] ?></th>
                                <th>
                                    <a href="" class="btn btn-dark">Delete</a>
                                </th>
                            </tr>
                        <?php
                    }
                }
            ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation example" class="d-flex">
        <ul class="pagination mr-3">
            <li class="page-item"><a class="page-link" href="database4.php?limit=<?php echo $lim ?>&page=<?php echo $page-1 ?>">Previous</a></li>
            <?php 
                for($i=1; $i<=$countPage; ++$i){
                    ?>
                    <li class="page-item"><a class="page-link" href="database4.php?limit=<?php echo $lim ?>&page=<?php echo $i ?>"><?php echo $i ?></a></li>
                    <?php
                }
            ?>
            <li class="page-item"><a class="page-link" href="database4.php?limit=<?php echo $lim ?>&page=<?php echo $page+1 ?>">Next</a></li>
        </ul>
        <select class="form-select" id="limit" aria-label="Default select example">
            <option selected ></option>
            <option value="5">Five</option>
            <option value="10">Ten</option>
        </select>
    </nav>
    <form action="database4.php" method="POST">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">UserID</label>
    <input type="text" class="form-control" name = "uid" id="exampleInputEmail1" aria-describedby="emailHelp" name="uid">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Username</label>
    <input type="text" class="form-control" id="exampleInputPassword1" name="un">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Sex</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="sex">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Position</label>
    <input type="text" class="form-control" id="exampleInputPassword1" name="pos">
  </div>
  <button class="btn btn-primary" name="insert">Insert</button>
  <button class="btn btn-primary" name="update">Update</button>
</form>
</div>
<?php
    if(isset($_POST['insert'])){
        $un = $_POST['un'];
        $sex = $_POST['sex'];
        $pos = $_POST['pos'];
        $query = "insert into user(username, sex, position)VALUES('$un', '$sex', '$pos')";
        $result = mysqli_query($conn, $query);
        header('Location: database4.php');
    }

    if(isset($_POST['update'])){
        $uid = $_POST['uid'];
        $un = $_POST['un'];
        $sex = $_POST['sex'];
        $pos = $_POST['pos'];
        $query = "update user set username = '$un', sex = '$sex' , position = '$pos' where user_id = $uid ";
        $result = mysqli_query($conn, $query);
        header('Location: database4.php');
    }
?>
<script>
    const search = document.querySelector('#search_box');
    const btn_submit = document.querySelector('#btn-submit');
    btn_submit.addEventListener('click',function(e){
        e.preventDefault();
        const text = search.value;
        window.location.href =`database4.php?limit=<?php echo $lim?>&page=<?php echo $page ?>&name=${text}&sort=<?php echo $sort ?>`;
    })
    const sort = document.querySelector('#sort');
    sort.addEventListener('change', function(e){
        const sorttt = e.currentTarget.value;
        window.location.href =`database4.php?limit=<?php echo $lim?>&page=<?php echo $page ?>&name=<?php echo $name ?>&sort=${sorttt}`;
    })
    const limit = document.querySelector('#limit');
    limit.addEventListener('change', function(e){
        const limitt = e.currentTarget.value;
        window.location.href =`database4.php?limit=${limitt}&page=<?php echo $page ?>&name=<?php echo $name ?>&sort=<?php echo $sort ?>`;
    })
</script>
</body>
</html>