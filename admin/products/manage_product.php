<?php

require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
	$qry = $conn->query("SELECT * from `product_list` where id = '{$_GET['id']}' ");
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_assoc() as $k => $v) {
			$$k = $v;
		}
	}
}
if (isset($_GET['branch_id']) && $_GET['branch_id'] > 0) {
	$qbranch = $conn->query("SELECT * FROM branch where status = 1 and id = '{$_GET['branch_id']}'");
	$branchs = array();
	while ($row = $qbranch->fetch_assoc()) {
		$branchs[] = $row;
	}
}
?>
<div class="container-fluid">
	<form action="" id="product-form">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<input type="hidden" name="user_id" value="<?php echo isset($_SESSION["userdata"]["id"]) ? $_SESSION["userdata"]["id"] : '' ?>">
		<input type="hidden" name="last_price" value="<?php echo isset($price) ? $price : '' ?>">
		<input type="hidden" name="last_price2" value="<?php echo isset($price2) ? $price2 : '' ?>">


		<?php if (isset($_GET['branch_id'])) : ?>
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
			<label for="category_id" class="control-label">ຫມວດ</label>
			<select name="category_id" id="category_id" class="form-control form-control-sm rounded-0" required>
				<option value="" disabled <?= !isset($category_id) ? "selected" : "" ?>></option>
				<?php

				$qry = $conn->query("SELECT * FROM `category_list` where delete_flag = 0 and `status` = 1 and branch_id=" . $_GET['branch_id'] . " order by `name` asc");
				while ($row = $qry->fetch_array()) :
				?>
					<option value="<?= $row['id'] ?>" <?php echo isset($category_id) && $category_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="code" class="control-label">ລະຫັດສິນຄ້າ</label>
			<input type="text" name="code" id="code" class="form-control form-control-sm rounded-0" value="<?php echo isset($code) ? $code : ''; ?>" required />
		</div>
		<div class="form-group">
			<label for="name" class="control-label">ຊື່ສິນຄ້າ</label>
			<input type="text" name="name" id="name" class="form-control form-control-sm rounded-0" value="<?php echo isset($name) ? $name : ''; ?>" required />
		</div>
		<div class="form-group">
			<label for="description" class="control-label">ຄຳອະທິບາຍ</label>
			<textarea type="text" name="description" id="description" class="form-control form-control-sm rounded-0" required><?php echo isset($description) ? $description : ''; ?></textarea>
		</div>
		<div class="form-group">
			<label for="unit_id" class="control-label">ຫົວຫນ່ວຍ</label>
			<select name="unit_id" id="unit_id" class="form-control form-control-sm rounded-0" required>
				<?php
				$qry = $conn->query("SELECT * FROM `unit` where `status` = 1 order by `name` asc");
				while ($row = $qry->fetch_array()) :
				?>
					<option value="<?= $row['id'] ?>" <?php echo isset($unit_id) && $unit_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="price" class="control-label">ລາຄາ</label>
			<input type="number" name="price" id="price" class="form-control form-control-sm rounded-0 text-right" value="<?php echo isset($price) ? $price : ''; ?>" required />
		</div>
		<div class="form-group">
			<label for="price" class="control-label">ລາຄາພະນັກງານ</label>
			<input type="number" name="price2" id="price2" class="form-control form-control-sm rounded-0 text-right" value="<?php echo isset($price2) ? $price2 : ''; ?>" required />
		</div>
		<div class="form-group">
			<label for="status" class="control-label">ສະຖານະ</label>
			<select name="status" id="status" class="form-control form-control-sm rounded-0" required>
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>ນຳໃຊ້</option>
				<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>ປິດການນຳໃຊ້</option>
			</select>
		</div>
		<div class="form-group">
			<label for="sort" class="control-label">ລຳດັບ</label>
			<input type="number" name="sort" id="sort" class="form-control form-control-sm rounded-0 text-right" value="<?php echo isset($sort) ? $sort : ''; ?>" required />
		</div>
		<div class="form-group">
			<label for="" class="control-label">ຮູບ</label>
			<div class="custom-file">
				<input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))" accept="image/png, image/jpeg">
				<label class="custom-file-label" for="customFile">ເລືອກຮູບ</label>
			</div>
		</div>
		<div class="form-group d-flex justify-content-center">
			<img src="<?php echo validate_image(isset($meta['image_path']) ? $meta['image_path'] : $image_path) ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
		</div>
	</form>
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
			$('#cimg').attr('src', "<?php echo validate_image(isset($meta['image_path']) ? $meta['image_path'] : '') ?>");
		}
	}

	$(document).ready(function() {
		$('#uni_modal').on('shown.bs.modal', function() {
			$('#category_id').select2({
				placeholder: "Please select here",
				width: '100%',
				dropdownParent: $('#uni_modal'),
				containerCssClass: 'form-control form-control-sm rounded-0'
			})
		})



		$('#product-form').submit(function(e) {
			e.preventDefault();
			var _this = $(this)
			console.log(_this.serialize())
			$('.err-msg').remove();
			start_loader();
			$.ajax({
				url: _base_url_ + "classes/Master.php?f=save_product",
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
						$("html, body,.modal").scrollTop(0);
						end_loader()
					} else {
						alert_toast("An error occured", 'error');
						end_loader();
					}
				}
			})
		})

	})
</script>