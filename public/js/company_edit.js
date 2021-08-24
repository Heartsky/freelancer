$(document).ready(function () {
    Company.init();
});

var Company = {
    currentRoute: "",
    init(){

        console.log("123");
        $("#company-edit-button").on('click', function (e){
            //debugger;
            e.preventDefault();
            let formId = $(this).attr('target-form');
            $(this).addClass('collapse');
            $("#"+formId).find("input").removeAttr('disabled');
            $("#"+formId).find("select").removeAttr('disabled');
            $("#"+formId).find("button").removeClass('collapse');

        });

        $("#account-edit-button").on('click', function (e){
            //debugger;
            e.preventDefault();
            let formId = $(this).attr('target-form');
            $(this).addClass('collapse');
            $("#"+formId).find("input").removeAttr('disabled');
            $("#"+formId).find("button").removeClass('collapse');

        });
    },



}

