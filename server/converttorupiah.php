<?php
    $kurs_dollar = 15502;

    function setRupiah($price){
        $result = "Rp".number_format($price,0,',','.');
        return $result;
    }
?>