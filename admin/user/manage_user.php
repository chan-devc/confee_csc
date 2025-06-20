<?php
if (isset($_GET['id'])) {
	$user = $conn->query("SELECT * FROM users where id ='{$_GET['id']}' ");
	foreach ($user->fetch_array() as $k => $v) {
		$meta[$k] = $v;
	}
}
$qbranch = $conn->query("SELECT * FROM branch where status = 1");
$branchs = array();
while ($row = $qbranch->fetch_assoc()) {

	$branchs[] = $row;
}
?>
<?php
if ($_settings->chk_flashdata('success')) : ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-body">
		<div class="container-fluid">
			<div id="msg"></div>
			<form action="" id="manage-user">
				<input type="hidden" name="id" value="<?= isset($meta['id']) ? $meta['id'] : '' ?>">

				<?php if (!isset($_GET['id'])) : ?>
					<div class="form-group">
						<label for="branch_id" class="control-label">ສາຂາ</label>
						<select name="branch_id" id="branch_id" class="form-control form-control-sm rounded-0" required>
							<?php foreach ($branchs as $key => $value) : ?>
								<option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif ?>

				<div class="form-group">
					<label for="name">ຊື່</label>
					<input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo isset($meta['firstname']) ? $meta['firstname'] : '' ?>" required>
				</div>
				<div class="form-group">
					<label for="name">ນາມສະກຸນ</label>
					<input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo isset($meta['lastname']) ? $meta['lastname'] : '' ?>" required>
				</div>
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username'] : '' ?>" required autocomplete="off">
				</div>
				<div class="form-group">
					<label for="password"><?= isset($meta['id']) ? " ລະຫັດຜ່ານໃຫມ່" : "ລະຫັດຜ່ານ" ?> </label>
					<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
					<?php if (isset($meta['id'])) : ?>
						<small><i>ປ່ອຍວ່າງໄວ້ຖ້າບໍ່ຕ້ອງການປ່ຽນລະຫັດຜ່ານໃຫມ່.</i></small>
					<?php endif; ?>
				</div>
				<div class="form-group">
					<label for="type" class="control-label">ສິດ</label>
					<select name="type" id="type" class="form-control form-control-sm rounded-0" required>
						<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : '' ?>>Administrator</option>
						<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : '' ?>>Staff</option>
						<option value="3" <?php echo isset($meta['type']) && $meta['type'] == 3 ? 'selected' : '' ?>>Cashier</option>
					</select>
				</div>
				<div class="form-group">
					<label for="" class="control-label">Avatar</label>
					<div class="custom-file">
						<input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))" accept="image/png, image/jpeg">
						<label class="custom-file-label" for="customFile">Choose file</label>
					</div>
				</div>
				<div class="form-group d-flex justify-content-center">
					<img src="<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] : '') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
				</div>
			</form>
		</div>
	</div>
	<div class="card-footer">
		<div class="col-md-12">
			<div class="row">
				<button class="btn btn-sm btn-primary rounded-0 mr-3" form="manage-user">ບັນທືກ</button>
				<a href="./?page=user/list" class="btn btn-sm btn-default border rounded-0" form="manage-user"><i class="fa fa-angle-left"></i> ຍົກເລີກ</a>
			</div>
		</div>
	</div>
</div>
<style>
	img#cimg {
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	function displayImg(input, _this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#cimg').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		} else {
			$('#cimg').attr('src', "<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] : '') ?>");
		}
	}
	$('#manage-user').submit(function(e) {
		e.preventDefault();
		start_loader()
		$.ajax({
			url: _base_url_ + 'classes/Users.php?f=save',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				console.log(resp)
				if (resp == 1) {
					location.href = './?page=user/list'
				} else {
					$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
					end_loader()
				}
			}
		})
	})
</script>