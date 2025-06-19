<?php

require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT p.*, c.name as `category` from `product_list` p inner join category_list c on p.category_id = c.id where p.id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }


    $qry_log = $conn->query("SELECT l.*,u.firstname,u.lastname FROM product_list_log l left join users u on l.user_id = u.id where product_id = '{$_GET['id']}' order by l.id desc ");
    $log = array();
    if ($qry_log->num_rows > 0) {
        while ($row = $qry_log->fetch_assoc()) {
            $log[] = $row;
        }
    }

    // print_r($log);
}
?>
<style>
    #uni_modal .modal-footer {
        display: none;
    }

    img#cimg {
        height: 15vh;
        width: 15vh;
        object-fit: cover;
        border-radius: 100% 100%;
    }
</style>
<div class="container-fluid">
    <dl>
        <dt class="text-muted">ຫມວດ</dt>
        <dd class="pl-4"><?= isset($category) ? $category : "" ?></dd>
        <dt class="text-muted">ລະຫັດສິນຄ້າ</dt>
        <dd class="pl-4"><?= isset($code) ? $code : "" ?></dd>
        <dt class="text-muted">ຊື່ສິນຄ້າ</dt>
        <dd class="pl-4"><?= isset($name) ? $name : "" ?></dd>
        <dt class="text-muted">ຄຳອະທິບາຍ</dt>
        <dd class="pl-4"><?= isset($description) ? $description : '' ?></dd>
        <dt class="text-muted">ລາຄາ</dt>
        <dd class="pl-4"><?= isset($price) ? format_num($price) : '' ?></dd>
        <dt class="text-muted">ສະຖານະ</dt>
        <dd class="pl-4">
            <?php if ($status == 1) : ?>
                <span class="badge badge-success px-3 rounded-pill">ນຳໃຊ້</span>
            <?php else : ?>
                <span class="badge badge-danger px-3 rounded-pill">ປິດການນຳໃຊ້</span>
            <?php endif; ?>
        </dd>
        <dt class="text-muted">
            <div class="form-group d-flex justify-content-center">
                <img src="<?php echo validate_image(isset($meta['image_path']) ? $meta['image_path'] : $image_path) ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
            </div>
        </dt>

        <?php if (count($log) > 0) :  ?>
            <dd>ປະຫວັດການແກ້ໄຂລາຄາ</dd>
            <?php foreach ($log as $lg) { ?>

                <dd><?php echo "- ວັນທີ " . date("d-m-Y H:i:s", strtotime($lg['date_created'])) . " {$lg['description']} ໂດຍ {$lg['firstname']} {$lg['lastname']}" ?></dd>
            <?php } ?>
        <?php endif; ?>
    </dl>
    <div class="clear-fix my-3"></div>
    <div class="text-right">
        <button class="btn btn-sm btn-dark bg-gradient-dark btn-flat" type="button" data-dismiss="modal"><i class="fa fa-times"></i> ປິດ</button>
    </div>
</div>