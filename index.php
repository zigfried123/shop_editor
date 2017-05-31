<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<?php
require_once "db.php";


$q = $db->query("SELECT category FROM categories WHERE parent_id != 0");


while ($categories = $q->fetch_assoc()) {
    $cat_arr[] = $categories["category"];
}

sort($cat_arr);


$products = $db->query("SELECT products.id,products.product,products.price,categories.category FROM products,categories WHERE products.category_id = categories.id");
?>
<div class="row" style="width:100%">
    <div class="col-md-offset-4 col-md-4">
        <table class="table table-hover table-striped">

            <th>ID</th>
            <th>Название продукта</th>
            <th>Категория</th>
            <th>Цена</th>
            <th></th>
            <th></th>


            <?php
            while ($product = $products->fetch_assoc()) {

                ?>
                <tr class="<?= $product["id"] ?>">
                    <td class="table_product_id"><?= $product["id"] ?></td>
                    <td class="table_product_name"><?= $product["product"] ?></td>
                    <td class="table_product_category"><?= $product["category"] ?></td>
                    <td class="table_product_price"><?= $product["price"] ?></td>
                    <td><a href="" data-toggle="modal" data-target="#myModal" class="edit"
                           data-product_id="<?= $product["id"] ?>" data-product_name="<?= $product["product"] ?>"
                           data-product_category="<?= $product["category"] ?>"
                           data-product_price="<?= $product["price"] ?>">редактировать</a>
                    </td>
                    <td class="del"><a data-product_id="<?= $product["id"] ?>" href="" class="product_del">удалить</a>
                    </td>
                </tr>
                <?php
            }


            ?>

        </table>
        <p class="text-right"><a href="" data-toggle="modal" data-target="#myModal" class="add">добавить</a></p>
    </div>
</div>


<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">×</button>
                <h4 class="modal-title">Редактирование</h4>
            </div>
            <div class="modal-body">
                <form id="form_product" class="form-horizontal">
                    <div class="form-group">
                        <label for="product_id" class="col-md-3 control-label">ID</label>
                        <div class="col-md-9">
                            <input type="text" id="product_id" name="product_id" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="product_name" class="col-md-3 control-label">Название продукта</label>
                        <div class="col-md-9">
                            <input type="text" id="product_name" name="product_name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="product_category" class="col-md-3 control-label">Категория</label>
                        <div class="col-md-9">
                            <select class="form-control">
                                <?php foreach ($cat_arr as $category) { ?>
                                    <option class="product_category" value="<?= $category ?>"><?= $category ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="product_price" class="col-md-3 control-label">Цена</label>
                        <div class="col-md-9">
                            <input type="text" id="product_price" name="product_price" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">

                        <div class="col-md-offset-3 col-md-9">
                            <button style="display:none" id="button_add" class="btn btn-success" type="submit">добавить</button>
                        </div>
                    </div>

                </form>

            </div>
            <br>
            <div class="modal-footer">
                <button class="btn btn-default" type="button" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('.edit').on('click', function () {

        $("#button_add").hide();

        var id = $(this).data("product_id");
        var product = $(this).data("product_name");
        var category = $(this).data("product_category");
        var price = $(this).data("product_price");

        $(".modal-body #product_id").val(id);
        $(".modal-body #product_name").val(product);
        $(".modal-body select option[value='" + category + "']").prop("selected", "true");
        $(".modal-body #product_price").val(price);

    });

    $("#product_name").keyup(function () {
        edit_product("product_name");
    });

    $("#product_price").keyup(function () {
        edit_product("product_price");
    });

    $("select").change(function () {
        edit_product("product_category", true);
    });


    function edit_product(name_cell, list=false) {
        var id = $("#product_id").val();
        if (!list) {
            var cell_val = $("#" + name_cell + "").val();
        } else {
            var cell_val = $("." + name_cell + ":selected").val();
        }
        $.ajax({
            url: "/ajax/search_and_change_product.php",
            data: "product_id=" + id + "&" + name_cell + "=" + cell_val + "",
            type: "POST",
            success: function (data) {
                $("." + id + " .table_" + name_cell + "").html(cell_val);
                $("." + id + " a").data(name_cell, cell_val);
            }
        });
    }


    $('.add').on('click', function () {

        $("#button_add").show();

        $(".modal-body #product_id").val("");
        $(".modal-body #product_name").val("");
        $(".modal-body select option:nth-child(1)").prop("selected", "true");
        $(".modal-body #product_price").val("");


    });


    $("#form_product").on("submit", function (event) {
        event.preventDefault();

        var msg = $(this).serialize();

        var category = $(".product_category:selected").val();

        $.ajax({
            url: "/ajax/add_product.php",
            data: msg + "&product_category=" + category + "",
            type: "POST",
            success: function (data) {
                location.reload();
            }
        });

    });


    $(".product_del").click(function () {
        event.preventDefault();
        $id = $(this).data("product_id");
        $.ajax({
            url: "/ajax/del_product.php",
            data: "product_id=" + $id + "",
            type: "POST",
            success: function (data) {
                location.reload();
            }
        });
    });


</script>