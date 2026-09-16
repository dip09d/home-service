 <?php
$this->load->model('admin_notification/admin_notification_model'); 
$admin_detail = get_session('admin_detail');
$menu_list = $this->permission_model->getUserMenu();
$uri_segment = uri_string();
$admin_notification_count = $this->admin_notification_model->getUnreadCount();
?>
 <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar sidebar-light-primary">
    <a class="brand-link">
      <img src="<?php echo ADMIN_IMAGES;?>logo.png" alt="<?php echo get_setting('site_title');?>" class="brand-image" style="opacity: .8" height="48" />
    </a>
    <!-- sidebar: style can be found in sidebar.less -->
    <div class="sidebar">
      
      <!-- Sidebar user panel
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php // echo ADMIN_IMAGES;?>avatar5.png" class="img-circle" alt="User Image">
        </div>
        <div class="info">
          <p class="mb-0"><?php // echo $admin_detail['full_name']; ?></p>
        </div>
      </div>
       -->
      
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form" hidden>
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      
      <!-- Sidebar Menu -->
      <nav class="mt-2">
      <!-- <ul class=" sidebar-menu">-->
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">        
		<li class="nav-item">
          <a href="<?php echo base_url('dashboard')?>" class="nav-link <?php echo $uri_segment == 'dashboard' ? 'active' : '';?>">
           <i class="icon-material-outline-dashboard"></i> <span>Dashboard</span>
           <small class="badge badge-success right">Hot</small>            
          </a>
        </li>
		<li class="nav-item">
          <a href="<?php echo base_url('admin_notification/list_record')?>" class="nav-link <?php echo $uri_segment == 'admin_notification/list_record' ? 'active' : '';?>">
           <i class="icon-feather-bell"></i> <span>Notification</span>
           <small class="badge badge-danger right"><?php echo $admin_notification_count; ?></small>   
          </a>
        </li>
				
		<?php if($menu_list){foreach($menu_list  as $k => $v){ 
		$childs = $v['child'];
		$isactive = '';
		$icon_class = null;
		if(!empty($v['style_class'])){
			if(stripos($v['style_class'], 'fa-') !== FALSE){
				$icon_class = 'fa '.$v['style_class'];
			}else{
				$icon_class = $v['style_class'];
			}
		}else{
			$icon_class = 'fa fa-bars';
		}
		foreach($childs as $c){
			if($uri_segment == $c['url']){
				$isactive = 'active';
				break;
			}
		}
		?>
		<li class="nav-item">
          <a href="#" class="nav-link <?php echo $isactive; ?>">
            <i class="<?php echo $icon_class; ?>"></i>
            <span><?php echo $v['name'];?></span>           
            <i class="icon-line-awesome-angle-right right"></i>            
          </a>
          <ul class="nav nav-treeview">
			<?php if($childs){foreach($childs as $key => $child){ ?>
            <li class="nav-item">
            <a href="<?php echo base_url($child['url']);?>" class="nav-link <?php echo ($uri_segment == $child['url']) ? 'active' : '';?>"><i class="icon-line-awesome-check"></i> <?php echo $child['name']; ?></a></li>
			<?php } } ?>
          </ul>
        </li>
		<?php } } ?>
        	<li class="nav-item">
          <a href="#" class="nav-link <?php echo (strpos($uri_segment, 'message') === 0) ? 'active' : ''; ?>">
            <i class="fa fa-comments"></i>
            <span>Message Board</span>           
            <i class="icon-line-awesome-angle-right right"></i>            
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('message/list_record'); ?>" class="nav-link <?php echo (strpos($uri_segment, 'message') === 0) ? 'active' : ''; ?>"><i class="icon-line-awesome-check"></i> Message Board</a>
            </li>
          </ul>
        </li>
      </ul>
      </nav>
    </div>
	
	
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->