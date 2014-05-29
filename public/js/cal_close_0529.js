$(document).ready(function() {
	/*Staff Name Copy*/
	$("#staff_name").change(function(){
		$("#staff_on_duty1").val($("#staff_name").val());
	});
	
	$("#cal_closing_cash_balance").click(function(){
		
		var amount100 = parseFloat($("#amount_note_100").val());
		var amount50 = parseFloat($("#amount_note_50").val());
		var amount20 = parseFloat($("#amount_note_20").val());
		var amount10 = parseFloat($("#amount_note_10").val());
		var amount5 = parseFloat($("#amount_note_5").val());
		//Coin 
		var amount2 = parseFloat($("#amount_coin_2").val());
		var amount1 = parseFloat($("#amount_coin_1").val());
		var amount05 = parseFloat($("#amount_coin_05").val());
		var amount02 = parseFloat($("#amount_coin_02").val());
		var amount01 = parseFloat($("#amount_coin_01").val());
		var amount005 = parseFloat($("#amount_coin_005").val());
		
		var total_cash_closing  = amount100 + amount50 + amount20 + amount10 + amount5 + amount2 + amount1 + amount05 + amount02  + amount01  + amount005; 	

		$("#close_balance").val(total_cash_closing.toFixed(2));		
		$("#id_cal_close_bal").val("1");
		$("#sp_id_cal_bal").addClass("clicked");
//	document.getElementById("close_balance").value = the_total_cash.toFixed(2)
		var total_cash_sales = 0;
		if($("#manual_cash_opening").length >0 ){
				total_cash_sales = total_cash_closing - parseFloat($("#manual_cash_opening").val()) + parseFloat($("#total_expense_today").val());
			$("#total_cash_sales").val(total_cash_sales.toFixed(2));	
		}
		else{
				total_cash_sales = total_cash_closing - parseFloat($("#open_balance_normal").val()) + parseFloat($("#total_expense_today").val());
			$("#total_cash_sales").val(total_cash_sales.toFixed(2));
		}
	});

});
function summarySubExpense(){
	var subExp1 = document.getElementById("sub_expense_1").value;
	var subExp2 = document.getElementById("sub_expense_2").value;
	var subExp3 = document.getElementById("sub_expense_3").value;
	var subExp4 = document.getElementById("sub_expense_4").value;
	var subExp5 = document.getElementById("sub_expense_5").value;
	var subExp6 = document.getElementById("sub_expense_6").value;
	
	if(!isNaN(subExp1) && !isNaN(subExp2) && !isNaN(subExp3) && !isNaN(subExp4) && !isNaN(subExp5) && !isNaN(subExp6) && subExp1 >= 0 && subExp2 >= 0 &&  subExp3 >= 0 &&  subExp4 >= 0 &&  subExp5 >= 0 &&  subExp6 >= 0 
	&& subExp1!="" && subExp2!="" && subExp3!="" && subExp4!="" && subExp5!="" && subExp6!=""
	 ){
		
		var subTotal = parseFloat(subExp1) + parseFloat(subExp2) + parseFloat(subExp3) + parseFloat(subExp4) + parseFloat(subExp5) + parseFloat(subExp6);
	
	    document.getElementById("sub_expense_total").value = parseFloat(subTotal).toFixed(2);
		passExpenseValue();
		}
	else{
				
		alert("Incorrect input \n The Amount you Type in is NOT a Number");

		if(isNaN(subExp1) || subExp1 <0 || subExp1=="" ){document.getElementById("sub_expense_1").value = 0; document.form_cashaccount.sub_expense_1.focus();}
		if(isNaN(subExp2) || subExp2 <0 || subExp2==""  ){document.getElementById("sub_expense_2").value = 0; document.form_cashaccount.sub_expense_2.focus();}
		if(isNaN(subExp3) || subExp3 <0 || subExp3==""  ){document.getElementById("sub_expense_3").value = 0; document.form_cashaccount.sub_expense_3.focus();}
		if(isNaN(subExp4) || subExp4 <0 || subExp4==""  ){document.getElementById("sub_expense_4").value = 0; document.form_cashaccount.sub_expense_4.focus();}
		if(isNaN(subExp5) || subExp5 <0 || subExp5==""  ){document.getElementById("sub_expense_5").value = 0; document.form_cashaccount.sub_expense_5.focus();}
		if(isNaN(subExp6) || subExp6 <0 || subExp6==""  ){document.getElementById("sub_expense_6").value = 0; document.form_cashaccount.sub_expense_6.focus();}
						
		}	


	
	
	}
function passExpenseValue(){	
	$("#total_expense_today").val($("#sub_expense_total").val()); 
	}
function calculateInitCashInTill(){
	
	var open_balance = parseFloat(document.getElementById("open_balance").value);
	var expense =  parseFloat(document.getElementById("expense_record").value);
	var bankin =  parseFloat(document.getElementById("bankin_record").value);
	
	document.getElementById("cash_in_till").value = parseFloat(open_balance - expense - bankin).toFixed(2);
	document.getElementById("id_cal_init_cash").value = 1;	
	document.getElementById("sp_id_cal_init_cash").setAttribute("class","clicked");
	
	} 
function calculateTotalCashInTill(){
	
	var amount100 = parseFloat(document.getElementById("amount_note_100").value);
	var amount50 = parseFloat(document.getElementById("amount_note_50").value);
	var amount20 = parseFloat(document.getElementById("amount_note_20").value);
	var amount10 = parseFloat(document.getElementById("amount_note_10").value);
	var amount5 = parseFloat(document.getElementById("amount_note_5").value);
	var amount2 = parseFloat(document.getElementById("amount_coin_2").value);
	var amount1 = parseFloat(document.getElementById("amount_coin_1").value);
	var amount05 = parseFloat(document.getElementById("amount_coin_05").value);
	var amount02 = parseFloat(document.getElementById("amount_coin_02").value);
	var amount01 = parseFloat(document.getElementById("amount_coin_01").value);
	var amount005 = parseFloat(document.getElementById("amount_coin_005").value);
	
	var the_total_cash = amount100 + amount50 + amount20 + amount10 + amount5 + amount2 + amount1 + amount05 + amount02  + amount01  + amount005; 
	document.getElementById("close_balance").value = the_total_cash.toFixed(2)
	document.getElementById("total_cash_sales").value = parseFloat(document.getElementById("close_balance").value - 300 + parseFloat(document.getElementById("total_expense_today").value)).toFixed(2);
	document.getElementById("id_cal_close_bal").value = 1;	
	document.getElementById("sp_id_cal_bal").setAttribute("class","clicked");

	
												
	}
function calculateCashAmount(note,input,output){

	if(isNaN(document.getElementById(input).value) || document.getElementById(input).value < 0 || document.getElementById(input).value==""){
		
		document.getElementById(input).value = 0;
		alert("The Amount you Type in is NOT a Number");
				
		}
	else{
		var tmpvalue = parseFloat(note)* parseFloat(document.getElementById(input).value);
		document.getElementById(output).value = tmpvalue.toFixed(2); 

		}	
	document.getElementById("id_cal_close_bal").value = 0;	
	document.getElementById("sp_id_cal_bal").setAttribute("class","notclickyet");	
	document.getElementById("id_cal_total_cash_sales").value = 0;	
	document.getElementById("sp_id_cal_total_cash_sales").setAttribute("class","notclickyet");	
	
	}
function calculateCashSales(){
	
	if(document.getElementById("id_cal_close_bal").value ==1 ){
		
		document.getElementById("total_cash_sales").value =  parseFloat(document.getElementById("close_balance").value).toFixed(2) - parseFloat(document.getElementById("cash_in_till").value).toFixed(2) + parseFloat(document.getElementById("total_expense_today").value).toFixed(2)
	
		document.getElementById("id_cal_total_cash_sales").value = 1;	
		document.getElementById("sp_id_cal_total_cash_sales").setAttribute("class","clicked");
	}
	else{
		
		alert("Click the 'Calculate Colsing Balance Button First'");
		
	}
	
	}

function calculateElectronicCashSales(){
	var tmpvalue =  parseFloat(document.getElementById("dbcr_cash").value) + parseFloat(document.getElementById("amex_cash").value) + parseFloat(document.getElementById("offline_voucher").value) ;
	document.getElementById("total_electronic_cash").value = tmpvalue.toFixed(2);
	document.getElementById("total_electronic_sales").value = tmpvalue.toFixed(2);	
	document.getElementById("id_cal_electronic_cash").value = 1;	
	document.getElementById("sp_id_cal_electronic_cash").setAttribute("class","clicked");	
	
	}

function electronicCashChange(){
	
	document.getElementById("id_cal_electronic_cash").value = 0;	
	document.getElementById("sp_id_cal_electronic_cash").setAttribute("class","notclickyet");		
	
	}	

function calculateDifference(){
	
	
	var theDif =  parseFloat(document.getElementById("apos_total").value) - parseFloat(document.getElementById("total_net_sales").value); 
	document.getElementById("cash_discrepancy").value = theDif.toFixed(2)
	if(Math.abs(theDif) > 0.04){
		
		document.getElementById("sp_id_inconsistency").setAttribute("class","notmatch");
		document.getElementById("remark_div").setAttribute("class","");	 
		
		}
	else{
		document.getElementById("sp_id_inconsistency").setAttribute("class","clicked");
		document.getElementById("remark_div").setAttribute("class","error_inv");		
		
		}		
	}
function calculateTotalNetSales(){
	
	var tmpValue = parseFloat(document.getElementById("total_cash_sales").value) + parseFloat(document.getElementById("total_electronic_cash").value);
	document.getElementById("total_net_sales").value =  tmpValue.toFixed(2);

	
	document.getElementById("id_cal_net_sales").value = 1;	
	document.getElementById("sp_id_cal_net_sales").setAttribute("class","clicked");	 

	}			
// JavaScript Document