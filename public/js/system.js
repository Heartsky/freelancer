$(document).ready(function () {
    App.init();
});

var App = {
    currentRoute: "",
    init(){
        this.currentRoute = $("#current_route").val();
        console.log( this.currentRoute);
        this.detectMenu();
    },
    detectMenu(){
        $("#sidenav-main").find(".nav-link").each(function () {
            if($(this).attr('href') == App.currentRoute) {
                App.activeMenu(this);
            }
        });
    },
    activeMenu(e){
        var parent =  $(e).parents(".collapse").first();
        $(e).addClass("active");
        parent.addClass("show");
        parent.parent().find('.nav-link').first().addClass("active").attr("aria-expanded", true);
    }



}

