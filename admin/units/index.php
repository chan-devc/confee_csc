 <style>
     .user-avatar {
         width: 3rem;
         height: 3rem;
         object-fit: scale-down;
         object-position: center center;
     }
 </style>

 <div class="card card-outline rounded-0 card-navy">
     <div class="card-header">
         <h3 class="card-title">ຫົວຫນ່ວຍ</h3>
         <div class="card-tools">
             <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> ສ້າງໃຫມ່</a>
         </div>

     </div>
     <div class="card-body">
         <div class="container-fluid">
             <table class="table table-hover table-striped table-bordered" id="list">
                 <thead>
                     <tr>
                         <th>#</th>
                         <th>ຫົວຫນ່ວຍ</th>
                         <th>ສະຖານະ</th>
                         <th>ການຈັດການ</th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php
                        $i = 1;
                        $qry = $conn->query("SELECT * from `unit` order by `name` asc ");
                        while ($row = $qry->fetch_assoc()) :
                        ?>
                         <tr>
                             <td class="text-center"><?php echo $i++; ?></td>
                             <td><?php echo $row['name'] ?></td>
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
                                     <!-- <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> ເບິ່ງຂໍ້ມູນ</a>
                                     <div class="dropdown-divider"></div> -->
                                     <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> ແກ້ໄຂຂໍ້ມູນ</a>
                                     <!-- <div class="dropdown-divider"></div>
                                     <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> ລົບ</a> -->
                                 </div>
                             </td>
                         </tr>
                     <?php endwhile; ?>
                 </tbody>
             </table>
         </div>
     </div>
 </div>
 <script>
     $(document).ready(function() {
         $('#create_new').click(function() {
             uni_modal("<i class='fa fa-plus'></i> ເພີ່ມຫົວຫນ່ວຍໃຫມ່", "units/manage_unit.php")
         })
         $('.edit_data').click(function() {
             uni_modal("<i class='fa fa-edit'></i> ອັບເດດຂໍ້ມູນຫົວຫນ່ວຍ", "units/manage_unit.php?id=" + $(this).attr('data-id'))
         })


         $('.table').dataTable({
             columnDefs: [{
                 orderable: false,
                 targets: [1]
             }],
             order: [0, 'asc']
         });
         $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
     });
 </script>