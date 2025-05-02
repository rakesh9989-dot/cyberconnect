<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    header('location:login.php');
}

if(isset($_POST['comment_post'])){
    $post_id = $_POST['post_id'];
    $comment_text = $_POST['comment_text'];

    // Insert comment
    $insert_comment = $conn->prepare("INSERT INTO comments (user_id, post_id, comment_text, created_at) VALUES (?, ?, ?, NOW())");
    $insert_comment->execute([$user_id, $post_id, $comment_text]);

    // Update points for the post creator
    $post_user = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
    $post_user->execute([$post_id]);
    $post_user_id = $post_user->fetchColumn();

    $update_points = $conn->prepare("UPDATE users SET points = points + 1 WHERE id = ?");
    $update_points->execute([$post_user_id]);

    header('location:teachers.php');
}
?>
