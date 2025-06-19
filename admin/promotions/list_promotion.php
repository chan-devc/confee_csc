<?php

$q = $conn->query("SELECT ps.*,p.date_start,p.date_end,p.docno ,pl.name as product_name,
(select pll.name from product_list pll where pll.id = ps.product_id_promotion) as product_name_promotion
,(select c.name from category_list c where c.id = pl.category_id ) cate_name
,(select c.name from category_list c where c.id = (select pll.category_id from product_list pll where pll.id = ps.product_id_promotion)) cate_name2
FROM promotion p  
left join  promotion_sub ps on p.id = ps.promotion_id
left join product_list pl on ps.product_id = pl.id
where p.id = {$_GET['id']} ");
$promot = array();
while ($row = $q->fetch_assoc()) {
    $promot[] = $row;
}

// print_r($promot);


?>


<?php if ($_settings->chk_flashdata('success')) : ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
    </script>
<?php endif; ?>


<div class="card card-outline rounded-0 card-navy">
    <div class="card-header">
        <h3 class="card-title">Promotion ເລກທີ <?= $promot[0]["docno"] ?></h3>
        <br />
        <h5 class="card-title">ວັນທີເລີ່ມຕົ້ນ <?= date("d-m-Y", strtotime($promot[0]["date_start"]))  ?> ~ </h5>
        <h5 class="card-title"> ວັນທີສິ້ນສຸດ <?= date("d-m-Y", strtotime($promot[0]["date_end"]))  ?></h5><br />
        <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> ເພີ່ມ</a>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-hover table-striped table-bordered" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="15%">
                    <col width="25%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ສິນຄ້າ</th>
                        <th>ສິນຄ້າແຖມ</th>
                        <th>ຈັດການ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($promot as $row) :
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo $row['product_name']." ~ ".$row["cate_name"] ?></td>
                            <td><?php echo $row['product_name_promotion']." ~ ".$row["cate_name2"] ?></td>
                            <td class="text-center">
                                <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> ລົບ</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function delete_promotion(id) {
        _conf("Are you sure to delete this promotion?", "delete_promotion", [id])
    }
    $(document).ready(function() {
        $('.delete_data').click(function() {
            _conf("ທ່ານຕ້ອງການລົບສິນຄ້ານີບໍ?", "delete_product_promotion", [$(this).attr('data-id')])
        })
        $('#create_new').click(function() {
            uni_modal("<i class='fa fa-plus'></i> ເພີ່ມໃຫມ່", "promotions/add_product_promotion.php?branch_id=<?php echo isset($_GET['branch_id']) ? $_GET['branch_id'] : '' ?>&promotion_id=<?php echo $_GET['id'] ?>")
        })
    })
    

    function delete_product_promotion($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_product_promotion",
			method: "POST",
			data: {
				id: $id
			},
			dataType: "json",
			error: err => {
				console.log(err)
				alert_toast("An error occured.", 'error');
				end_loader();
			},
			success: function(resp) {
				if (typeof resp == 'object' && resp.status == 'success') {
					location.reload();
				} else {
					alert_toast("An error occured.", 'error');
					end_loader();
				}
			}
		})
	}
</script>