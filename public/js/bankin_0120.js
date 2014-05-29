// JavaScript Document
$(document).ready(function() {
		$('input[id^="bi_confirm"]').click(function(){
			var total = 0;		
			var theID;
			var checkBoxStr	
			$('input[id^="bi_amt"]').each(function(){
				theID = $(this).attr("id").substr(6,10);
				//document.write(theID);
				if ($('#bi_confirm' + theID + ':checked' ).val() != null)
				{
    			//Checked
				total += parseFloat($(this).val());
				//alert('#bi_confirm' + theID);
				}
				else{
				 //alert('#bi_confirm' + theID);
					}
				//total += parseFloat($(this).val());
				$('#total_bkin').val(total.toFixed(2));
				});
		});
});