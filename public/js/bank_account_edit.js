$(document).ready(function () {
    BankAccount.init();
});

var BankAccount = {
    init(){

        //$("#input-company_id").select2();
        //$("#input-customer_id").select2();

        $("#bank_account-edit-button").on('click', function (e){
            //debugger;
            e.preventDefault();
            let formId = $(this).attr('target-form');
            $(this).addClass('collapse');
            $("#"+formId).find("input").removeAttr('disabled');
            $("#"+formId).find("button").removeClass('collapse');
            $("#"+formId).find("select").removeAttr('disabled');
           // $("#"+formId).find("select").select2();
            //$("#input-company_id").select2();
          //  $("#input-customer_id").select2();
        });

    },



}

