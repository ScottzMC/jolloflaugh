<!-- Footer
<footer class="footer container-fluid pl-30 pr-30">
  <div class="row">
    <div class="col-sm-5">
      <a href="<d?php echo site_url('home'); ?>" class="brand mr-30"><img src="<d?php echo base_url('asset/images/logo.png'); ?>" alt="logo"/></a>
    </div>
    <div class="col-sm-7 text-right">
      <p>2019 &copy; MCStitches</p>
    </div>
  </div>
</footer>
<!-- /Footer -->

<!-- JavaScript -->

  <!-- jQuery -->
  <script src="<?php echo base_url('vendors/bower_components/jquery/dist/jquery.min.js'); ?>"></script>

  <!-- Form Flie Upload Data JavaScript -->
		<script src="<?php echo base_url('dist/js/form-file-upload-data.js'); ?>"></script>

  <!-- Bootstrap Core JavaScript -->
  <script src="<?php echo base_url('vendors/bower_components/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>

  <!-- Summernote Plugin JavaScript -->
  <script src="<?php echo base_url('vendors/bower_components/summernote/dist/summernote.min.js'); ?>"></script>

  <!-- Summernote Wysuhtml5 Init JavaScript -->
  <script src="<?php echo base_url('dist/js/summernote-data.js'); ?>"></script>

	<!-- Bootstrap Wysuhtml5 Init JavaScript -->
	<script src="<?php echo base_url('dist/js/bootstrap-wysuhtml5-data.js'); ?>"></script>

<!-- Counter Animation JavaScript -->
<script src="<?php echo base_url('vendors/bower_components/waypoints/lib/jquery.waypoints.min.js'); ?>"></script>
<script src="<?php echo base_url('vendors/bower_components/Counter-Up/jquery.counterup.min.js'); ?>"></script>

<!-- Data table JavaScript -->
<script src="<?php echo base_url('vendors/bower_components/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('dist/js/productorders-data.js'); ?>"></script>

<!-- Slimscroll JavaScript -->
<script src="<?php echo base_url('dist/js/jquery.slimscroll.js'); ?>"></script>

<!-- Fancy Dropdown JS -->
<script src="<?php echo base_url('dist/js/dropdown-bootstrap-extended.js'); ?>"></script>

<script src="<?php echo base_url('vendors/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js'); ?>"></script>
<script src="<?php echo base_url('dist/js/skills-counter-data.js'); ?>"></script>

<!-- Init JavaScript -->
<script src="<?php echo base_url('dist/js/init.js'); ?>"></script>
<script src="<?php echo base_url('dist/js/dashboard3-data.js'); ?>"></script>
<script src="<?php echo base_url('dist/js/check-length.js'); ?>"></script>

<script type="text/javascript">

        //edtor summernote
        $('#editordata').summernote({
          height: 300,
          toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['picture',['picture']]
          ],

          onImageUpload: function(files, editor, welEditable) {
            for (var i = files.lenght - 1; i >= 0; i--) {
            uploadFile(files[i], this);
            }
          }
        });

        $('#editorspecific').summernote({
          height: 300,
          toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['picture',['picture']]
          ],

          onImageUpload: function(files, editor, welEditable) {
            for (var i = files.lenght - 1; i >= 0; i--) {
            uploadFile(files[i], this);
            }
          }
        });

        $('#editorabout').summernote({
          height: 300,
          toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['picture',['picture']]
          ],

          onImageUpload: function(files, editor, welEditable) {
            for (var i = files.lenght - 1; i >= 0; i--) {
            uploadFile(files[i], this);
            }
          }
        });

        function uploadFile(file) {
          data = new FormData();
          data.append("file", file);

          $.ajax({
              data: data,
              type: "POST",
              url: "admin/product/add_product/saveFile", //replace with your url
              cache: false,
              contentType: false,
              processData: false,
              success: function(url) {
               console.log(url);
               $('#editordata').summernote("insertImage", url);
              }
          });
        }

</script>
