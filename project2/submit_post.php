<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    header('location:login.php');
}

if(isset($_POST['submit_post'])){
    $content = $_POST['post_content'];
    $file_path = '';

    // File upload
    if(!empty($_FILES['post_file']['name'])){
        $file_name = $_FILES['post_file']['name'];
        $file_tmp_name = $_FILES['post_file']['tmp_name'];
        $file_path = 'uploaded_files/'.$file_name;
        move_uploaded_file($file_tmp_name, $file_path);
    }

    // Insert post into database
    $insert_post = $conn->prepare("INSERT INTO posts (user_id, content, file_path, created_at) VALUES (?, ?, ?, NOW())");
    $insert_post->execute([$user_id, $content, $file_path]);

    header('location:teachers.php');
}
?>
