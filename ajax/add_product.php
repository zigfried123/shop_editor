<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/db.php";

$q1 = $db->query("SELECT id FROM categories WHERE category='{$_POST["product_category"]}'");

$id = $q1->fetch_assoc()["id"];

$q2 = $db->query("INSERT INTO products (product,price,category_id) VALUES ('{$_POST["product_name"]}','{$_POST["product_price"]}','$id')");

var_dump($q2);