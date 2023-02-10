<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
    <?php 
    $username = "root";
    $pass = "";
    $server = "localhost";
    $dtbase = "demo";

    $conn = new mysqli($server, $username, $pass, $dtbase);
    if(!$conn){
        echo "Hello";
    }
    if(isset($_GET['limit'])){
        $limit = $_GET['limit'];
    }else{
        $limit = 5 ;
    }

    if(isset($_GET['page'])){
        $page = $_GET['page'];
    }else{
        $page =  1;
    }

    if(isset($_GET['name'])){
        $name = $_GET['name'];
    }else{
        $name =  "";
    }

    if(isset($_GET['name'])){
        $name = $_GET['name'];
    }else{
        $name =  "";
    }

    if(isset($_GET['sort'])){
        $sort= $_GET['sort'];
    }else{
        $sort =  "asc";
    }
    $offset = ceil($page * $limit) - $limit;
    echo $limit." ".$page."".$offset;
    ?>
    <div class="container">
    <div class="form-group w-100 d-flex justify-content-center">
        <input type="text" class="form-control mr-3 " name="" id="sname" style="width :30%">
        <input type="submit" value="submit" id="submit" class="mr-3 bg-success">
        <select name="" id="sort" class="form-select">
            <option value=""></option>
            <option value="asc">Asc</option>
            <option value="desc">Desc</option>
        </select>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>user_id</th>
                <th>username</th>
                <th>sex</th>
                <th>position</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $query = "SELECT * FROM `user` where username LIKE ('%$name%') ORDER BY (user_id) $sort LIMIT $limit OFFSET $offset";
                $queryCount = "SELECT * FROM `user` where username LIKE ('%$name%') ORDER BY (user_id) $sort";
                $resultCount = mysqli_query($conn, $queryCount);
                $count = mysqli_num_rows($resultCount);
                $countPage = ceil($count/$limit);
                
                $result = mysqli_query($conn, $query);
                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        $user_id = $row['user_id'];
                        $username = $row['username'];
                        $sex = $row['sex'];
                        $position = $row['position'];
                        ?>
                            <tr>
                                <td><?php echo $user_id ?></td>
                                <td><?php echo $username ?></td>
                                <td><?php echo $sex ?></td>
                                <td><?php echo $position ?></td>
                            </tr>
                        <?php
                    }
                }
            ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation example" class="w-25 mx-auto d-flex">
        <ul class="pagination mr-3">
            
            <li class="page-item <?php echo $page == 1 ? 'disabled' : '' ?> "><a class="page-link" href="#">Previous</a></li>
            <?php 
                for($i = 1; $i < $countPage; ++$i){
                    ?>
                        <li class="page-item"><a class="page-link" href="database5.php?limit=<?php echo $limit  ?>&page=<?php echo $i ?>"><?php echo $i ?></a></li>
                    <?php
                }
            ?>
            <li class="page-item <?php echo $page >= $countPage ? 'disabled':'' ; ?>" ><a class="page-link" href="database5.php?limit=<?php echo $limit  ?>&page=<?php echo $page+1 ?>">Next</a></li>
        </ul>
        <!-- <select name="" id="" class="form-select"> -->
        <select class="form-select" aria-label="Default select example " id="limit">
            <option value=""></option>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="100">All<a/option>
        </select>
    </nav>
    </div>
    <script>
        const limit = document.querySelector('#limit');
        limit.addEventListener('change',function(e){
            const valuee = e.currentTarget.value;     
            window.location.href = `database5.php?limit=${valuee}&page=<?php echo $page ?>&name=<?php echo $name ?>&sort=<?php echo $sort ?>`;
        });
        const sort = document.querySelector("#sort");
        sort.addEventListener('change', function(e){
            const valuee = e.currentTarget.value;
            window.location.href = `database5.php?limit=<?php echo $limit ?>&page=<?php echo $page ?>&name=<?php echo $name ?>&sort=${valuee}`;
        })
        const sname = document.querySelector('#sname');
        const submit = document.querySelector('#submit');

        submit.addEventListener('click',function(e){
            const value = sname.value;
            window.location.href = `database5.php?limit=<?php echo $limit ?>&page=<?php echo $page ?>&name=${value}&sort=<?php echo $sort ?>`;
            // e.preventDefault;
            
        })
    </script>
</body>
</html>