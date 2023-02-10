<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <?php 
        $con = mysqli_connect("localhost","root","","demo");

        // Check connection
        if (mysqli_connect_errno()) {
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
          exit();
        }
        if(isset($_GET['limit'])){
            $lim = $_GET['limit'];
            echo $lim;
        }else{
            $lim = 5;
            echo $lim;
        }

        if(isset($_GET['pag'])){
            $pig= $_GET['pag'];
            echo $pig;
        }else{
            $pig = 1;
            echo $pig;
        }

        if(empty($pig)||$pig ==0){
            $pig = 1;
        }

        $offset = ceil($lim * $pig) - $lim;
        echo $offset ."<br/>";

        if(isset($_GET['name'])){
            $name = $_GET['name'];
            echo $name ;
        }else{
            $name = "";
        }

        if(isset($_GET['sort'])){
            $sort = $_GET['sort'];
            echo $sort;
        }else{
            $sort = "asc";
            echo $sort;
        }

        if(isset($_GET['delete'])){
            $sid = $_GET['delete'];
            $query = "DELETE From user Where user_id = $sid";
            $Delete = mysqli_query($con, $query);
            if(!$Delete){
                echo "<script>alert('unsess');</script>";
                echo "<script>alert('Erorr create user!')</script>";
            }else{
                echo "<script>alert('unsecussfully')</script>";
            }
            header('Location: newDatabase.php');
        }

        
        
   
    ?>
    <div class="container">
        <form class="d-flex mt-3 mb-3">
            <div class=" d-flex mb-3 mr-3">
                <input type="text" class="form-control" id="search_name">
            </div>
            <button type="submit" class="btn btn-primary mr-3" id="btn_submit">Submit</button>
            <select class="form-select" aria-label="Default select example" id="sort_name">
                <option selected></option>
                <option value="desc" >desc</option>
                <option value="asc">Asc</option>
            </select>
        </form>
    <table class="table">
        <thead>
            <tr>
            <th scope="col">sid</th>
            <th scope="col">sname</th>
            <th scope="col">sex</th>
            <th scope="col">position</th>
            <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $query = "SELECT * FROM `user` WHERE LOWER(username) LIKE lower('%$name%') ORDER by user_id $sort LIMIT $lim OFFSET $offset";
                $result = mysqli_query($con,$query);

                $queryCount = "SELECT * FROM `user` WHERE LOWER(username) LIKE lower('%$name%')";
                $resultCount = mysqli_query($con, $queryCount);
                $Count = mysqli_num_rows( $resultCount);
                $countPage = ceil($Count / $lim);
                if(mysqli_num_rows($result)>0){
                    while($row = mysqli_fetch_assoc($result)){
                        $sid = $row['user_id'];
                        $sn = $row['username'];
                        $sex = $row['sex'];
                        $pos = $row['position'];
                        ?>
                        <tbody>
                            <tr>
                                <td><?php echo $sid?></td>
                                <td><?php  echo $sn ?></td>
                                <td><?php echo $sex ?> </td>
                                <td><?php echo $pos ?> </td>
                                <td>
                                    <a class="btn btn-dark" href="newDatabase.php?delete=<?php echo $sid?>">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                        <?php
                    }
                }
            ?>
        </tbody>
        
    </table>  
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center align-items-center">
            <li class="page-item"><a class="page-link" href="newDatabase.php?limit=<?php echo $lim ?>&pag=<?php echo $pig-1 ?>">Previous</a></li>
            <?php 
                for( $i=1; $i<=$countPage; $i++){
                    ?>
                    <li class="page-item"><a class="page-link" href="newDatabase.php?limit=<?php echo $lim ?>&pag=<?php echo $i ?>"><?php  echo $i?></a></li>
                    <?php
                }
            ?>
            <li class="page-item"><a class="page-link" href="newDatabase.php?limit=<?php echo $lim ?>&pag=<?php echo $pig+1 ?>">Next</a></li>
            <li>
            <select class="form-select" aria-label="Default select example" id="page_limit">
                <option selected></option>
                <option value="5">Five</option>
                <option value="10">Ten</option>
                <option value="100">All</option>
            </select>
            </li>
        </ul>
        
</nav>   
<form action="newDatabase.php" method = "post">
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
        <button class ="btn btn-primary" name="btn" >Insert</button>
        <button class ="btn btn-dark" name ="btnDelete">Delete</button>
        <button class = "btn btn-info" name = "btnUpdate">Update</button>
    </form>
    </div>
    <?php
        if(isset($_POST['btnUpdate'])){
            $uid = $_POST['id'];
            $sname = $_POST['name'];
            $sex = $_POST['sex'];
            $pos = $_POST['position'];
            $Insert = mysqli_query($con, "UPDATE user SET username = '$snames', sex = '$sex', position = '$pos' WHERE user_id = '$uid'");
            if(!$Insert){
                echo "<script>alert('unsess');</script>";
                echo "<script>alert('Erorr create user!')</script>";
            }else{
                echo "<script>alert('unsecussfully')</script>";
            }
            header('Location: newDatabase.php');
        }
        if(isset($_POST['btn'])){
            $sname = $_POST['name'];
            $sex = $_POST['sex'];
            $pos = $_POST['position'];
            $Insert = mysqli_query($con, "insert into user(username, sex, position)VALUES('$sname', '$sex', '$pos')");
            if(!$Insert){
                echo "<script>alert('unsess');</script>";
                echo "<script>alert('Erorr create user!')</script>";
            }else{
                echo "<script>alert('unsecussfully')</script>";
            }
            header('Location: newDatabase.php');
            
        }
    ?>
    <script>
        const pageLimit = document.querySelector("#page_limit");
        pageLimit.addEventListener("change",function(e){
            const valuee = e.currentTarget.value;
            window.location.href= `newDatabase.php?limit=${valuee}&pag=<?php echo $pig?>`;
        })

        const sortName = document.querySelector("#sort_name");
        sortName.addEventListener("change",function(e){
            const valuee = e.currentTarget.value;
            window.location.href= `newDatabase.php?limit=<?php echo $lim?>&pag=<?php echo $pig?>&name=<?php echo $name?>&sort=${valuee}`;
        })

        const name = document.querySelector("#search_name");
        const btnSubmit = document.querySelector("#btn_submit");
        // name.addEventListener("input",function(e){
        //     alert("e");
        // })
        btnSubmit.addEventListener("click",function(e){
            // alert(name.value);
            e.preventDefault();
            window.location.href= `newDatabase.php?limit=<?php echo $lim?>&pag=<?php echo $pig?>&name=${name.value}&sort=<?php echo $sort?>`;
        })

    </script>
</body>
</html>