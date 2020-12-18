$(document).ready(function() {
	
	

	$(".experiment").click(function() { 

		var datatarget=$(this).attr('data-target');
		var modal=$(datatarget);
		modal.modal();
		var jobid=datatarget.split('-').pop()
		var submitbutton=$("#submit-" + jobid);
		submitbutton.removeClass("disabled");
	});

	$(".select-mount-button").click(function(){
		
			var caller=$(this).parent().children('.mount-field').attr('id');
			$("#mountcaller").val("#" + caller);
			var link = $("#selectmounturl").val();
			window.open(link, "Ratting",
			"height=500,width=800,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no, status=no");
		
	});

	$(".clear-mount-button").click(function(){
		var mountf=$(this).parent().children('.mount-field');
		mountf.val('');
		mountf.trigger("change");
	});

	$(".experiment-submit-btn").click(function(){
		form=$(this).closest('form');
		modal=$(this).closest('.modal');
		form.submit();
		$(this).addClass('disabled');
		
	});





	
	

});