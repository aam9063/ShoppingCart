<?php
require  '../vendor/autoload.php'; // Composer autoload

use ShoppingCart\Config\DbConfig; // DbConfig class
use ShoppingCart\Classes\Cart; // Cart class

$dbConfig = new DbConfig(); // Database configuration
$db = $dbConfig->connect(); // Database connection

// initialize shopping cart class
$cart = new Cart;

if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])){ // Check if action is set
    if($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])){ // Check if action is addToCart and id is set
        $productID = $_REQUEST['id']; // Get product ID
        // get product details
        $query = $db->query("SELECT * FROM products WHERE id = ".$productID); // Get product details
        $row = $query->fetch_assoc(); // Fetch product details
        $itemData = array( // Item data
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'qty' => 1
        );
        
        $insertItem = $cart->insert($itemData); // Insert item into cart
        $redirectLoc = $insertItem?'viewCart.php':'index.php'; // Redirect location
        header("Location: ".$redirectLoc); // Redirect to the location
    }elseif($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['id'])){ // Check if action is updateCartItem and id is set
        $itemData = array( // Item data
            'rowid' => $_REQUEST['id'],
            'qty' => $_REQUEST['qty']
        );
        $updateItem = $cart->update($itemData); // Update item quantity
        echo $updateItem?'ok':'err';die; // Return ok if successful, otherwise return err
    }elseif($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])){ // Check if action is removeCartItem and id is set
        $deleteItem = $cart->remove($_REQUEST['id']); // Remove item from cart
        header("Location: viewCart.php"); // Redirect to viewCart.php
    }elseif($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0 && !empty($_SESSION['sessCustomerID'])){ // Check if action is placeOrder and cart is not empty
        // insert order details into database
        $insertOrder = $db->query("INSERT INTO orders (customer_id, total_price, created, modified) VALUES ('".$_SESSION['sessCustomerID']."', '".$cart->total()."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')"); 
        
        if($insertOrder){ // Check if order inserted successfully
            $orderID = $db->insert_id; // Get the order ID
            $sql = ''; 
            // get cart items
            $cartItems = $cart->contents();
            foreach($cartItems as $item){
                $sql .= "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('".$orderID."', '".$item['id']."', '".$item['qty']."');";
            }
            // insert order items into database
            $insertOrderItems = $db->multi_query($sql);
            
            if($insertOrderItems){
                $cart->destroy();
                header("Location: orderSuccess.php?id=$orderID");
            }else{
                header("Location: checkout.php");
            }
        }else{
            header("Location: checkout.php");
        }
    }else{
        header("Location: index.php");
    }
}else{
    header("Location: index.php");
}
