let password = document.getElementById('password');
let hideView = document.getElementById('hide_view');

let bool = true;


hideView.addEventListener('click', (event) => {
    event.preventDefault();
    if (bool == true){
        password.type = 'text';
        hideView.classList.remove('view');
        bool = false;
    } else {
        password.type = 'password';
        hideView.classList.add('view');
        bool = true;
    }
})