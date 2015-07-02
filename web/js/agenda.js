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
};

document.querySelector(".exit").onclick = function () {
    $(".cbp-spmenu-open").removeClass("cbp-spmenu-open");
};

function disableOther( button ) {
    if( button !== 'showLeft' ) {
        classie.toggle( showLeft, 'disabled' );
    }
}

scheduler.init('scheduler_here', new Date(),"month");
var events = [];
$(document).ready(function(){

    jQuery('#form_date').datetimepicker({
        inline:false,
        theme:'light',
        lang:'nl',
        timepicker:false,
        datepicker:true,
        format:'d-m-Y'
    });

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
        dataType: 'json',
    }).done(function(data){
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
                events.push({id:parseInt(key), text:text,   start_date:startDate,end_date:endDate });
            }
        }
        events.clean(undefined);

        scheduler.parse(events, "json");
        setTimeout(function(){
            for(var i =0; i<el.length;i++){
                el[i].onclick = function(e){
                    console.log(e.target.innerHTML);

                }
            }
        }, 1000);
        var el = document.querySelectorAll(".dhx_month_body");
    });
}
function saveResults(){
    var room_id = document.querySelector("#form_room").value;
    var firstname = document.querySelector("#form_name").value;
    var surname = document.querySelector("#form_lastName").value;
    var date = document.querySelector("#form_date").value;
    var startTime = document.querySelector("#form_timeStart_hour").value+":"+document.querySelector("#form_timeStart_minute").value;
    var endTime = document.querySelector("#form_timeEnd_hour").value+":"+document.querySelector("#form_timeEnd_minute").value;

    $.ajax({
        url: "/app/results",
        cache: false,
        method: 'post',
        data: {room_id: room_id, firstname: firstname, surname: surname, date:date, startTime:startTime, endTime:endTime},
        dataType: 'json'
    }).done(function(data){
        var responseText = document.querySelector("#responseText");
        responseText.innerHTML = "";

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

        schedulerUpdate();
    });
}
