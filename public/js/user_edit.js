$(document).ready(function () {
    User.init();
});

var User = {
    currentRoute: "",
    init(){

        console.log("123");
        $("#user-edit-button").on('click', function (e){
            //debugger;
            e.preventDefault();
            let formId = $(this).attr('target-form');
            $(this).addClass('collapse');
            $("#"+formId).find("input").removeAttr('disabled');
            $("#"+formId).find("button").removeClass('collapse');

        });

    },



}

