<?php
ob_start();

include ('header.php');
// Include the connection file
include ('koneksi.php');



// Create a Product object
$product = new Product($koneksi);

?>

<?php

    /*  include cart items if it is not empty */
    include ('Template/_checkout.php');
    /*  include cart items if it is not empty */

?>

<?php
// include footer.php file
include ('footer.php');
?>
