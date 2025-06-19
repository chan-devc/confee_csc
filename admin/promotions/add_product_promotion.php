<?php

require_once('../../config.php');
// if (isset($_GET['id']) && $_GET['id'] > 0) {
//     $qry = $conn->query("SELECT  * from promotion p  where p.id = '{$_GET['id']}' ");
//     if ($qry->num_rows > 0) {
//         foreach ($qry->fetch_assoc() as $k => $v) {
//             $$k = $v;
//         }
//     }
// }



$products = array();
$qry = $conn->query("SELECT p.*,(select c.name from category_list c where c.id = p.category_id ) cate_name from product_list p where branch_id = {$_GET["branch_id"]} and status = 1 order by `sort` asc ");
while ($row = $qry->fetch_assoc()) {
    $products[] = $row;
}

?>

<div class="container-fluid">
    <form action="" id="promotion-form">
        <!-- <input type="hidden" name="user_id" value="<?php $_SESSION["datauser"]["id"] ?>"> -->
        <!-- <input type="hidden" name="branch_id" value="<?php echo isset($_GET["branch_id"]) ? $_GET["branch_id"] : '' ?>"> -->
        <input type="hidden" name="promotion_id" value="<?php echo isset($_GET["promotion_id"]) ? $_GET["promotion_id"] : '' ?>">

        <div class="form-group">
            <label for="product_id" class="control-label">ສິນຄ້າໂປ</label>
            <select name="product_id" id="product_id" class="form-control form-control-sm rounded-0" required>
                <?php foreach ($products as $key => $value) : ?>
                    <option value="<?php echo $value['id'] ?>" <?php
                                                                if (isset($product_id)) {
                                                                    echo $product_id == $value['id'] ? 'selected' : '';
                                                                }
                                                                ?>><?php echo $value['name']. " ~ ".$value["cate_name"] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="product_id_promotion" class="control-label">ສິນຄ້າແຖມ</label>
            <select name="product_id_promotion" id="product_id_promotion" class="form-control form-control-sm rounded-0" required>
                <?php foreach ($products as $key => $value) : ?>
                    <option value="<?php echo $value['id'] ?>" <?php
                                                                if (isset($product_id_promotion)) {
                                                                    echo $product_id_promotion == $value['id'] ? 'selected' : '';
                                                                }
                                                                ?>><?php echo $value['name']. " ~ ".$value["cate_name"] ?></option>
                <?php endforeach; ?>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {

        $('#promotion-form').submit(function(e) {
            e.preventDefault();
            var _this = $(this)
            $('.err-msg').remove();
            start_loader();

            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_product_promotion",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: err => {
                    console.log(err)
                    alert_toast("An error occured", 'error');
                    end_loader();
                },
                success: function(resp) {
                    if (typeof resp == 'object' && resp.status == 'success') {
                        location.reload()
                    } else if (resp.status == 'failed' && !!resp.msg) {
                        var el = $('<div>')
                        el.addClass("alert alert-danger err-msg").text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        $("html, body").animate({
                            scrollTop: _this.closest('.card').offset().top
                        }, "fast");
                        end_loader()
                    } else {
                        alert_toast("An error occured", 'error');
                        end_loader();
                        console.log(resp)
                    }
                }
            })
        })

    })
</script>