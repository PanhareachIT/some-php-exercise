<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
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
       if(isset($_GET['limit'])){
        $lim = $_GET['limit'];
        
        
       }else{
        $lim = "";
       }

       if($lim==0||empty($lim)){
        $limit = 5;
       }else{
        $limit = $lim;
       }
       echo "Limit = ".$limit;
       if(isset($_GET['page'])){
        $p = $_GET['page'];
        echo "page=".$p;
       
       }else{
        $p = "";
       }
       
       if($p===0||empty($p)){
        $page = 1;
       }else{
        $page = $p;
       }
       $offset = ceil($page * $limit) - $limit;
       echo "offset".$offset;
       if(isset($_GET['q'])){
        $q = $_GET['q'];
        echo "Q = ".$q;
       }else{
        $q = "";
       }
       
       if(isset($_GET['sort'])){
        $s = $_GET['sort'];
       }else {
        $s = "";
       }

       if(empty($s)){
        $sort = "asc";
       }else if($s=="new"){
        $sort = "desc";
       }else{
        $sort = "asc";
       }
       
    ?>
    
    <div class = "container">
    <form class="d-flex mt-3 mb-3" role="search">
        <input class="form-control mr-3 " type="search" placeholder="Search" aria-label="Search" id = "search_value">
        <button class="btn btn-outline-success mr-3" type="submit" id="btn_search">Search</button>
        <select class="form-select " aria-label="Default select example" id="sort_id">
            <option selected></option>
            <option value="old">Old</option>
            <option value="new">New</option>
        </select>
    </form>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>User_ID</th>
                <th>User_Name</th>
                <th>Sex</th>
                <th>Position</th>
                <th class="text-secondary">Action</th>
            </tr>
        </thead>
                    <?php
                    $query = "SELECT * FROM user WHERE LOWER(username) like LOWER('%$q%') ORDER BY user_id $sort limit $limit OFFSET $offset";
                    $result = mysqli_query($conn, $query);
                    
                    $sql1 = "SELECT * FROM user WHERE username like ('%$q%')";
                    $count = mysqli_query($conn, $sql1);
                    $resultCount = mysqli_num_rows($count);
                    $pageCount = ceil($resultCount/$limit);
                    if(mysqli_num_rows($result)>0){
                        while($row = mysqli_fetch_assoc($result)){
                            $useid = $row['user_id'];
                            $un  = $row['username'];
                            $sex = $row['sex'];
                            $pos = $row['position'];
                    ?>
        <tbody>
            <tr>
                <td><?php echo $useid?></td>
                <td><?php echo $un?></td>
                <td><?php echo $sex?></td>
                <td><?php echo $pos?></td>

                <td class="d-flex  align-items-center" >
                    <a class = "btn btn-primary mr-3"href="database.php">Detail</a>
                    <a class = "btn btn-dark mr-3"href="database.php">Delete</a>
                </td>
            </tr>
           
        </tbody>
        <?php
            }
        }
?>
    </table>
    <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center align-items-center">
        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
        <?php 
            for($i =1; $i<=$pageCount; $i++){
                ?>
                <li class="page-item"><a class="page-link" href="database.php?limit=<?php echo $limit?>&page=<?php echo $i ?>"><?php echo $i?></a></li>
                <?php
            }
        ?>
        <li class="page-item"><a class="page-link" href="#">Next</a></li>
        <li>
        <select class="form-select ml-3" aria-label="Default select example" id="select_limit">
        <option selected></option>
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="100">100</option>
    </select>
        </li>
    </ul>
    
</nav>
<button class="btn btn-primary">Add</button>
<button class="btn btn-info">Update</button>
    </div>
    <script>
        const limit = document.querySelector('#select_limit');
        limit.addEventListener('change',function(e){
            const value = e.currentTarget.value;
            window.location.href= `database.php?limit=${value}&page=0`;
        });

        const search = document.querySelector('#search_value');
        const btn = document.querySelector('#btn_search');
        btn.addEventListener('click',function(e){
            e.preventDefault();
            window.location.href= `database.php?<?php echo $limit?>&page=0&q=${search.value}&sort=<?php echo $sort?>`;
            // alert('hi');
            // window.location.href= `database.php?limit=<?php  echo $limit ?>&page=0`;
        });

        const sortt = document.querySelector('#sort_id');
        sortt.addEventListener('change',function(e){
            const value = e.currentTarget.value;
            alert(value);
            window.location.href= `database.php?<?php echo $limit?>&page=0&q=<?php echo $q ?>&sort=${value}`;
        })
    </script>
</body>
</html>