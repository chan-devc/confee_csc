<?php

require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT  * from promotion p  where p.id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}



?>

<div class="container-fluid">
    <form action="" id="promotion-form">
        <!-- <input type="hidden" name="user_id" value="<?php $_SESSION["datauser"]["id"] ?>"> -->
        <input type="hidden" name="branch_id" value="<?php echo isset($_GET["branch_id"]) ? $_GET["branch_id"] : '' ?>">

        <div class="form-group">
            <label for="date_start">ວັນທີເລີ່ມຕົ້ນ</label>
            <input type="date" name="date_start" value="<?= $date ?>" class="form-control form-control-sm rounded-0" required>
        </div>

        <div class="form-group">
            <label for="date_end">ວັນທີສິ້ນສຸດ</label>
            <input type="date" name="date_end" value="<?= $date ?>" class="form-control form-control-sm rounded-0" required>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#promotion-form').submit(function(e) {
            e.preventDefault();
            var _this = $(this)
            $('.err-msg').remove();
            start_loader();

            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_promotion",
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