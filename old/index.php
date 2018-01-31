<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Image Loading Example</title>
<style>
#checkingForImgs{
    bottom: 20px;
    position:fixed;
    right: 500px;	
}
#imageContainer{height:500px; width:200px;}
#scrollToTop {
    bottom: 20px;
    position:fixed;
    right: 10px;
}
#endOfImageLotMessage{
    bottom: 20px;
    position:fixed;
    right: 200px;
}
.hide{display:none;}
.redAlert{color:#F00;}
</style>
<script type="text/javascript" language="javascript" src="jquery-1.7.1.min.js.php"></script>
<script type="text/javascript" language="javascript">
$(document).ready(function(){
	var start = 0,
		limit = 3,
		nextStart = start,
		imageContainer = $("#imageContainer"),
		imageContainerHtml = "";
	$("#imageContainer").html('');					   
	
	// Load images initially when the page is loaded
	$.getJSON('ajax.php?action=loadImgs&start=' + start + '&limit=' + limit, function(data, e){
		imageContainerHtml = "";	
		$("#checkingForImgs").removeClass('hide');
		if (data.endOfImgLot != true){
			$.each(data.imageSet, function(i){
				imageContainerHtml += '<img src="' + data.imageSet[i].uploadTImgLocation + '" title="' + data.imageSet[i].title + '" />';
			});
			imageContainer.html(imageContainerHtml);
			$("#checkingForImgs").addClass('hide');
		}else{
			$("#endOfImageLotMessage").removeClass('hide');
			$("#checkingForImgs").addClass('hide');
		}
		nextStart = data.nextStart;
	});
	
	$(window).scroll(function(){
		if ($(window).scrollTop() == $(document).height() - $(window).height()){
			$("#scrollToTop").removeClass('hide');
			// Load next images
			$.getJSON('ajax.php?action=loadImgs&start=' + nextStart + '&limit=' + limit, function(data){
				$("#checkingForImgs").removeClass('hide');
				if (data.endOfImgLot != true){
					$.each(data.imageSet, function(i){
						imageContainerHtml += '<img src="' + data.imageSet[i].uploadTImgLocation + '" title="' + data.imageSet[i].title + '" />';
					});
					imageContainer.html(imageContainerHtml);
					$("#checkingForImgs").addClass('hide');
				}else{
					$("#endOfImageLotMessage").removeClass('hide');
					$("#checkingForImgs").addClass('hide');
				}
				nextStart = data.nextStart;
			});
		}
	});
	$("#scrollToTop").click(function(){
		$('html,body').animate({scrollTop: $("#imageHeader").offset().top - 100}, 500);	
		$("#scrollToTop").addClass('hide');
		$("#endOfImageLotMessage").addClass('hide');
	});
});
</script>
</head>
<body>
<h2 id="imageHeader">Images</h2>
<div id="imageContainer"></div>
<div id="scrollToTop" class="hide"><a href="javascript:void(0);">Scroll To Top</a></div>
<div id="endOfImageLotMessage" class="hide"><span class="redAlert">No more images to load</span></div>
<div id="checkingForImgs" class="hide">&nbsp;&nbsp;&nbsp;<img src="indicator.gif" title="Loading..." />Loading images...</div>
</body>
</html>