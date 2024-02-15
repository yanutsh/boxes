$(document).ready(function(){

	$(document).on('click', '#matchButton', function(e){
		qty = $('#producttobox-shipped_qty').val()
		$('#producttobox-received_qty').val(qty)
	})

	//console.dir($('.productInBox tbody>tr').length)
	if($('.productInBox tbody>tr').length > 0) {
		summa_kol=0
		summa_price =0
		is_kol = true	// проверка Shipped Qty > 0 у каждого продукта
		$('.productInBox tbody>tr').each(function (index, el) {
			if(is_kol*1*$(el).find('td:nth-child(4)').text() == 0) is_kol = false
			//else is_kol = false	
			summa_kol += 1*$(el).find('td:nth-child(4)').text()
			summa_price += 1*$(el).find('td:nth-child(6)').text()
		})
		if(is_kol===false) $('#box-status_id option').attr('disabled', true)
		$('#summaKol').text(summa_kol)
		$('#summaPrice').text(summa_price)
	}

	// обработка отмеченных коробок в списке коробок
	$(document).on('click', '#changeStatus', function bulkAction(a) {
		console.log('присвоение статуса коробкам')
		cheked_els =  $('input[type="checkbox"]:not(.select-on-check-all):checked')
		if(cheked_els.length ==0){
			alert('Надо выбрать коробку (коробки)')
			return
		}	
		if($('#box-status').val()=='') {
			alert('Надо выбрать новый статус');	
			return
		}	
		
		// цикл по коробкам
		messages = ''
		cheked_els.each(function (index, el) {
			console.dir('коробка №'+$(el).attr('value')) //.val())
			box_id = $(el).attr('value')
			status_id = $('#box-status').val()
			message = ''
			// если новый статус - expected - меняем 
			if(status_id == 1) {
				console.log('меняем статус на Expected')				
				$.get('/box/change-status', {box_id: box_id, status_id: status_id}, function(data){
					console.log(data);
				});			
			}
			// если новый статус - At warehouse - проверяем weight и совпадение количеств
			else {
				console.log('меняем статус на At Warehouse')
				if($(el).parents('tr').find('td:nth-child(5)').text() == 0) {
					message += 'Error: Коробка '+box_id+'- определить вес.\n'
					//return true;
				}

				console.log('hasClass='+$(el).parents('tr').find('td:nth-child(2) > div').hasClass('nonEQ'))
				if($(el).parents('tr').find('td:nth-child(2) > div').hasClass ('nonEQ')) {
					message += 'Error: Коробка '+box_id+'- не совпадают количества.\n'
					//return true;
				}

				console.log(message.length)
				if(message.length == 0 )				
					$.get('/box/change-status', {box_id: box_id, status_id: status_id}, function(data){
						console.log(data);
					});	

			}
			messages += message
		})	
		if(messages.length > 0) alert(messages)

        //var keys = $('#grid').yiiGridView('getSelectedRows');
        //window.location.href='<?php echo Url::to(['mycontroller/bulk']); ?>&action='+a+'&ids='+keys.join();
    })

    $(document).on('click', 'button[type="reset"]', function(e){
    	$('#boxSearch input').attr('value','')
    	$('#boxsearch-status_id option:selected').removeAttr('selected');
    	$('#boxSearch button[type="submit"]').click()    	
    })

    $(document).on('click', '.fb__export button', function(e){
    	$.post('/box/export-excel',{}, function(msg){
			alert("Файл сохранен: "+msg);
		});
		return
    })	

   
})

