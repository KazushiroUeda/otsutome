function timeFormat(datetime) {
    var dd = new Date(datetime);
    var hh = dd.getHours();
    var mm = dd.getMinutes();
    var ss = dd.getSeconds();
    if (hh < 10) { hh = "0" + hh; }
    if (mm < 10) { mm = "0" + mm; }
    if (ss < 10) { ss = "0" + ss; }
    return hh + ":" + mm + ":" + ss;
}

var body = document.body;
var timerElem = document.createElement('aside');
timerElem.classList.add('timer-wrap');
var timerText = document.createElement('div');
timerText.classList.add('timer-time');
var mainText = document.createElement('div');
mainText.classList.add('timer-text');
mainText.innerText = 'まもなく午後2時です';
var subText = document.createElement('div');
subText.classList.add('timer-sub');
subText.innerText = '(Japan Jiba)';
var styles = document.createElement('style');
styles.innerText = `.timer-wrap {
    position: fixed;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    display: none;
    flex-wrap: wrap;
    padding: 10px;
    background: #ffeae5;
    border: solid 2px;
    color: #f02d00;
    font-weight: bolder;
    z-index: 99999;
}
.timer-wrap.active {
    display: flex;
}
.timer-text, .timer-time, .timer-sub {
    width: 100%;
    text-align: center;
    line-height: 1;
}
.timer-time {
    margin: 8px auto 2px;
}
.timer-sub {
    font-size: 70%;
    color: #aaa;
}`;
timerElem.appendChild(mainText);
timerElem.appendChild(timerText);
timerElem.appendChild(subText);

function getTime(){
    var datetime = null;
    $.ajax({
        async: false,
        url: 'https://worldtimeapi.org/api/timezone/Asia/Tokyo',
        dataType: 'json',
        success: function(data){
            datetime = data.datetime;
        }
    });
    return datetime;
}

setInterval(function(){
    var time = getTime();
    if(timeFormat(time) >= '16:45:00' && timeFormat(time) < '16:46:00'){
        timerElem.classList.add('active');
        timerText.innerText = timeFormat(time);
    } else {
        timerElem.classList.remove('active');
    }
},1000);

body.appendChild(styles);
body.appendChild(timerElem);
