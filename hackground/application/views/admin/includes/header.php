<?php
$admin_detail = get_session('admin_detail');
?>
<!-- Site wrapper -->
<div class="wrapper">
  <header>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top">    
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <!--<li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>-->
    </ul>
	<ul class="navbar-nav ml-auto">
        <!--<li class="nav-item active">
			<a class="nav-link" href="#">Home</a>
        </li>-->
      	<li class="nav-item dropdown user user-menu">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="<?php echo ADMIN_IMAGES;?>avatar5.png" class="user-image" alt="<?php echo $admin_detail['full_name']; ?>'s image">
          <span class="hidden-xs"><?php echo $admin_detail['full_name']; ?></span>
        </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">            
          <a href="<?php echo SITE_URL;?>" class="dropdown-item" target="_blank">View site</a> 
          <div class="dropdown-divider"></div>            
          <a href="javascript:void(0)" class="dropdown-item" onclick="edit_profile()">Profile</a>
          <div class="dropdown-divider"></div>
          <a href="<?php echo base_url('login/logout')?>" class="dropdown-item">Sign out</a>          		          
        </div>            
	  </li>            
    </ul>
      <div class="navbar-custom-menu" hidden>
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu" hidden>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <img src="<?php echo ADMIN_IMAGES;?>avatar5.png" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <!-- end message -->
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu" hidden>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu" hidden>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo ADMIN_IMAGES;?>avatar5.png" class="user-image" alt="<?php echo $admin_detail['full_name']; ?>'s image">
              <span class="hidden-xs"><?php echo $admin_detail['full_name']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo ADMIN_IMAGES;?>avatar5.png" class="img-circle" alt="<?php echo $admin_detail['full_name']; ?>'s image">

                <p>
                 <?php echo $admin_detail['full_name']; ?>
                  <small></small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body" hidden>
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="javascript:void(0)" class="btn btn-default btn-flat" onclick="edit_profile()">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url('login/logout')?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li class="" hidden>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  
<script>

function edit_profile(){
	var url = '<?php echo base_url('admin_user/load_ajax_page?page=edit&id='.get_session('admin_id')); ?>';
	if($('#ajaxModal').length == 0){
		var html = '<div class="modal fade" id="ajaxModal"><div class="modal-dialog"><div class="modal-content"></div></div></div>';
		$('body').append(html);
		load_ajax_modal(url);
	}else{
		load_ajax_modal(url);
	}
}

</script>

<?php require_once('left-nav.php'); ?>