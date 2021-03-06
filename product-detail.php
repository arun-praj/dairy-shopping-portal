<?php
// $error = false;
// $messageCode="Warning";
// $message = "Only items are available in Stock";
    session_start();
    require_once('./backend/includes/db_connect.php');
    include('./includes/cart.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./imgs/title-cow.png" type="img/png">
    <title>Dairy Store</title>
    <link rel="stylesheet" href="./css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap" rel="stylesheet">
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <link rel="stylesheet" type="text/css" href="./slick/slick.css">
    <link rel="stylesheet" type="text/css" href="./slick/slick-theme.css">

</head>

<body>
    <div class="container">
        <?php include('./contents/nav.php');?>
        <?php
            $product_id = $_GET['productId'];
            // echo $product_id;
            $sql = "SELECT * FROM product WHERE product_id = '$product_id'";
            $result = mysqli_query($conn,$sql);
            $product = array_column($_SESSION['cart'],'product_id');
            $row = mysqli_fetch_assoc($result);
            
            $key = array_search($product_id, array_column($_SESSION['cart'], 'product_id'));
        ?>
         <div class="breadcrumb ">
                <a href="./index.php" class="breadcrumb--links">Home</a>
                <svg class="breadcrumb-icon">
                    <use xlink:href="./imgs/icons/sprite.svg#icon-chevron-right"></use>
                </svg>
                <a href="./collection.php?page=<?=$_GET['page']?>" class="breadcrumb--links"><?=ucfirst($_GET['page']);?></a>
                <svg class="breadcrumb-icon">
                    <use xlink:href="./imgs/icons/sprite.svg#icon-chevron-right"></use>
                </svg>
                <a href="#" class="breadcrumb--links"><?=ucfirst($row['product_name']);?></a>
            </div>
        <section class="section-view-product">
        <?php
        if(isset($_GET['error']) == true) {?>
            <div class="notice-bar  notice-bar-danger m-top-btm-2" id="notice-error">
                        <span class="notice--text bold">ERROR - </span>
                        <span class="notice--text">Only <?=$_GET['item_available']?> items avaliable in stock</span>
                        <span class="notice--close-btn" onclick="" id="notice-error-cross">
                            <i class="lnr lnr-cross"> 
                                <svg class="icon-cross">
                                    <use xlink:href="./imgs/icons/sprite.svg#icon-cross"></use>
                                 </svg>
                            </i>
                        </span>
            </div> 
        <?php } ?>
            <div class="view-product-container">
                <div class="col-1">
                    <div class="product-box">
                        <img src="./backend/uploads/<?=$row['image_source']?>" class="product-img" alt="">
                    </div>
                </div>
                <div class="col-2">
                    <h1 class="product-heading"><?=$row['product_name']?></h1>
                    <span>
                        <?php
                            if($row['stock'] <= 10 && $row['stock'] != 0){
                        ?>
                                <p class="badge-popular">Limited Stock</p>
                        <?php }
                            elseif($row['stock'] == 0){
                        ?>
                        <p class="badge-out-of-stock">Out of stock</p>


                        <?php
                            } else{
                        ?>
                        <p class="badge-green">In Stock</p>

                        <?php
                            }
                        ?>
                    </span>
                    <p class="product-description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt
                        quos, eius itaque earum nostrum quibusdam eligendi repellat vero qui laudantium?</p>
                    <p class="product-price">Rs. <?=$row['unit_price']?>
                        <span class="product-per">/ cup</span>
                    </p>
                    <?php  if($row['stock'] != 0){ ?>
                    <form action="./includes/cart.php?action=addFromDetail&productId=<?=$product_id?>" method="POST">
                        <div class="input-group">
                            <input type="hidden" name="product_id" id="" value="<?=$product_id?>">
                            <input type="button" value="-" class="button-minus" data-field="quantity">
                            <?php 
                                if($_SESSION['cart'][$key]['product_id'] == $product_id  ) {
                            ?>  
                                <input type="number" step="1" max="" value="<? print_r($_SESSION['cart'][$key]['quantity']); ?>" name="quantity" class="quantity-field">
                            <?php
                                } else{
                            ?>
                                <input type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                            <?php
                                }
                            ?>      
                            <input type="button" value="+" class="button-plus" data-field="quantity">
                            <?php
                                    if(in_array($row['product_id'],$product)){
                                ?>
                            <a href="./checkout.php" class=" btn-cart btn-cart-small">Go to Cart</a>
                            <?php
                                    } else{
                                ?>
                            <input type="submit" name="add" value="Add to cart" class="btn-cart btn-cart-small"></input>
                            <?php
                                    }
                                ?>
                            <!-- <input type="submit" name="add" class="btn-cart" value="Add to cart"> -->
                            <!-- <a href="something" class="button7" style="background-color:#2979FF">Login</a> -->
                        </div>
                    </form>
                                <?php } ?>
                </div>
            </div>
        </section>

        <!-- Section yogurt -->
        <div class="section-yogurt section-product" id="section-yogurt">
            <div class="container-1">
                <div class="headline">
                    <h1 class="heading__primary--sub headling__main">Similar Products.</h1>
                </div>
                <div class="u-margin-top-small carousel-1 carousel slick-slider slider">
                    <?php
                        // require_once('./backend/includes/db_connect.php');
                        $sql = "SELECT * FROM product WHERE category = 'yogurt'";
                        $max_sold_query = "SELECT MAX(quantity_sold) as max_sold FROM product WHERE category = 'yogurt'";
                        $output = mysqli_query($conn, $max_sold_query);
                        $max = 0;
                        if (mysqli_num_rows($output) > 0) {
                            $roww = mysqli_fetch_assoc($output);
                            $max = $roww['max_sold'];
                        } 
                        $result = mysqli_query($conn, $sql);
                        $product = array_column($_SESSION['cart'],'product_id');
                        if (mysqli_num_rows($result) > 0) {
                            $temp = 0;
                            while($row = mysqli_fetch_assoc($result)) {
                                $product_id = $row['product_id'];
                    ?>
                    <div class="slick-list">
                        <a href="./product-detail.php?action=viewProduct&productId=<?=$product_id?>">
                            <div class="slick-list-container">
                                <img class="slider-img" src="./backend/uploads/<?=$row['image_source']?>" alt="" />
                                <div class="slider-details">
                                    <span class="slider-details--name"><?=$row['product_name'];?></span>
                                    <span class="slider-details--discription">Lorem ipsum dolor sit </span>
                                    <?php
                                        if($row['stock'] == 0){
                                            ?>
                                    <span class="u-margin-top-v-small badge-out-of-stock">Out of stock</span>
                                    <?php
                                        } 
                                    ?>
                                    <?php
                                        if($row['quantity_sold'] == $max){
                                            ?>
                                    <span class="u-margin-top-v-small badge-popular">Most popular</span>
                                    <?php
                                        } 
                                    ?>
                                    <span class="slider-details--price">Rs.<?=$row['unit_price'];?></span>
                                    <div class="slider-icon-box">
                                        <!-- <svg class="slider-icon">
                                                <use xlink:href="./imgs/icons/sprite.svg#icon-cart-arrow-down"></use>
                                            </svg> -->
                                        <form action="" method="POST">
                                            <input type="hidden" name="product_id" value="<?=$row['product_id']?>">
                                            <?php
                                                                if(in_array($row['product_id'],$product)){
                                                            ?>
                                            <p>Added</p>
                                            <?php
                                                                } else{
                                                            ?>
                                            <button type="submit" name="add">
                                                Add to Cart
                                            </button>
                                            <?php
                                                                }
                                                            ?>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                            }
                        } else {
                            echo "0 results";
                        }
                    ?>
                </div>
            </div>
        </div>
        <!-- end of section yogurt -->


        <?php include('./contents/footer.php');?>
        <?php include('./contents/modals.php');?>
    </div>
</body>
<!-- Slick slider script -->
<script>
    $(document).ready(function () {
        $(".slick-slider").slick({
            arrows: true,
            dots: true,
            infinite: false,
            slidesToShow: 5,
            slidesToScroll: 5,
            initialSlide: 0,
            rows: 1,
            //   autoplay:true,
            //   centerMode:true,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: "40px",
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: "40px",
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
</script>
<!-- end of slick slider script -->
<script src="./js/quantity.js"></script>
<script>
    document.getElementById('notice-error-cross').addEventListener('click',function(){
        document.getElementById('notice-error').style.display="none";
    });
</script>
</html>