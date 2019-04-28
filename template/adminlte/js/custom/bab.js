$('.materi-content').hide();
$('.materi').click(function(){
	
	var n = $('.materi').val();
	var t = "#materi" + n;
	if($(t).css('display') == 'none'){
		$(t).slideDown();
	} else {
		$(t).slideUp();
	}
});