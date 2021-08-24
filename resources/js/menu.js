$(document).ready(function () {
    App.init();
});

var App = {
    currentRoute: "",
    init(){
        this.currentRoute = $("#current_route").val();
        console.log("test2331");
        this.detectMenu();
    },
    detectMenu(){
        $("#sidenav-main").find(".nav-link").each(function () {
            console.log($(this).attr('href'), App.currentRoute);
            if($(this).attr('href') == App.currentRoute) {


                App.activeMenu(this);
            }
        });
    },
    activeMenu(e){
        console.log($(e).text());
    }



}

