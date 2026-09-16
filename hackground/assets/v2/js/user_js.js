function setCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}

function deleteCookie(name, path, domain) {

  if (getCookie(name)) document.cookie = name + '=' +

    ((path) ? ';path=' + path : '') +

    ((domain) ? ';domain=' + domain : '') +

    ';expires=Thu, 01-Jan-1970 00:00:01 GMT';

}

function upload_file(field_name , files , dirname , callBack , ele){
	if(dirname == undefined){
		dirname = 'uploads';
	}
	if(typeof ele == 'undefined'){
		ele = '';
	}
	if(callBack == undefined){
		callBack = defaultCallback;
	}
	var formdata = new FormData();
	formdata.append(field_name , files);
	formdata.append('dirname' , dirname);
	formdata.append('field' , field_name);
	$.ajax({
		url: VPATH + 'user/upload_img',
		data: formdata,
		processData: false,
		contentType: false,
		type: 'POST',
		beforeSend : function(){
			if(ele != ''){
				$(ele).hide();
			}
			$('#loading').show();
			$('#loading').html('<img src="'+VPATH+"assets/app_assets/default/images/loading.gif"+'" class="img-circle"/>');
		},
		complete : function(){
			$('#loading').hide();
			$('#loading').html('');
			if(ele != ''){
				$(ele).show();
			}
		},
		dataType: 'json',
		error: function (xhr, status, errorThrown) {
			alert(xhr.status + " : " + xhr.responseText);
       
		},
		success: function (res) {
			callBack(res);
		}
	});		
}
		
function delete_file(file , dir , callBack){
	if(file && dir){
		if(callBack == undefined){
			callBack = defaultCallback;
		}
		$.ajax({
			url: VPATH + 'user/del_img',
			data: {'file' : file , 'dir' : dir},
			type: 'POST',
			dataType: 'json',
			success: function (res) {
				callBack(res);
			}
		});
	}
}

function defaultCallback(item){
			console.log(item);
}

		
function get_time(timestamp){
	var month = ["Jan" , "Feb" , "Mar" , "Apr" , "May" , "Jun" , "Jul" , "Aug" , "Sept" , "Oct" , "Nov" ,"Dec"];
	if(typeof timestamp == 'undefined'){
		var d = new Date(),
		dt = d.getDate(),
		m = month[d.getMonth()],
		y = d.getFullYear();
		return m + " " + dt + ", " + y;
	}
	return_date = '';
	var time = new Date(Date.parse(timestamp));
	var curr_tme = Date.now();
	if((curr_tme - time) < 60*1000){
		return_date += Math.round(((curr_tme - time)/1000)) + " seconds ago";
	}else if((curr_tme - time) > 60*1000 && (curr_tme - time) < 60*60*1000){
		return_date += Math.round(((curr_tme - time)/(60*1000))) + " minute ago";
	}else if((curr_tme - time) > 60*60*1000 && (curr_tme - time) < 60*60*24*1000){
		return_date += Math.round(((curr_tme - time)/(60*60*1000))) + " hour ago";
	}else if((curr_tme - time) > 60*60*24*1000 && (curr_tme - time) < 60*60*24*30*1000){
		return_date += Math.round(((curr_tme - time)/(60*60*24*1000))) + " days ago";
	}else{
		return_date += month[time.getMonth()] + " " + time.getDate() + ", " + time.getFullYear();
	}
	return return_date;
}	
