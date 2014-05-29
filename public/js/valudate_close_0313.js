// JavaScript Document
$(function(){
	$("#wizard").scrollable({
		onSeek: function(event,i){
			$("#status li").removeClass("active").eq(i).addClass("active");
		},
		onBeforeSeek:function(event,i){
			if(i==1){
			var staff_name  = $("#staff_name").val();
				if(staff_name==""){
					$("#error_message_1").removeClass("error_inv").addClass("error");
					$("#error_detail").html("Fill the [*] Field: You must fill staff name before proceed!<br />");
					$("#staff_name").focus();
					return false;
				}
				else{
					$("#error_message_1").removeClass("error").addClass("error_inv");			
				}
				
				if(!$("#went_to_bank_1").is(":checked") && !$("#went_to_bank_2").is(":checked")   ){
					$("#error_message_1").removeClass("error_inv").addClass("error");
					$("#error_detail").html("You must choose if you went to bank today or not before proceed!<br />");
					$("#staff_name").focus();
					return false;
					
					
					}					
			}
			if(i==2){
				
				var apos_total = $("#apos_total").val();
				
				if(apos_total =="" || apos_total<= 0 || isNaN(apos_total)){
					$("#error_message_2").removeClass("error_inv").addClass("error");
					$("#error_detail_2").html("You must fill in  CORRECT APOS total before proceed!<br />");
					$("#apos_total").focus();
					return false;					
					}
				else{
					$("#error_message_2").removeClass("error").addClass("error_inv");
					$("#apos_total_check").val(apos_total);					
					//return true;
				}
					
				var efpos_total = $("#efpos_total").val();
				
				if(efpos_total =="" || isNaN(efpos_total)){
					$("#error_message_2").removeClass("error_inv").addClass("error");
					$("#error_detail_2").html("You must fill in CORRECT Electronic Cash Sales before proceed!<br />");
					$("#efpos_total").focus();
					return false;					
					}
				else{
					$("#error_message_2").removeClass("error").addClass("error_inv");
					$("#efpos_total_check").val(efpos_total);					
					//return true;
				}				
				
			}
			if(i==3){

			//Exp1			
			var sub_expense_1  = $("#sub_expense_1").val();
				if(!isNaN(sub_expense_1) && sub_expense_1 > 0){
					
					var name_expense_1 = $("#name_expense_1").val();
					var staff_name_expense_1 = $("#staff_name_expense_1").val();
					
					if(name_expense_1=="" || staff_name_expense_1 == ""){
						$("#error_message_3").removeClass("error_inv").addClass("error");
						$("#error_detail_2").html("Missing Information:The amount is not empty however other information is missing<br />");
						$("#name_expense_1").focus();
						
						return false;
						}
						else{
						$("#error_message_3").removeClass("error").addClass("error_inv");
						}
								
			
				}	
			//Exp2	
			var sub_expense_2  = $("#sub_expense_2").val();
				if(!isNaN(sub_expense_2) && sub_expense_2 > 0){
					
					var name_expense_2 = $("#name_expense_2").val();
					var staff_name_expense_2 = $("#staff_name_expense_2").val();
					
					if(name_expense_2=="" || staff_name_expense_2 == ""){
						
						$("#error_message_3").removeClass("error_inv").addClass("error");						
						$("#error_detail_3").html("Missing Information:The amount is not empty however other information is missing<br />");
						$("#name_expense_2").focus();
						return false;
						}		
						else{
						$("#error_message_3").removeClass("error").addClass("error_inv");	
						}				
				}	
			//Exp3
			var sub_expense_3  = $("#sub_expense_3").val();
				if(!isNaN(sub_expense_3) && sub_expense_3 > 0){
					
					var name_expense_3 = $("#name_expense_3").val();
					var staff_name_expense_3 = $("#staff_name_expense_3").val();
					
					if(name_expense_3=="" || staff_name_expense_3 == ""){
						
						
						$("#error_message_3").removeClass("error_inv").addClass("error");
						$("#error_detail_3").html("Missing Information:The amount is not empty however other information is missing<br />");
						$("#name_expense_3").focus();
						return false;
						}		
						else{
						$("#error_message_3").removeClass("error").addClass("error_inv");						
						}			
				}								
			//Exp4
			var sub_expense_4  = $("#sub_expense_4").val();
				if(!isNaN(sub_expense_4) && sub_expense_4 > 0){
					
					var name_expense_4 = $("#name_expense_4").val();
					var staff_name_expense_4 = $("#staff_name_expense_4").val();
					
					if(name_expense_4=="" || staff_name_expense_4 == ""){
						
						$("#error_message_3").removeClass("error_inv").addClass("error");
						$("#error_detail_3").html("Missing Information:The amount is not empty however other information is missing<br />");
						$("#name_expense_4").focus();
						return false;
						}		
						else{
						$("#error_message_3").removeClass("error").addClass("error_inv");
						}				
				}	
			//Exp5
			var sub_expense_5  = $("#sub_expense_5").val();
				if(!isNaN(sub_expense_5) && sub_expense_5 > 0){
					
					var name_expense_5 = $("#name_expense_5").val();
					var staff_name_expense_5 = $("#staff_name_expense_5").val();
					
					if(name_expense_5=="" || staff_name_expense_5 == ""){
						
						$("#error_message_3").removeClass("error_inv").addClass("error");
						$("#error_detail_3").html("Missing Information:The amount is not empty however other information is missing<br />");
						$("#name_expense_5").focus();
						return false;
						}		
						else{
						$("#error_message_3").removeClass("error").addClass("error_inv");						
						}			
				}	
			//Exp6
			var sub_expense_6  = $("#sub_expense_6").val();
				if(!isNaN(sub_expense_6) && sub_expense_6 > 0){
					
					var name_expense_6 = $("#name_expense_6").val();
					var staff_name_expense_6 = $("#staff_name_expense_6").val();
					
					if(name_expense_6=="" || staff_name_expense_6 == ""){
						
						$("#error_message_3").removeClass("error_inv").addClass("error");
						$("#error_detail_3").html("Missing Information:The amount is not empty however other information is missing<br />");
						$("#name_expense_6").focus();
						return false;
						}												
						
						else{
						
						$("#error_message_3").removeClass("error").addClass("error_inv");
						
						}		
			
				}
			//Manager Name 
			var approved_manager = $("#approved_manager").val();
			var sub_expense_total = $("#sub_expense_total").val();
			if(approved_manager =="" && sub_expense_total >0){
					
						$("#error_message_3").removeClass("error_inv").addClass("error");
						$("#error_detail_3").html("All Expense Must Be Approved By Store Manager,Please Fill in the Name for Check<br />");
						$("#approved_manager").focus();
						return false;				
				}												
				else{
						$("#error_message_3").removeClass("error").addClass("error_inv");				
				
				}
			}
		if(i==4){
				if($("#dbcr_cash").val() > 0 && $("#qty_merchant_copy").val()== 0 ){
					
						$("#error_message_4").removeClass("error_inv").addClass("error");
						$("#error_detail_4").html("Correct the Qty of Merchant Copy!!<br />");					
						return false;					
					}
				else{
					$("#error_message_4").removeClass("error").addClass("error_inv");		
					}
				
				
				if($("#id_cal_electronic_cash").val() ==0 ){
						$("#error_message_4").removeClass("error_inv").addClass("error");
						$("#error_detail_4").html("Click The Calculate Buttons before proceed!!<br />");					
						return false;
					}
				else{
						$("#error_message_4").removeClass("error").addClass("error_inv");				
					
					}
				var total_electronic_cash = $("#dbcr_cash").val();
				var efpos_total_check = $("#efpos_total_check").val();
				if(parseFloat(total_electronic_cash) != parseFloat(efpos_total_check) ){
						$("#error_message_4").removeClass("error_inv").addClass("error");		
						$("#error_detail_4").html("Debit/Credit Net Totals do Not Match what you filled earlier!!<br />");					
						return false;					
					}	
				else{
						$("#error_message_4").removeClass("error").addClass("error_inv");					
					}					
		}			
		if(i==5){
		/*		Nothing at the moment		*/
		}			
			
			
		if(i==6){
				if($("#id_cal_close_bal").val() ==0 ){
						$("#error_message_6").removeClass("error_inv").addClass("error");						
						$("#error_detail_6").html("Click The Calculate Buttons before proceed!!<br />");;					
						return false;
					}
				else{
						$("#error_message_6").removeClass("error").addClass("error_inv");						
					}
		}	
							
			
		
		}
			
	});
	$("#sub").click(function(){
		if($("#id_cal_net_sales").val() ==0){
						
						$("#error_message_7").removeClass("error_inv").addClass("error");						
						$("#error_detail_7").html("Click the Calculate Button Before you proceed!! <br />");					
						return false;
					}
				else{
						$("#error_message_7").removeClass("error").addClass("error_inv");					
					}									
			
			
		if($("#apos_total").val()<=0 ){
						$("#error_message_7").removeClass("error_inv").addClass("error");				
						$("#error_detail_7").html(" Can Not Submit: Total APOS Net Sales Should Greater than 0!! <br />");					
						return false;
					}
				else{
						$("#error_message_7").removeClass("error").addClass("error_inv");	
					}
		if($("#cash_discrepancy").val() !=0 ){
			//alert($("#remark_cont").val().length);
			if($("input[name=ind_inconsistency]:checked").val() == null || $("#remark_cont").val().length < 5 ){
						
						$("#error_message_7").removeClass("error_inv").addClass("error");
						$("#error_detail_7").html(" The Numbers are Not Match and You must Acknowledge the difference and remark the reason before proceed <br />");					
						return false;				
					
				}
			else{
						$("#error_message_7").removeClass("error").addClass("error_inv");	
				}				
		}					
		
		var data = $("form").serialize();
		//alert(data);
		$("form").submit();		
		
	});
});