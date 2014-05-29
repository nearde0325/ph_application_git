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
				
				var apos_total = $("#apos_total").val();
				
				if(apos_total =="" || apos_total<= 0 || isNaN(apos_total)){
					$("#error_message_2").removeClass("error_inv");
					$("#error_message_2").addClass("error");
					document.getElementById("error_detail_2").innerHTML = "You must fill in  CORRECT APOS total before proceed!<br />";
					document.form_cashaccount.apos_total.focus();
					return false;					
					}
				else{
					$("#error_message_2").removeClass("error");
					$("#error_message_2").addClass("error_inv");
					document.getElementById("apos_total_check").value = apos_total;					
					//return true;
				}
					
				var efpos_total = $("#efpos_total").val();
				
				if(efpos_total =="" || efpos_total< 0 || isNaN(efpos_total) || parseFloat(efpos_total) > parseFloat(apos_total)){
					$("#error_message_2").removeClass("error_inv");
					$("#error_message_2").addClass("error");
					document.getElementById("error_detail_2").innerHTML = "You must fill in CORRECT Electronic Cash Sales before proceed!<br />";
					document.form_cashaccount.efpos_total.focus();
					return false;					
					}
				else{
					$("#error_message_2").removeClass("error");
					$("#error_message_2").addClass("error_inv");	
					document.getElementById("efpos_total_check").value = efpos_total;					
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
						
						//alert("Missing Information, The amount is not empty however other information is missing");
						$("#error_message_3").removeClass("error_inv");
						$("#error_message_3").addClass("error");
						
						document.getElementById("error_detail_3").innerHTML = "Missing Information:The amount is not empty however other information is missing<br />";
						document.form_cashaccount.name_expense_1.focus();
						
						return false;
						}
						else{
						
						$("#error_message_3").removeClass("error");
						$("#error_message_3").addClass("error_inv");
						
						}
								
			
				}	
			//Exp2	
			var sub_expense_2  = $("#sub_expense_2").val();
				if(!isNaN(sub_expense_2) && sub_expense_2 > 0){
					
					var name_expense_2 = $("#name_expense_2").val();
					var staff_name_expense_2 = $("#staff_name_expense_2").val();
					
					if(name_expense_2=="" || staff_name_expense_2 == ""){
						
						$("#error_message_3").removeClass("error_inv");
						$("#error_message_3").addClass("error");
						
						document.getElementById("error_detail_3").innerHTML = "Missing Information:The amount is not empty however other information is missing<br />";
						
						document.form_cashaccount.name_expense_2.focus();
						return false;
						}		
						else{
						
						$("#error_message_3").removeClass("error");
						$("#error_message_3").addClass("error_inv");
						
						}				
				}	
			//Exp3
			var sub_expense_3  = $("#sub_expense_3").val();
				if(!isNaN(sub_expense_3) && sub_expense_3 > 0){
					
					var name_expense_3 = $("#name_expense_3").val();
					var staff_name_expense_3 = $("#staff_name_expense_3").val();
					
					if(name_expense_3=="" || staff_name_expense_3 == ""){
						
						
						$("#error_message_3").removeClass("error_inv");
						$("#error_message_3").addClass("error");
						
						document.getElementById("error_detail_3").innerHTML = "Missing Information:The amount is not empty however other information is missing<br />";
						
						document.form_cashaccount.name_expense_1.focus();
						return false;
						}		
						else{
						
						$("#error_message_3").removeClass("error");
						$("#error_message_3").addClass("error_inv");
						
						}			
				}								
			//Exp4
			var sub_expense_4  = $("#sub_expense_4").val();
				if(!isNaN(sub_expense_4) && sub_expense_4 > 0){
					
					var name_expense_4 = $("#name_expense_4").val();
					var staff_name_expense_4 = $("#staff_name_expense_4").val();
					
					if(name_expense_4=="" || staff_name_expense_4 == ""){
						
						$("#error_message_3").removeClass("error_inv");
						$("#error_message_3").addClass("error");
						
						document.getElementById("error_detail_3").innerHTML = "Missing Information:The amount is not empty however other information is missing<br />";
						
						document.form_cashaccount.name_expense_4.focus();
						return false;
						}		
						else{
						
						$("#error_message_3").removeClass("error");
						$("#error_message_3").addClass("error_inv");
						
						}				
				}	
			//Exp5
			var sub_expense_5  = $("#sub_expense_5").val();
				if(!isNaN(sub_expense_5) && sub_expense_5 > 0){
					
					var name_expense_5 = $("#name_expense_5").val();
					var staff_name_expense_5 = $("#staff_name_expense_5").val();
					
					if(name_expense_5=="" || staff_name_expense_5 == ""){
						
						$("#error_message_3").removeClass("error_inv");
						$("#error_message_3").addClass("error");
						
						document.getElementById("error_detail_3").innerHTML = "Missing Information:The amount is not empty however other information is missing<br />";
						
						document.form_cashaccount.name_expense_5.focus();
						return false;
						}		
						else{
						
						$("#error_message_3").removeClass("error");
						$("#error_message_3").addClass("error_inv");
						
						}			
				}	
			//Exp6
			var sub_expense_6  = $("#sub_expense_6").val();
				if(!isNaN(sub_expense_6) && sub_expense_6 > 0){
					
					var name_expense_6 = $("#name_expense_6").val();
					var staff_name_expense_6 = $("#staff_name_expense_6").val();
					
					if(name_expense_6=="" || staff_name_expense_6 == ""){
						
						$("#error_message_3").removeClass("error_inv");
						$("#error_message_3").addClass("error");
						
						document.getElementById("error_detail_3").innerHTML = "Missing Information:The amount is not empty however other information is missing<br />";
						
						document.form_cashaccount.name_expense_6.focus();
						return false;
						}												
						
						else{
						
						$("#error_message_3").removeClass("error");
						$("#error_message_3").addClass("error_inv");
						
						}		
			
				}
			//Manager Name 
			var approved_manager = $("#approved_manager").val();
			var sub_expense_total = $("#sub_expense_total").val();
			if(approved_manager =="" && sub_expense_total >0){
					
						$("#error_message_3").removeClass("error_inv");
						$("#error_message_3").addClass("error");
						
						document.getElementById("error_detail_3").innerHTML = "All Expense Must Be Approved By Store Manager,Please Fill in the Name for Check<br />";
						
						document.form_cashaccount.approved_manager.focus();
						return false;				
				}												
				else{
						$("#error_message_3").removeClass("error");
						$("#error_message_3").addClass("error_inv");				
				
				}
			}
		if(i==4){
				
				if(document.getElementById("id_cal_electronic_cash").value ==0 ){
						$("#error_message_4").removeClass("error_inv");
						$("#error_message_4").addClass("error");
						
						document.getElementById("error_detail_4").innerHTML = "Click The Calculate Buttons before proceed!!<br />";					
						return false;
					}
				else{
						$("#error_message_4").removeClass("error");
						$("#error_message_4").addClass("error_inv");					
					
					}
				var total_electronic_cash = $("#dbcr_cash").val();
				var efpos_total_check = $("#efpos_total_check").val();
				if(parseFloat(total_electronic_cash) != parseFloat(efpos_total_check) ){
						$("#error_message_4").removeClass("error_inv");
						$("#error_message_4").addClass("error");
						
						document.getElementById("error_detail_4").innerHTML = "Debit/Credit Net Totals do Not Match what you filled earlier!!<br />";					
						return false;					
					
					}	
				else{
						$("#error_message_4").removeClass("error");
						$("#error_message_4").addClass("error_inv");					
					
					}					
		}			
		if(i==5){
		/*
				if(document.getElementById("total_electronic_cash").value <0 ){
						$("#error_message_5").removeClass("error_inv");
						$("#error_message_5").addClass("error");
						
						document.getElementById("error_detail_5").innerHTML = "Total Electronic Cash Under 0 Correct the Error Before Proceed!!<br />";					
						return false;
					}
				else{
						$("#error_message_5").removeClass("error");
						$("#error_message_5").addClass("error_inv");					
					
					}					
		*/
		}			
			
			
		if(i==6){
				if(document.getElementById("id_cal_close_bal").value ==0 ){
						$("#error_message_6").removeClass("error_inv");
						$("#error_message_6").addClass("error");
						
						document.getElementById("error_detail_6").innerHTML = "Click The Calculate Buttons before proceed!!<br />";					
						return false;
					}
				else{
						$("#error_message_6").removeClass("error");
						$("#error_message_6").addClass("error_inv");					
					
					}
		}	
							
			
		
		}
			
	});
	$("#sub").click(function(){
		if(document.getElementById("id_cal_net_sales").value ==0){
						
						$("#error_message_7").removeClass("error_inv");
						$("#error_message_7").addClass("error");
						
						document.getElementById("error_detail_7").innerHTML = "Click the Calculate Button Before you proceed!! <br />";					
						return false;
					}
				else{
						$("#error_message_7").removeClass("error");
						$("#error_message_7").addClass("error_inv");					
					
					}									
			
			
		if(document.getElementById("apos_total").value <=0 ){
						$("#error_message_7").removeClass("error_inv");
						$("#error_message_7").addClass("error");
						
						document.getElementById("error_detail_7").innerHTML = " Can Not Submit: Total APOS Net Sales Should Greater than 0!! <br />";					
						return false;
					}
				else{
						$("#error_message_7").removeClass("error");
						$("#error_message_7").addClass("error_inv");					
					
					}
		if(document.getElementById("cash_discrepancy").value !=0 ){
			if(document.getElementById("ind_inconsistency").checked == false || document.getElementById("remark_cont").value.length == 0 ){
						
						$("#error_message_7").removeClass("error_inv");
						$("#error_message_7").addClass("error");
						
						document.getElementById("error_detail_7").innerHTML = " The Numbers are Not Match and You must Acknowledge the difference and remark the reason before proceed <br />";					
						return false;				
					
				}
			else{
						$("#error_message_7").removeClass("error");
						$("#error_message_7").addClass("error_inv");				
				
				}				
		}					
		
		var data = $("form").serialize();
		//alert(data);
		$("form").submit();		
		
	});
});