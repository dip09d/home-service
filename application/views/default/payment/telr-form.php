<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
       swal({
	      type: 'pending',
	      text: '<?php D(__('popup_telr_processing_payment',"Processing Payment"));?>',
	      timer: 3000,
	      onOpen: function(){
	      	swal.showLoading();
	      	//$('#pay').click();
	      }
	      }).then(function(){
	      	window.location.href="<?php D($redirect)?>";
	      	
    	})

</script>
<section style="background-color: #2c3e50;min-height: 200px">
	
</section>