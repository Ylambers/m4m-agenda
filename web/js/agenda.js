var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
    showLeft = document.getElementById( 'showLeft' ),

    body = document.body;

showLeft.onclick = function() {
    classie.toggle( this, 'active' );
    classie.toggle( menuLeft, 'cbp-spmenu-open' );
    disableOther( 'showLeft' );
};
document.querySelector("#scheduler_here").onclick = function () {

    $(".cbp-spmenu-open").removeClass("cbp-spmenu-open");
    if(picker_all != null){
        picker_all.hideScreen();
    }
};

document.querySelector(".exit").onclick = function () {
    $(".cbp-spmenu-open").removeClass("cbp-spmenu-open");
    if(picker_all != null){
        picker_all.hideScreen();
    }
};

function disableOther( button ) {
    if( button !== 'showLeft' ) {
        classie.toggle( showLeft, 'disabled' );
    }
}

scheduler.init('scheduler_here', new Date(),"month");
var events = [];
$(document).ready(function(){
    //
    //jQuery('#form_date').datetimepicker({
    //    inline:false,
    //    theme:'light',
    //    lang:'nl',
    //    timepicker:false,
    //    datepicker:true,
    //    format:'d-m-Y'
    //});

    schedulerUpdate();

    document.querySelector("#form_save").onclick = saveResults;
    document.querySelector("#form_save").setAttribute("type","button");

});
Array.prototype.clean = function(deleteValue) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == deleteValue) {
            this.splice(i, 1);
            i--;
        }
    }
    return this;
};
function schedulerUpdate(){
    $.ajax({
        url: "/app/reservations",
        cache: false,
        dataType: 'json'
    }).done(function(data){
        scheduler.clearAll();
        events = [];
        var key, count = 0;
        for(key in data){
            if(data.hasOwnProperty(key)) {
                var room = data[key].room;
                var text = data[key].name + " " + data[key].lastname;//yyyy-MM-dd HH:mm:s
                var expDate = data[key].date.date.split(" ")[0].split("-");
                var expStartTime = data[key].startTime.date.split(" ")[1].split(".")[0].split(":");
                var expEndTime = data[key].endTime.date.split(" ")[1].split(".")[0].split(":");
                var startDate = expDate[1]+"/"+expDate[2]+"/"+expDate[0]+" "+expStartTime[0]+":"+expStartTime[1];//new Date(expDate[0],expDate[1],expDate[2],expStartTime[0],expStartTime[1],expStartTime[2]);
                var endDate = expDate[1]+"/"+expDate[2]+"/"+expDate[0]+" "+expEndTime[0]+":"+expEndTime[1];//new Date(expDate[0],expDate[1],expDate[2],expEndTime[0],expEndTime[1],expEndTime[2]);

                text += " "+room.name;
                events.push({id:parseInt(key), text:text,   start_date:startDate,end_date:endDate  });
            }
        }
        events.clean(undefined);

        scheduler.parse(events, "json");
        var el = document.querySelectorAll(".dhx_month_body");
        setTimeout(function(){
            for(var i =0; i<el.length;i++){
                el[i].onclick = function(e){
                    console.log(e.target.innerHTML);
                    scheduler._click.dhx_cal_today_button();

                }
            }
        }, 1000);
    });
}

function saveResults(){
    var room_id = document.querySelector("#form_room").value;
    var firstname = document.querySelector("#form_name").value;
    var surname = document.querySelector("#form_lastName").value;
    //var date = document.querySelector("#form_date").value;
    var form_date = document.querySelector("#form_date");
    var selects = form_date.getElementsByTagName("select");
    var dateRes = selects[2].value+"-"+selects[1].value+"-"+selects[0].value;
    var st1 = document.querySelector("#form_timeStart_hour").value;
    var st2 = document.querySelector("#form_timeStart_minute").value;
    var et1 = document.querySelector("#form_timeEnd_hour").value;
    var et2 = document.querySelector("#form_timeEnd_minute").value;

    st1 = parseInt(st1) < 10 ? "0"+st1 : ""+st1;
    st2 = parseInt(st2) < 10 ? "0"+st2 : ""+st2;
    et1 = parseInt(et1) < 10 ? "0"+et1 : ""+et1;
    et2 = parseInt(et2) < 10 ? "0"+et2 : ""+et2;

    console.log(dateRes);
    var startTime = st1+":"+st2;
    var endTime = et1+":"+et2;

    $.ajax({
        url: "/app/results",
        cache: false,
        method: 'post',
        data: {room_id: room_id, firstname: firstname, surname: surname, date:dateRes, startTime:startTime, endTime:endTime},
        dataType: 'json'
    }).done(function(data){
        var responseText = document.querySelector("#responseText");
        responseText.innerHTML = "";
        if(data[0] == "token") {
            var $val = data[1];
            var cookies = getCookie("tokens");
            if (cookies != "") {
                cookiesSplit = cookies.split(",");
                if(cookiesSplit.length == 20){
                    var newCookies = cookiesSplit[1];
                    for(var i=2;i<cookiesSplit.length;i++){
                        newCookies += ","+cookiesSplit[i];
                    }
                    cookies = newCookies;
                }
                setCookie("tokens",cookies+","+$val,365);
            } else {
                setCookie("tokens",$val,365);
            }


            document.querySelector(".modal-title").innerHTML = "Aanpassen";

            document.querySelector(".modal-body").innerHTML = "<p>Uw reservering is aangemaakt.<br />\nAls u deze graag aan wil passen heeft u een token nodig. " +
                "Het token is:<br />\n<pre>"+ $val+ "</pre><br />Ga naar <a href='/change/"+ $val+ "'>-website-/change/" + $val + "</a> </p>";//De tokens worden ook opgeslagen als cookies en die kunt u later weer oproepen!
            $('#puppupBox').modal('show');
            responseText.setAttribute("class", "");
            responseText.setAttribute("role", "");
        }else{
            if (data.length == 0){
                responseText.setAttribute("class", "");
                responseText.setAttribute("role", "");
            }
            var el;
            for(var i = 0;i < data.length;i++){

                el = document.createElement("div");
                el.innerHTML = data[i];
                responseText.appendChild(el);
                responseText.setAttribute("class","alert alert-danger");
                responseText.setAttribute("role", "alert");
            }
        }
        schedulerUpdate();
    });
}
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}
