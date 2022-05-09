<?php

?>
<script>
	$( document ).ready(function() {
		var pathname = window.location.pathname; 
		
		var csrf_token = $('meta[name="csrf-token"]').attr('content');		
		var base_cost = $("#base_cost");
		var upcharge_amount = $('#upcharge_amount');
		var subtotal = $("#subtotal");
		var tax_1_total = $("#tax_1_total");
		var tax_2_total = $("#tax_2_total");
		var tax = $("#tax");		
		var total_cost = $("#total_cost");
		var shipping_cost_display = $("#shipping_cost");
		var shipping_cost_line = $("#invoice1");
	    
		var inventory_json = "";
		var inventory_id = 0;
		var sides = 1;
		var qty = 1;
		var per_sheet = 1;
		var upcharge_percent = 0;
		var shipping_cost = 0;
		var fourover_price = 0;
		var show = 'new';
		var project_id = 0;
		
		if(pathname != '/admin/projects/create'){
			myArr = pathname.split("/");
			project_id = myArr[3];
			show = 'show';
			invoice_ajax('show');
		}
		console.log(Lit.bus);
		Lit.bus.$on('fieldValueChanged', (value) => {
			console.log(value);	
			console.log(Lit.bus);		
			// Get Info from Database About Inventory
			if(value[0] == 'inventory_id'){	
				inventory_id = value[1];
				invoice_ajax('inventory_id');
			}
			// Record How Many Sides
			if(value[0] == 'sides'){
				sides = value[1];
				invoice_ajax('sides');
			}
			// Record Qty
			if(value[0] == 'qty'){
				qty = value[1];
				invoice_ajax('qty');
			}
			// Record How Many Fit on 1 sheet
			if(value[0] == 'per_sheet'){
				per_sheet = value[1];
				invoice_ajax('per_sheet');
			}
			// Record Upcharge Percent
			if(value[0] == 'upcharge_percent'){
				upcharge_percent = value[1];
				invoice_ajax('upcharge_percent');
			}
			// Record 4Over Price
			if(value[0] == 'fourover_price'){
				fourover_price = value[1];
				invoice_ajax('fourover_price');
			}
			// Record 4Over Shipping
			if(value[0] == 'shipping_cost'){
				shipping_cost = value[1];
				invoice_ajax('shipping_cost');
			}
			
		});
		
		
		function invoice_ajax(action){
			$.ajax({
				method: "POST",
				url: "/admin/inventories/byid",
				dataType: 'JSON',
				data: { '_token': csrf_token, 'inventory_id': inventory_id, 'sides': sides, 'qty': qty, 'per_sheet': per_sheet, 'upcharge_percent': upcharge_percent, 'action':action, 'fourover_price':fourover_price, 'shipping_cost':shipping_cost, 'show':show, 'project_id':project_id}
			})
			.done(function( html ) {
				inventory_json = html;
				if(action == 'show'){
					inventory_id = inventory_json.inventory_id;
					sides = inventory_json.sides;
					qty = inventory_json.qty;
					per_sheet = inventory_json.per_sheet;;
					upcharge_percent = inventory_json.upcharge_percent;
					shipping_cost = inventory_json.shipping_cost;
				}
				update_invoice(inventory_json);
			});	
		}
		
		function update_invoice(inventory_json){
			
			base_cost.text(inventory_json.base_cost);
			upcharge_amount.text(inventory_json.upcharge_amount);
			subtotal.text(inventory_json.subtotal);
			tax_1_total.text(inventory_json.tax_1_total);
			tax_2_total.text(inventory_json.tax_2_total);
			tax.text(inventory_json.tax);
			total_cost.text(inventory_json.total_cost);
			
			if(inventory_id == 2){
				shipping_cost_line.show();
				shipping_cost_display.text(inventory_json.shipping_cost);
			}	
			
		}
		function wait(ms){
		   var start = new Date().getTime();
		   var end = start;
		   while(end < start + ms) {
			 end = new Date().getTime();
		  }
		}
		
	});	
		
</script>
