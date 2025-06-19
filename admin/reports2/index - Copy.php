<?php
$date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
$dateto = isset($_GET['date_to']) ? $_GET['date_to'] : date("Y-m-d");
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
if ($_settings->userdata('type') == 3) {
    $user_id = $_settings->userdata('id');
}

$qbranch = $conn->query("SELECT * FROM branch where status = 1");
$branchs = array();
while ($row = $qbranch->fetch_assoc()) {
    $branchs[] = $row;
}

$qcate = $conn->query("SELECT * FROM category_list where status = 1 and branch_id = {$_GET['branch_id']} order by `id` asc");
$categorys = array();
while ($row = $qcate->fetch_assoc()) {
    $categorys[] = $row;
}

?>
<?php if ($_settings->chk_flashdata('success')) : ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
    </script>
<?php endif; ?>
<div class="card card-outline rounded-0 card-navy">
    <div class="card-header">
        <h3 class="card-title">ລາຍງານຍອດຂາຍລາຍວັນ+ສິນຄ້າ</h3>
    </div>

    <?php if (isset($_GET["branch_id"])) : ?>
        <div class="card-body">
            <div class="container-fluid">
                <fieldset class="border px-2 mb-2 ,x-2">
                    <legend class="w-auto px-2">Filter</legend>
                    <form id="filter-form" action="">
                        <div class="row align-items-end">
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="date">ວັນທີ</label>
                                    <input type="date" name="date" value="<?= $date ?>" class="form-control form-control-sm rounded-0" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="date">ຫາວັນທີ</label>
                                    <input type="date" name="date_to" value="<?= $dateto ?>" class="form-control form-control-sm rounded-0" required>
                                </div>
                            </div>
                            <?php if ($_settings->userdata('type') != 3) : ?>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="user_id">ພະນັກງານຂາຍ</label>
                                        <select name="user_id" class="form-control form-control-sm" required>
                                            <option value="0" <?= $user_id == 0 ? 'selected' : '' ?>>ທັງຫມົດ</option>
                                            <?php
                                            $where_user = "";
                                            if ($_GET['branch_id'] > 0) {
                                                $where_user = " where branch_id = '{$_GET['branch_id']}' ";
                                            }
                                            $qry = $conn->query("SELECT *, concat(firstname, ' ', lastname) as `name` from users  {$where_user} order by `name` asc");
                                            while ($row = $qry->fetch_assoc()) :
                                            ?>
                                                <option value="<?= $row['id'] ?>" <?= $user_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>

                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group mt-4">
                                        <label for="branch_id">ສາຂາ</label>
                                        <select name="branch_id" id="branch_id" class="form-control form-control-sm rounded-0" required>
                                            <option value="0" <?= $_GET['branch_id'] == 0 ? 'selected' : '' ?>>ທັງຫມົດ</option>
                                            <?php foreach ($branchs as $key => $value) : ?>
                                                <option value="<?php echo $value['id'] ?>" <?php
                                                                                            if (isset($_GET['branch_id'])) {
                                                                                                echo $_GET['branch_id'] == $value['id'] ? 'selected' : '';
                                                                                            }
                                                                                            ?>><?php echo "ສາຂາ" . $value['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group mt-4">
                                    <label for="category_id">ຫມວດ</label>
                                    <select name="category_id" id="category_id" class="form-control form-control-sm rounded-0" required>
                                        <option value="0" <?= (isset($_GET['category_id'])) && $_GET['category_id'] == 0 ? 'selected' : '' ?>>ທັງຫມົດ</option>
                                        <?php foreach ($categorys as $key => $value) : ?>
                                            <option value="<?php echo $value['id'] ?>" <?php
                                                                                        if (isset($_GET['category_id'])) {
                                                                                            echo $_GET['category_id'] == $value['id'] ? 'selected' : '';
                                                                                        }
                                                                                        ?>><?php echo $value['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <button class="btn btn-primary rounded-0 btn-sm"><i class="fa fa-filter"></i> Filter</button>
                                    <button class="btn btn-light border rounded-0 btn-sm" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </fieldset>
                <div class="container-fluid" id="printout">
                    <table class="table table-hover table-striped table-bordered" id="report-list">
                        <colgroup>
                            <!-- <col width="5%">
                            <col width="20%">
                            <col width="20%">
                            <col width="25%">
                            <col width="15%">
                            <col width="15%"> -->
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <!-- <th>ວັນທີສ້າງ</th> -->
                                <th>ລະຫັດສິນຄ້າ</th>
                                <th>ຈຳນວນ</th>
                                <th>ຫົວຫນ່ວຍ</th>
                                <th>ລາຄາຕໍ່ຫນ່ວຍ</th>
                                <th>ລວມມູນຄ່າ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            $total_qty = 0;
                            $i = 1;
                            $where = "";
                            if ($user_id > 0) {
                                $where = " and sl.user_id = '{$user_id}' ";
                            }
                            if ($_GET["branch_id"] > 0) {
                                $where .= " and sl.branch_id = '{$_GET["branch_id"]}' ";
                            }
                            if (isset($_GET["category_id"]) && $_GET["category_id"] > 0) {
                                $where .= " and pl.category_id = '{$_GET["category_id"]}' ";
                            }
                            // $users_qry = $conn->query("select * from sale_products");
                            // $user_arr = array_column($users_qry->fetch_all(MYSQLI_ASSOC), 'name', 'id');
                            $qry = $conn->query("select pl.name,cl.name cname,un.name unname,pl.price,sum(sp.qty) qty,sum(sp.price * sp.qty) amount from sale_products  sp 
                            left join sale_list sl on sl.id = sp.sale_id 
                            left join product_list pl on pl.id = sp.product_id
                            left join category_list cl on cl.id = pl.category_id
                            left join unit un on un.id = pl.unit_id
                            left join users u on u.id = sl.user_id
                            where sl.status = 1  and date(sl.date_created) between '$date' and '$dateto'  $where  group by sp.product_id");
                            while ($row = $qry->fetch_assoc()) :
                                $total += $row['amount'];
                                $total_qty += $row['qty'];
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td>
                                        <p class="m-0"><?= $row['name'] . "~" . $row["cname"] ?></p>
                                    </td>
                                    <td class='text-right'>
                                        <p class="m-0"><?= format_num($row['qty'], 2) ?></p>
                                    </td>
                                    <td>
                                        <p class="m-0"><?= $row['unname'] ?></p>
                                    </td>
                                    <td class='text-right'><?= format_num($row["price"]) ?></td>
                                    <td class='text-right'><?= format_num($row['amount']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-center">ລວມທັງຫມົດ</th>
                                <th class="text-right"><?= format_num($total_qty, 2) ?></th>
                                <th class="text-right"> </th>
                                <th class="text-right"> </th>
                                <th class="text-right"><?= format_num($total, 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
<noscript id="print-header">
    <style>
        html,
        body {
            background: unset !important;
            min-height: unset !important
        }
    </style>
    <div class="d-flex w-100">
        <div class="col-2 text-center">
        </div>
        <div class="col-8 text-center" style="line-height:.9em">
            <h4 class="text-center m-0"><?= $_settings->info('name') ?></h4>
            <h3 class="text-center m-0"><b>Daily Sales Report</b></h3>
            <h5 class="text-center m-0"><b>as of</b></h5>
            <h3 class="text-center m-0"><b><?= date("F d, Y", strtotime($date)) ?></b></h3>
        </div>
    </div>
    <hr>
</noscript>
<script>
    $(document).ready(function() {
        $('[name="user_id"]').select2({
            placeholder: 'Please Select User Here',
            width: '100%',
            containerCssClass: 'form-control form-control-sm rounded-0'
        })
        $('#filter-form').submit(function(e) {
            e.preventDefault()
            location.href = "./?page=reports2&branch_id=<?= $_GET["branch_id"] ?>&" + $(this).serialize()
        })
        $('#report-list td,#report-list th').addClass('py-1 px-2 align-middle')
        $('#print').click(function() {
            var head = $('head').clone()
            var p = $($('#printout').html()).clone()
            var phead = $($('noscript#print-header').html()).clone()
            var el = $('<div class="container-fluid">')
            head.find('title').text("Daily Sales Report-Print View")
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

        // $('#branch_id').on('change', function() {
        //     location.replace('<?php echo base_url ?>admin/?page=reports&branch_id=' + $(this).val())
        // })
    })
</script>