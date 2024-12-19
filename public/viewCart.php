<?php
require '../vendor/autoload.php';

use ShoppingCart\Classes\Cart;

$cart = new Cart;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Cart - PHP Shopping Cart Tutorial</title>
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

    input[type="number"] {
        width: 60px;
        text-align: center;
    }
</style>
    <script>
        function updateCartItem(obj, id) { // Update item quantity
            $.get("cartAction.php", {
                action: "updateCartItem",
                id: id,
                qty: obj.value
            }, function(data) {
                if (data == 'ok') {
                    location.reload();
                } else {
                    alert('Cart update failed, please try again.');
                }
            });
        }
    </script>
</head>

<body>
    <div class="container">
        <h1>Shopping Cart</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($cart->total_items() > 0) { // Get cart items from session
                    $cartItems = $cart->contents(); // Loop through the cart items
                    foreach ($cartItems as $item) { // Get the product details
                ?>
                        <tr>
                            <td><?php echo $item["name"]; ?></td>
                            <td><?php echo '$' . $item["price"] . ' USD'; ?></td>
                            <td><input type="number" class="form-control text-center" value="<?php echo $item["qty"]; ?>" onchange="updateCartItem(this, '<?php echo $item["rowid"]; ?>')"></td>
                            <td><?php echo '$' . (isset($item["subtotal"]) ? $item["subtotal"] : '0.00') . ' USD'; ?>
                            </td>
                            <td>
                                <a href="cartAction.php?action=removeCartItem&id=<?php echo $item["rowid"]; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="glyphicon glyphicon-trash"></i></a>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="5">
                            <p>Your cart is empty.....</p>
                        </td>
                    <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td><a href="index.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i> Continue Shopping</a></td>
                    <td colspan="2"></td>
                    <?php if ($cart->total_items() > 0) { ?>
                        <td class="text-center"><strong>Total <?php echo '$' . $cart->total() . ' USD'; ?></strong></td>
                        <td><a href="checkout.php" class="btn btn-success btn-block">Checkout <i class="glyphicon glyphicon-menu-right"></i></a></td>
                    <?php } ?>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>