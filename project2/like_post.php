<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    header('location:login.php');
}

if(isset($_POST['like_post'])){
    $post_id = $_POST['post_id'];

    // Check if the user already liked this post
    $check_like = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
    $check_like->execute([$user_id, $post_id]);

    if($check_like->rowCount() == 0){
        // Insert like
        $like = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
        $like->execute([$user_id, $post_id]);

        // Update points for the post creator
        $post_user = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
        $post_user->execute([$post_id]);
        $post_user_id = $post_user->fetchColumn();

        $update_points = $conn->prepare("UPDATE users SET points = points + 1 WHERE id = ?");
        $update_points->execute([$post_user_id]);
    }

    header('location:teachers.php');
}
?>
