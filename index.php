<?php require_once('./config.php'); ?>
 <!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<?php require_once('inc/header.php') ?>
  <body class="layout-top-nav layout-fixed layout-navbar-fixed" style="height: auto;    background-color: azure;">
    <div class="wrapper">
     <?php require_once('inc/topBarNav.php') ?>
     <?php if($_settings->chk_flashdata('success')): ?>
      <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
      </script>
      <?php endif;?>    
     <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home';  ?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper pt-3" style="min-height: 567.854px;">
     
      <br>
    

<div class="banner_wrapper">
	<div class="next_arrow"></div>
	<div class="prev_arrow"></div>
	<div class="banner">
		<input type="radio" name="xx">
		<input type="radio" name="xx" id="x1">
		<label for="x3"></label>
		<div class="banner_box">
			<img src="images/pic1.jpg" alt="">
		</div>

		<input type="radio" name="xx">
		<input type="radio" name="xx" id="x2">
		<label for="x1"></label>
		<div class="banner_box">
			<img src="images/pic2.jpg" alt="">
		</div>

		<input type="radio" name="xx">
		<input type="radio" name="xx" id="x3">
		<label for="x2"></label>
		<div class="banner_box">
			<img src="images/pic3.jpg" alt="">
		</div>


	</div>
</div>


        <!-- Main content -->
        <section class="content ">
          <div class="container">
            <?php 
              if(!file_exists($page.".php") && !is_dir($page)){
                  include '404.html';
              }else{
                if(is_dir($page))
                  include $page.'/index.php';
                else
                  include $page.'.php';

              }
            ?>
          </div>
        </section>
        <!-- /.content -->
  <div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body">
        <div id="delete_content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal_right" role='dialog'>
    <div class="modal-dialog modal-full-height  modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="fa fa-arrow-right"></span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
              <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
              <img src="" alt="">
      </div>
    </div>
  </div>
      </div>
      <!-- /.content-wrapper -->
      <?php require_once('inc/footer.php') ?>
      <style>
        *,*:after,*:before{
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	-ms-box-sizing: border-box;
	box-sizing: border-box;
}
body{
	font-family: arial;
	font-size: 16px;
	background: #fff;
	margin: 0;
}
img{
	max-width: 100%;
}

.banner_wrapper{
	position: relative;
}
.next_arrow{
	position: absolute;
	right: 0;
	top: 0;
	width: 100px;
	height: 100%;
	z-index: 10;
	pointer-events: none;
	background: url('../images/next.png') center center no-repeat;
	background-size: 60%;
}
.prev_arrow{
	position: absolute;
	left: 0;
	top: 0;
	width: 100px;
	height: 100%;
	z-index: 10;
	pointer-events: none;
	background: url('../images/prev.png') center center no-repeat;
	background-size: 60%;
}
.banner{
	max-width: 100%;
	max-height: 100vh;
	overflow: hidden;
	position: relative;
}
.banner_box{
	position: absolute;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	opacity: 0;
	transition: all 0.3s ease-in-out;
}
.banner_box:first-of-type{
	position: relative;
	opacity: 1;
	z-index: 1;
}
input{
	position: absolute;
	right: 0;
	top: 0;
	width: 100px;
	height: 100%;
	cursor: pointer;
	opacity: 0;
}
label{
	position: absolute;
	left: 0;
	top: 0;
	width: 100px;
	height: 100%;
	cursor: pointer;
	opacity: 0;
}
input+input{
	right: auto;
	left: 0;
}
input:first-of-type,
label:first-of-type{
	z-index: 3;
}

input:checked{
	z-index: 0;
}
input:checked + input+label+.banner_box+input{
	z-index: 4
}
input:checked + input+label+.banner_box{
	z-index: 0;
	opacity: 0;
}
input:checked + input+label+.banner_box+input+input+label{
	z-index: 4;
}
input:checked + input+label+.banner_box+input+input+label+.banner_box{
	z-index: 2;
	opacity: 1;
}

input+input:checked + label{
	z-index: 4;
}
input+input:checked + label+.banner_box{
	z-index: 2;
	opacity: 1;
}





        </style>
  </body>
</html>
