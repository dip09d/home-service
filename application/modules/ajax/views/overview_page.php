<section class="sub-banner">
	<h2 class="text-center">Overview</h2>
</section>
<section class="sec overview" id="overview">
	<div class="container">	
    <?php 
	//get_print($overview);
	if(!empty($overview)){  
			foreach($overview as $key => $val){
				if($key/2 == 0){
		
	?>
    		
				
	<?php } } } ?>
        
     
            
</div>    
</section>

 

<script type="text/javascript">
$('.collapse').on('shown.bs.collapse', function(){
$(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
}).on('hidden.bs.collapse', function(){
$(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
});
</script>