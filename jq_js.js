// JavaScript Document

$(document).ready(function() {
    $("#myTable").tableDnD({
        onDragClass: "dragRow",
        onDrop: function(table, row) {
            var data = new Object();
            data.data = new Object();
            data.key = $(table).find("tbody tr td").attr("rel");
            $(row).fadeOut("fast").fadeIn("slow");      
            
            // ресорт
            resortBigRow();
            $(table).find("tbody tr").each(function(i, e){
                var id = $(e).find("td:first").attr("id");
                var order = i -2;
                data.data[order] = id;
                // $(e).find("td[rel=sort_order]").html(order);
				//alert(data.key);
            });
            $.ajax({
                type: "POST",
                url: "update_num_rows.php",
                data: data,
                success: function(html){  
                    $("#myTable tr").removeClass("color");
                    $("#myTable tr:even").addClass("color");                    
                     
                }                        
            });                   
        }
    });
});

//сортировка строк
function resortBigRow(){
    var i = 1;
    $('#myTable').find('tbody tr').each(function(index, element) {
        if(!$(this).hasClass('nodrag')){
            $(this).find('td').eq(0).html('<p><span>'+(i++)+'</span></p>');//пересчет строк 
        }       
    });
}
$(document).on('click', '#deleted_show', function(event) {
    event.preventDefault();
    if($(this).hasClass('checked')){
        $(this).removeClass('checked');
        $('.deleted_row.nodrag.nodrop td').toggle(100);
    }else{
        $(this).addClass('checked');
        $('.deleted_row.nodrag.nodrop td').toggle(100);
    }
    
});
$('.addRow').click(function(){
	alert('нажал');
	});