<?php ob_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" >
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
        $limit = $_GET['limit'];
       }else{
        $limit = 5;
       }

       if(isset($_GET['page'])){
        $page = $_GET['page'];
       }else{
        $page = 1;
       }

       $offset = ceil($limit * $page) - $limit;

       echo "limit = ". $limit. " Page = ". $page. " OFFSET = ". $offset."<br>";
    
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
        <div class="mb-3 d-flex">
            <input type="text" class="form-control mr-3" id="search_box" aria-describedby="emailHelp" placeholder="Seach...">
            <button type="submit" class="btn btn-primary mr-3" id="submit">Submit</button>
            <select class="form-select" aria-label="Default select example" id="sort">
            <option value="asc"></option>
            <option value=""></option>
                <option value="asc">asc</option>
                <option value="desc">desc</option>
            </select>
        </div>
       <table class="table table-striped">
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
                    $query = "SELECT * FROM `user` WHERE lower(username) like lower('%$name%') ORDER by user_id $sort LIMIT $limit OFFSET $offset";
                    // $query = "SELECT * FROM `user`";
                    $result = mysqli_query($conn, $query);
                    $queryCount = "SELECT * FROM `user` WHERE lower(username) like lower('%$name%')";
                    $resultCount = mysqli_query($conn ,$queryCount);
                    $countPage = mysqli_num_rows($resultCount);
                    $countPagee = ceil($countPage/$limit);
                    echo $countPagee;
                    if(mysqli_num_rows($result)>0){
                        while($rows= mysqli_fetch_assoc($result)){
                            $uid = $rows['user_id'];
                            $un = $rows['username'];
                            $sex = $rows['sex'];
                            $pos = $rows['position'];
                            ?>
                                <tr>
                                    <td><?php echo $uid?></td>
                                    <td><?php echo $un?></td>
                                    <td><?php echo $sex?></td>
                                    <td><?php echo $pos?></td>
                                    <td>
                                        <a href="database3.php?delete=<?php echo $uid ?>" class="btn btn-dark">Delete</a>
                                    </td>
                                </tr>
                            <?php
                        }
                    }
                ?>
            </tbody>
       </table>
       <nav aria-label="Page navigation example" class="d-flex">
            <ul class="pagination mr-3">
                <li class="page-item"><a class="page-link" href="database3.php?limit=<?php echo $limit ?>&page=<?php echo $page-1 ?>">Previous</a></li>
                <?php
                    for($i=1; $i<=$countPagee; ++$i){
                        ?>
                        <li class="page-item"><a class="page-link" href="database3.php?limit=<?php echo $limit ?>&page=<?php echo $i ?>"><?php echo $i ?></a></li>
                        <?php
                    }
                ?>
                <li class="page-item"><a class="page-link" href="database3.php?limit=<?php echo $limit ?>&page=<?php echo $page+1 ?>">Next</a></li>
            </ul>
            <select class="form-select" aria-label="Default select example" id="limit">
                <option value="0">0</option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="100">100</option>
            </select>
        </nav>
        <form action="database3.php" method="post">
            <div class="form-group">
                <label for="user_id">user_id</label>
                <input type="text" class="form-control" name="userid">
            </div>
            <div class="form-group">
                <label for="username">user_name:</label>
                <input type="text" class="form-control" name="username">
            </div>
            <div class="form-group">
                <label for="sex">sex</label>
                <input type="text" class="form-control" name="sex">
            </div>
            <div class="form-group">
                <label for="position">position</label>
                <input type="text" class="form-control" name="position">
            </div>
            <button class="btn btn-primary" name="insert">Insert</button>
            <button class="btn btn-secondary" name="update">Update</button>
        </form>
        
        <!-- <a href="" class="btn btn-primary" name="insert">Insert</a>
        <a href="" class="btn btn-secondary" name="update">Update</a> -->
    </div>
    <?php
        if(isset($_POST['insert'])){
            $un = $_POST['username'];
            $sex = $_POST['sex'];
            $pos = $_POST['position'];
            echo $un;
            $query = "INSERT into `user`(username, sex, position)VALUES('$un', '$sex', '$pos')";
            $result = mysqli_query($conn, $query);
            header('Location: database3.php');
        }
        if(isset($_POST['update'])){
            $uid = $_POST['userid'];
            $un = $_POST['username'];
            $sex = $_POST['sex'];
            $pos = $_POST['position'];
            echo $un;
            $query = "UPDATE `user` set username = '$un' , sex = '$sex', position = '$pos' where user_id = '$uid'";
            $result = mysqli_query($conn, $query);
            header('Location: database3.php');
        }
        if(isset($_GET['delete'])){
            $uid = $_GET['delete'];
            $query = "delete  from  `user` where user_id = '$uid'";
            $result = mysqli_query($conn, $query);
            header('Location: database3.php');
        }
    ?>
    <script>
        const box = document.querySelector('#search_box');
        const btn_submit = document.querySelector('#submit');
        btn_submit.addEventListener('click', function(e){
            e.preventDefault();
            window.location.href = `database3.php?limit=<?php echo $limit ?>&page=<?php echo $page ?>&name=${box.value}&sort=<?php echo $sort?>`;
            // window.location.href = `database3.php`;
        })
        const sort = document.querySelector('#sort');
        sort.addEventListener('change', function(e){
            const so = e.currentTarget.value;
            alert(so);
            window.location.href = `database3.php?limit=<?php echo $limit ?>&page=<?php echo $page ?>&name=<?php echo $name ?>&sort=${e.currentTarget.value}`;
        })
        const limit = document.querySelector('#limit');
        limit.addEventListener('change', function(e){
            window.location.href = `database3.php?limit=${e.currentTarget.value}&page=<?php echo $page ?>&name=<?php echo $name ?>&sort=<?php echo $sort ?>`;
        })
    </script>
</body>
</html>