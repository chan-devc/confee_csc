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
		<h3 class="card-title">ລາຍການຂາຍທັງຫມົດ</h3>
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
		<a href="./?page=sales/manage_sale&branch_id=<?php echo $_GET["branch_id"] ?>" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> ສ້າງໃຫມ່</a>

	</div>
	<?php if (isset($_GET["branch_id"])) : ?>
		<div class="card-body">
			<div class="container-fluid">
				<div class="container-fluid">
					<table class="table table-hover table-striped table-bordered">
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
								<th>ລະຫັດ</th>
								<th>ລູກຄ້າ</th>
								<th>ມູນຄ່າ</th>
								<th>ຈັດການ</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							if ($_settings->userdata('type') == 3) :
								$qry = $conn->query("SELECT *,(select c.name from client c where c.id=client_id) client FROM `sale_list` where user_id = '{$_settings->userdata('id')}' and branch_id = {$_GET["branch_id"]} order by unix_timestamp(date_created) desc ");
							else :
								$qry = $conn->query("SELECT *,(select c.name from client c where c.id=client_id) client FROM `sale_list` where  branch_id = {$_GET["branch_id"]} order by unix_timestamp(date_created) desc ");
							endif;
							while ($row = $qry->fetch_assoc()) :
							?>
								<tr style="<?= $row["status"] == 0 ? "color: red;text-decoration: line-through;text-decoration-color: red;" : "" ?>">
									<td class="text-center"><?php echo $i++; ?></td>
									<td>
										<p class="m-0 truncate-1"><?= date("M d, Y H:i", strtotime($row['date_created'])) ?></p>
									</td>
									<td>
										<p class="m-0 truncate-1"><?= $row['code'] ?></p>
									</td>
									<td>
										<p class="m-0 truncate-1"><?= $row['client'] ?></p>
									</td>
									<td class='text-right'><?= format_num($row['amount']) ?></td>
									<td align="center">
										<a class="btn btn-default bg-gradient-light btn-flat btn-sm" href="?page=sales/view_details&id=<?php echo $row['id'] ?>&branch_id=<?= $_GET["branch_id"] ?>"><span class="fa fa-eye text-dark"></span> ເບິ່ງຂໍ້ມູນ</a>
									</td>
								</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php endif ?>
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
			location.replace('<?php echo base_url ?>admin/?page=sales&branch_id=' + $(this).val())
		})
	})
</script>