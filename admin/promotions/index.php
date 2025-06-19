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
?>

<?php if ($_settings->chk_flashdata('success')) : ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
    </script>
<?php endif; ?>

<div class="card card-outline rounded-0 card-navy">
    <div class="card-header">
        <h3 class="card-title">Promotion</h3>
        <div class="form-group mt-4">
            <select name="branch_id" id="branch_id" class="form-control form-control-sm rounded-0" required>
                <?php foreach ($branchs as $key => $value) : ?>
                    <option value="<?php echo $value['id'] ?>" <?php
                                                                if (isset($_GET['branch_id'])) {
                                                                    echo $_GET['branch_id'] == $value['id'] ? 'selected' : '';
                                                                }
                                                                ?>><?php echo "ສາຂາ" . $value['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> ສ້າງໃຫມ່</a>

    </div>

    <?php if (isset($_GET["branch_id"])) :  ?>


        <div class="card-body">
            <div class="container-fluid">
                <table class="table table-hover table-striped table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="15%">

                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ເລກທີ</th>
                            <th>ວັນທີເລີ່ມຕົ້ນ</th>
                            <th>ວັນທີສິ້ນສຸດ</th>
                            <th>ການຈັດການ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $qry = $conn->query("SELECT * from `promotion` where  branch_id = {$_GET["branch_id"]} order by `id` asc ");
                        while ($row = $qry->fetch_assoc()) :
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo $row['docno'] ?></td>
                                <td><?php echo date("Y-m-d", strtotime($row['date_start'])) ?></td>
                                <td><?php echo date("Y-m-d", strtotime($row['date_end'])) ?></td>

                                <td align="center">
                                    <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                        ການຈັດການ
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <!-- <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> ເບິ່ງຂໍ້ມູນ</a>
                                        <div class="dropdown-divider"></div> -->
                                        <a class="dropdown-item edit_data" href="./?page=promotions/list_promotion&id=<?= $row['id'] ?>&branch_id=<?= $_GET["branch_id"] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> ແກ້ໄຂຂໍ້ມູນ</a>
                                        <!-- <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> ລົບ</a> -->
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif ?>
</div>

<script>
    $(document).ready(function() {

        $('#create_new').click(function() {
            uni_modal("<i class='fa fa-plus'></i> ເພີ່ມ Promotion", "promotions/manage_promotion.php?branch_id=<?php echo isset($_GET['branch_id']) ? $_GET['branch_id'] : '' ?>")
        })

        // $('.edit_data').click(function() {
        // 	uni_modal("<i class='fa fa-edit'></i> ອັບເດດຂໍ້ມູນ Promotion", "promotions/list_promotion.php?id=" + $(this).attr('data-id') + "&branch_id=<?php echo isset($_GET['branch_id']) ? $_GET['branch_id'] : '' ?>")
        // })

        $('.table').dataTable({
            columnDefs: [{
                orderable: false,
                targets: [3, 4]
            }],
            order: [0, 'asc']
        });
        $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
        $('#branch_id').on('change', function() {
            location.replace('<?php echo base_url ?>admin/?page=promotions&branch_id=' + $(this).val())
        })
    })
</script>