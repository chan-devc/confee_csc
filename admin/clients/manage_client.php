<?php

require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
	$qry = $conn->query("SELECT * from `client` where id = '{$_GET['id']}' ");
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_assoc() as $k => $v) {
			$$k = $v;
		}
	}
}
?>
<div class="container-fluid">

	<form action="" id="client-form">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<input type="hidden" name="branch_id" value="<?php echo isset($_GET["branch_id"]) ? $_GET["branch_id"] : '' ?>">

		<div class="form-group">
			<label for="client_type" class="control-label">ປະເພດ</label>
			<select name="client_type" id="client_type" class="form-control form-control-sm rounded-0" required>
				<option value="2" <?php echo isset($client_type) && $client_type == 2 ? 'selected' : '' ?>>ພະນັກງານ</option>
				<option value="1" <?php echo isset($client_type) && $client_type == 1 ? 'selected' : '' ?>>ລູກຄ້າທົ່ວໄປ</option>
			</select>
		</div>

		<div class="form-group">
			<label for="name" class="control-label">ຊື່</label>
			<input type="text" name="name" id="name" class="form-control form-control-sm rounded-0" value="<?php echo isset($name) ? $name : ''; ?>" required />
		</div>
		<!-- <div class="form-group">
			<label for="description" class="control-label">ຄຳອະທິບາຍ</label>
			<textarea type="text" name="description" id="description" class="form-control form-control-sm rounded-0" required><?php echo isset($description) ? $description : ''; ?></textarea>
		</div> -->
		<div class="form-group">
			<label for="status" class="control-label">ສະຖານະ</label>
			<select name="status" id="status" class="form-control form-control-sm rounded-0" required>
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>ນຳໃຊ້</option>
				<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>ປິດການນຳໃຊ້</option>
			</select>
		</div>
	</form>
</div>
<script>
	$(document).ready(function() {
		$('#client-form').submit(function(e) {
			e.preventDefault();
			var _this = $(this)
			$('.err-msg').remove();
			start_loader();

			$.ajax({
				url: _base_url_ + "classes/Master.php?f=save_client",
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
						location.reload()
					} else if (resp.status == 'failed' && !!resp.msg) {
						var el = $('<div>')
						el.addClass("alert alert-danger err-msg").text(resp.msg)
						_this.prepend(el)
						el.show('slow')
						$("html, body").animate({
							scrollTop: _this.closest('.card').offset().top
						}, "fast");
						end_loader()
					} else {
						alert_toast("An error occured", 'error');
						end_loader();
						console.log(resp)
					}
				}
			})
		})

	})
</script>