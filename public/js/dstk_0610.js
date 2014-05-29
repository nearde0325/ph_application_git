$(document).ready(function() {
	$('input:text[id^=qty_act_]').change(function(){
		
		var id = $(this).attr("id");
		var line = 0;
		var length = id.length;
		line = id.substring(8,length);
		
		var onhand = parseInt($("#qty_sys_" + line).val());
		var actual = parseInt($("#qty_act_" + line).val());		
		if(isNaN(actual) || onhand != actual){
			$("#qty_act_" + line).addClass("atten");
			}
		else{
			$("#qty_act_" + line).removeClass();
			}	
		});
	
	});