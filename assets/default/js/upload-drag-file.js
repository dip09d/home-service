$(function() {

    // preventing page from redirecting
    $("html").on("dragover", function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(".upload-area h4").text("Drag here");
    });

    $("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

    // Drag enter
    $('.upload-area').on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).find("h4").text("Drop");
    });

    // Drag over
    $('.upload-area').on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).find("h4").text("Drop");
    });

    // Drop
    $('.upload-area').on('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();

       // $(".upload-area h4").text("Upload");
        $(".upload-area h4").html("Drag and Drop file here<br>Or<br>Click to select file");

        var file = e.originalEvent.dataTransfer.files;
        var fd = new FormData();
		for(var i=0;i<file.length;i++){
        fd.append('fileinput', file[i]);

        uploadData(fd,e);
        }
    });

    // Open file selector on div click
    $("#uploadfile").click(function(){
        $("#fileinput").click();
    });

    // file selected
    $("#fileinput").change(function(){
        var fd = new FormData();
		var all_files= $('#fileinput')[0].files;
		for(var i=0;i<all_files.length;i++){
			var files = $('#fileinput')[0].files[i];
	        fd.append('fileinput',files);
	        uploadData(fd);
		}
        
    });
});

// Sending AJAX request and upload file


// Added thumbnail
function addThumbnail(data){
    //$("#uploadfile .upload-area h4").remove();
    var len = $("#uploadfile_container div.thumbnail").length;

    var num = Number(len);
    num = num + 1;

    var name = data.name;
    var size = convertSize(data.size);
    var src = data.src;

    // Creating an thumbnail
    $("#uploadfile_container").append('<div id="thumbnail_'+num+'" class="thumbnail"></div>');
    $("#thumbnail_"+num).append('<img src="'+src+'" width="100%" height="78%">');
    $("#thumbnail_"+num).append('<span class="size">'+size+'<span>');

}

// Bytes conversion
function convertSize(size) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (size == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(size) / Math.log(1024)));
    return Math.round(size / Math.pow(1024, i), 2) + ' ' + sizes[i];
}
