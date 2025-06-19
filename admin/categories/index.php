<?php
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
		<h3 class="card-title">ລາຍການຫມວດສິນຄ້າ</h3>
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
						<col width="20%">
						<col width="30%">
						<col width="15%">
						<col width="15%">
					</colgroup>
					<thead>
						<tr>
							<th>#</th>
							<th>ວັນທີສ້າງ</th>
							<th>ປະເພດ</th>
							<th>ຊື່ຫມວດ</th>
							<th>ຄຳອະທິບາຍ</th>
							<th>ສະຖານະ</th>
							<th>ການຈັດການ</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$qry = $conn->query("SELECT * from `category_list` where delete_flag = 0 and branch_id = {$_GET["branch_id"]} order by `name` asc ");
						while ($row = $qry->fetch_assoc()) :
						?>
							<tr>
								<td class="text-center"><?php echo $i++; ?></td>
								<td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
								<td><?php echo $row['category_type'] == 1 ? "ເຄື່ອງດື່ມກາເຟ" : "ສ່ວນປະກອບກາເຟຫລືເຄື່ອງໃຊ້ຕ່າງໆ" ?></td>
								<td><?php echo $row['name'] ?></td>
								<td>
									<p class="m-0 truncate-1"><?= $row['description'] ?></p>
								</td>
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
			_conf("ທ່ານຕ້ອງການລົບຫມວດສິນຄ້ານີ້ບໍ?", "delete_category", [$(this).attr('data-id')])
		})
		$('#create_new').click(function() {
			uni_modal("<i class='fa fa-plus'></i> ເພີ່ມຫມວດໃຫມ່", "categories/manage_category.php?branch_id=<?php echo isset($_GET['branch_id']) ? $_GET['branch_id'] : '' ?>")
		})
		$('.view_data').click(function() {
			uni_modal("<i class='fa fa-bars'></i> ຂໍ້ມູນຫມວດສິນຄ້າ", "categories/view_category.php?id=" + $(this).attr('data-id'))
		})
		$('.edit_data').click(function() {
			uni_modal("<i class='fa fa-edit'></i> ອັບເດດຂໍ້ມູນຫມວດສິນຄ້າ", "categories/manage_category.php?id=" + $(this).attr('data-id') + "&branch_id=<?php echo isset($_GET['branch_id']) ? $_GET['branch_id'] : '' ?>")
		})
		$('.table').dataTable({
			columnDefs: [{
				orderable: false,
				targets: [4, 5]
			}],
			order: [0, 'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')

		$('#branch_id').on('change', function() {
			location.replace('<?php echo base_url ?>admin/?page=categories&branch_id=' + $(this).val())
		})
	})

	function delete_category($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_category",
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