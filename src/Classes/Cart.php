<?php
namespace ShoppingCart\Classes;

class Cart {
    // Initialize cart contents array
    protected $cart_contents = array();

    // Constructor method
    public function __construct(){
        // Start session if not already started
        if(!session_id()){
            session_start();
        }
        // If cart contents exist in session, load them
        if(isset($_SESSION['cart_contents'])){
            $this->cart_contents = $_SESSION['cart_contents'];
        }else{
            // Otherwise, initialize cart with default values
            $this->cart_contents = array('cart_total' => 0, 'total_items' => 0);
        }
    }

    // Get all items in the cart
    public function contents(){
        // Reverse the cart contents array
        $cart = array_reverse($this->cart_contents);
        // Remove total items and cart total from the array
        unset($cart['total_items']);
        unset($cart['cart_total']);
        // Calculate and add subtotal for each item
        foreach ($cart as &$item) {
            $item['subtotal'] = $item['price'] * $item['qty'];
        }
        // Return the cart items
        return $cart;
    }

    // Get a specific item from the cart
    public function get_item($row_id){
        // Return the item if it exists, otherwise return FALSE
        return (isset($this->cart_contents[$row_id])) ? $this->cart_contents[$row_id] : FALSE;
    }

    // Get the total number of items in the cart
    public function total_items(){
        return $this->cart_contents['total_items'];
    }

    // Get the total price of the cart
    public function total(){
        return $this->cart_contents['cart_total'];
    }

    // Insert an item into the cart
    public function insert($item = array()){
        // Check if the item is a valid array
        if(!is_array($item) || count($item) === 0){
            return FALSE;
        }else{
            // Check if required item properties are set
            if(!isset($item['id'], $item['name'], $item['price'], $item['qty'])){
                return FALSE;
            }else{
                // Convert quantity to float
                $item['qty'] = (float) $item['qty'];
                // If quantity is zero, return FALSE
                if($item['qty'] == 0){
                    return FALSE;
                }
                // Convert price to float
                $item['price'] = (float) $item['price'];
                // Generate a unique row ID for the item
                $rowid = md5($item['id']);
                // Get the old quantity if the item already exists in the cart
                $old_qty = isset($this->cart_contents[$rowid]['qty'])?$this->cart_contents[$rowid]['qty']:0;
                // Set the row ID for the item
                $item['rowid'] = $rowid;
                // Add the old quantity to the new quantity
                $item['qty'] += $old_qty;
                // Add the item to the cart contents
                $this->cart_contents[$rowid] = $item;
                // Save the cart and return the result
                return $this->save_cart();
            }
        }
    }

    // Update an item in the cart
    public function update($item = array()){
        // Check if the item is a valid array
        if(!is_array($item) || count($item) === 0){
            return FALSE;
        }else{
            // Check if the row ID is set and the item exists in the cart
            if(!isset($item['rowid'], $this->cart_contents[$item['rowid']])){
                return FALSE;
            }else{
                // If quantity is set, update it
                if(isset($item['qty'])){
                    $item['qty'] = (float) $item['qty'];
                    // If quantity is zero, remove the item from the cart
                    if($item['qty'] == 0){
                        unset($this->cart_contents[$item['rowid']]);
                        return $this->save_cart();
                    }else{
                        // Otherwise, update the quantity
                        $this->cart_contents[$item['rowid']]['qty'] = $item['qty'];
                    }
                }
                // Save the cart and return the result
                return $this->save_cart();
            }
        }
    }

    // Save the cart to the session
    protected function save_cart(){
        // Reset total items and cart total
        $this->cart_contents['total_items'] = 0;
        $this->cart_contents['cart_total'] = 0;
        // Loop through the cart contents
        foreach($this->cart_contents as $key => $val){
            // Skip if the value is not an array or does not have price and quantity
            if(!is_array($val) || !isset($val['price'], $val['qty'])){
                continue;
            }
            // Calculate the cart total and total items
            $this->cart_contents['cart_total'] += ($val['price'] * $val['qty']);
            $this->cart_contents['total_items'] += $val['qty'];
        }
        // Save the cart contents to the session
        $_SESSION['cart_contents'] = $this->cart_contents;
        return TRUE;
    }

    // Remove an item from the cart
    public function remove($row_id){
        // Unset the item from the cart contents
        unset($this->cart_contents[$row_id]);
        // Save the cart and return the result
        $this->save_cart();
        return TRUE;
    }

    // Destroy the cart
    public function destroy(){
        // Reset the cart contents
        $this->cart_contents = array('cart_total' => 0, 'total_items' => 0);
        // Unset the cart contents from the session
        unset($_SESSION['cart_contents']);
    }
}