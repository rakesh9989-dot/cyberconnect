<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
}

// Fetch updated counts
$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

$select_users = $conn->prepare("
    SELECT u.id, u.name, 
           (SELECT COUNT(*) FROM `likes` WHERE user_id = u.id) + 
           (SELECT COUNT(*) FROM `comments` WHERE user_id = u.id) AS total_points 
    FROM `users` u
    ORDER BY total_points DESC
");
$select_users->execute();
$users = $select_users->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="profile">
   <h1 class="heading">Profile Details</h1>
   <div class="details">
      <div class="user">
         <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <p>Student</p>
         <a href="update.php" class="inline-btn">Update Profile</a>
      </div>
      <div class="box-container">
         <div class="box">
            <div class="flex">
               <i class="fas fa-bookmark"></i>
               <div>
                  <h3><?= $total_bookmarked; ?></h3>
                  <span>Saved Playlists</span>
               </div>
            </div>
            <a href="#" class="inline-btn">View Playlists</a>
         </div>
         <div class="box">
            <div class="flex">
               <i class="fas fa-heart"></i>
               <div>
                  <h3><?= $total_likes; ?></h3>
                  <span>Liked Tutorials</span>
               </div>
            </div>
            <a href="#" class="inline-btn">View Liked</a>
         </div>
         <div class="box">
            <div class="flex">
               <i class="fas fa-comment"></i>
               <div>
                  <h3><?= $total_comments; ?></h3>
                  <span>Video Comments</span>
               </div>
            </div>
            <a href="#" class="inline-btn">View Comments</a>
         </div>
      </div>
   </div>
</section>
<section class="leaderboard" style="padding: 40px; background-color: #fff; border-radius: 8px; margin: 40px auto; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 90%; max-width: 1200px;">
   <h1 class="heading" style="font-size: 2em; color: #333; margin-bottom: 20px;">Leaderboard</h1>
   <table style="width: 100%; border-collapse: collapse; margin-top: 30px; border: 1px solid #ddd; font-size: 1.2em;">
      <thead>
         <tr>
            <th style="padding: 20px; text-align: center; background-color: #007bff; color: white; font-size: 1.4em;">Serial No.</th>
            <th style="padding: 20px; text-align: center; background-color: #007bff; color: white; font-size: 1.4em;">Name</th>
            <th style="padding: 20px; text-align: center; background-color: #007bff; color: white; font-size: 1.4em;">Points</th>
         </tr>
      </thead>
      <tbody>
         <?php
         $serial_number = 1;
         foreach ($users as $user) {
            echo "<tr>
                  <td style='padding: 20px; text-align: center; border: 1px solid #ddd;'>{$serial_number}</td>
                  <td style='padding: 20px; text-align: center; border: 1px solid #ddd;'>{$user['name']}</td>
                  <td style='padding: 20px; text-align: center; border: 1px solid #ddd;'>{$user['total_points']}</td>
               </tr>";
            $serial_number++;
         }
         ?>
      </tbody>
   </table>
</section>

<footer class="footer">
</footer>

<script src="js/script.js"></script>
   
</body>
</html>
