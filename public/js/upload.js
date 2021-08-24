$(document).ready(function () {
    Invoice.init();
    console.log("vhange")

});

var Invoice = {
    currentRoute: "",
    init(){
        $("#input-company_id").on('change', function (){
            Invoice.updateTeam($(this).val());
        });
        $('#input-company_id').trigger('change');
    },
    updateTeam(companyId){
        if (companyId == ''){
            $("#input-team_id").html('<option value="">Select</option>');
        } else {
            $.ajax({
                url: $("#ajax-invoice-type").val(),
                method: "GET",
                dataType: "json",
                contentType: 'application/json',
                data: {"id":companyId, "type": 'get_team' },
                success: function(result){
                    console.log(result)
                    let html = '<option value="">Select</option>';
                    if (result.length > 0) {
                        $.each(result, function (key, item){
                            html += '<option value="'+item.id+'">'+ item.name+'</option>';
                        })
                    }
                    $("#input-team_id").html(html)
                }
            });
        }


    }




}

