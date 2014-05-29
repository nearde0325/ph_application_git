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
					$("#error_message_1").removeClass("error_inv");
					$("#error_message_1").addClass("error");
					document.getElementById("error_detail").innerHTML = "Fill the [*] Field: You must fill staff name before proceed!<br />";
					document.form_cashaccount.staff_name.focus();
					return false;
				}
				else{
					$("#error_message_1").removeClass("error");
					$("#error_message_1").addClass("error_inv");					
					//return true;
				}
			var staff_on_duty1  = $("#staff_on_duty1").val();
				if(staff_on_duty1==""){
					//alert("Fill the [*] Field!\n You Must Fill At Least One Staff Name On Duty this Shift!");
					$("#error_message_1").removeClass("error_inv");
					$("#error_message_1").addClass("error");
					document.form_cashaccount.staff_on_duty1.focus();
					document.getElementById("error_detail").innerHTML = "Fill the [*] Field: You Must Fill At Least One Staff Name On Duty this Shift!<br />";
					return false;
				}					
				else{
					$("#error_message_1").removeClass("error");
					$("#error_message_1").addClass("error_inv");					
					//return true;
				}				
			}
			if(i==2){
				if(document.getElementById("id_cal_open_cash_in_till").value ==0 ){
						$("#error_message_2").removeClass("error_inv");
						$("#error_message_2").addClass("error");
						
						document.getElementById("error_detail_2").innerHTML = "Click The Calculate Buttons before proceed!!<br />";					
						return false;
					}
				else{
						$("#error_message_2").removeClass("error");
						$("#error_message_2").addClass("error_inv");					
					
					}			
		
			}						
			if(i==3){}
		}
			
	});
	$("#sub").click(function(){
	
		if(!document.getElementById("cash_out_method1").checked && !document.getElementById("cash_out_method2").checked ){
						
						$("#error_message_3").removeClass("error_inv");
						$("#error_message_3").addClass("error");
						
						document.getElementById("error_detail_3").innerHTML = "You need to Choose Cash Out /Bank In Method Before Proceed!! <br />";					
						return false;
					}
				else{
						$("#error_message_3").removeClass("error");
						$("#error_message_3").addClass("error_inv");					
					
					}	
		if(!document.getElementById("cash_in_the_till_match").checked && document.getElementById("open_balance_not_match").value=="" ){
						
						$("#error_message_3").removeClass("error_inv");
						$("#error_message_3").addClass("error");
						
						document.getElementById("error_detail_3").innerHTML = "Please Remark the Reason If 'Total' Cash Amount Not Match Closing Balance Last Business Day<br />";		
						//alert("Fixed");			
						return false;
					}
				else{
						$("#error_message_3").removeClass("error");
						$("#error_message_3").addClass("error_inv");					
					
					}													
	
		var data = $("form").serialize();
		//alert(data);
		$("form").submit();		
		
	});
});