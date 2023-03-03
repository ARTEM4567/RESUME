document.addEventListener('DOMContentLoaded', function () {

    const root_element_page = document.querySelector("#woocommerce_coderun_yandexnoip_notification_url");

    const button_copy = document.createElement('div');

    button_copy.className = "button_copy";
    button_copy.innerHTML = "Копировать";

      root_element_page.parentNode.insertBefore(button_copy, root_element_page.nextSibling);

    button_copy.addEventListener("click", function (e) {
        e.preventDefault();
        document.execCommand("copy");
    });

    button_copy.addEventListener("copy", function (event) {
        event.preventDefault();
        if (event.clipboardData) {
            event.clipboardData.setData("text/plain", root_element_page.value);
            button_copy.style.backgroundColor = "green";
            setTimeout(function () {
                button_copy.style.backgroundColor = "gray";
            }, 1000);
        }
    });
}
);