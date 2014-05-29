// JavaScript Document
$(function(){
	$("#wizard").scrollable({
		onSeek: function(event,i){
			$("#status li").removeClass("active").eq(i).addClass("active");
		},
		onBeforeSeek:function(event,i){
			if(i==1){
			//check Prepared by 
			var staff_name  = $("#staff_name").val();
				if(staff_name==""){
					//alert("Fill the [*] Field!\n You Must Fill Staff Name Before Proceed!");
					$("#error_message_1").removeClass("error_inv").addClass("error");
					$("#error_detail").html("Fill the [*] Field: You must fill staff name before proceed!<br />");
					$("#staff_name").focus();
					return false;
				}
				else{
					$("#error_message_1").removeClass("error").addClass("error_inv");				
					//return true;
				}
				if(!($("#check_dvr1").is(":checked") || $("#check_dvr2").is(":checked"))){
					
					$("#error_message_1").removeClass("error_inv").addClass("error");
					$("#error_detail").html("You must select a confition of the DVR to inform the Head Office the DVR status, If your DVR not installed please select the second choice <br />");
					$("#check_dvr1").focus();
					return false;					
					
					}
				else{
					$("#error_message_1").removeClass("error").addClass("error_inv");				
					//return true;
				}					
			}
			if(i==2){
				var cal_cash_in_till = $("#id_cal_open_cash_in_till").val();
				if(cal_cash_in_till == 0 ){
						$("#error_message_2").removeClass("error_inv").addClass("error");
						$("#error_detail_2").html("Click The Calculate Buttons before proceed!!<br />");				
						return false;
					}
				else{
						$("#error_message_2").removeClass("error").addClass("error_inv");					
					}
				if($("#r_shop_not_open").length &&$("#r_shop_did_open").length ){
						
						if ($("input[name=r_shop_open]:checked").val() != null) {
							$("#error_message_2").removeClass("error").addClass("error_inv");	
							return true;
							}  
						else{
							$("#error_message_2").removeClass("error_inv").addClass("error");
							$("#error_detail_2").html("Please Select Why Yesterday Cash Closing Not Exist!!<br />");
							return false;
							
							}	
					}				
		
			}						
			if(i==3){}
		}
			
	});
	$("#sub").click(function(){
	
		if($("input[name=cash_out_method]:checked").val() == null ){
						
						$("#error_message_3").removeClass("error_inv").addClass("error");
						$("#error_detail_3").html("You need to Choose Cash Out /Bank In Method Before Submit!! <br />");					
						return false;
					}
				else{
						$("#error_message_3").removeClass("error").addClass("error_inv");				
					}	
		if($("input[name=cash_in_the_till_match]:checked").val() == null && parseFloat($("#open_balance_not_match").val().length) <5 ){
						
						$("#error_message_3").removeClass("error_inv").addClass("error");			
						$("#error_detail_3").html("Please Remark The Reason If 'Total' Cash Amount Not Match Closing Balance Last Business Day, Or Confirm They Are Matching<br />");				
						return false;
					}
				else{
						$("#error_message_3").removeClass("error").addClass("error_inv");			
					}													
	
		var data = $("form").serialize();
		//alert(data);
		$("form").submit();		
		
	});
});