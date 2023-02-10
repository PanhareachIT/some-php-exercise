<?php ob_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" >
</head>
<body>
    <?php
        if(isset($_GET['limit'])){
            $lim = $_GET['limit'];
        }else{
            $lim = 5;
        }
        echo $lim;

        if(isset($_GET['page'])){
            $page = $_GET['page'];
        }else{
            $page = 1;
        }
        echo $page;

        $offset = ceil($lim * $page) - $lim;
        echo $offset;

        if(isset($_GET['name'])){
            $name = $_GET['name'];
        }else{
            $name = "";
        }
        echo $name;
        if(isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }else{
            $sort = "asc"; 
        }
        $con = mysqli_connect("localhost","root","","demo");

        // Check connection
        if (mysqli_connect_errno()) {
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
          exit();
        }else{
            echo "succ";
        }
        if(isset($_GET['Deleteid'])){
            $uid = $_GET['Deleteid'];
            echo $_POST['id'];
            $query = "DELETE  from user where user_id = '$uid'";
            $result = mysqli_query($con, $query);
            // header('location: database2.php');
            header('Location: database2.php');
        }


    ?>
    <div class="container">
        <div class="d-flex mt-3 mb-3">
            <input class="form-control me-2 mr-3" type="search" placeholder="Search" aria-label="Search" id="search_value">
            <!-- <input type="search" class="form-control ml-3" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="seach..." id="search_box"> -->
            <button type="submit" class="btn btn-primary ml-3 mr-3" id="submit">Submit</button>
            <select class="form-select" aria-label="Default select example" id="sort">
                <option selected></option>
                <option value="desc">Desc</option>
                <option value="asc">Asc</option>
            </select>
        </div>
        <table class="table .table-striped ">
            <thead>
                <tr>
                    <th>user_id</th>
                    <th>user_name</th>
                    <th>Sex</th>
                    <th>Position</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // $sql = "SELECT * FROM user WHERE user_id like ('%$name')  ORDER BY user_id $Sort LIMIT $lim OFFSET $offset";
            // $result = mysqli_query($conn, $sql);
                 $query = "SELECT * FROM user WHERE username LIKE ('%$name%') ORDER BY username $sort LIMIT $lim OFFSET $offset";
                 $result = mysqli_query($con, $query);
                echo $name;
                 $queryCount = "SELECT * FROM user WHERE username LIKE ('%$name%')";
                 $resultCount = mysqli_query($con, $queryCount);
                 $countRow = mysqli_num_rows($resultCount);
                 echo "hh".$countRow;
                 $countPage = ceil($countRow / $lim);
                echo "countpage = ". $countPage;
                 //  echo $countPage;
                 if(mysqli_num_rows($result)>0){
                    while($row = mysqli_fetch_assoc($result)){
                        $useid = $row['user_id'];
                        $un  = $row['username'];
                        $sex = $row['sex'];
                        $pos = $row['position'];
                        ?>
                        <tr>
                            <td><?php echo $useid?></td>
                            <td><?php echo $un?></td>
                            <td><?php echo $sex?></td>
                            <td><?php echo $pos?></td>
                            <td>
                                <a class="btn btn-dark" href="database2.php?Deleteid=<?php echo $useid ?>"><?php echo "Delete" ?></a>
                                <a class="btn btn-secondary" href="database2.php?updateId=<?php echo $useid ?>"><?php echo "Edit" ?></a>
                            </td>
                        </tr>
                        <?php
                    }
                 }
            ?>
            </tbody>
        </table>
        <div class="d-flex mb-3 mr-3" ></div>
        <nav aria-label="Page navigation example" class="d-flex mt-3 mb-3 ">
            <ul class="pagination mr-3">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <?php
                for($i =1; $i<=$countPage; ++$i){
                    ?>
                    <li class="page-item"><a class="page-link" href="database2.php?limit=<?php echo $lim ?>&page=<?php echo $i ?>"><?php echo $i ?></a></li>
                <?php
                    }
                 ?>
                
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
            <select class="form-select" aria-label="Default select example" id="limit">
                <option selected></option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="999">all</option>
            </select>
        </nav>
        <form action="database2.php" method="POST">
        <div class="mb-3">
            <label for="id" class = "form-label">UserID</label>
            <input type="text" class = "form-control" name = "id">
        </div>
        <div class ="mb-3">
            <label for="UserName" class = "form-label">Username</label>
            <input type="text" class = "form-control" name ="name">
        </div>
        <div class = "mb-3">
            <label for="Sex" class = "form-label">Sex</label>
            <input type="text" class = "form-control" name = "sex">
        </div>
        <div class = "mb-3">
            <label for="Position" class = "form-label">Position</label>
            <input type="text" class = "form-control" name = "position">
        </div>
        <!-- <input type="submit" name = "submit" class="btn btn-primary mt-3"> -->
        <button class ="btn btn-primary" name="btn_insert" >Insert</button>
        <button class ="btn btn-dark" name ="btnDelete">Delete</button>
        <button class = "btn btn-info" name = "btnUpdate">Update</button>
        </form>
    </div>
    <?php 
        if(isset($_POST['btn_insert'])){
            $uid = $_POST['id'];
            $un = $_POST['name'];
            $sex = $_POST['sex'];
            $pos = $_POST['position'];
            $query = "insert into USER(username, sex, position)VALUES('$un', '$sex', '$pos')";
            $result = mysqli_query($con, $query);
            header('Location: database2.php');
        }
    ?>
   <?php
            if(isset($_POST['btnUpdate'])){
                $updateId = $_GET['updateId'];
                $uid = $_POST['id'];
                $un = $_POST['name'];
                $sex = $_POST['sex'];
                $pos = $_POST['position'];
                $queryUpdate = "UPDATE user set username  = '$un', sex = '$sex', position = '$pos' where user_id = '$uid' ";
                $resultUpdate = mysqli_query($con, $queryUpdate);
                header('Location: database2.php');
            }

        ?>
    <script>
        const limitt = document.querySelector('#limit');
        limitt.addEventListener("change", function(e){
            window.location.href = `database2.php?limit=${e.currentTarget.value}&page=<?php echo $page ?>&sort<?php $sort ?>`;
        })
         const textt = document.querySelector('#search_value');
        console.log(textt);
        const btn_submit = document.querySelector('#submit');
        btn_submit.addEventListener('click', function(e){
            e.preventDefault();
            window.location.href = `database2.php?limit=<?php echo $lim ?>&page=<?php echo $page ?>&name=${textt.value}&sort=<?php echo $sort ?>`;
        })
        const sortt = document.querySelector('#sort');
        sortt.addEventListener('change', function(e){
            const orderr = e.currentTarget.value;
            alert(orderr);
            window.location.href = `database2.php?limit=<?php echo $lim ?>&page=<?php echo $page ?>&name=<?php echo $name ?>&sort=${orderr}`;
        })
        
        
    </script>
</body>
</html>