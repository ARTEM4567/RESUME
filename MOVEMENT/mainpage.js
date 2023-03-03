let burgerMenu = document.getElementById('burger_menu');
let closeButton = document.getElementById('close_button');
let burgerMenuBg = document.getElementById('burger_menu_bg');

console.log(burgerMenu);

burgerMenu.addEventListener('click', () => {
    burgerMenuBg.classList.remove('display_none');
})

closeButton.addEventListener('click', () => {
    burgerMenuBg.classList.add('display_none');
})

window.addEventListener('click', (event) => {
    if (event.target == burgerMenuBg){
        burgerMenuBg.classList.add('display_none');
    }
})