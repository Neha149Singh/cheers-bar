<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

include 'components/add_wishlist.php';

//remove product from wishlist
if (isset($_POST['delete_item'])) {

    $wishlist_id = $_POST['wishlist_id'];
    $wishlist_id = filter_var($wishlist_id, FILTER_SANITIZE_STRING);

    $verify_delete = $conn->prepare("SELECT * FROM `wishlist` WHERE id =?");
    $verify_delete->execute([$wishlist_id]);

    if ($verify_delete->rowCount() > 0) {

        $delete_wishlist_id = $conn->prepare("DELETE FROM `wishlist` WHERE id =?");
        $delete_wishlist_id->execute([$wishlist_id]);
        $success_msg[] = 'item removed from wishlist';
    } else {
        $warning_msg[] = 'item already removed';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cheers Bar - my wishlist page</title>

    <link rel="stylesheet" href="css/user_style.css?v=<?php echo time(); ?>">

    <!----font awesome cdn link---->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


</head>

<body>
    <?php include 'components/user_header.php'; ?>
    <div class="banner">
        <div class="detail">
            <h1>my wishlist</h1>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. <br>Odio, accusamus magnam. Maiores excepturi
                provident natus hic, laudantium asperiores quia. Optio, ratione? Nulla obcaecati nemo sunt dignissimos.
                Nam unde provident animi.</p>
            <span><a href="home.php">home</a><i class="fa-solid fa-arrow-right"></i>my wishlist</span>
        </div>
    </div>
    <div class="products">
        <div class="heading">
            <h1>my wishlist</h1>
            <img src="image/separator-img.png">
        </div>
        <div class="box-container">
            <?php
            $grand_total = 0;

            $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id =? ");
            $select_wishlist->execute([$user_id]);

            if ($select_wishlist->rowCount() > 0) {
                while ($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {

                    $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                    $select_products->execute([$fetch_wishlist['product_id']]);

                    if ($select_products->rowCount() > 0) {
                        $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);

                        ?>
                        <form action="" method="post" class="box <?php if ($fetch_products['stock'] == 0) {
                            echo "disabled";
                        } ?>">
                            <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id'] ?>">
                            <img src="uploaded_files/<?= $fetch_products['image']; ?>" class="image">
                            <?php if ($fetch_products['stock'] > 9) { ?>
                                <span class="stock" style="color: green;">In stock</span>
                            <?php } else if ($fetch_products['stock'] == 0) { ?>
                                    <span class="stock" style="color: red;">Out of stock</span>
                            <?php } else { ?>
                                    <span class="stock" style="color: red;">Hurry, only <?= $fetch_products['stock']; ?>left</span>
                            <?php } ?>
                            <div class="content">
                                <img src="image/shape-19.png" class="shap">
                                <div class="button">
                                    <h3 class="name"><?= $fetch_products['name']; ?></h3>
                                    <div>
                                        <button type="submit" name="add_to_cart"><i class="fa-solid fa-cart-shopping"></i></button>
                                        <a href="view_page.php?pid=<?= $fetch_products['id'] ?>" class="fa-solid fa-eye"></a>
                                        <button type="submit" name="delete_item"
                                            onclick="return confirm('remove from wishlist'); "><i
                                                class="fa-solid fa-xmark"></i></button>
                                    </div>
                                </div>
                                <input type="hidden" name="product_id" value="<?= $fetch_products["id"]; ?>">
                                <div class="flex">
                                    <p class="price">price $<?= $fetch_products['price']; ?></p>
                                </div>
                                <div class="flex">
                                    <a href="checkout.php?get_id=<?= $fetch_products['id'] ?>" class="btn">buy now</a>
                                    <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2"
                                        class="qty box">
                                </div>
                            </div>
                        </form>
                        <?php
                        $grand_total += $fetch_wishlist['price'];
                    }
                }
            } else {
                echo '
<div class="empty">
<p>no products added yet</p>
</div>
';
            }
            ?>
        </div>
    </div>














    <?php include 'components/footer.php'; ?>

    <!-----Jquery link------->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!--sweetalert cdn link--->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!--custom js link--->
    <script src="js/user_script.js"></script>

    <?php include 'components/alert.php'; ?>

</body>

</html>