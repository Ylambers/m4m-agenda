/**
 * Created by Alwin on 9-7-2015.
 */
var picker_all;
window.onload = function(){


    var timers = document.querySelectorAll(".picker");

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
            var selects = e.target.getElementsByTagName("select");
            //console.log(selects[0].value, selects[1].value);

            picker_all = timePicker(e.target, selects[0].value, selects[1].value);
            //var picker = timePicker(e.target, 23, 59);
            picker_all.open();
        };
    }
};

function timePicker(element,hours,minutes){

    this.hours = hours == undefined ? 0 : hours;
    this.minutes = minutes == undefined ? 0 : minutes;
    this.element = element;
    if(this.minutes % 5){
        var x = this.minutes;
        console.log("Before: "+this.minutes);
        for(var i=3;i < 60;i +=5){
            if(x < i){
                this.minutes = i-3;
                break;
            }
        }
        this.minutes = this.minutes > 55 ? 55 : this.minutes;
        console.log("After: "+this.minutes);
    }

    this.open = function(){
        var picker = document.createElement("div");
        var hours = document.createElement("div");
        var minutes = document.createElement("div");
        picker.id = "m4mPicker";
        hours.setAttribute("class", "hours");
        hours.dataset.val = this.hours;
        minutes.setAttribute("class", "minutes");
        minutes.dataset.val = this.minutes;

        hours.appendChild(emptyDiv());

        minutes.appendChild(emptyDiv());

        for(var i=0; i<24;i++){
            var item = document.createElement("div");
            item.setAttribute("class","item");
            item.innerHTML = i < 10 ? "0"+i : ""+i;
            hours.appendChild(item);
        }

        for(var i=0; i<12;i++){
            var item = document.createElement("div");
            item.setAttribute("class","item");
            item.innerHTML = i < 2 ? "0"+i*5 : ""+i*5;
            minutes.appendChild(item);
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


        var bg = document.createElement("div");
        bg.setAttribute("class","bg");


        hours.appendChild(emptyDiv());
        minutes.appendChild(emptyDiv());

        bg.appendChild(selected);
        bg.appendChild(upH);
        bg.appendChild(upM);
        bg.appendChild(hours);
        bg.appendChild(minutes);
        bg.appendChild(downH);
        bg.appendChild(downM);
        bg.appendChild(done);
        picker.appendChild(bg);

        document.body.appendChild(picker);

        document.querySelector("#m4mPicker .hours").scrollTop = (this.hours * 88.5);
        //console.log(this.hours * 60);
        document.querySelector("#m4mPicker .minutes").scrollTop = ((this.minutes/5) * 88.5);

        document.querySelector("#m4mPicker .upH").onclick = function(){
            var h = document.querySelector("#m4mPicker .hours");
            hVal = parseInt(h.dataset.val);
            if(hVal > 0){
                hVal--;
            }
            h.scrollTop = (hVal * 88.5);
            h.dataset.val = hVal;
        };
        document.querySelector("#m4mPicker .downH").onclick = function(){
            var h = document.querySelector("#m4mPicker .hours");
            hVal = parseInt(h.dataset.val);
            if(hVal < 23) {
                hVal++;
            }
            h.scrollTop = (hVal * 88.5);
            h.dataset.val = hVal;
        };
        document.querySelector("#m4mPicker .upM").onclick = function(){
            var m = document.querySelector("#m4mPicker .minutes");
            mVal = parseInt(m.dataset.val);
            if(mVal > 0){
                mVal = mVal - 5;
            }
            m.scrollTop = ((mVal/5) * 88.5);
            m.dataset.val = mVal;
        };
        document.querySelector("#m4mPicker .downM").onclick = function(){
            var m = document.querySelector("#m4mPicker .minutes");
            mVal = parseInt(m.dataset.val);
            if(mVal < 55){
                mVal = mVal + 5;
            }
            m.scrollTop = ((mVal/5) * 88.5);
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

            picker_all = null;
            document.getElementById('m4mPicker').parentNode.removeChild(document.getElementById('m4mPicker'));
        };
    };
    this.getElement = function(){
        return this.element;
    };
    return this;
}
function emptyDiv(){

    var empty = document.createElement("div");
    empty.setAttribute("class", "empty");
    return empty;
}