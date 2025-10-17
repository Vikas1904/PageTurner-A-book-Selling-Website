<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_users.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom admin CSS file link -->
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="users">

    <h1 class="title">User Accounts</h1>

    <div class="search-container">
        <form action="" method="GET">
            <input type="text" name="search" placeholder="Search by username or email" class="box">
            <input type="submit" value="Search" class="btn">
        </form>
    </div>

    <div class="box-container">
        <?php
        // Handling search functionality
        $search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
        $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE name LIKE '%$search_query%' OR email LIKE '%$search_query%'") or die('query failed');

        while ($fetch_users = mysqli_fetch_assoc($select_users)) {
        ?>
            <div class="box">
                <p>User ID: <span><?php echo $fetch_users['id']; ?></span></p>
                <p>Username: <span><?php echo $fetch_users['name']; ?></span></p>
                <p>Email: <span><?php echo $fetch_users['email']; ?></span></p>
                <p>User Type: <span style="color:<?php echo $fetch_users['user_type'] == 'admin' ? 'var(--orange)' : 'var(--blue)'; ?>"><?php echo $fetch_users['user_type']; ?></span></p>
                <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.');" class="delete-btn">Delete User</a>
            </div>
        <?php
        }
        ?>
    </div>

</section>

<!-- Custom admin JS file link -->
<script src="js/admin_script.js"></script>

</body>
</html>
