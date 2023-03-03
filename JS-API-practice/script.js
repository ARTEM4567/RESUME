let row = document.querySelector('.row');

function mainCard (array){
    let col = document.createElement('div');
    let card = document.createElement('div');
    let cardImgTop = document.createElement('img');
    let cardBody = document.createElement('div');
    let cardTitle = document.createElement('h5');
    let cardTextContinent = document.createElement('p');
    let cardText1 = document.createElement('p');
    let cardText2 = document.createElement('p');
    let cardText3 = document.createElement('p');

    col.classList.add('col');
    card.classList.add('card');
    cardImgTop.classList.add('card-img-top');
    cardBody.classList.add('card-body');
    cardTitle.classList.add('card-title');
    cardTextContinent.classList.add('card-text');
    cardText1.classList.add('card-text');
    cardText2.classList.add('card-text');
    cardText3.classList.add('card-text');

    cardImgTop.src = array[0].flags.svg;
    cardTitle.innerText = array[0].name.common;
    cardTextContinent.innerText = array[0].continents[0];
    cardText1.innerText = (array[0].population / 1000000).toFixed(1) + ' млн';
    for (let key in array[0].languages) {
        cardText2.innerText = array[0].languages[key];
    }
    for (let key in array[0].currencies) {
        cardText3.innerText = array[0].currencies[key].symbol + ' ' + array[0].currencies[key].name;
    }

    row.append(col);
    col.append(card);
    card.append(cardImgTop);
    card.append(cardBody);
    cardBody.append(cardTitle);
    cardBody.append(cardTextContinent);
    cardBody.append(cardText1);
    cardBody.append(cardText2);
    cardBody.append(cardText3);    
}

function neighborCard (array){
    let col = document.createElement('div');
    let card = document.createElement('div');
    let headText = document.createElement('p');
    let cardImgTop = document.createElement('img');
    let cardBody = document.createElement('div');
    let cardTitle = document.createElement('h5');
    let cardTextContinent = document.createElement('p');
    let cardText1 = document.createElement('p');
    let cardText2 = document.createElement('p');
    let cardText3 = document.createElement('p');

    col.classList.add('col');
    card.classList.add('card');
    card.classList.add('neighbor_card');
    headText.classList.add('headText');
    cardImgTop.classList.add('card-img-top');
    cardBody.classList.add('card-body');
    cardTitle.classList.add('card-title');
    cardTextContinent.classList.add('card-text');
    cardText1.classList.add('card-text');
    cardText2.classList.add('card-text');
    cardText3.classList.add('card-text');

    headText.innerText = 'Соседняя страна';
    cardImgTop.src = array[0].flags.svg;
    cardTitle.innerText = array[0].name.common;
    cardTextContinent.innerText = array[0].continents[0];
    cardText1.innerText = (array[0].population / 1000000).toFixed(1) + ' млн';
    for (let key in array[0].languages) {
        cardText2.innerText = array[0].languages[key];
    }
    for (let key in array[0].currencies) {
        cardText3.innerText = array[0].currencies[key].symbol + ' ' + array[0].currencies[key].name;
    }

    row.append(col);
    col.append(card);
    card.append(headText);
    card.append(cardImgTop);
    card.append(cardBody);
    cardBody.append(cardTitle);
    cardBody.append(cardTextContinent);
    cardBody.append(cardText1);
    cardBody.append(cardText2);
    cardBody.append(cardText3);    
}

fetch('https://restcountries.com/v3.1/name/peru')
    .then((response) => {
    return response.json();
    })
    .then((cardObject) => {
        console.log(cardObject);
        mainCard(cardObject);

        let borders = cardObject[0].borders;

        borders.forEach(element => {
            fetch(`https://restcountries.com/v3.1/name/${element}`)
                .then((response) => {
                return response.json();
                })
                .then((cardObject) => {
                    neighborCard(cardObject);

            })
        });

    })