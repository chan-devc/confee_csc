<?php
$date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
$dateto = isset($_GET['date_to']) ? $_GET['date_to'] : date("Y-m-d");
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
$client_id = isset($_GET['client_id']) ? $_GET['client_id'] : 0;
if ($_settings->userdata('type') == 3) {
    $user_id = $_settings->userdata('id');
}

$qbranch = $conn->query("SELECT * FROM branch where status = 1");
$branchs = array();
while ($row = $qbranch->fetch_assoc()) {
    $branchs[] = $row;
}

?>
<?php if ($_settings->chk_flashdata('success')) : ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
    </script>
<?php endif; ?>
<div class="card card-outline rounded-0 card-navy">
    <div class="card-header">
        <h3 class="card-title">ລາຍງານຍອດຂາຍລາຍວັນ</h3>
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
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="client_id">ລູກຄ້າ</label>
                                    <select name="client_id" class="form-control form-control-sm" required>
                                        <option value="0" <?= $client_id == 0 ? 'selected' : '' ?>>ທັງຫມົດ</option>
                                        <?php
                                        $where_client = "where status = 1";
                                        if ($_GET['branch_id'] > 0) {
                                            $where_client .= " and branch_id = '{$_GET['branch_id']}' ";
                                        }
                                        $qry = $conn->query("SELECT *  from client  {$where_client} order by `name` asc");
                                        while ($row = $qry->fetch_assoc()) :
                                        ?>
                                            <option value="<?= $row['id'] ?>" <?= $client_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
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
                            <col width="5%">
                            <col width="20%">
                            <col width="20%">
                            <col width="25%">
                            <col width="15%">
                            <col width="15%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ວັນທີສ້າງ</th>
                                <th>ລະຫັດການຂາຍ</th>
                                <th>ລູກຄ້າ</th>
                                <th>ພະນັກງານຂາຍ</th>
                                <th>ລາຄາສຸດທິ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            $i = 1;
                            $where = "";
                            if ($user_id > 0) {
                                $where = " and user_id = '{$user_id}' ";
                            }
                            if ($client_id > 0) {
                                $where .= " and client_id = '{$client_id}' ";
                            }
                            if ($_GET["branch_id"] > 0) {
                                $where .= " and branch_id = '{$_GET["branch_id"]}' ";
                            }
                            $users_qry = $conn->query("SELECT id, concat(firstname, ' ', lastname) as `name` FROM `users` where id in (SELECT user_id FROM `sale_list` where date(date_created) between '{$date}' and '{$dateto}' {$where}) ");
                            $user_arr = array_column($users_qry->fetch_all(MYSQLI_ASSOC), 'name', 'id');
                            $qry = $conn->query("SELECT *,(select c.name from client c where c.id=client_id) client FROM `sale_list` where  status= 1 and  date(date_created) between '{$date}' and '{$dateto}' {$where} order by unix_timestamp(date_updated) desc ");
                            while ($row = $qry->fetch_assoc()) :
                                $total += $row['amount'];
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td>
                                        <p class="m-0"><?= date("M d, Y H:i", strtotime($row['date_updated'])) ?></p>
                                    </td>
                                    <td>
                                        <p class="m-0"><?= $row['code'] ?></p>
                                    </td>
                                    <td>
                                        <p class="m-0"><?= $row['client'] ?></p>
                                    </td>
                                    <td class=''><?= ucwords(isset($user_arr[$row['user_id']]) ? $user_arr[$row['user_id']] : "N/A") ?></td>
                                    <td class='text-right'><?= format_num($row['amount']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-center">ລວມທັງຫມົດ</th>
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
            <h3 class="text-center m-0"><b><?= date("F d, Y", strtotime($date)) ?> TO <?= date("F d, Y", strtotime($dateto)) ?></b></h3>
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
            location.href = "./?page=reports&branch_id=<?= $_GET["branch_id"] ?>&" + $(this).serialize()
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