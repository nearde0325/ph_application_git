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

	document.getElementById("total_expense_today").value = document.getElementById("sub_expense_total").value; 
	//document.getElementById("id_cal_init_cash").value = 0;	
	//document.getElementById("sp_id_cal_init_cash").setAttribute("class","notclickyet");	
	}
function calculateInitCashInTill(){
	
	var open_balance = parseFloat(document.getElementById("open_balance").value);
	var expense =  parseFloat(document.getElementById("expense_record").value);
	var bankin =  parseFloat(document.getElementById("bankin_record").value);
	
	document.getElementById("cash_in_till").value = parseFloat(open_balance - expense - bankin).toFixed(2);
	document.getElementById("id_cal_init_cash").value = 1;	
	document.getElementById("sp_id_cal_init_cash").setAttribute("class","clicked");
	
	}
function inputBankin(){
	
	if(isNaN(document.getElementById("bankin_record").value) || document.getElementById("bankin_record").value < 0 ){
		
		alert("The Amount you Type in is NOT a Number");
		document.getElementById("bankin_record").value = 0;
		}
		
	document.getElementById("id_cal_init_cash").value = 0;	
	document.getElementById("sp_id_cal_init_cash").setAttribute("class","notclickyet");	
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
	/*
	if(isNaN(amount100) || amount100 <0 || amount100=="" ){document.getElementById("amount_note_100").value = amount100= 0; alert("The Amount you Type in is NOT a Number"); }
	if(isNaN(amount50) || amount50 <0 || amount50=="" ){document.getElementById("amount_note_50").value = amount50= 0; alert("The Amount you Type in is NOT a Number"); }
	if(isNaN(amount20) || amount20 <0 || amount20=="" ){document.getElementById("amount_note_20").value = amount20= 0; alert("The Amount you Type in is NOT a Number"); }
	if(isNaN(amount10) || amount10 <0 || amount10=="" ){document.getElementById("amount_note_10").value = amount10= 0; alert("The Amount you Type in is NOT a Number"); }
	if(isNaN(amount5) || amount5 <0 || amount5=="" ){document.getElementById("amount_note_5").value = amount5= 0; alert("The Amount you Type in is NOT a Number"); }
	if(isNaN(amount2) || amount2 <0 || amount2=="" ){document.getElementById("amount_coin_2").value = amount2= 0; alert("The Amount you Type in is NOT a Number"); }
	if(isNaN(amount1) || amount1 <0 || amount1=="" ){document.getElementById("amount_coin_1").value = amount1= 0; alert("The Amount you Type in is NOT a Number"); }
	if(isNaN(amount05) || amount05 <0 || amount05=="" ){document.getElementById("amount_coin_05").value = amount05= 0; alert("The Amount you Type in is NOT a Number"); }
	if(isNaN(amount02) || amount02 <0 || amount02=="" ){document.getElementById("amount_coin_02").value = amount02= 0; alert("The Amount you Type in is NOT a Number"); }
	if(isNaN(amount01) || amount01 <0 || amount01=="" ){document.getElementById("amount_coin_01").value = amount01= 0; alert("The Amount you Type in is NOT a Number"); }
	if(isNaN(amount005) || amount005 <0 || amount005=="" ){document.getElementById("amount_coin_005").value = amount005= 0; alert("The Amount you Type in is NOT a Number"); }
	*/
	
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
	
	
	theDif =  parseFloat(document.getElementById("apos_total").value) - parseFloat(document.getElementById("total_net_sales").value); 
	document.getElementById("cash_discrepancy").value = theDif.toFixed(2)
	if(theDif != 0){
		
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