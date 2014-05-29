// JavaScript Document
$(document).ready(function() {
		$('input[id^="bi_confirm"]').click(function(){
			var total = 0;			
			$('input[id^="bi_amt"]').each(function(){
				total += $(this).val();
				$('#total_bkin').val() =  total;
				});
		});
});