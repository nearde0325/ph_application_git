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
	
	var total_cash_opening  = amount100 + amount50 + amount20 + amount10 + amount5 + amount2 + amount1 + amount05 + amount02  + amount01  + amount005; 
	
	document.getElementById("total_open_cash").value = total_cash_opening.toFixed(2);
	document.getElementById("opening_cash_in_till").value = total_cash_opening.toFixed(2);
	
	var closing_balance_yesterday = parseFloat(document.getElementById("close_balance_yesterday").value).toFixed(2);
	
	document.getElementById("id_cal_open_cash_in_till").value = 1;	
	document.getElementById("sp_id_cal_bal").setAttribute("class","clicked");
	document.getElementById("cashout_amount").value = parseFloat(total_cash_opening.toFixed(2) -300).toFixed(2) ;	
	//see if it match 
	if( parseFloat(total_cash_opening).toFixed(2) == closing_balance_yesterday){
		document.getElementById("sp_open_balance_not_match").setAttribute("class","error_inv");	
		document.getElementById("sp_open_close_not_match").setAttribute("class","error_inv");	
		
	}
	else{
		document.getElementById("sp_open_balance_not_match").setAttribute("class","");
		document.getElementById("sp_open_close_not_match").setAttribute("class","");
		document.getElementById("sp_id_cal_bal").setAttribute("class","notmatch");	 	
	}
												
	}
function calculateInitCashInTill(){
	
	var total_cash_in_till = parseFloat(document.getElementById("opening_cash_in_till").value);
	var cashout =  parseFloat(document.getElementById("cashout_record").value);
	var bankin =  parseFloat(document.getElementById("bankin_record").value);	
	document.getElementById("cash_left_in_till").value = parseFloat(total_cash_in_till - cashout - bankin).toFixed(2);
	
	document.getElementById("id_cal_init_cash").value = 1;	
	document.getElementById("sp_id_cal_init_cash").setAttribute("class","clicked");
		
	if(parseFloat(document.getElementById("cash_left_in_till").value).toFixed(2) != parseFloat(300).toFixed(2)){
		document.getElementById("sp_id_inconsistency").setAttribute("class","notmatch");
		}
	else{
		document.getElementById("sp_id_inconsistency").setAttribute("class","clicked");
		}	
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
	document.getElementById("id_cal_open_cash_in_till").value = 0;	
	document.getElementById("sp_id_cal_bal").setAttribute("class","notclickyet");	
	//document.getElementById("id_cal_init_cash").value = 0;	
	//document.getElementById("sp_id_cal_init_cash").setAttribute("class","notclickyet");	
	
	}
function fillStaffName(input,output){
	document.getElementById(output).value = document.getElementById(input).value;
	}
function var_dump(obj) {
if(typeof obj == "object") {
return "Type: "+typeof(obj)+((obj.constructor) ? "\nConstructor: "+obj.constructor : "")+"\nValue: " + obj;
} else {
return "Type: "+typeof(obj)+"\nValue: "+obj;
}
}// JavaScript Document