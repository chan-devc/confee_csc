</style>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-dark elevation-4 sidebar-no-expand">
  <!-- Brand Logo -->
  <a href="<?php echo base_url ?>admin" class="brand-link bg-blue text-sm">
    <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="Store Logo" class="brand-image img-circle elevation-3" style="opacity: .8;width: 1.5rem;height: 1.5rem;max-height: unset">
    <span class="brand-text font-weight-light" style="font-family: 'Phetsarath OT';"><?php echo $_settings->info('short_name') . " ~ ສາຂາ" . $_SESSION['userdata']['branch_name'] ?> </span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
    <div class="os-resize-observer-host observed">
      <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
    </div>
    <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
      <div class="os-resize-observer"></div>
    </div>
    <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 646px;"></div>
    <div class="os-padding">
      <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
        <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
          <!-- Sidebar user panel (optional) -->
          <div class="clearfix"></div>
          <!-- Sidebar Menu -->
          <nav class="mt-4" style="font-family: 'Phetsarath OT';">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">
              <li class="nav-item dropdown">
                <a href="./" class="nav-link nav-home">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
              </li>
              <?php if ($_settings->userdata('type') != 3) : ?>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=products&branch_id=<?php echo $_SESSION["userdata"]["branch_id"] ?>" class="nav-link nav-products">
                    <i class="nav-icon fas fa-mug-hot"></i>
                    <p>
                      ລາຍການສິນຄ້າ
                    </p>
                  </a>
                </li>
              <?php else : ?>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=sales/manage_sale&branch_id=<?php echo $_SESSION["userdata"]["branch_id"] ?>" class="nav-link nav-sales_manage_sale">
                    <i class="nav-icon fas fa-plus"></i>
                    <p>
                      ສ້າງການຂາຍ
                    </p>
                  </a>
                </li>
              <?php endif; ?>
              <li class="nav-item dropdown">
                <a href="<?php echo base_url ?>admin/?page=sales&branch_id=<?php echo $_SESSION["userdata"]["branch_id"] ?>" class="nav-link nav-sales">
                  <i class="nav-icon fas fa-file-invoice"></i>
                  <p>
                    ລາຍການຂາຍ
                  </p>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a href="<?php echo base_url ?>admin/?page=queue&branch_id=<?php echo $_SESSION["userdata"]["branch_id"] ?>" class="nav-link nav-queue">
                  <i class="nav-icon fas fa-file-invoice"></i>
                  <p>
                    ຄິວ
                  </p>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a href="<?php echo base_url ?>admin/?page=promotions&branch_id=<?php echo $_SESSION["userdata"]["branch_id"] ?>" class="nav-link nav-promotions">
                  <i class="nav-icon fas fa-file-invoice"></i>
                  <p>
                    Promotion
                  </p>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a href="<?php echo base_url ?>admin/?page=reports&branch_id=<?php echo $_SESSION["userdata"]["branch_id"] ?>" class="nav-link nav-reports">
                  <i class="nav-icon fas fa-calendar-day"></i>
                  <p>
                    ລາຍງານຍອດຂາຍລາຍວັນ
                  </p>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a href="<?php echo base_url ?>admin/?page=reports2&branch_id=<?php echo $_SESSION["userdata"]["branch_id"] ?>" class="nav-link nav-reports2">
                  <i class="nav-icon fas fa-calendar-day"></i>
                  <p>
                    ລາຍງານຍອດຂາຍລາຍວັນ+ສິນຄ້າ
                  </p>
                </a>
              </li>
              <?php if ($_settings->userdata('type') == 1) : ?>
                <li class="nav-header">Maintenance</li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=categories&branch_id=<?php echo $_SESSION["userdata"]["branch_id"] ?>" class="nav-link nav-categories">
                    <i class="nav-icon fas fa-th-list"></i>
                    <p>
                      ລາຍການຫມວດສິນຄ້າ
                    </p>
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=units" class="nav-link nav-units">
                    <i class="nav-icon fas fa-th-list"></i>
                    <p>
                      ຫົວຫນ່ວຍສິນຄ້າ
                    </p>
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=user/list" class="nav-link nav-user/list">
                    <i class="nav-icon fas fa-users-cog"></i>
                    <p>
                      ຜູ້ໃຊ້ລະບົບ
                    </p>
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=clients&branch_id=<?php echo $_SESSION["userdata"]["branch_id"] ?>" class="nav-link nav-clients">
                    <i class="nav-icon fas fa-users-cog"></i>
                    <p>
                      ລູກຄ້າ
                    </p>
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=system_info" class="nav-link nav-system_info">
                    <i class="nav-icon fas fa-tools"></i>
                    <p>
                      ການຕັ້ງຄ່າລະບົບ
                    </p>
                  </a>
                </li>
              <?php endif; ?>
            </ul>
          </nav>
          <!-- /.sidebar-menu -->
        </div>
      </div>
    </div>
    <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
      <div class="os-scrollbar-track">
        <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
      </div>
    </div>
    <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
      <div class="os-scrollbar-track">
        <div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px);"></div>
      </div>
    </div>
    <div class="os-scrollbar-corner"></div>
  </div>
  <!-- /.sidebar -->
</aside>
<script>
  $(document).ready(function() {
    var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
    var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
    page = page.replace(/\//g, '_');
    console.log(page)

    if ($('aside.main-sidebar .nav-link.nav-' + page).length > 0) {
      $('aside.main-sidebar .nav-link.nav-' + page).addClass('active')
      if ($('aside.main-sidebar .nav-link.nav-' + page).hasClass('tree-item') == true) {
        $('aside.main-sidebar .nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
        $('aside.main-sidebar .nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
      }
      if ($('aside.main-sidebar .nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
        $('aside.main-sidebar .nav-link.nav-' + page).parent().addClass('menu-open')
      }

    }
    $('aside.main-sidebar .nav-link.active').addClass('bg-blue')
  })
</script>