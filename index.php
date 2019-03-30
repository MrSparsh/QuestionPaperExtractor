<!-- <?php
    include_once 'includes/dbhandler.php';
?>
<?php
    $sql = "select * from student;";
    $res = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($res)){
        echo $row['Name'];
    }
?> -->

<!DOCTYPE html>
<html >
    <head>
        <title>Upload Image</title>
    </head>
    <body>
        <form action="upload.php" enctype="multipart/form-data" method="POST" >
            <input type="file" name="myfile">
            <button name="submit" 
            type="submit">"Upload"</button>
        </form>

    </body>
</html>