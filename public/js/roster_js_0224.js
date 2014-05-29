$(document).ready(function() {
	/*Staff Name Copy*/
	$('input.shift_confirm').click(function(){
		var line = $(this).val().toString();
		//alert(line);
		if(this.checked){
		//$("#shift_begin_hour" + line).attr("readonly",true);
		//$("#shift_begin_min" + line).attr("readonly",true);
		//$("#shift_end_hour" + line).attr("readonly",true);
		//$("#shift_end_min" + line).attr("readonly",true);
		//$("#shift_break" + line).attr("readonly",true);
		$("#btn_final").removeAttr("disabled");
		//$("input").data("c" + line).val("k");
		}
		else{
		//$("#shift_begin_hour" + line).removeAttr("readonly");
		//$("#shift_begin_min" + line).removeAttr("readonly");
		//$("#shift_end_hour" + line).removeAttr("readonly");
		//$("#shift_end_min" + line).removeAttr("readonly");
		//$("#shift_break" + line).removeAttr("readonly");
		$("#btn_final").attr("disabled",true);
		}
	});
	$('input.shift_remove').click(function(){
		var line = $(this).val().toString();
		//alert(line);
		if(this.checked){
		$("#shift_begin_hour" + line).val("00");
		$("#shift_begin_min" + line).val("00");
		$("#shift_end_hour" + line).val("00");
		$("#shift_end_min" + line).val("00");
		$("#shift_break" + line).val("0");
		$("#shift_total_hours" + line).val("0");
		//$("input").data("c" + line).val("k");
		}
		else{
		$("#shift_begin_hour" + line).val($("#bksbh" + line).val());
		$("#shift_begin_min" + line).val($("#bksbm" + line).val());
		$("#shift_end_hour" + line).val($("#bkseh" + line).val());
		$("#shift_end_min" + line).val($("#bksem" + line).val());
		$("#shift_break" + line).val($("#bksbk" + line).val());
		$("#shift_total_hours" + line).val($("#bksth" + line).val());
		//$("#shift_break" + line).removeAttr("readonly");
		}
	});	
	$('input.add_staff').click(function(){
		$("#add_staff_form").removeClass('addstaff');	
	});
	$('input[id^="shift_begin_hour"]').click(function(){
		var  ronly = $(this).attr("readonly");
		//if(ronly =="readonly"){
		//alert("untick the confirm check box , then you can edit this line");
		//}
		});
	$('input:text[id^=shift_]').change(function(){
		
		//alert("only when it is text and it changed");
		var id = $(this).attr("id");
		alert(id);
		$("#trigger").val(id);
		var line = 0;
		var length = id.length;
		if(id.substring(0,16) == "shift_begin_hour"){
			line = id.substring(16,length);
			}
		else if(id.substring(0,15) == "shift_begin_min"){
			line = id.substring(15,length);
			}
		else if(id.substring(0,14) == "shift_end_hour"){
			line = id.substring(14,length);
			}
		else if(id.substring(0,13) == "shift_end_min"){
			line = id.substring(13,length);
			}			
		else if(id.substring(0,11) == "shift_break"){
			line = id.substring(11,length);
			}
		//get values 
		var shift_begin_hour = parseFloat($("#shift_begin_hour" + line).val());
		var shift_begin_min = parseFloat($("#shift_begin_min" + line).val()); 
		var shift_end_hour =  parseFloat($("#shift_end_hour" + line).val());
		var shift_end_min =  parseFloat($("#shift_end_min" + line).val());
		var shift_break =  parseFloat($("#shift_break" + line).val());
		var totalhour = (shift_end_hour - shift_begin_hour) + (shift_end_min - shift_begin_min) / 60 - shift_break / 60;
		$("#shift_total_hours" + line).val(totalhour);
		$("form").submit();
		});	
	$('#btn_final').click(function(){
		$("form").attr("action","/roster/save-confirm-roster");
$("form").submit();
		});
	$('#btn_final_arrange').click(function(){
		$("form").attr("action","/roster/save-arrange-roster");
$("form").submit();
		});		

});// JavaScript Document