/**
 * Created by Alwin on 9-7-2015.
 */
var picker_all;
window.onload = function(){

    var timers = document.querySelectorAll(".timePicker");

    for(var i=0;i<timers.length;i++){
        timers[i].innerHTML = timers[i].innerHTML.split(":").join("");
        //timers[i].innerHTML = timers[i].innerHTML.split(":").join("");
        var selects = timers[i].getElementsByTagName("select");
        var span = document.createElement("span");
        var s0 = selects[0].value;
        var s1 = selects[1].value;
        span.innerHTML = (s0 < 10 ? "0"+s0 : ""+s0) + ":" + (s1 < 10 ? "0"+s1 : ""+s1);
        timers[i].appendChild(span);
        timers[i].onclick = function(e){
            //alert(e.target.innerHTML);
            var selects = e.target.querySelectorAll("select");
            //console.log(selects[0].value);
            //console.log(selects[1].value);

            //console.log(selects[0].value, selects[1].value);

            if(picker_all != null){
                picker_all.hideScreen();
            }
            picker_all = timePicker(e.target, selects[0].value, selects[1].value);
            //var picker = timePicker(e.target, 23, 59);
            picker_all.openScreen();
        };
    }
    var datesToPick = document.querySelectorAll(".datePicker");

    for(var i=0;i<datesToPick.length;i++){
        //timers[i].innerHTML = timers[i].innerHTML.split(":").join("");
        var selects = datesToPick[i].getElementsByTagName("select");
        var span = document.createElement("span");
        var s0 = selects[0].value;
        var s1 = selects[1].value;
        var s2 = selects[2].value;
        span.innerHTML = (s2 < 10 ? "0"+s2 : ""+s2) + "-" + (s1 < 10 ? "0"+s1 : ""+s1)+"-"+s0;
        datesToPick[i].appendChild(span);
        datesToPick[i].onclick = function(e){
            //alert(e.target.innerHTML);
            var selects = e.target.querySelectorAll("select");
            //console.log(selects[0].value);
            //console.log(selects[1].value);

            //console.log(selects[0].value, selects[1].value);

            if(picker_all != null){
                picker_all.hideScreen();
            }
            picker_all = datePicker(e.target, selects[2].value, selects[1].value, selects[0].value);
            //var picker = timePicker(e.target, 23, 59);
            picker_all.openScreen();
        };
    }
};

function datePicker(element,day,month,year){

    this.element = element;

    this.day = day == undefined ? 0 : day;
    this.month = month == undefined ? 0 : month;
    this.year = "";

    var mOptions = this.element.getElementsByTagName("select")[1].getElementsByTagName("option");
    this.monthsArrayNames = [];
    this.monthsArrayIds = [];
    for(i=0; i<mOptions.length;i++) {
        this.monthsArrayNames[i] = mOptions[i].innerHTML;
        this.monthsArrayIds[i] = mOptions[i].value;
        if(month == mOptions[i].value){
            this.month = i;
        }
    }

    var yOptions = this.element.getElementsByTagName("select")[0].getElementsByTagName("option");
    this.yearsArray = [];
    for(i=0; i<yOptions.length;i++) {
        this.yearsArray[i] = yOptions[i].value;
        if(year == yOptions[i].value){
            this.year = i;
        }
    }

    this.openScreen = function(){
        var picker = document.createElement("div");
        var days = document.createElement("div");
        var months = document.createElement("div");
        var years = document.createElement("div");

        picker.id = "m4mPicker";
        picker.dataset.datepicker = true;

        days.setAttribute("class", "days");
        days.dataset.val = this.day;

        months.setAttribute("class", "months");
        months.dataset.val = this.month;

        years.setAttribute("class", "years");
        years.dataset.val = this.year;

        var dInner = crDiv("inner");
        var mInner = crDiv("inner");
        var yInner = crDiv("inner");


        dInner.appendChild(crDiv("empty"));
        mInner.appendChild(crDiv("empty"));
        yInner.appendChild(crDiv("empty"));

        var dLength = 32;
        var i;
        for(i=1; i<dLength;i++){
            var item = document.createElement("div");
            item.setAttribute("class","item");
            item.innerHTML = i < 10 ? "0"+i : ""+i;
            dInner.appendChild(item);
        }

        for(i=0; i<this.monthsArrayNames.length;i++){
            var item = document.createElement("div");
            item.setAttribute("class","item");
            item.innerHTML = this.monthsArrayNames[i];//i < 10 ? "0"+i : ""+i;
            mInner.appendChild(item);
        }

        for(i=0; i<this.yearsArray.length;i++){
            var item = document.createElement("div");
            item.setAttribute("class","item");
            item.innerHTML = this.yearsArray[i];
            yInner.appendChild(item);
        }

        var upD = document.createElement("i");
        upD.setAttribute("class", "fa fa-caret-up");
        upD.dataset.target = "days";
        upD.dataset.direction = "up";

        var upM = document.createElement("i");
        upM.setAttribute("class", "fa fa-caret-up");
        upM.dataset.target = "months";
        upM.dataset.direction = "up";

        var upY = document.createElement("i");
        upY.setAttribute("class", "fa fa-caret-up");
        upY.dataset.target = "years";
        upY.dataset.direction = "up";


        var downD = document.createElement("i");
        downD.setAttribute("class", "fa fa-caret-down");
        downD.dataset.target = "days";
        downD.dataset.direction = "down";
        downD.dataset.max = dLength;

        var downM = document.createElement("i");
        downM.setAttribute("class", "fa fa-caret-down");
        downM.dataset.target = "months";
        downM.dataset.direction = "down";
        downM.dataset.max = "12";

        var downY = document.createElement("i");
        downY.setAttribute("class", "fa fa-caret-down");
        downY.dataset.target = "years";
        downY.dataset.direction = "down";
        downY.dataset.max = this.yearsArray.length;



        var selected = document.createElement("div");
        selected.setAttribute("class","selected");


        var done = document.createElement("div");
        done.setAttribute("id","done");
        //done.style.width = "225px";
        done.innerHTML = "Opslaan";

        var cancel = document.createElement("div");
        cancel.setAttribute("id","cancel");
        //cancel.style.width = "225px";
        cancel.innerHTML = "Annuleren";
        //
        //
        var bg = document.createElement("div");
        bg.setAttribute("class","bg");
        //
        //
        //
        dInner.appendChild(crDiv("empty"));
        mInner.appendChild(crDiv("empty"));
        yInner.appendChild(crDiv("empty"));
        //
        days.appendChild(dInner);
        months.appendChild(mInner);
        years.appendChild(yInner);
        //
        //
        bg.appendChild(crDiv("overlapTop"));
        bg.appendChild(crDiv("overlapBottom"));
        bg.appendChild(selected);

        bg.appendChild(upD);
        bg.appendChild(upM);
        bg.appendChild(upY);

        bg.appendChild(days);
        bg.appendChild(months);
        bg.appendChild(years);

        bg.appendChild(downD);
        bg.appendChild(downM);
        bg.appendChild(downY);

        bg.appendChild(done);
        bg.appendChild(cancel);
        picker.appendChild(bg);

        //picker.style.width = "450px";
        //picker.style.marginLeft = "-225px";

        //
        document.body.appendChild(picker);
        document.body.appendChild(crDiv("shadow_background"));
        //
        document.querySelector("#m4mPicker .days").dataset.val = this.day-1;
        document.querySelector("#m4mPicker .months").dataset.val = this.month;
        document.querySelector("#m4mPicker .years").dataset.val = this.year;


        document.querySelector("#m4mPicker .days .inner").scrollTop = ((this.day-1) * 88.5);
        document.querySelector("#m4mPicker .months .inner").scrollTop = (this.month * 88.5);
        document.querySelector("#m4mPicker .years .inner").scrollTop = (this.year * 88.5);
        document.querySelector(".shadow_background").style.opacity = 1;
        document.querySelector("#m4mPicker").style.opacity = 1;

        var upEls = document.querySelectorAll("#m4mPicker [data-direction]");
        for(i=0;i<upEls.length;i++){
            upEls[i].onclick = function(ev){
                var a = document.querySelector("#m4mPicker ."+ev.target.dataset.target);
                aVal = parseInt(a.dataset.val);
                if(ev.target.dataset.direction == "up"){
                    if(aVal > 0){
                        aVal--;
                    }
                }else{

                    if(aVal < parseInt(ev.target.dataset.max)){
                        aVal++;
                    }
                }
                a.querySelector(".inner").scrollTop = (aVal * 88.5);
                a.dataset.val = aVal;
            };
        }
        document.querySelector("#m4mPicker #done").onclick = function(){
            var d = document.querySelector("#m4mPicker .days");
            var m = document.querySelector("#m4mPicker .months");
            var y = document.querySelector("#m4mPicker .years");
            dVal = parseInt(d.dataset.val)+1;
            mVal = parseInt(m.dataset.val);
            yVal = parseInt(y.dataset.val);

            var el = picker_all.getElement();
            var selects = el.getElementsByTagName("select");
            selects[0].value = picker_all.yearsArray[yVal];
            selects[1].value = mVal+1;
            selects[2].value = dVal;
            //document.querySelector(".picker")
            mVal = picker_all.monthsArrayIds[mVal];
            el.getElementsByTagName("span")[0].innerHTML = (dVal < 10 ? "0"+dVal : ""+dVal) + "-" + (mVal < 10 ? "0"+mVal : ""+mVal)+"-"+picker_all.yearsArray[yVal];


            picker_all.hideScreen();
            //picker_all = null;
            //document.getElementById('m4mPicker').parentNode.removeChild(document.getElementById('m4mPicker'));
        };
        document.querySelector("#m4mPicker #cancel").onclick = function(){
            picker_all.hideScreen();
        };
        var scrollTimer = -1;
        document.querySelector("#m4mPicker .days .inner").addEventListener("scroll",function(){
            if (scrollTimer != -1)
                clearTimeout(scrollTimer);

            scrollTimer = window.setTimeout(function(){
                setDateGood();
            }, 500);
        },false);
        document.querySelector("#m4mPicker .months .inner").addEventListener("scroll",function(){
            if (scrollTimer != -1)
                clearTimeout(scrollTimer);

            scrollTimer = window.setTimeout(function(){
                setDateGood();
            }, 500);
        },false);
        document.querySelector("#m4mPicker .years .inner").addEventListener("scroll",function(){
            if (scrollTimer != -1)
                clearTimeout(scrollTimer);

            scrollTimer = window.setTimeout(function(){
                setDateGood();
            }, 500);
        },false);
    };
    this.hideScreen = function(){

        document.querySelector(".shadow_background").style.opacity = 0;
        document.querySelector("#m4mPicker").style.opacity = 0;
        setTimeout(function(){
            document.getElementById('m4mPicker').parentNode.removeChild(document.getElementById('m4mPicker'));

            document.querySelector('.shadow_background').parentNode.removeChild(document.querySelector('.shadow_background'));
            picker_all = null;
        },500);
    };
    this.getElement = function(){
        return this.element;
    };
    return this;
}


function timePicker(element,hours,minutes){

    this.hours = hours == undefined ? 0 : hours;
    this.minutes = minutes == undefined ? 0 : minutes;
    this.element = element;
    if(this.minutes % 5){
        var x = this.minutes;
        for(var i=3;i < 60;i +=5){
            if(x < i){
                this.minutes = i-3;
                break;
            }
        }
        this.minutes = this.minutes > 55 ? 55 : this.minutes;
    }
    this.openScreen = function(){
        var picker = document.createElement("div");
        var hours = document.createElement("div");
        var minutes = document.createElement("div");
        picker.id = "m4mPicker";
        hours.setAttribute("class", "hours");
        hours.dataset.val = this.hours;
        minutes.setAttribute("class", "minutes");
        minutes.dataset.val = this.minutes;
        var hInner = document.createElement("div");
        hInner.setAttribute("class","inner");
        var mInner = document.createElement("div");
        mInner.setAttribute("class","inner");

        hInner.appendChild(crDiv("empty"));

        mInner.appendChild(crDiv("empty"));

        for(var i=0; i<24;i++){
            var item = document.createElement("div");
            item.setAttribute("class","item");
            item.innerHTML = i < 10 ? "0"+i : ""+i;
            hInner.appendChild(item);
        }

        for(var i=0; i<12;i++){
            var item = document.createElement("div");
            item.setAttribute("class","item");
            item.innerHTML = i < 2 ? "0"+i*5 : ""+i*5;
            mInner.appendChild(item);
        }
        var upH = document.createElement("i");
        upH.setAttribute("class", "upH fa fa-caret-up");
        var upM = document.createElement("i");
        upM.setAttribute("class", "upM fa fa-caret-up");
        var downH = document.createElement("i");
        downH.setAttribute("class", "downH fa fa-caret-down");
        var downM = document.createElement("i");
        downM.setAttribute("class", "downM fa fa-caret-down");

        var selected = document.createElement("div");
        selected.setAttribute("class","selected");


        var done = document.createElement("div");
        done.setAttribute("id","done");
        done.innerHTML = "Opslaan";

        var cancel = document.createElement("div");
        cancel.setAttribute("id","cancel");
        cancel.innerHTML = "Annuleren";


        var bg = document.createElement("div");
        bg.setAttribute("class","bg");



        hInner.appendChild(crDiv("empty"));
        mInner.appendChild(crDiv("empty"));

        hours.appendChild(hInner);
        minutes.appendChild(mInner);


        bg.appendChild(crDiv("overlapTop"));
        bg.appendChild(crDiv("overlapBottom"));
        bg.appendChild(selected);
        bg.appendChild(upH);
        bg.appendChild(upM);
        bg.appendChild(hours);
        bg.appendChild(minutes);
        bg.appendChild(downH);
        bg.appendChild(downM);
        bg.appendChild(done);
        bg.appendChild(cancel);
        picker.appendChild(bg);

        document.body.appendChild(picker);
        document.body.appendChild(crDiv("shadow_background"));

        document.querySelector("#m4mPicker .hours").dataset.val = this.hours;
        document.querySelector("#m4mPicker .minutes").dataset.val = this.minutes/5;

        //console.log(document.querySelector("#m4mPicker .hours").dataset.val);
        //console.log(document.querySelector("#m4mPicker .minutes").dataset.val);
        document.querySelector("#m4mPicker .hours .inner").scrollTop = (this.hours * 88.5);
        document.querySelector("#m4mPicker .minutes .inner").scrollTop = ((this.minutes/5) * 88.5);
        document.querySelector(".shadow_background").style.opacity = 1;
        document.querySelector("#m4mPicker").style.opacity = 1;

        document.querySelector("#m4mPicker .upH").onclick = function(){
            var h = document.querySelector("#m4mPicker .hours");
            hVal = parseInt(h.dataset.val);
            if(hVal > 0){
                hVal--;
            }
            h.querySelector(".inner").scrollTop = (hVal * 88.5);
            h.dataset.val = hVal;
        };
        document.querySelector("#m4mPicker .downH").onclick = function(){
            var h = document.querySelector("#m4mPicker .hours");
            hVal = parseInt(h.dataset.val);
            if(hVal < 23) {
                hVal++;
            }
            h.querySelector(".inner").scrollTop = (hVal * 88.5);
            h.dataset.val = hVal;
        };
        document.querySelector("#m4mPicker .upM").onclick = function(){
            var m = document.querySelector("#m4mPicker .minutes");
            mVal = parseInt(m.dataset.val);
            if(mVal > 0){
                mVal = mVal - 5;
            }
            m.querySelector(".inner").scrollTop = ((mVal/5) * 88.5);
            m.dataset.val = mVal;
        };
        document.querySelector("#m4mPicker .downM").onclick = function(){
            var m = document.querySelector("#m4mPicker .minutes");
            mVal = parseInt(m.dataset.val);
            if(mVal < 55){
                mVal = mVal + 5;
            }
            m.querySelector(".inner").scrollTop = ((mVal/5) * 88.5);
            m.dataset.val = mVal;
        };
        document.querySelector("#m4mPicker #done").onclick = function(){
            var h = document.querySelector("#m4mPicker .hours");
            var m = document.querySelector("#m4mPicker .minutes");
            hVal = parseInt(h.dataset.val);
            mVal = parseInt(m.dataset.val);
            var el = picker_all.getElement();
            var selects = el.getElementsByTagName("select");
            selects[0].value = hVal;
            selects[1].value = mVal;
            //document.querySelector(".picker")
            el.getElementsByTagName("span")[0].innerHTML = (hVal < 10 ? "0"+hVal : ""+hVal) + ":" + (mVal < 10 ? "0"+mVal : ""+mVal);


            picker_all.hideScreen();
            //picker_all = null;
            //document.getElementById('m4mPicker').parentNode.removeChild(document.getElementById('m4mPicker'));
        };
        document.querySelector("#m4mPicker #cancel").onclick = function(){
            picker_all.hideScreen();
        };
        //document.querySelector("#m4mPicker .hours .inner").onscroll = function(e){
        //    is_scrolling = true;
        //};
        var scrollTimer = -1;
        document.querySelector("#m4mPicker .hours .inner").addEventListener("scroll",function(){
            if (scrollTimer != -1)
                clearTimeout(scrollTimer);

            scrollTimer = window.setTimeout(function(){
                setTimeGood();
            }, 500);
        },false);
        document.querySelector("#m4mPicker .minutes .inner").addEventListener("scroll",function(){
            if (scrollTimer != -1)
                clearTimeout(scrollTimer);

            scrollTimer = window.setTimeout(function(){
                setTimeGood();
            }, 500);
        },false);
    };
    this.hideScreen = function(){

        document.querySelector(".shadow_background").style.opacity = 0;
        document.querySelector("#m4mPicker").style.opacity = 0;
        setTimeout(function(){
            document.getElementById('m4mPicker').parentNode.removeChild(document.getElementById('m4mPicker'));

            document.querySelector('.shadow_background').parentNode.removeChild(document.querySelector('.shadow_background'));
            picker_all = null;
        },500);
    };
    this.getElement = function(){
        return this.element;
    };
    return this;
}
function setTimeGood(){

    var h = document.querySelector("#m4mPicker .hours");
    var m = document.querySelector("#m4mPicker .minutes");
    hScroll = h.querySelector(".inner").scrollTop / 88.5;
    mScroll = m.querySelector(".inner").scrollTop / 88.5;
    h.querySelector(".inner").scrollTop = (Math.round(hScroll) * 88.5);
    m.querySelector(".inner").scrollTop = (Math.round(mScroll) * 88.5);
    h.dataset.val = Math.round(hScroll);
    m.dataset.val = Math.round(mScroll*5);

}
function setDateGood(){

    var d = document.querySelector("#m4mPicker .days");
    var m = document.querySelector("#m4mPicker .months");
    var y = document.querySelector("#m4mPicker .years");
    dScroll = d.querySelector(".inner").scrollTop / 88.5;
    mScroll = m.querySelector(".inner").scrollTop / 88.5;
    yScroll = y.querySelector(".inner").scrollTop / 88.5;
    d.querySelector(".inner").scrollTop = (Math.round(dScroll) * 88.5);
    m.querySelector(".inner").scrollTop = (Math.round(mScroll) * 88.5);
    y.querySelector(".inner").scrollTop = (Math.round(yScroll) * 88.5);
    d.dataset.val = Math.round(dScroll);
    m.dataset.val = Math.round(mScroll);
    y.dataset.val = Math.round(yScroll);

}
function crDiv(className){
    var overlap = document.createElement("div");
    overlap.setAttribute("class", className);
    return overlap;
}