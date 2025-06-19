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
        if (isset($user_id) && is_numeric($user_id)) {
            $user = $conn->query("SELECT concat(firstname,' ', lastname) as `name` FROM `users` where id = '{$user_id}' ");
            if ($user->num_rows > 0) {
                $user_name = $user->fetch_array()['name'];
            }
        }
    } else {
        echo '<script> alert("Unknown sale\'s ID."); location.replace("./?page=sales"); </script>';
    }

    $today = date("Y-m-d");

    $qry_queue = $conn->query("SELECT * FROM `queue` where branch_id = '{$_GET['branch_id']}' and date(queue_date)  between '$today ' and '$today'");
    $data_queue = array();
    if ($qry_queue->num_rows > 0) {
        while ($row = $qry_queue->fetch_assoc()) {
            $data_queue[] = $row;
        }
    }
    // print_r($data_queue);

    $my_queue = 0;
    foreach ($data_queue as $k => $v) {
        if ($v['sale_id'] == $id) {
            $my_queue = $v['queue_no'];
        }
    }
    $wait_queue = 0;
    $data_queue2 = array();
    $q = 1;
    foreach ($data_queue as $k => $v) {
        if ($q < $my_queue) {
            $data_queue2[] = $v;
        }
        $q++;
    }

    foreach ($data_queue as $k => $v) {
        if ($v["state"] == 1) {
            $wait_queue++;
        }
    }



    // echo "my queue: " . $my_queue;
} else {
    echo '<script> alert("sale\'s ID is required to access the page."); location.replace("./?page=sales"); </script>';
}
?>
<?php if (isset($_GET["branch_id"])) : ?>

    <div id="printOut2" style="display:none;width:330px;border:1px solid black">
        <div>
            <div class="d-flex">
                <!-- <div class="col-auto"><b style="font-family: 'Phetsarath OT';">ຄິວ: <?= $my_queue ?></b></div>
                <div class="col-auto ps-1 flex-shrink-1 flex-grow-1">ລໍຖ້າອີກ <?= $wait_queue - 1 ?> ຄິວ</div> -->
            </div>
            <div class="d-flex">
                <div class="col-auto"><b style="font-family: 'Phetsarath OT';">ລະຫັດການຂາຍ:</b></div>
                <div class="col-auto ps-1 flex-shrink-1 flex-grow-1 border-bottom border-dark"><?= isset($code) ? $code : "" ?></div>
            </div>
            <div class="d-flex">
                <div class="col-auto"><b style="font-family: 'Phetsarath OT';">ວັນທີ:</b></div>
                <div class="col-auto ps-1 flex-shrink-1 flex-grow-1 border-bottom border-dark"><?= isset($date_created) ? date("Y-m-d h:i A", strtotime($date_created)) : "" ?></div>
            </div>
            <div class="mb-2"></div>
            <h5 class="d-flex border-bottom border-dark">
                <div class=" text-center" style="font-family: 'Phetsarath OT';width:30px">ຈຳນວນ</div>
                <div class=" text-center" style="font-family: 'Phetsarath OT';width:200px">ລາຍການ</div>
                <div class=" text-center" style="font-family: 'Phetsarath OT';width:100px">ລວມ</div>
            </h5>
            <?php if (isset($id)) : ?>
                <?php
                $sp_query = $conn->query("SELECT sp.*, p.name as `product` FROM `sale_products` sp inner join `product_list` p on sp.product_id =p.id where sp.sale_id = '{$id}'");
                while ($row = $sp_query->fetch_assoc()) :
                ?>
                    <div class="d-flex border-bottom border-dark">
                        <div class="text-center" style="width:30px"><?= $row['qty'] ?></div>
                        <div class="" style="line-height:1.9em;width:200px">
                            <p class="m-0" style="font-family: 'Phetsarath OT"><?= $row['product'] ?></p>
                            <p class="m-0"><small>x <?= format_num($row['price']) ?></small></p>
                        </div>
                        <div class="  text-right" style="width:100px"><?= format_num($row['price'] * $row['qty']) ?></div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
            <h3 class="d-flex border-top border-dark">
                <div class="" style="font-family: 'Phetsarath OT';width: 80px;">ລວມ</div>
                <div class="text-right" style="width: 250px;"><?= isset($amount) ? format_num($amount) : 0 ?></div>
            </h3>
            <h5 class="d-flex">
                <div style="font-family: 'Phetsarath OT';width: 80px;">ຮັບເງິນມາ</div>
                <div class="text-right" style="width: 250px;"><?= isset($tendered) ? format_num($tendered) : 0 ?></div>
            </h5>
            <h5 class="d-flex">
                <div class="" style="font-family: 'Phetsarath OT';width: 80px;">ທອນ</div>
                <div class="text-right" style="width: 250px;"><?= isset($amount) && isset($tendered) ? format_num($tendered - $amount) : 0 ?></div>
            </h5>
            <h5 class="d-flex">
                <div class="" style="font-family: 'Phetsarath OT';width: 150px;">ປະເພດການຈ່າຍ</div>
                <div class="text-right" style="width: 180px;font-family: 'Phetsarath OT'">
                    <?php
                    $payment_type = isset($payment_type) ? $payment_type : 0;
                    switch ($payment_type) {
                        case 1:
                            echo "ເງິນສົດ";
                            break;
                        case 2:
                            echo "ເງິນໂອນ";
                            break;
                        case 3:
                            echo "Credit Card";
                            break;
                        default:
                            echo "N/A";
                            break;
                    }
                    ?>
                </div>
            </h5>
            <?php if ($payment_type > 1) : ?>
                <h5 class="d-flex">
                    <div class="" style="width: 80px;">Payment Code</div>
                    <div class="text-right" style="width: 250px"><?= isset($payment_code) ? $payment_code : "" ?></div>
                </h5>
            <?php endif; ?>
            <div class="d-flex">
                <div class="col-auto"><b style="font-family: 'Phetsarath OT';">ພະນັກງານຂາຍ:</b></div>
                <div class="col-auto ps-1 flex-shrink-1 flex-grow-1 border-bottom border-dark"><?= isset($user_name) ? ucwords($user_name) : "" ?></div>
            </div>
        </div>
    </div>


    <div class="content py-3">
        <div class="card card-outline card-navy rounded-0 shadow">
            <div class="card-header">
                <h4 class="card-title">ຂໍ້ມູນການຂາຍ: <b><?= isset($code) ? $code : "" ?></b></h4>
                <div class="card-tools">
                    <a href="./?page=sales&branch_id=<?= $_GET["branch_id"] ?>" class="btn btn-default border btn-sm"><i class="fa fa-angle-left"></i> ກັບໄປຫນ້າລາຍການຂາຍ</a>
                </div>
            </div>
            <div class="card-body">
                <div class="container-fluid row justify-content-center">
                    <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12" style="font-family: 'Phetsarath OT';" id="printout">
                        <div class="d-flex">
                            <!-- <div class="col-auto"><b style="font-family: 'Phetsarath OT';">ຄິວ:<?= $my_queue ?></b></div>
                            <div class="col-auto ps-1 flex-shrink-1 flex-grow-1 ">ລໍຖ້າອີກ <?= $wait_queue - 1 ?> ຄິວ</div> -->
                        </div>
                        <div class="d-flex">
                            <div class="col-auto"><b style="font-family: 'Phetsarath OT';">ລະຫັດການຂາຍ:</b></div>
                            <div class="col-auto ps-1 flex-shrink-1 flex-grow-1 border-bottom border-dark"><?= isset($code) ? $code : "" ?></div>
                        </div>
                        <div class="d-flex">
                            <div class="col-auto"><b style="font-family: 'Phetsarath OT';">ວັນທີ:</b></div>
                            <div class="col-auto ps-1 flex-shrink-1 flex-grow-1 border-bottom border-dark"><?= isset($date_created) ? date("Y-m-d h:i A", strtotime($date_created)) : "" ?></div>
                        </div>
                        <div class="mb-2"></div>
                        <h4 class="d-flex border-bottom border-dark">
                            <div class="col-2 text-center" style="font-family: 'Phetsarath OT';">ຈຳນວນ</div>
                            <div class="col-7 text-center" style="font-family: 'Phetsarath OT';">ລາຍການ</div>
                            <div class="col-3 text-center" style="font-family: 'Phetsarath OT';">ລວມ</div>
                        </h4>
                        <?php if (isset($id)) : ?>
                            <?php
                            $sp_query = $conn->query("SELECT sp.*, p.name as `product` FROM `sale_products` sp inner join `product_list` p on sp.product_id =p.id where sp.sale_id = '{$id}'");
                            while ($row = $sp_query->fetch_assoc()) :
                            ?>
                                <div class="d-flex border-bottom border-dark">
                                    <div class="col-2 text-center"><?= $row['qty'] ?></div>
                                    <div class="col-7" style="line-height:.9em">
                                        <p class="m-0"><?= $row['product'] ?></p>
                                        <p class="m-0"><small>x <?= $row['price'] == 0 ? " ແຖມພີ" :  format_num($row['price']) ?></small></p>
                                    </div>
                                    <div class="col-3 text-right"><?= format_num($row['price'] * $row['qty']) ?></div>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                        <h3 class="d-flex border-top border-dark">
                            <div class="col-4" style="font-family: 'Phetsarath OT';">ລວມ</div>
                            <div class="col-8 text-right"><?= isset($amount) ? format_num($amount) : 0 ?></div>
                        </h3>
                        <h5 class="d-flex">
                            <div class="col-5" style="font-family: 'Phetsarath OT';">ຮັບເງິນມາ</div>
                            <div class="col-7 text-right"><?= isset($tendered) ? format_num($tendered) : 0 ?></div>
                        </h5>
                        <h5 class="d-flex">
                            <div class="col-4" style="font-family: 'Phetsarath OT';">ທອນ</div>
                            <div class="col-8 text-right"><?= isset($amount) && isset($tendered) ? format_num($tendered - $amount) : 0 ?></div>
                        </h5>
                        <h5 class="d-flex">
                            <div class="col-4" style="font-family: 'Phetsarath OT';">ປະເພດການຈ່າຍ</div>
                            <div class="col-8 text-right">
                                <?php
                                $payment_type = isset($payment_type) ? $payment_type : 0;
                                switch ($payment_type) {
                                    case 1:
                                        echo "ເງິນສົດ";
                                        break;
                                    case 2:
                                        echo "ເງິນໂອນ";
                                        break;
                                    case 3:
                                        echo "Credit Card";
                                        break;
                                    default:
                                        echo "N/A";
                                        break;
                                }
                                ?>
                            </div>
                        </h5>
                        <?php if ($payment_type > 1) : ?>
                            <h5 class="d-flex">
                                <div class="col-4">Payment Code</div>
                                <div class="col-8 text-right"><?= isset($payment_code) ? $payment_code : "" ?></div>
                            </h5>
                        <?php endif; ?>
                        <div class="d-flex">
                            <div class="col-auto"><b style="font-family: 'Phetsarath OT';">ພະນັກງານຂາຍ:</b></div>
                            <div class="col-auto ps-1 flex-shrink-1 flex-grow-1 border-bottom border-dark"><?= isset($user_name) ? ucwords($user_name) : "" ?></div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row justify-content-center">
                    <?php if ($_SESSION["userdata"]["type"] == 1) :  ?>
                        <!-- <a class="btn btn-primary bg-gradient-primary border col-lg-3 col-md-4 col-sm-12 col-xs-12 rounded-pill" href="./?page=sales/manage_sale&id=<?= isset($id) ? $id : '' ?>&branch_id=<?= $_GET["branch_id"] ?>"><i class="fa fa-edit"></i> ແກ້ໄຂ</a> -->
                    <?php endif ?>
                    <button class="btn btn-light bg-gradient-light border col-lg-3 col-md-4 col-sm-12 col-xs-12 rounded-pill" id="print"><i class="fa fa-print"></i> ປີ້ນ</button>
                    <?php if ($_SESSION["userdata"]["type"] == 1 && $status == 1) :  ?>
                        <!-- <button class="btn btn-danger bg-gradient-danger border col-lg-3 col-md-4 col-sm-12 col-xs-12 rounded-pill" id="delete_sale" type="button"><i class="fa fa-trash"></i> ລົບ</button> -->
                        <button class="btn btn-danger bg-gradient-danger border col-lg-3 col-md-4 col-sm-12 col-xs-12 rounded-pill" id="cancel_sale" type="button"><i class="fa fa-cancel"></i> ຍົກເລີກ</button>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>
<noscript id="print-header">
    <style>
        html,
        body {
            background: unset !important;
            min-height: unset !important
        }
    </style>
    <div class="d-flex w-100">
        <!-- <div class="col-2 text-center">
        </div> -->
        <div class="">
            <span class="text-left" style="padding: 120px;font-size: 22px;font-family: 'Phetsarath OT'"><?= $_settings->info('name') ?></span><br />
            <span class="text-left" style="padding: 135px;font-size: 33px;font-family: 'Phetsarath OT'"><b>ບິນຂາຍ</b></span>
        </div>
    </div>
    <hr>
</noscript>
<script>
    $(function() {
        $('#print').click(function() {
            var head = $('head').clone()
            var p = $($('#printOut2').html()).clone()
            var phead = $($('noscript#print-header').html()).clone()
            var el = $('<div class="container-fluid">')
            head.find('title').text("Sale Details-Print View")
            el.append(phead)
            el.append(p)
            el.find('.bg-gradient-navy').css({
                'background': '#001f3f linear-gradient(180deg, #26415c, #001f3f) repeat-x !important',
                'color': '#fff'
            })
            el.find('.bg-gradient-secondary').css({
                'background': '#6c757d linear-gradient(180deg, #828a91, #6c757d) repeat-x !important',
                'color': '#fff'
            })
            el.find('tr.bg-gradient-navy').attr('style', "color:#000")
            el.find('tr.bg-gradient-secondary').attr('style', "color:#000")
            start_loader();
            var nw = window.open("", "_blank", "width=1000, height=900")
            nw.document.querySelector('head').innerHTML = head.prop('outerHTML')
            nw.document.querySelector('body').innerHTML = el.prop('outerHTML')
            nw.document.close()
            setTimeout(() => {
                nw.print()
                setTimeout(() => {
                    nw.close()
                    end_loader()
                }, 300)
            }, 500)
        })
        $('#delete_sale').click(function() {
            _conf("Are you sure to delete this sale permanently?", "delete_sale", [])
        })
        $('#cancel_sale').click(function() {
            _conf("Are you sure to cancel this sale ?", "cancel_sale", [])
        })
    })

    function cancel_sale($id) {
        start_loader();
        // console.log(<?= $_settings->userdata('id') ?>)
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=cancel_sale",
            method: "POST",
            data: {
                id: '<?= isset($id) ? $id : "" ?>',
                user_id: '<?= $_settings->userdata('id') ?>'
            },
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occured.", 'error');
                end_loader();
            },
            success: function(resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.replace('./?page=sales&branch_id=<?= $_GET["branch_id"] ?>');
                } else {
                    alert_toast("An error occured.", 'error');
                    end_loader();
                }
            }
        })
    }

    function delete_sale($id) {
        // console.log($id)
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_sale",
            method: "POST",
            data: {
                id: '<?= isset($id) ? $id : "" ?>'
            },
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occured.", 'error');
                end_loader();
            },
            success: function(resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.replace('./?page=sales&branch_id=<?= $_GET["branch_id"] ?>');
                } else {
                    alert_toast("An error occured.", 'error');
                    end_loader();
                }
            }
        })
    }
</script>