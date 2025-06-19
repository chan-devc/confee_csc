<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM `sale_list` where id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        $res = $qry->fetch_array();
        foreach ($res as $k => $v) {
            if (!is_numeric($k)) {
                $$k = $v;
            }
        }
    } else {
        echo '<script> alert("Unknown Sale\'s ID."); location.replace("./?page=sales"); </script>';
    }
}

$client = array();
$type_price = "price";
if (isset($_GET["client_id"])) {
    $qry_client = $conn->query("SELECT * FROM `client` where id = '{$_GET['client_id']}' ");
    if ($qry_client->num_rows == 1) {
        $client = $qry_client->fetch_assoc();
        // print_r($client);
        if ($client["client_type"] == 2) {
            $type_price = "price2";
        }
    } else {
        echo '<script> alert("Unknown Client\'s ID."); location.replace("./?page=sales"); </script>';
    }
}

$check_date_pro = date('Y-m-d');

$sql_promotion = "SELECT p.*,ps.product_id product_id_select,ps.product_id_promotion product_id_free,pl.name product_name_free,pl.price product_price_free 
FROM promotion p left join promotion_sub ps on ps.promotion_id = p.id left join product_list pl on pl.id = ps.product_id_promotion
WHERE  p.date_start <= '$check_date_pro'  and p.date_end >= '$check_date_pro' and p.branch_id = {$_GET["branch_id"]}";

$promo_arr = array();
$qry_promo = $conn->query($sql_promotion);
if ($qry_promo->num_rows > 0) {
    while ($row = $qry_promo->fetch_assoc()) {
        $promo_arr[] = $row;
    }
}

?>
<style>
    #sales-panel {
        height: 70vh;
    }

    #panel-left,
    #item-list {
        background: rgb(255 255 255 / 17%);
    }

    #item-list {
        height: 60%;
    }

    img#cimg {
        height: 14vh;
        width: 14vh;
        object-fit: cover;
        border-radius: 50% 50%;
    }
</style>


<?php if (isset($_GET["branch_id"]) && $_GET["branch_id"] > 0) : ?>
    <div class="content py-3">
        <div class="container-fluid">
            <div class="card card-outline card-outline rounded-0 shadow blur">
                <div class="card-header">
                    <h5 class="card-title"><?= isset($id) ? "ແກ້ໄຂ " . $code . "" : "ສ້າງໃຫມ່" ?></h5>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <form action="" id="sale-form">
                            <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">
                            <input type="hidden" name="amount" value="<?= isset($amount) ? $amount : '' ?>">
                            <input type="hidden" name="branch_id" value="<?= $_GET["branch_id"] ?>">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-10">
                                    <div class="form-group mb-3">
                                        <label for="client_id" class="control-label">ຊື່ລູກຄ້າ</label>
                                        <!-- <input type="text" name="client_name" id="client_name" class="form-control form-control-sm rounded-0" value="<?= isset($client_name) ? $client_name : "Guest" ?>" required="required"> -->
                                        <select id="client_id" name="client_id" class="form-control form-control-sm" required>
                                            <?php
                                            $where_client = "";
                                            if ($_GET['branch_id'] > 0) {
                                                $where_client = " where branch_id = '{$_GET['branch_id']}' and status = 1";
                                            }
                                            $qry = $conn->query("SELECT * from client  {$where_client} order by `id` asc");
                                            while ($row = $qry->fetch_assoc()) :
                                            ?>
                                                <option value="<?= $row['id'] ?>" <?= count($client) > 0  && ($client["id"] == $row['id']) ? "selected" : "" ?>><?= $row['name'] ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-2 ">
                                    <a href="javascript:void(0)" id="create_new" class="btn my-4 btn-flat btn-primary"><span class="fas fa-plus"></span> ສ້າງໃຫມ່</a>
                                </div>
                            </div>
                            <div class="border rounded-0 shadow bg-gradient-navy px-1 py-1" id="sales-panel">
                                <div class="d-flex h-100 w-100">
                                    <div class="col-7 px-0 h-100" id="panel-left">
                                        <div class="card card-primary bg-transparent border-0 h-100 card-tabs rounded-0">
                                            <div class="card-header bg-gradient-dark p-0 pt-1">
                                                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                                    <?php
                                                    $has_active = false;
                                                    $category = $conn->query("SELECT * FROM `category_list` where delete_flag = 0 and `status` = 1 and branch_id = {$_GET["branch_id"]} and category_type = 1  order by `id` asc");
                                                    $product = $conn->query("SELECT * FROM `product_list` where delete_flag = 0 and `status` = 1 and branch_id = {$_GET["branch_id"]}   order by `sort` asc");
                                                    $prod_arr = [];
                                                    while ($row = $product->fetch_array()) {
                                                        $prod_arr[$row['category_id']][] = $row;
                                                    }
                                                    $cat_arr = array_column($category->fetch_all(MYSQLI_ASSOC), 'name', 'id');
                                                    foreach ($cat_arr as $k => $v) :
                                                    ?>
                                                        <li class="nav-item">
                                                            <a class="nav-link <?= (!$has_active) ? 'active' : '' ?>" id="custom-tabs-one-home-tab" data-toggle="pill" href="#cat-tab-<?= $k ?>" role="tab" aria-controls="cat-tab-<?= $k ?>" aria-selected="<?= (!$has_active) ? 'true' : 'false' ?>"><?= $v ?></a>
                                                        </li>
                                                    <?php
                                                        $has_active = true;
                                                    endforeach;
                                                    ?>

                                                </ul>
                                            </div>
                                            <div class="card-body">
                                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                                    <?php
                                                    $has_active = false;
                                                    foreach ($cat_arr as $k => $v) :
                                                    ?>
                                                        <div style="height:580px" class="tab-pane fade   overflow-auto <?= (!$has_active) ? 'active show' : '' ?>" id="cat-tab-<?= $k ?>" role="tabpanel" aria-labelledby="cat-tab-<?= $k ?>-tab">
                                                            <div class="container">
                                                                <div class="row">


                                                                    <?php if (isset($prod_arr[$k])) : ?>
                                                                        <?php foreach ($prod_arr[$k] as $row) : ?>
                                                                            <div class="col-12 ">
                                                                                <a href="javascript:void(0)" class="card rounded-pill text-dark text-decoration-none prod-item" data-price="<?= $row[$type_price] ?>" data-id="<?= $row['id'] ?>">

                                                                                    <div class="card-body" style="background-color: black;color:white; border-radius: 30px; font-weight: bold;padding: 15px;"><?= $row["sort"] == 0 ? "" : $row["sort"] . ". " ?><?= $row['name'] ?><br />
                                                                                        <?= number_format($row[$type_price], 2)  ?></div>


                                                                                </a>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                        $has_active = true;
                                                    endforeach;
                                                    ?>

                                                </div>
                                            </div>
                                            <!-- /.card -->
                                        </div>
                                    </div>
                                    <div class="col-5 h-100">
                                        <table class="table table-bordered table-striped mb-0">
                                            <colgroup>
                                                <col width="20%">
                                                <col width="45%">
                                                <col width="25%">
                                                <col width="10%">
                                            </colgroup>
                                            <thead>
                                                <tr class="bg-gradient-navy-dark">
                                                    <th class="text-center px-2 py-1">ຈຳນວນ</th>
                                                    <th class="text-center px-2 py-1">ສິນຄ້າ</th>
                                                    <th class="text-center px-2 py-1">ລວມ</th>
                                                    <th class="text-center px-2 py-1"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr></tr>
                                            </tbody>
                                        </table>
                                        <div id="item-list" class="overflow-auto">
                                            <table class="table table-bordered table-striped" id="product-list">
                                                <colgroup>
                                                    <col width="20%">
                                                    <col width="45%">
                                                    <col width="25%">
                                                    <col width="10%">
                                                </colgroup>
                                                <tbody>
                                                    <?php if (isset($id)) : ?>
                                                        <?php
                                                        $sp_query = $conn->query("SELECT sp.*, p.name as `product` FROM `sale_products` sp inner join `product_list` p on sp.product_id =p.id where sp.sale_id = '{$id}'");
                                                        while ($row = $sp_query->fetch_assoc()) :
                                                        ?>
                                                            <tr>
                                                                <td class="px-2 py-1 align-middle">
                                                                    <input type="hidden" name="product_id[]" value="<?= $row['product_id'] ?>">
                                                                    <input type="hidden" name="product_price[]" value="<?= $row['price'] ?>">
                                                                    <input type="number" class="form-control form-control-sm rounded-0 text-center" min="0" name="product_qty[]" value="<?= $row['qty'] ?>" required>
                                                                </td>
                                                                <td class="px-2 py-1 align-middle" style="line-height:.9em">
                                                                    <p class="product_name m-0 truncate-1"><?= $row['product'] ?></p>
                                                                    <p class="m-0"><small class="product_price">x <?= format_num($row['price']) ?></small></p>
                                                                </td>
                                                                <td class="px-2 py-1 align-middle text-right product_total"><?= format_num($row['price'] * $row['qty']) ?></td>
                                                                <td class="px-2 py-1 align-middle text-center"><button class="btn btn-outline-danger border-0 btn-sm rounded-0 rem-product p-1" type="button"><i class="fa fa-times"></i></button></td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <h3 class="text-light w-100 d-flex">
                                            <div class="col-auto">ລວມ:</div>
                                            <div class="col-auto flex-shrink-1 flex-grow-1 truncate-1 text-right" id="amount"><?= isset($amount) ? format_num($amount) : '0.00' ?></div>
                                        </h3>
                                        <h3 class="d-flex w-100 align-items-center">
                                            <div class="col-4">ຮັບເງິນ:</div>
                                            <div class="col-8">
                                                <input type="text" pattern="[0-9\.]*$" class="form-control form-control-lg rounded-0 text-right" id="tendered" name="tendered" value="<?= isset($tendered) ? str_replace(",", "", format_num($tendered)) : '0' ?>" required />
                                            </div>
                                        </h3>
                                        <h3 class="d-flex w-100 align-items-center">
                                            <div class="col-4">ທອນ:</div>
                                            <div class="col-8">
                                                <input type="text" pattern="[0-9\.]*$" class="form-control form-control-lg rounded-0 text-right" id="change" value="<?= isset($amount) && isset($tendered) ? format_num($tendered - $amount) : '0' ?>" readonly />
                                            </div>
                                        </h3>
                                        <h3 class="d-flex w-100 align-items-center">
                                            <div class="col-4">ປະເພດ:</div>
                                            <div class="col-8">
                                                <select name="payment_type" id="payment_type" class="form-control rounded-0" required="required">
                                                    <option value="1" <?= isset($payment_type) && $payment_type == 1 ? "selected" : "" ?>>ເງິນສົດ</option>
                                                    <option value="2" <?= isset($payment_type) && $payment_type == 2 ? "selected" : "" ?>>ເງິນໂອນ</option>
                                                    <!-- <option value="3" <?= isset($payment_type) && $payment_type == 3 ? "selected" : "" ?>>Credit Card</option> -->
                                                </select>
                                            </div>
                                        </h3>
                                        <input type="text" id="payment_code" class="form-control form-control-sm rounded-0 d-none" name="payment_code" value="<?= isset($payment_code) ? $payment_code : "0321500410004298" ?>" placeholder="Payment Code">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-footer py-2 text-right">
                    <button class="btn btn-primary rounded-0" form="sale-form">ບັນທຶກການຂາຍ</button>
                    <?php if (!isset($id)) : ?>
                        <a class="btn btn-default border rounded-0" href="./?page=sales&branch_id=<?= $_GET["branch_id"] ?>">ຍົກເລີກ</a>
                    <?php else : ?>
                        <a class="btn btn-default border rounded-0" href="./?page=sales/view_details&id=<?= $id ?>&branch_id=<?= $_GET["branch_id"] ?>">ຍົກເລີກ</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>
<noscript id="product-clone">
    <tr>
        <td class="px-2 py-1 align-middle">
            <input type="hidden" name="product_id[]">
            <input type="hidden" name="product_price[]">
            <input type="number" class="form-control form-control-sm rounded-0 text-center" min="0" name="product_qty[]" value="1" required>
        </td>
        <td class="px-2 py-1 align-middle" style="line-height:.9em">
            <p class="product_name m-0 truncate-1">Product 101</p>
            <p class="m-0"><small class="product_price">x 123.00</small></p>
        </td>
        <td class="px-2 py-1 align-middle text-right product_total"></td>
        <td class="px-2 py-1 align-middle text-center">
            <button class="btn btn-outline-danger border-0 btn-sm rounded-0 rem-product p-1" type="button"><i class="fa fa-times"></i></button>
            <button class="btn btn-outline-primary border-0 btn-sm rounded-0 free-product p-1" type="button"><span>ເລືອກ</span></button>
        </td>
    </tr>
</noscript>


<script>
    function calc_change() {
        var amount = $('[name="amount"]').val()
        var tendered = $('[name="tendered"]').val()
        amount = amount > 0 ? amount : 0;
        tendered = tendered > 0 ? tendered : 0;
        var change = parseFloat(tendered) - parseFloat(amount)
        $('#change').val(parseFloat(change).toLocaleString('en-US'))
    }

    function calc_total_amount() {
        var total = 0;
        $('#product-list tbody tr').each(function() {
            var qty = $(this).find('[name="product_qty[]"]').val()
            qty = qty > 0 ? qty : 0
            total += (parseFloat($(this).find('[name="product_price[]"]').val()) * parseFloat(qty))
        })
        $('[name="amount"]').val(parseFloat(total))
        $('#amount').text(parseFloat(total).toLocaleString('en-US'))
        $('#tendered').val(total)
        calc_change()
    }

    function calc_product() {
        var total = 0;

        $('#product-list tbody tr').each(function() {
            var qty = $(this).find('[name="product_qty[]"]').val()
            qty = qty > 0 ? qty : 0
            total += (parseFloat($(this).find('[name="product_price[]"]').val()) * parseFloat(qty))
        })
        $('#product_total').text(parseFloat(total).toLocaleString('en-US'))
        calc_total_amount()
    }
    $(function() {


        $('#client_id').on('change', function() {
            console.log('<?php echo base_url ?>admin/?page=sales/manage_sale&branch_id=<?= $_GET["branch_id"] ?>&client_id=' + $(this).val())
            location.replace('<?php echo base_url ?>admin/?page=sales/manage_sale&branch_id=<?= $_GET["branch_id"] ?>&client_id=' + $(this).val())
        })

        $('[name="client_id"]').select2({
            placeholder: 'ເລືອກລູກຄ້າທີ່ຕ້ອງການ',
            width: '100%',
            containerCssClass: 'form-control form-control-sm rounded-0'
        })

        $('#create_new').click(function() {
            uni_modal("<i class='fa fa-plus'></i> ເພີ່ມ ລູກຄ້າໃຫມ່", "clients/manage_client.php?branch_id=<?php echo isset($_GET['branch_id']) ? $_GET['branch_id'] : '' ?>")
        })

        const promo = <?= count($promo_arr) > 0 ? json_encode($promo_arr) : json_encode([]) ?>;
        $('body').addClass('sidebar-collapse')
        $('#payment_type').change(function() {
            var type = $(this).val()
            if (type == 1) {
                $('#payment_code').addClass('d-none').attr('required', false)
            } else {
                $('#payment_code').removeClass('d-none').attr('required', true)
            }
        })
        $('#product-list tbody tr').find('.rem-product').click(function() {
            var tr = $(this).closest('tr')
            if (confirm("Are you sure to remove " + (tr.find('.product_name').text()) + " from product list?") === true) {
                tr.remove()
                calc_product()
            }
        })
        $('#product-list tbody tr').find('[name="product_qty[]"]').on('input change', function() {
            var tr = $(this).closest('tr')
            var price = tr.find('[name="product_price[]"]').val()
            var qty = $(this).val()
            qty = qty > 0 ? qty : 0
            price = price > 0 ? price : 0
            var total = parseFloat(qty) * parseFloat(price)
            tr.find('.product_total').text(parseFloat(total).toLocaleString())
            calc_product()

        })
        $('#tendered').on('input', function() {
            calc_change()
        })
        $('.prod-item').click(function() {
            var id = $(this).attr('data-id')
            // if ($('#product-list tbody tr input[name="product_id[]"][value="' + id + '"]').length > 0) {
            //     alert("Product already on the list.")
            //     return false;
            // }
            var name = ($(this).find('.card-body').text()).trim()
            var price = $(this).attr('data-price')
            var tr = $($('noscript#product-clone').html()).clone()
            tr.find('input[name="product_id[]"]').val(id)
            tr.find('input[name="product_price[]"]').val(price)
            tr.find('.product_name').text(name)
            tr.find('.product_price').text('x ' + parseFloat(price).toLocaleString())
            tr.find('.product_total').text(parseFloat(price).toLocaleString())
            $('#product-list tbody').append(tr)
            if (promo.length > 0) {
                const data_promo = promo.find((item) => item.product_id_select == id)
                if (data_promo) {
                    var tr2 = $($('noscript#product-clone').html()).clone()
                    tr2.find('input[name="product_id[]"]').val(data_promo.product_id_free)
                    tr2.find('input[name="product_price[]"]').val(0)
                    tr2.find('.product_name').text(data_promo.product_name_free)
                    tr2.find('.product_price').text('Free')
                    tr2.find('.product_total').text("0")
                    // tr2.find('input[name="product_qty[]"]').val(1)
                    // tr2.find('input[name="product_qty[]"]').attr('disabled', true)
                    tr2.find('.rem-product').addClass("d-none")
                    $('#product-list tbody').append(tr2)
                }
            }
            calc_product()
            tr.find('.rem-product').click(function() {
                if (confirm("ຍືນຍັນທີ່ຕ້ອງການລົບລາຍການນີ້ " + name) === true) {
                    tr.remove()
                    if (promo.length > 0) {
                        const data_promo = promo.find((item) => item.product_id_select == id)
                        if (data_promo) {
                            tr2.remove()
                        }
                    }
                    calc_product()
                }
            })
            tr.find('.free-product').click(function() {
                if (confirm("ຍືນຍັນທີ່ຕ້ອງການແຖມຟຣີລາຍການນີ້ " + name) === true) {
                    tr.find('input[name="product_price[]"]').val(0)
                    tr.find('.product_price').text('x ' + "Free")
                    tr.find('.product_total').text(parseFloat(0).toLocaleString())
                    const trFree = tr.find('.free-product')
                    trFree.removeClass('btn-outline-primary').addClass('btn-outline-success').find('i').removeClass('fa-check-circle').addClass('fa-check')
                    trFree.find('span').text('ແຖມ')
                    calc_product()
                }
            })
            tr.find('[name="product_qty[]"]').on('input change', function() {
                var qty = $(this).val()
                qty = qty > 0 ? qty : 0
                var total = parseFloat(qty) * parseFloat(price)
                tr.find('.product_total').text(parseFloat(total).toLocaleString())
                calc_product()
                if (promo.length > 0) {
                    const data_promo = promo.find((item) => item.product_id_select == id)
                    if (data_promo) {
                        tr2.find('[name="product_qty[]"]').val(qty)
                    }
                }
            })
        })
        $('#sale-form').submit(function(e) {
            e.preventDefault();
            var _this = $(this)
            console.log($(this)[0])
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_sale",
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
                        location.href = "./?page=sales/view_details&id=" + resp.sid + "<?= isset($_GET['branch_id']) ? "&branch_id=" . $_GET['branch_id'] : "" ?>";
                    } else if (resp.status == 'failed' && !!resp.msg) {
                        var el = $('<div>')
                        el.addClass("alert alert-danger err-msg").text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        $("html, body,.modal").scrollTop(0);
                        end_loader()
                    } else {
                        alert_toast("An error occured", 'error');
                        end_loader();
                    }
                }
            })
        })
    })
</script>