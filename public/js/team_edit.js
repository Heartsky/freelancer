$(document).ready(function () {
    Company.init();
});

var Company = {
    currentRoute: "",
    init(){

      //  $("#input-company_id").select2();
        $("#team-edit-button").on('click', function (e){
            //debugger;
            e.preventDefault();
            let formId = $(this).attr('target-form');
            $(this).addClass('collapse');
            $("#"+formId).find("input").removeAttr('disabled');
            $("#"+formId).find("button").removeClass('collapse');
            $("#"+formId).find("select").removeAttr('disabled');
           // $("#"+formId).find("select").select2();

        });

    },



}

