<!doctype html>
<html>
  <head>
    <title>Love 9</title>
    <meta charset="utf-8">
    <meta name="viewport" content="minimum-scale=1, initial-scale=1, width=device-width">
    <script type="text/javascript" src="<?php echo JS;?>jquery-3.7.0.min.js"></script>
  </head> 
  <body>
  <?php 
	 $this->config->load('pusher');
	 $pusher_api_key = $this->config->item('pusher_api_key');
	 $pusher_cluster = $this->config->item('pusher_cluster');
	 ?>
	  
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    // Enable logging for debugging
    Pusher.logToConsole = false;
    var pusher = new Pusher('<?php echo$pusher_api_key;?>',
	 {
      cluster: '<?php echo $pusher_cluster?>',
		  authEndpoint: "<?php echo base_url('/app/pusher/auth')?>",
    });
    // USER ID of the logged-in user

	  var endcall = pusher.subscribe("end-video-call");
    endcall.bind("end_call", function(data) {
        console.log("Message Received:", data);
        // alert('pusher received'+'<?php echo $meetingdtl['id']?>');
        if(data.req_id=='<?php echo $meetingdtl['id']?>'){
          window.close();
        }
    });

    </script>
  <!-- The embed's <iframe> will replace this <div> tag. -->
  <div id="myembed"></div>
<?php if($this->input->get('win') && $this->input->get('win')=='close'){?>
<script>window.close();</script>
  <?php }else{?>
  <script>
  var script = document.createElement("script");
  script.type = "text/javascript";

  script.addEventListener("load", function (event) {
    const config = {
      theme: "DEFAULT", // DARK || LIGHT || DEFAULT
      name: "test",
      meetingId: "<?php echo $meetingId;?>",
      apiKey: "<?php echo $apiKey;?>",

      containerId: null,

      micEnabled: true,
      webcamEnabled: false,
      participantCanToggleSelfWebcam: true,
      participantCanToggleSelfMic: true,

      chatEnabled: false,
      screenShareEnabled: false,
      redirectOnLeave: "<?php echo base_url().uri_string();?>?win=close",

      branding: {
        enabled: true,
        logoURL: "<?php echo IMAGE;?>logo.png",
        name: "",
        poweredBy: false,
      },
      waitingScreen: {
        imageUrl: "<?php echo IMAGE;?>logo.png",
        text: "Creating  a Meeting Room",
      },
      joinScreen: {
        visible: true,
        //title: "Daily scrum",
        //meetingUrl: "customURL.com",
      },
      /*

     Other Feature Properties
      
      */
    };

    const meeting = new VideoSDKMeeting();
    meeting.init(config);
    
  });

  script.src =
    "https://sdk.videosdk.live/rtc-js-prebuilt/0.3.40/rtc-js-prebuilt.js";
  document.getElementsByTagName("head")[0].appendChild(script);

  /* var pingInterval = setInterval(() => {
        $.get("<?php echo base_url('/app/video_call_ping')?>", { chatroom_id: '<?php echo $meetingdtl['channel_id']?>',request_id: '<?php echo $meetingdtl['id']?>'}, function(res){
          
        },'JSON');
    }, 60000);

    window.addEventListener("beforeunload", function () {
      clearInterval(pingInterval);
       $.get("<?php echo base_url('/app/end_call')?>", { chatroom_id: '<?php echo $meetingdtl['channel_id']?>',request_id: '<?php echo $meetingdtl['id']?>'}, function(res){
          
        },'JSON');
      });
      
      pingInterval */
</script>


    
    
<?php }?>
</body>
</html>