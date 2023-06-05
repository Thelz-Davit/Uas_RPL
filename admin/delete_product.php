<?php
    include('../server/connection.php');

    $id_produk = $_GET['product_id'];
    $sql = "DELETE FROM products WHERE product_id = $id_produk";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header('location: products.php?success_update_message=Product has been deleted successfully');
    } else {
        header('location: products.php?fail_update_message=Error occured, try again!');
    }
?>