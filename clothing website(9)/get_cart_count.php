<?php
session_start();
$count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $count += $quantity;
    }
}
echo $count; 