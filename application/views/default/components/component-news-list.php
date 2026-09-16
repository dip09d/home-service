<?php
$this->load->model('news/news_model');
?>
<div class="row">
	<?php if($news){foreach($news as $k => $v){ ?>
	<aside class="col-sm-6">
		<div class="row row-0">
			<div class="col-lg-6">
				<div class="card">
					<div class="card-image">
						<img src="<?php echo $this->news_model->get_thumbnail_image_url($v->thumbnail_photo); ?>" alt="" class="img-fluid">
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card details">  
					<div class="card-body">
						<p><a href="<?php echo base_url('news-'.$v->id.'-'.$v->slug); ?>"><i class="zmdi zmdi-account"></i> <?php echo $v->category;?></a> <span class="ms-3"><i class="zmdi zmdi-calendar"></i> <?php echo date(DATE_FORMAT, strtotime($v->date));?></span></p>
						<h4><?php echo $v->title;?></h4>
						<p><?php echo $v->short_dscr; ?> </p>
						<a href="<?php echo base_url('news-'.$v->id.'-'.$v->slug); ?>"><?php echo __('component_joblist_read_more','Read More');?> <i class="zmdi zmdi-trending-flat"></i></a>
					</div>
				</div>
			</div>
		</div>            	                
	</aside>
	<?php } } ?>
</div>