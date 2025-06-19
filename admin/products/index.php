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

$categorys = array();
$qry = $conn->query("SELECT * FROM category_list where status = 1 and branch_id = {$_GET['branch_id']} order by `id` asc");
while ($row = $qry->fetch_assoc()) {
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
		<h3 class="card-title">ລາຍການສິນຄ້າທັງຫມົດ</h3>
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
		<div class="form-group mt-4">
			<label for="category_id" class="control-label">ຫມວດ</label>
			<select name="category_id" id="category_id" class="form-control form-control-sm rounded-0" required>
				<option value="alls" <?php echo isset($_GET['category_id']) && $_GET["category_id"] == "all" ? "selected" : "" ?>>ທັງຫມົດ</option>
				<?php foreach ($categorys as $key => $value) : ?>
					<option value="<?php echo $value['id'] ?>" <?php
																if (isset($_GET['category_id'])) {
																	echo $_GET['category_id'] == $value['id'] ? 'selected' : '';
																}
																?>><?php echo $value['name'] ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> ສ້າງໃຫມ່</a>

	</div>

	<?php if (isset($_GET["branch_id"])) : ?>
		<div class="card-body">
			<div class="container-fluid">
				<table class="table table-hover table-striped table-bordered" id="list">
					<!-- <colgroup>
						<col width="5%">
						<col width="15%">
						<col width="20%">
						<col width="5%">
						<col width="20%">
						<col width="15%">
						<col width="15%">
						<col width="15%">
						<col width="15%">
					</colgroup> -->
					<thead>
						<tr>
							<th>#</th>
							<th>ວັນທີສ້າງ</th>
							<th>ຫມວດ</th>
							<th>ລຳດັບ</th>
							<th>ລະຫັດສິນຄ້າ</th>
							<th>ຊື່ສິນຄ້າ</th>
							<th>ລາຄາ</th>
							<th>ລາຄາພະນັກງານ</th>
							<th>ສະຖານະ</th>
							<th>ການຈັດການ</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$where_gate = "";
						if(isset($_GET['category_id']) && $_GET['category_id'] != "alls"){
							$where_gate = " and p.category_id = {$_GET['category_id']}";

						}
						$qry = $conn->query("SELECT p.*, c.name as `category` from `product_list` p inner join category_list c on p.category_id = c.id where p.delete_flag = 0 and p.branch_id = {$_GET["branch_id"]} $where_gate order by p.`name` asc ");
						while ($row = $qry->fetch_assoc()) :
						?>
							<tr>
								<td class="text-center"><?php echo $i++; ?></td>
								<td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
								<td><?php echo $row['category'] ?></td>
								<td><?php echo $row['sort'] ?></td>
								<td><?php echo $row['code'] ?></td>
								<td><?php echo $row['name'] ?></td>
								<td class="text-right"><?php echo format_num($row['price'],2) ?></td>
								<td class="text-right"><?php echo format_num($row['price2'],2) ?></td>
								<td class="text-center">
									<?php if ($row['status'] == 1) : ?>
										<span class="badge badge-success px-3 rounded-pill">ນຳໃຊ້</span>
									<?php else : ?>
										<span class="badge badge-danger px-3 rounded-pill">ປິດການນຳໃຊ້</span>
									<?php endif; ?>
								</td>
								<td align="center">
									<button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
										ການຈັດການ
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<div class="dropdown-menu" role="menu">
										<a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> ເບິ່ງຂໍ້ມູນ</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> ແກ້ໄຂຂໍ້ມູນ</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> ລົບ</a>
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
		$('.delete_data').click(function() {
			_conf("ທ່ານຕ້ອງການລົບສິນຄ້ານີບໍ?", "delete_product", [$(this).attr('data-id')])
		})
		$('#create_new').click(function() {
			uni_modal("<i class='fa fa-plus'></i> ເພີ່ມສິນຄ້າໃຫມ່", "products/manage_product.php?branch_id=<?php echo isset($_GET['branch_id']) ? $_GET['branch_id'] : '' ?>")
		})
		$('.view_data').click(function() {
			uni_modal("<i class='fa fa-bars'></i> ຂໍ້ມູນສິນຄ້າ", "products/view_product.php?id=" + $(this).attr('data-id') + "&branch_id=<?php echo isset($_GET['branch_id']) ? $_GET['branch_id'] : '' ?>")
		})
		$('.edit_data').click(function() {
			uni_modal("<i class='fa fa-edit'></i> ອັບເດດຂໍ້ມູນສິນຄ້າ", "products/manage_product.php?id=" + $(this).attr('data-id') + "&branch_id=<?php echo isset($_GET['branch_id']) ? $_GET['branch_id'] : '' ?>")
		})
		$('.table').dataTable({
			columnDefs: [{
				orderable: false,
				targets: [6]
			}],
			order: [3, 'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')

		$('#branch_id').on('change', function() {
			location.replace('<?php echo base_url ?>admin/?page=products&branch_id=' + $(this).val() + '&category_id=alls')
		})

		$('#category_id').on('change', function() {
			location.replace('<?php echo base_url ?>admin/?page=products&branch_id=' + $('#branch_id').val() + '&category_id=' + $(this).val())
		})

	})



	function delete_product($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_product",
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