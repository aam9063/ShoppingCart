<?php
require  '../vendor/autoload.php'; // Require Composer's autoloader

use ShoppingCart\Config\DbConfig;  // Use the DbConfig class

$dbConfig = new DbConfig(); // Create a new DbConfig object
$db = $dbConfig->connect(); // Get a database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP Shopping Cart Tutorial</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
    .container {
        padding: 50px;
        max-width: 1200px;
        margin: auto;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
        font-weight: bold;
        font-size: 36px;
        color: #333;
        margin-bottom: 20px;
    }
    .cart-link {
        width: 100%;
        text-align: right;
        display: block;
        font-size: 22px;
        color: #007bff;
        text-decoration: none;
        margin-bottom: 20px;
    }
    .cart-link:hover {
        text-decoration: underline;
    }
    .table {
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
    }
    .table th, .table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    .table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    .table tr:hover {
        background-color: #f1f1f1;
    }
    .btn {
        padding: 10px 20px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .btn-danger {
        background-color: #dc3545;
        color: #fff;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
    .form-control {
        width: 80px;
        text-align: center;
    }
</style>
</head>
<body>
<div class="container">
    <h1>Products</h1>
    <a href="viewCart.php" class="cart-link" title="View Cart"><i class="glyphicon glyphicon-shopping-cart"></i></a>
    <div id="products" class="row list-group">
        <?php
        //get rows query
        $query = $db->query("SELECT * FROM products ORDER BY id DESC LIMIT 10"); // Get the latest 10 products
        if($query->num_rows > 0){  // Loop through the product rows
            while($row = $query->fetch_assoc()){ // Display the product details
        ?>
        <div class="item col-lg-4">
            <div class="thumbnail">
                <div class="caption">
                    <h4 class="list-group-item-heading"><?php echo $row["name"]; ?></h4>
                    <p class="list-group-item-text"><?php echo $row["description"]; ?></p>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="lead"><?php echo '$'.$row["price"].' USD'; ?></p>
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-success" href="cartAction.php?action=addToCart&id=<?php echo $row["id"]; ?>">Add to cart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } }else{ ?>
        <p>Product(s) not found.....</p>
        <?php } ?>
    </div>
</div>
</body>
</html>
