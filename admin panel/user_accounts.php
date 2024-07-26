<?php
include '../components/connect.php';
if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];
} else {
    $seller_id = '';
    header('location:login.php');
}

//delete message from databse

if(isset($_POST['delete_msg'])){
    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_delete = $conn->prepare("SELECT * FROM message WHERE id = ? ");
    $verify_delete->execute([$delete_id]);

    if($verify_delete->rowCount() > 0){
        $delete_msg = $conn->prepare("DELETE FROM message WHERE id = ? ");
        $delete_msg->execute([$delete_id]);

        $success_msg[] = 'message deleted successfully!';
    }else{
        $warning_msg[] = 'message already deleted!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cheers Bar - user register page</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <!----font awesome cdn link---->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<style>
    .user-container .box-container{
    margin-top: 3rem;
}

.user-container .box-container .box{
    text-align: center;
    padding: 2rem;
    text-transform: capitalize;
    line-height: 2;
    color: #000;
    margin-bottom: 4rem;
}
.user-container .box-container .box span{   
    text-transform: none;
}
.user-container .box-container .box img{
    width: 120px;
    height: 120px;
    border-radius: 50%;
    box-shadow: var(--box-shadow);
    padding: .2rem;
    background-color: var(--main-color);
    margin-top: -5rem;
    margin-bottom: 1rem;
}
</style>
<body>

    <div class="main-container">
        <?php include '../components/admin_header.php' ?>


        <!-- Second lecture part-->

        <section class="user-container">
            <div class="heading">
                <h1>register users</h1>
                <img src="../image/separator-img.png">
            </div>
            <div class="box-container">
            <?php
                
                $select_users = $conn->prepare("SELECT * FROM `users` ");
                $select_users->execute();
                if ($select_users->rowCount() > 0) {
                    while ($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)) {
                        $user_id = $fetch_users['id'];
                ?>
                <div class="box">
                   <img src="../uploaded_files/<?= $fetch_users['image']; ?>" >
                   <p>user id : <span><?= $user_id; ?></span></p>
                   <p>user name : <span><?= $fetch_users['name']; ?></span></p>
                   <p>user email : <span><?= $fetch_users['email']; ?></span></p>
                  
                </div>    
                
                <?php
                        }

                    }else{
                        echo '
                        
                        <div class="empty">
                               <p>no user yet! </p>                        
                        </div>
                        
                        ';
                    }
                ?>
            </div>
        </section>

    </div>









    <!--sweetalert cdn link--->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!--custom js link--->
    <script src="../js/admin_script.js"></script>

    <?php include '../components/alert.php'; ?>

</body>

</html>