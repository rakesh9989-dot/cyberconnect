<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Content</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="content-section">
    <h1 class="heading">Community Content</h1>

    <!-- Post Submission Form -->
    <form action="submit_post.php" method="post" class="post-form" enctype="multipart/form-data">
        <textarea name="post_content" placeholder="Share something..." required></textarea>
        <input type="file" name="post_file" accept=".pdf,.jpg,.jpeg,.png,.mp4">
        <button type="submit" name="submit_post">Post</button>
    </form>

    <!-- Display Posts -->
    <div class="post-container">
        <?php
        $select_posts = $conn->prepare("
            SELECT posts.*, users.name, 
            (SELECT COUNT(*) FROM likes WHERE post_id = posts.id) AS like_count,
            (SELECT COUNT(*) FROM comments WHERE post_id = posts.id) AS comment_count 
            FROM `posts` 
            INNER JOIN `users` ON posts.user_id = users.id 
            ORDER BY posts.created_at DESC
        ");
        $select_posts->execute();

        if ($select_posts->rowCount() > 0) {
            while ($post = $select_posts->fetch(PDO::FETCH_ASSOC)) {
                $post_id = $post['id'];
                $post_user_id = $post['user_id'];
        ?>
        <div class="post-box">
            <p><strong><?= htmlspecialchars($post['name']) ?></strong></p>
            <p><?= htmlspecialchars($post['content']) ?></p>

            <?php if ($post['file_path']) { ?>
                <a href="uploaded_files/<?= htmlspecialchars($post['file_path']) ?>" target="_blank">View Attachment</a>
            <?php } ?>

            <form action="like_post.php" method="post">
                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                <button type="submit" name="like_post" class="like-btn">Like</button>
            </form>

            <form action="comment_post.php" method="post">
                <input type="text" name="comment_text" placeholder="Add a comment" required>
                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                <button type="submit" name="comment_post" class="comment-btn">Comment</button>
            </form>

            <p>Likes: <?= $post['like_count'] ?> | Comments: <?= $post['comment_count'] ?></p>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No posts available!</p>';
        }
        ?>
    </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
