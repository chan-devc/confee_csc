<?php

require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `category_list` where id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
?>
<style>
    #uni_modal .modal-footer {
        display: none;
    }
</style>
<div class="container-fluid">
    <dl>
        <dt class="text-muted">ປະເພດ</dt>
        <dd class="pl-4"><?= isset($category_type) && $category_type == 1 ? "ເຄື່ອງດື່ມກາເຟ" : "ສ່ວນປະກອບກາເຟຫລືເຄື່ອງໃຊ້ຕ່າງໆ" ?></dd>
        <dt class="text-muted">ຫມວດສິນຄ້າ</dt>
        <dd class="pl-4"><?= isset($name) ? $name : "" ?></dd>
        <dt class="text-muted">ຄຳອະທິບາຍ</dt>
        <dd class="pl-4"><?= isset($description) ? $description : '' ?></dd>
        <dt class="text-muted">ສະຖານະ</dt>
        <dd class="pl-4">
            <?php if ($status == 1) : ?>
                <span class="badge badge-success px-3 rounded-pill">ນຳໃຊ້</span>
            <?php else : ?>
                <span class="badge badge-danger px-3 rounded-pill">ປິດການນຳໃຊ້</span>
            <?php endif; ?>
        </dd>
    </dl>
    <div class="clear-fix my-3"></div>
    <div class="text-right">
        <button class="btn btn-sm btn-dark bg-gradient-dark btn-flat" type="button" data-dismiss="modal"><i class="fa fa-times"></i> ປິດ</button>
    </div>
</div>