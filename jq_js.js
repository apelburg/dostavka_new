// JavaScript Document

$(document).ready(function() {
    $("#myTable").tableDnD({
        onDragClass: "dragRow",
        onDrop: function(table, row) {
            var data = new Object();
            data.data = new Object();
            data.key = $(table).find("tbody tr td").attr("rel");
            $(row).fadeOut("fast").fadeIn("slow");      
            $(table).find("tbody tr").each(function(i, e){
                var id = $(e).find("td:first").attr("id");
                var order = i -2;
                data.data[order] = id;
                $(e).find("td[rel=sort_order]").html(order);
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
$('.addRow').click(function(){
	alert('нажал');
	});