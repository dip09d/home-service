<?php $currency=get_setting('site_currency');?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
      <div class="col-sm-6 col-12">
      <h1>
         <?php echo $main_title ? $main_title : '';?>
      </h1>
	  </div>
      <div class="col-sm-6 col-12"><?php echo $breadcrumb ? $breadcrumb : '';?></div>
	</div>
    </section>

    <!-- Main content -->
    <section class="content">
      
		<!-- working area -->
		
		 <!-- Small boxes (Stat box) -->
      <div class="row">
        
        <!-- ./col -->
        <div class="col-lg-3 col-sm-6 col-12">
          <!-- small box -->
          <div class="small-box bg-purple">
            <div class="inner">
              <h3><?php echo $withdrawn_request_count; ?></h3>

              <p>Withdrawn Request</p>
            </div>
            <div class="icon">
              <i class="ion icon-feather-dollar-sign"></i>
            </div>
            <a href="<?php echo base_url('wallet/withdrawn_list'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      
        <!-- ./col -->
        <!-- ./col -->
        <div class="col-lg-3 col-sm-6 col-12">
          <!-- small box -->
          <div class="small-box bg-teal">
            <div class="inner">
              <h3><?php echo $unread_notification_count; ?></h3>
              <p>Unread notification</p>
            </div>
            <div class="icon">
              <i class="ion icon-feather-bell"></i>
            </div>
            <a href="<?php echo base_url('admin_notification/list_record'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <!-- ./col -->
        <div class="col-lg-3 col-sm-6 col-12">
          <!-- small box -->
          <div class="small-box bg-cyan">
            <div class="inner">
              <h3><?php echo $users_freelancer_count; ?></h3>
              <p>Total Customer</p>
            </div>
            <div class="icon">
              <i class="ion icon-feather-users"></i>
            </div>
            <a href="<?php echo base_url('member/list_record?&panel_open=1'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <!-- ./col -->
        <div class="col-lg-3 col-sm-6 col-12">
          <!-- small box -->
          <div class="small-box bg-pink">
            <div class="inner">
              <h3><?php echo $users_employer_count; ?></h3>
              <p>Total Worker</p>
            </div>
            <div class="icon">
              <i class="ion icon-material-outline-account-circle"></i>
            </div>
            <a href="<?php echo base_url('worker/list_record'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <!-- ./col -->
        <div class="col-lg-3 col-sm-6 col-12">
          <!-- small box -->
          <div class="small-box bg-gray">
            <div class="inner">
              <h3><?php echo $contact_request_count; ?></h3>
              <p>Contact Request</p>
            </div>
            <div class="icon">
              <i class="ion icon-feather-phone"></i>
            </div>
            <a href="<?php echo base_url('contact/list_record'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        
      
        <!-- ./col -->
        <div class="col-lg-3 col-sm-6 col-12">
          <!-- small box -->
          <div class="small-box bg-indigo">
            <div class="inner">
              <h3><?php echo $review_count; ?></h3>
              <p>Reviews</p>
            </div>
            <div class="icon">
              <i class="ion icon-feather-star"></i>
            </div>
            <a href="<?php echo base_url('review/list_record'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        
                <!-- ./col -->
        <div class="col-lg-3 col-sm-6 col-12">
          <!-- small box -->
          <div class="small-box bg-cyan">
            <div class="inner">
              <h3><?php echo $invoice_count; ?></h3>
              <p>Invoice</p>
            </div>
            <div class="icon">
              <i class="ion icon-feather-file"></i>
            </div>
            <a href="<?php echo base_url('invoice/list_record'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        

      </div>
      <!-- /.row -->
	  
	  
		<div class="row">
		  <div class="col-sm-6 col-12">
			<!-- solid sales graph -->
			  <div class="box box-solid bg-teal-gradient">
				<div class="box-header">
				  <i class="fa fa-th"></i>

				  <h3 class="box-title">Service Graph</h3>

				</div>
				<div class="box-body border-radius-none">
				  <div class="chart" id="line-chart" style="height: 250px;"></div>
				</div>
				<!-- /.box-body -->
			  </div>
			  <!-- /.box -->
		  </div>
		  
		  <div class="col-sm-6 col-12">
				<div class="box box-solid">
					<div class="box-header">
					  <i class="fa fa-th"></i>

					  <h3 class="box-title">Member Graph</h3>

					  
					</div>
					<div class="box-body border-radius-none">
					  <div class="chart" id="revenue-chart" style="height: 250px;"></div>
					</div>
					<!-- /.box-body -->
				</div>
		  </div>
		  
		</div>
    <div class="row">
		  <div class="col-sm-6 col-12">
			<!-- solid sales graph -->
			  <div class="box box-solid bg-pink">
				<div class="box-header">
				  <i class="fa fa-th"></i>

				  <h3 class="box-title">Add Fund Graph</h3>

				</div>
				<div class="box-body border-radius-none">
				  <div class="chart" id="addfund-chart" style="height: 250px;"></div>
				</div>
				<!-- /.box-body -->
			  </div>
			  <!-- /.box -->
		  </div>
		  
		  <div class="col-sm-6 col-12">
				<div class="box box-solid bg-purple">
					<div class="box-header">
					  <i class="fa fa-th"></i>

					  <h3 class="box-title">Profit Graph</h3>

					  
					</div>
					<div class="box-body border-radius-none">
					  <div class="chart" id="profit-chart" style="height: 250px;"></div>
					</div>
					<!-- /.box-body -->
				</div>
		  </div>
		  
		</div>
	  


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <link rel="stylesheet" href="<?php echo ADMIN_PLUGINS;?>morris.js/morris.css"type="text/css">
  <style>
  #line-chart .morris-hover.morris-default-style .morris-hover-point,
  #addfund-chart .morris-hover.morris-default-style .morris-hover-point,
  #profit-chart .morris-hover.morris-default-style .morris-hover-point
  {
    color: #000 !important;
  }
  </style>
<script src="<?php echo ADMIN_PLUGINS;?>raphael/raphael.min.js"></script>
<script src="<?php echo ADMIN_PLUGINS;?>morris.js/morris.min.js"></script>
<script src="<?php echo ADMIN_PLUGINS;?>jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script src="<?php echo ADMIN_PLUGINS;?>jquery-knob/dist/jquery.knob.min.js"></script>
<script src="<?php echo ADMIN_PLUGINS;?>moment/min/moment.min.js"></script>


<script>
var project_statistics = <?php echo json_encode($statics['project']);?>;
var member_statistics = <?php echo json_encode($statics['member']);?>;
var addfund_statistics = <?php echo json_encode($statics['addfund']);?>;
var profit_statistics = <?php echo json_encode($statics['profit']);?>;
var line = new Morris.Line({
    element          : 'line-chart',
    resize           : true,
	  data			 : project_statistics,
    xkey             : 'y',
    ykeys            : ['item1'],
    labels           : ['Project'],
    lineColors       : ['#efefef'],
    lineWidth        : 2,
    hideHover        : 'auto',
    gridTextColor    : '#fff',
    gridStrokeWidth  : 0.4,
    pointSize        : 4,
    pointStrokeColors: ['#efefef'],
    gridLineColor    : '#efefef',
    gridTextFamily   : 'Open Sans',
    gridTextSize     : 10,
  });
  
   /* Morris.js Charts */
  // Sales chart
  var area = new Morris.Area({
    element   : 'revenue-chart',
    resize    : true,
	  data	  : member_statistics,
    xkey      : 'y',
    ykeys     : ['item1', 'item2'],
    labels    : ['Worker','Customer'],
    lineColors: ['#a0d0e0', '#3c8dbc'],
    hideHover : 'auto',
  });

  var addfund = new Morris.Line({
    element          : 'addfund-chart',
    resize           : true,
	  data			 : addfund_statistics,
    xkey             : 'y',
    ykeys            : ['item1'],
    labels           : ['Total(<?php echo $currency;?>)'],
    lineColors       : ['#efefef'],
    lineWidth        : 2,
    hideHover        : 'auto',
    gridTextColor    : '#fff',
    gridStrokeWidth  : 0.4,
    pointSize        : 4,
    pointStrokeColors: ['#efefef'],
    gridLineColor    : '#efefef',
    gridTextFamily   : 'Open Sans',
    gridTextSize     : 10
  });
  var profit = new Morris.Line({
    element          : 'profit-chart',
    resize           : true,
	  data			 : profit_statistics,
    xkey             : 'y',
    ykeys            : ['item1'],
    labels           : ['Total(<?php echo $currency;?>)'],
    lineColors       : ['#efefef'],
    lineWidth        : 2,
    hideHover        : 'auto',
    gridTextColor    : '#fff',
    gridStrokeWidth  : 0.4,
    pointSize        : 4,
    pointStrokeColors: ['#efefef'],
    gridLineColor    : '#efefef',
    gridTextFamily   : 'Open Sans',
    gridTextSize     : 10,
  });
</script>