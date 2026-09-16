<div class="row">
<?php if($list){foreach($list as $k => $v){ 
$default_image = IMAGE.'project_2.jpg';
if(!$v->photos){
	$cover_image = $default_image;
}else{
	$img = $v->photos[0];
	$cover_image = $this->rental_model->getFileURL($img);
}
$detail_url = base_url('rental-detail-'.$v->rental_id);
?>
<aside class="col-sm-4">
<div class="card">
	<div class="card-image"><img src="<?php echo $cover_image;?>" alt="" /></div>
</div>
<div class="card details">   
	<div class="card-body">
	<h4><?php echo $v->title;?></h4>
	<p><?php echo $v->short_dscr;?></p>
	<a href="<?php echo $detail_url;?>" class="btn btn-border"><?php echo __('component_joblist_rent_now','Rent Now');?></a>
	</div>
</div>
</aside>
<?php } } ?>
</div>
