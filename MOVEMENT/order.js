let changeButton = document.getElementById('changeButton');
let linearProducts = document.querySelector('.linear_products');
let gridProducts = document.querySelector('.grid_products');

changeButton.addEventListener('click', () => {
    changeButton.classList.toggle('change_button1');
    linearProducts.classList.toggle('display_none');
    gridProducts.classList.toggle('display_none');
})