var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
    showLeft = document.getElementById( 'showLeft' ),

    body = document.body;

showLeft.onclick = function() {
    classie.toggle( this, 'active' );
    classie.toggle( menuLeft, 'cbp-spmenu-open' );
    disableOther( 'showLeft' );
};
function disableOther( button ) {
    if( button !== 'showLeft' ) {
        classie.toggle( showLeft, 'disabled' );
    }
}


scheduler.init('scheduler_here', new Date(),"month");
var events = [];
$(document).ready(function(){

    $.ajax({
        url: "/app/reservations",
        cache: false,
        dataType: 'json',
    }).done(function(data){
        var key, count = 0;
        for(key in data){
            if(data.hasOwnProperty(key)) {
                var text = data[key].name + " " + data[key].lastname;//yyyy-MM-dd HH:mm:s
                var expDate = data[key].date.date.split(" ")[0].split("-");
                var expStartTime = data[key].startTime.date.split(" ")[1].split(".")[0].split(":");
                var expEndTime = data[key].endTime.date.split(" ")[1].split(".")[0].split(":");
                var startDate = expDate[1]+"/"+expDate[2]+"/"+expDate[0]+" "+expStartTime[0]+":"+expStartTime[1];//new Date(expDate[0],expDate[1],expDate[2],expStartTime[0],expStartTime[1],expStartTime[2]);
                var endDate = expDate[1]+"/"+expDate[2]+"/"+expDate[0]+" "+expEndTime[0]+":"+expEndTime[1];//new Date(expDate[0],expDate[1],expDate[2],expEndTime[0],expEndTime[1],expEndTime[2]);

                events.push({id:parseInt(key), text:text,   start_date:startDate,end_date:endDate});

            }
        }
        events.clean(undefined);

        scheduler.parse(events, "json");

    });

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