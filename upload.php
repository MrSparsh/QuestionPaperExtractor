<?php
   include 'readDocx3.php';
   if(isset($_FILES['myfile'])){
      $errors= array();
      $file_name = $_FILES['myfile']['name'];
      $file_size =$_FILES['myfile']['size'];
      $file_tmp =$_FILES['myfile']['tmp_name'];
      $file_type=$_FILES['myfile']['type'];
      $file_ext=explode('.',$file_name);
      $file_ext=strtolower(end($file_ext));
      $extensions= array("jpeg","jpg","png","docx");
      
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"uploads/".$file_name);
         
         read_docx("uploads/".$file_name);
         echo "Success";
      }else{
         print_r($errors);
      }
   }
?>