

let httpRequest = new XMLHttpRequest();
// Верхняя часть (погода сейчас)
let h2 = document.querySelector('h2');
let timeNow = document.querySelector('.time_now');
let topTemperature = document.querySelector('.top_temperature');
let weatherСonditions = document.querySelector('h3');
let speed = document.querySelector('.Speed');
let topPicture = document.querySelector('.top_picture');


// Функция определяющая количество блоков с прогнозом погоды
function weather (numberIndex){
    for (let i = 1; i <= numberIndex; i++){
        bottomDiv = document.createElement('div');
        bottomDiv.classList.add('bottom_div')
        bottomDiv.id = `bottom_div_${i}`
        date = document.createElement('h4');
        weatherImg = document.createElement('img');
        temp = document.createElement('p');

        date.innerText = JSON.parse(httpRequest.responseText).list[i].dt_txt;
        weatherImg.src = `http://openweathermap.org/img/wn/${JSON.parse(httpRequest.responseText).list[i].weather[0].icon}.png`;
        temp.innerText = Math.round(JSON.parse(httpRequest.responseText).list[i].main.temp - 273.15) + '°C';
        
        document.querySelector('.bottom').appendChild(bottomDiv);
        document.getElementById(`bottom_div_${i}`).appendChild(date);
        document.getElementById(`bottom_div_${i}`).appendChild(weatherImg);
        document.getElementById(`bottom_div_${i}`).appendChild(temp);
    };
};

httpRequest.onload = function() {
    // Верхняя часть (погода сейчас)
    console.log(JSON.parse(httpRequest.responseText).list[0]);
    console.log(JSON.parse(httpRequest.responseText).list[1]);
    console.log(JSON.parse(httpRequest.responseText).list)
    h2.innerText = JSON.parse(httpRequest.responseText).city.name;
    timeNow.innerText = new Date().toLocaleTimeString().slice(0,-3);
    topTemperature.innerText = Math.round(JSON.parse(httpRequest.responseText).list[0].main.temp - 273.15) + '°C';
    weatherСonditions.innerText = JSON.parse(httpRequest.responseText).list[0].weather[0].main;
    speed.innerText = JSON.parse(httpRequest.responseText).list[0].wind.speed + ' m/s';
    topPicture.src = `http://openweathermap.org/img/wn/${JSON.parse(httpRequest.responseText).list[0].weather[0].icon}.png`;

    weather(5);
};



httpRequest.open('GET', 'https://api.openweathermap.org/data/2.5/forecast?q=Minsk&appid=a94d0a5ac08570add4b47b8da933f247');
httpRequest.send();

console.log(Date())