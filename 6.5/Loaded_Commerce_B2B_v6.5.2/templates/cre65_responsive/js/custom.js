$(document).ready(function() {	  
	$(document).on('click','img.minus',function() {
		var qtyval	=	parseInt($("#v").val())-1;
		if (qtyval < 0) {
			qtyval = 0;
		}
		$("#v").val(qtyval);
		$("#sub_products_qty_input").val(qtyval);
	});
	
	$(document).on('click','img.plus',function() {
    var qtyval	=	parseInt($("#v").val())+1;
		$("#v").val(qtyval);
		$("#sub_products_qty_input").val(qtyval);
	});
		
	$('#v').change(function()	{
		$("#sub_products_qty_input").val(this.value);
	});						
});