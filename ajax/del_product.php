<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/db.php";

$q = $db->query("DELETE FROM products WHERE id = '{$_POST["product_id"]}'");
    
