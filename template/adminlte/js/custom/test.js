function endTest(){
	var soalID = $('#soalid').val();
	$.ajax({
		  type: "POST", 
		  url: "ajax.php?type=test&action=end",
		  data: "id="+soalID,
		  success: function (html){
				  if(html == 'true'){
					  window.location='index.php';
				  }
		  }
	  });
}
  $.widget.bridge('uibutton', $.ui.button);
  $('#btn-start').click(function () {
	$('#start-text').hide();
	var soalID = $('#soalid').val();
	var timelimit = $('#timelimit').val();
	  $.ajax({
		  type: "POST", 
		  url: "ajax.php?type=test&action=start",
		  data: "id="+soalID,
	  });
	$.ajax({
		  type: "POST", 
		  url: "ajax.php?type=test&action=loaddisplay",
		  data: "display=sectsoal",
		  success: function (html){
				  $("#section-soal").html(html);
		  }
	  });
	$.ajax({
		  type: "POST", 
		  url: "ajax.php?type=test&action=loaddisplay",
		  data: "display=navsoal&id="+soalID,
		  success: function (html){
				  $("#section-nav").html(html);
		  }
	  });
	  
	  $.ajax({
		  type: "POST", 
		  url: "ajax.php?type=test&action=load",
		  data: "id="+soalID+"&no=1",
		  success: function (html){
				  $("#body-soal").html(html);
				  MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
		  }
	  });
    $('#timer').countdown({
        until: +timelimit,
		onExpiry: endTest,
        compact: true
    });
});
  $(function() {
	 var test = {};
    $(document).on('click', '.nav-soal-btn', function(event) {
       event.preventDefault();
	  

	  test.no = $(this).val();
	  var soalID = $('#soalid').val();
	  $.ajax({
		  type: "POST", 
		  url: "ajax.php?type=test&action=load",
		  data: "id="+soalID+"&no="+test.no,
		  success: function (html){
				  $("#body-soal").html(html);
				  MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
		  }
	  });
    });
	
	$(document).on('click', '#btn-save', function(event) {
		event.preventDefault();
	  
	  
	  var ans;
	  var soalID = $('#soalid').val();
	  if($('#answer').length){
		  ans = $('#answer').val();
	  } else {
		  ans = $("input[name='optSoal']:checked").val();
	  }
	  
	  if(test.no == undefined){
		test.no = 1;
	  }
	  
	  $.ajax({
		  type: "POST", 
		  url: "ajax.php?type=test&action=save",
		  data: "ans="+ans+"&soal_id="+soalID+"&no="+test.no,
		  success: function (html){
			  if(html == 'true'){
				  alert('Jawaban telah disimpan!');
			  } else if(html == 'false') {
				  alert('Jawaban tidak dapat disimpan, silahkan dicoba lagi.');
			  }
		  }
	  });
	});
	
	$(document).on('click', '#endtest', function(event) {
	var soalID = $('#soalid').val();
	$.ajax({
		  type: "POST", 
		  url: "ajax.php?type=test&action=end",
		  data: "id="+soalID,
		  success: function (html){
				  if(html == 'true'){
					  window.location='index.php';
				  }
		  }
	  });
	});
	
});