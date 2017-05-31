<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/db.php";
if(isset($_POST["product_name"])) {

    $name = $_POST["product_name"];
    $id = $_POST["product_id"];
    $db->query("UPDATE products set product = '" . $name . "' WHERE id=" .  $id . "");
}

if(isset($_POST["product_price"])) {
    $db->query("UPDATE products set price = '" . $_POST["product_price"] . "' WHERE id=" . $_POST["product_id"] . "");
}

if(isset($_POST["product_category"])) {

    $q = $db->query("SELECT id FROM categories WHERE category='".$_POST["product_category"]."'");

    $id = $q->fetch_assoc()["id"];

    $q = $db->query("UPDATE products set category_id = '".$id."' WHERE id='" . $_POST["product_id"] . "'");

    var_dump($q);

}

?>