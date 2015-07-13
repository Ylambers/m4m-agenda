/**
 * Created by yaron on 1-7-2015.
 */

$(window).ready(function(){
    makePageGood();
});
$(window).resize(function(){
    makePageGood();

});

function makePageGood(){
    if(document.querySelector(".container-addRoom") != null){
        document.querySelector(".container-addRoom").style.height = document.querySelector(".container-box").offsetHeight+"px";
        document.querySelector(".container-room").style.height = document.querySelector(".container-box").offsetHeight+"px";
        document.querySelector(".container-scheduler").style.height = document.querySelector(".container-box").offsetHeight+"px";
    }
    if(document.querySelector(".dhx_cal_navline") != null){
        var navItems = document.querySelector(".dhx_cal_navline").querySelectorAll("div");
        for(var i=0;i<navItems.length;i++){
            navItems[i].style.top = "0px";
        }
    }
}