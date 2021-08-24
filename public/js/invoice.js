$(document).ready(function () {
    Invoice.init();
    console.log("vhange")

});

var Invoice = {
    currentRoute: "",
    init(){
        $("#input-company_id").on('change', function (){
            Invoice.updateCustomer($(this).val());
        });
        $("#input-customer_id").on('change', function (){
           // Invoice.updateInvoiceType($(this).val());
        });
    },
    updateCustomer(companyId){
        if (companyId == ''){
            $("#input-customer_id").html('<option value="">Select</option>');
        } else {
            $.ajax({
                url: $("#ajax-invoice-type").val(),
                method: "GET",
                dataType: "json",
                contentType: 'application/json',
                data: {"id":companyId, "type": 'get_customer' },
                success: function(result){
                    console.log(result)
                    let html = '<option value="">Select</option>';
                    if (result.length > 0) {
                        $.each(result, function (key, item){
                            html += '<option value="'+item.id+'">'+ item.name+'</option>';
                        })
                    }
                    $("#input-customer_id").html(html)
                }
            });
        }


    },
    updateInvoiceType(customerId){
        if (customerId == ''){
            $("#input-invoice_type").html('<option value="">Select</option>');
        } else {
            $.ajax({
                url: $("#ajax-invoice-type").val(),
                method: "GET",
                dataType: "json",
                contentType: 'application/json',
                data: {"id":customerId, "type": 'get_invoice_type' },
                success: function(result){
                    console.log(result)
                    let html = '<option value="">Select</option>';
                    if (result.length > 0) {
                        $.each(result, function (key, item){
                            html += '<option value="'+item.id+'">'+ item.name+'</option>';
                        })
                    }
                    $("#input-invoice_type").html(html)
                }
            });
        }


    }



}

