<!-- css -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>


<!-- js -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">



<!-- Control -->
<script>
  $(document).ready(function() {
    function show_data() {
      $.ajax({
        url: '<?= site_url('admin/menu') ?>',
        type: 'post',
        dataType: 'json',
        success: function(data) {
          var menu = ''
          for (var i = 0; i < data.length; i++) {
            var sub = '';
            for (var j = 0; j < data[i].submenu.length; j++) {
              submenu = '<li class="nav-item ml-2" data-url="' + data[i].submenu[j].url + '">' +
              '<a href="#' + data[i].submenu[j].url + '" class="nav-link">' +
              '<i class="' + data[i].submenu[j].icon + ' nav-icon"></i>' +
              '<p>' + data[i].submenu[j].title + '</p>' +
              '</a>' +
              '</li>';
              sub += submenu;
            }
            menu += '<li class="nav-item has-treeview">' +
            '<a href="#" class="nav-link">' +
            '<i class="nav-icon ' + data[i].icon + '"></i>' +
            '<p>' +
            data[i].title +
            '<i class="right fas fa-angle-left"></i>' +
            '</p>' +
            '</a>' +
            '<ul class="nav nav-treeview submenu" >' + sub + '</ul>' +
            '</li>';
          }
          $('#menu').html(menu);
          $('.nav-link').click(function() {
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
          });
          // $('.submenu').on('click', '.nav-item', function() {
          //   url = $(this).data('url');
          //   $('#show_data').load('<?= site_url() ?>' + '/' + url);
          // });
          $('.submenu').on('click', 'li', function() {
            link = $(this).data('url');
            $.ajax({
              url: '<?= site_url() ?>' + link,
              type: 'get',
              success: function(data) {
                $('#show_data').html(data);
              },

            })
          });
        }
      });
    }
    $('#data').on('click',
      '.cek',
      function() {
        roleId = $(this).data('role_id');
        moduleId = $(this).data('module_id');
        moduleMethod = $(this).data('module_method');
        $.ajax({
          url: '<?= site_url('permission/aksi') ?>',
          type: 'post',
          data: {
            role_id: roleId,
            module_id: moduleId,
            module_method: moduleMethod
          },
          dataType: 'json',
          success: function(result) {
            show_data();
            if (result == true) {
              toastr['success']('Permission Diberikan')
            } else {
              toastr['error']('Permission Dihapus')
            }

          }
        });
      });
  });
</script>