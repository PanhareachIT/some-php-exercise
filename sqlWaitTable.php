<?php ob_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"> </script> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" >
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
        if(isset($_GET['limit'])){
            $lim = $_GET['limit'];

        }else{
            $lim = "";
        }
        if($lim ==0||empty($lim)){
            $lim = 5;
        }
        if(isset($_GET['page'])){
            $pag = $_GET['page'];
          //  echo $pag;
        }else{
            $pag = "";
        }
        if($pag==0||empty($pag)){
            $pigination = 1;
            echo "empty";
        }else{
            $pigination = $pag;
            echo "not empty";
        }
        // if(isset($_GET['page'])){   
        //     if(($_GET['page'])==0||empty($_GET['page'])){
        //         $pigination = 1;
        //         echo $pigination;
        //     }else{
        //         $pigination = $_GET['page'];
        //         echo $pigination;
        //     }
        // }else{
        //     $pigination = 1;
        //     echo $pigination;
        // }
        $offset = ceil($pigination*$lim) - $lim;
        echo $offset ;

        if(isset($_GET['q'])){
            $Q  = $_GET['q'];
            echo $Q;
        }else{
            $Q = "";
        }

        if(isset($_GET['sort'])){
            $S = $_GET['sort'];
            echo "a jm";
        }else{
            $S = "";
        }
        
        if(empty($S)){
            $Sort = "asc";
            echo "emp";
        }
        else if($S=="new"){
            $Sort = "desc";
            echo "new";
        }else{
            $Sort = "asc";
            echo "old";
        }
        
    ?>
    <div class = "container ">
        <form class="d-flex justify-content-center mt-3 mb-3" id = "search_box">
            <input class="form-control me-2 mr-3" type="search" placeholder="Search" aria-label="Search" id="search_value">
            <button class="btn btn-outline-success mr-3" type="submit">Search</button>
            <select class="form-select " aria-label="Default select example" id = "sort_id">
                <option selected></option>
                <option value="new">new</option>
                <option value="old">old</option>
        </select>
        </form>
        
        <table class="table table-bordered">
            <thead>
                <th>UserID</th>
       
                <th>Username</th>
                <th>Sex</th>
                <th>Position</th>
                <th class = "text-warning">Action</th>
            </thead>
            
    <?php
        $sql = "SELECT * FROM user WHERE user_id like ('%$Q')  ORDER BY user_id $Sort LIMIT $lim OFFSET $offset";
        $result = mysqli_query($conn, $sql);
        $sql1 = "SELECT * FROM user WHERE user_id like ('%$Q') ";
        $queryCount= mysqli_query($conn, $sql1);
        $resultCount = mysqli_num_rows($queryCount);
        $pageCount = ceil($resultCount/$lim);

        if(mysqli_num_rows($result)> 0){
            while($row = mysqli_fetch_assoc($result)){
                $useid = $row['user_id'];
                $un  = $row['username'];
                $sex = $row['sex'];
                $pos = $row['position'];
                ?>
                <tbody>
                <td><?php echo $useid ?></td>
                <td><?php echo $un ?></td>
                <td><?php echo $sex ?></td>
                <td><?php echo $pos ?></td>
                <td class ="d-fles align-items-center">
                    <a class = "btn btn-secondary" href="table2.php?Detail=<?php echo $useid ?>">Detail</a>
                    <a class = "btn btn-dark" href="sqlWaitTable.php?Delete=<?php echo $useid ?>">Delete</a>
                    <a class = "btn btn-info" href="sqlWaitTable.php?Update=<?php echo $useid ?>" >Update</a>
                </td>
                <?php 
            }
        }
       ?>
            </tbody>
        </table>
        
    </div>
    <nav aria-label="Page navigation example "class = "d-flex justify-content-center align-items-center">
            <ul class="pagination">
            <li class="page-item <?php echo $pagination === 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="sqlWaitTable.php?limit=<?php echo $lim?>&page=<?php echo $pigination-1?>">Previous</a>
                </li>
                <?php
                    for($i =1; $i<=$pageCount; $i++){
                        ?>
                        <li class="page-item"><a class="page-link" href="sqlWaitTable.php?limit=<?php echo $lim?>&page=<?php echo $i ?> "><?php echo $i?></a></li>
                    <?php
                    }
                    ?>
                <li class="page-item"><a class="page-link" href="sqlWaitTable.php?limit=<?php echo $lim?>&page=<?php echo $pigination+1?>">Next</a></li>
            </ul>
            <select class="form-select form-select-lg mb-3 ml-3 " aria-label=".form-select-lg example" id ="page_limit">
                <option selected></option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
            </select>
        </nav>
    <div class = "container">
        <form action="sqlWaitTable.php" method = "post">
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
         if(isset($_POST["btn"])){
            echo 'Hello';
            $usern = $_POST['name'];
            $sx = $_POST['sex'];
            $pos = $_POST['position'];
            $query = "insert into user(username, sex, position)VALUES('$usern', '$sx', '$pos')";
            $result = mysqli_query($conn, $query);
            header('Location: sqlWaitTable.php');
         }
         
        if(!$result){
          #  echo "<script>alert('unsuccessfully')</script>";
        }else{
          #0  echo "<script>alert('successfully')</script>";
        }
    ?>
    <?php
        if(isset($_GET['Update'])){
            
            $uid = $_GET['Update'];
            $usern = mysqli_real_escape_string($conn, $_POST['name']);
            $sx= mysqli_real_escape_string($conn, $_POST['sex']);
            $pos = mysqli_real_escape_string($conn, $_POST['position']);
            $query = "UPDATE user set username = '$usern', sex = '$sx', position = '$pos' where user_id = $uid";
            $result = mysqli_query($conn, $query);
            header('Location: sqlWaitTable.php');
            if(!$result){
                echo "<script>alert('unsuccessfully')</script>";
            }else{
                echo "<script>alert('successfully')</script>";
            }
            header('Location : sqlWaitTable.php');
        }
    ?>
    <?php
    if(isset($_GET['Delete'])){
        $useidxxx= $_GET['Delete'];
        echo $useidxxx;
        $query = "DELETE from user where user_id =". $useidxxx;
            $result = mysqli_query($conn, $query);
            if(!$result){
                echo "<script>alert('unsuccessfully')</script>";
              }else{
                echo "<script>alert('successfully')</script>";
              }
              header('Location: sqlWaitTable.php');
         }
    ?>
    <?php
        if(isset($_POST['btnDelete'])){
            $uid = mysqli_real_escape_string($conn, $_POST['id']);
            echo $uid;
            $query = "DELETE from user where user_id =". $uid;
            $result = mysqli_query($conn, $query);
            if(!$result){
                echo "<script>alert('unsuccessfully')</script>";
              }else{
                echo "<script>alert('successfully')</script>";
              }
              header('Location: sqlWaitTable.php');
        }

        
    ?>
   <?php
    if(isset($_POST['btnUpdate'])){
        $uid = mysqli_real_escape_string($conn, $_POST['id']);
        $usern = mysqli_real_escape_string($conn, $_POST['name']);
        $sx= mysqli_real_escape_string($conn, $_POST['sex']);
        $pos = mysqli_real_escape_string($conn, $_POST['position']);
        $query = "UPDATE user set username = $usern, sex = $sx, position = $pos where user_id = $uid";
        $result = mysqli_query($conn, $query);
        header('Location: sqlWaitTable.php');
        if(!$result){
            echo "<script>alert('unsuccessfully')</script>";
          }else{
            echo "<script>alert('successfully')</script>";
          }
    }
   ?>
   <script  >
    const limitPage = document.querySelector('#page_limit');
    limitPage.addEventListener("change",function(e){
        const value = e.currentTarget.value; 
        console.log(value);
        
        window.location.href = `sqlWaitTable.php?limit=${value}&page=0`;
    });
    
    const searchValue = document.querySelector('#search_value');
    searchbar =  document.querySelector('#search_box');
    searchbar.addEventListener("submit",function(e){
        e.preventDefault();
        console.log(searchValue.value);
        // window.location.href = 'sqlWaitTable.php?limit=<?php echo $lim ?>&page=0';
         window.location.href = `sqlWaitTable.php?limit=<?php echo $lim ?>&page=0&q=${searchValue.value}`;
    
        // searchValue.addEventListener("input",function(e){

        // })
    })
    const sort = document.querySelector('#sort_id');
    sort.addEventListener('change',function(e){
        const sortt = e.currentTarget.value;
        alert(sortt);
        window.location.href = `sqlWaitTable.php?limit=<?php echo $lim ?>&page=0&q=${searchValue.value}&sort=${sortt}`;
    });
        
    
   </script>
</body>
</html>