$(document).ready(function() {
	/*Staff Name Copy*/
	$("#staff_name").change(function(){
		$("#staff_on_duty1").val($("#staff_name").val());
	});
	/*If can not find yesterday's closing cash*/
	$("#r_shop_not_open").click(function(){
		
		$("#last_bus_day").html("<strong>Last business day Closing Cash:<strong>");
		$("#manual_input_cash_closing").val($("#close_balance_yesterday_init").val());
		$("#close_balance_yesterday").val($("#close_balance_yesterday_init").val());
		//.removeClass("input").addClass("disinput")
	});
	$("#r_shop_did_open").click(function(){
		$("#last_bus_day").html("Yesterday Closing Cash:");
		$("#manual_input_cash_closing").val("0");
		//.removeClass("disinput").addClass("input")
	});	
	$("#manual_input_cash_closing").change(function(){
		$("#close_balance_yesterday").val($("#manual_input_cash_closing").val());
	});	
	$("#cashout_amount").change(function(){
		if(parseFloat($("#cashout_amount").val()) < 0.04){
				//alert("small");
				$("#cash_out_method3").removeAttr('disabled');
				$("#cash_out_method3").attr('checked','checked');
			}
		else{
				$("#cash_out_method3").removeAttr('checked');
				$("#cash_out_method3").attr('disabled','disabled');
			}	
		});
	
	/*Calculator*/
	$("#btn_cal_total_cash_in_till").click(function(){
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
		
		var total_cash_opening  = amount100 + amount50 + amount20 + amount10 + amount5 + amount2 + amount1 + amount05 + amount02  + amount01  + amount005; 
		
		$("#total_open_cash").val(total_cash_opening.toFixed(2));
		$("#opening_cash_in_till").val(total_cash_opening.toFixed(2));
		
		var closing_balance_yesterday = parseFloat($("#close_balance_yesterday").val()).toFixed(2);
		
		$("#id_cal_open_cash_in_till").val("1");
		$("#error_message_2").addClass("error_inv");
		
		if(total_cash_opening > 300){	
				$("#cashout_amount").val(parseFloat(total_cash_opening - 300).toFixed(2));	
				$("#cash_out_method3").attr('disabled','disabled');
			}
		else{
				$("#cashout_amount").val("0");
				$("#cash_out_method3").removeAttr("disabled");
			}
		if( parseFloat(total_cash_opening).toFixed(2) == closing_balance_yesterday){
				$("#sp_open_balance_not_match").addClass("error_inv");
				$("#sp_open_close_not_match").addClass("error_inv");
				$("#sp_id_cal_bal").removeClass("notmatch").removeClass("notclickyet").addClass("clicked");
				$("#cash_in_the_till_match").removeAttr("disabled");
		}
		else{
				$("#sp_open_balance_not_match").removeClass("error_inv");
				$("#sp_open_close_not_match").removeClass("error_inv");
				$("#sp_id_cal_bal").removeClass("clicked").removeClass("notclickyet").addClass("notmatch");
				$("#cash_in_the_till_match").attr('disabled','disabled');
		}
			
	});
	
});


	 
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
	document.getElementById("sp_open_close_not_match").setAttribute("class","error_inv");
	
	}

//	

function var_dump(obj) {
if(typeof obj == "object") {
return "Type: "+typeof(obj)+((obj.constructor) ? "\nConstructor: "+obj.constructor : "")+"\nValue: " + obj;
} else {
return "Type: "+typeof(obj)+"\nValue: "+obj;
}
}// JavaScript Document