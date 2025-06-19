<?php
if ($_SESSION["userdata"]["type"] == 3) {
    $branch_id = $_SESSION["userdata"]["branch_id"];
    $qbranch = $conn->query("SELECT * FROM branch where status = 1 and id = $branch_id");
} else {

    $qbranch = $conn->query("SELECT * FROM branch where status = 1");
}
$branchs = array();
while ($row = $qbranch->fetch_assoc()) {
    $branchs[] = $row;
}

$branch_id = $_GET["branch_id"];
$today = date('Y-m-d');
$sql_queue = "SELECT q.*,s.code FROM queue q 
left join sale_list s on s.id = q.sale_id 
where q.branch_id = $branch_id and date(q.queue_date) between '$today' and '$today' and q.state = 1";
$qry_queue = $conn->query($sql_queue);
$queues = array();
while ($row = $qry_queue->fetch_assoc()) {
    $queues[] = $row;
}



// echo "<pre>";
// print_r($queues);
// echo "</pre>";
$first_queue = array();
foreach ($queues as $q) {
    if ($q["state"] == 1) {
        $first_queue = $q;
        break;
    }
}

$total_queues = 0;
foreach ($queues as $q) {
    if ($q["state"] == 1) {
        $total_queues++;
    }
}

if (count($first_queue) > 0) {
    $sql_sale_list = "select sp.*,pl.name from sale_products sp 
left join sale_list sl on sl.id = sp.sale_id 
left join product_list pl on pl.id = sp.product_id
where sl.id = " . $first_queue["sale_id"];
    $qry_sale_list = $conn->query($sql_sale_list);
    $sale_list = array();
    while ($row = $qry_sale_list->fetch_assoc()) {
        $sale_list[] = $row;
    }
}
// echo "<pre>";
// print_r($sale_list);
// echo "</pre>";
?>


<div class="content py-3">
    <div class="card card-outline card-navy rounded-0 shadow">
        <!-- <div class="card-header">
            <h4 class="card-title">queue: <b><?= isset($code) ? $code : "" ?></b></h4>
            <div class="card-tools">
                <a href="./?page=sales&branch_id=<?= $_GET["branch_id"] ?>" class="btn btn-default border btn-sm"><i class="fa fa-angle-left"></i> ກັບໄປຫນ້າລາຍການຂາຍ</a>
            </div>
        </div> -->
        <div class="card-body">
            <?php if (count($first_queue) > 0) { ?>
                <div class="container-fluid row justify-content-center">
                    <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12" style="font-family: 'Phetsarath OT';" id="printout">

                        <h1 class="text-center">ຄິວທີ <?= $first_queue["queue_no"] ?></h1>
                        <h6 class="mt-1 text-center" style="font-family: 'Phetsarath OT';">ຄິວທີ່ລໍຖ້າທັງຫມົດ <?= $total_queues - 1 ?></h6>

                        <h3 class="mt-5 text-center" style="font-family: 'Phetsarath OT';">ລາຍການທີ່ສັ່ງ</h3>
                        <!-- <div class="col-8 text-right"><?= isset($amount) ? format_num($amount) : 0 ?></div> -->

                        <?php foreach ($sale_list as $sale) { ?>
                            <h5 class="d-flex border-bottom border-secondary">
                                <div class="col-8" style="font-family: 'Phetsarath OT';"><?= $sale["name"] ?></div>
                                <div class="col-4"><?= $sale["qty"] ?></div>
                            </h5>
                        <?php } ?>

                    </div>
                </div>
                <hr>
                <div class="row justify-content-center g-1">
                    <button class="btn btn-light bg-gradient-light border col-lg-3 col-md-4 col-sm-12 col-xs-12 rounded-pill" id="reload"><i class="fa fa-refresh"></i> ໂຫລດໃຫມ່</button>
                    <button class="btn btn-danger border col-lg-3 col-md-4 col-sm-12 col-xs-12 rounded-pill" id="next_queue"><i class="fa fa-arrow-alt-circle-right"></i> ຄິວຖັດໄປ</button>
                </div>
            <?php } else { ?>
                <h1 class='text-center'>ບໍ່ມີຄິວທີສັ່ງ</h1>

                <div class="row justify-content-center g-1">
                    <button class="btn btn-light bg-gradient-light border col-lg-3 col-md-4 col-sm-12 col-xs-12 rounded-pill" id="reload"><i class="fa fa-refresh"></i> ໂຫລດໃຫມ່</button>

                </div>

            <?php } ?>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        $('.table').dataTable({
            columnDefs: [{
                orderable: false,
                targets: [5]
            }],
            order: [0, 'asc']
        });
        $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')

        $('#branch_id').on('change', function() {
            location.replace('<?php echo base_url ?>admin/?page=queue&branch_id=' + $(this).val())
        })

        $('#reload').click(function() {
            location.reload()
        })

        $('#next_queue').click(function() {
            start_loader()

            const id = <?= count($first_queue)  > 0 ? $first_queue["id"] : "''" ?>;
            const formData = new FormData()
            formData.append('id', id)
            $.ajax({
                url: _base_url_ + 'classes/Master.php?f=save_queue',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function(resp) {
                    console.log(resp)
                    const res = JSON.parse(resp)
                    if (res.status == "success") {
                        location.reload()
                    } else {
                        $('#msg').html('<div class="alert alert-danger">What wrong with queue</div>')
                        end_loader()
                    }
                }
            })
        })
    })
</script>