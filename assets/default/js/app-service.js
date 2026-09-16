;
var AppService = (function(){
	
	var service_url = null,
		service_register = {};
		
	
	var setUrl = function(url){
		service_url = url;
	};
	
	var onEvent = function(evt_name, fun){
		if(typeof service_register[evt_name] == 'undefined'){
			service_register[evt_name] = [fun];
		}else{
			service_register[evt_name].push(fun);
		}
		
	};
	
	var init = function(){
		if(!service_url){
			throw new Error('Missing Service URL');
		}
		var service = new EventSource(service_url);
		service.onmessage = function(e){
			if(e.data != '0'){
				var d = JSON.parse(e.data);
				for(var i in d){
					if(typeof service_register[i] != 'undefined'){
						var fun_arr = service_register[i],
							j = fun_arr.length;
						
						while(j--){
							fun_arr[j](d[i]);
						}
					}
					
				}
			}
		};
	};
	
	
	
	return {
		setUrl: setUrl,
		on: onEvent,
		init: init,
	}
	
	
})();