function getUrlVars() {
    let vars = {};
    let parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}

function getValue(name) {
    return (document.getElementById(name).value || false);
}

function showApplePayButton() {
    HTMLCollection.prototype[Symbol.iterator] = Array.prototype[Symbol.iterator];
    const buttons = document.getElementsByClassName("apple-button-wrapper");
    for (let button of buttons) {
        button.className += " visible";
    }
}

function applePayButtonClicked(label, amount, account, signature, salt) {
    const paymentRequest = {
        countryCode: "RU",
        currencyCode: "RUB",
        total: {
            label: label,
            amount: amount
        },
        supportedNetworks: ["masterCard", "visa"],
        merchantCapabilities: ["supports3DS"]
    };

    const session = new ApplePaySession(1, paymentRequest);

    session.onvalidatemerchant = function (event) {
        const validationURL = event.validationURL;
        getApplePaySession(validationURL, account, signature, salt, label).then(function (response) {
            session.completeMerchantValidation(response);
        });
    };

    session.onpaymentauthorized = function (event) {
        paymentAuthorized(event.payment);
    };

    session.begin();
}

function paymentAuthorized(token) {
    // submit form to securecarddata
    let monetadomain = getValue('monetadomain');
    let publicid = getValue('publicid');
    let orderaccountid = getValue('orderaccountid');
    let transactionid = getValue('transactionid');
    let secsignature = getValue('secsignature');
    let asssignature = getValue('asssignature');
    let orderamount = getValue('orderamount');
    let unitid = getValue('unitid');

    let ap_form;
    ap_form = document.createElement("FORM");
    ap_form.name = "monetaprocessapplepay";
    ap_form.method = "POST";
    ap_form.action = "https://" + encodeURI(monetadomain) + "/secureData.htm";

    let ap_tb;
    ap_tb = document.createElement("INPUT");
    ap_tb.type = "TEXT";
    ap_tb.name = "publicId";
    ap_tb.value = encodeURI(publicid);
    ap_form.appendChild(ap_tb);

    let redirecturl = "/assistant.htm?MNT_ID=" + encodeURI(orderaccountid) + "&MNT_TRANSACTION_ID=" + encodeURI(transactionid) + "&MNT_AMOUNT=" + encodeURI(orderamount) + "&paymentSystem.unitId=" + encodeURI(unitid) + "&followup=true";
    ap_tb = document.createElement("INPUT");
    ap_tb.type = "TEXT";
    ap_tb.name = "redirectUrl";
    ap_tb.value = redirecturl;
    ap_form.appendChild(ap_tb);

    ap_tb = document.createElement("INPUT");
    ap_tb.type = "TEXT";
    ap_tb.name = "secure[MNT_ID]";
    ap_tb.value = encodeURI(orderaccountid);
    ap_form.appendChild(ap_tb);

    ap_tb = document.createElement("INPUT");
    ap_tb.type = "TEXT";
    ap_tb.name = "secure[MNT_TRANSACTION_ID]";
    ap_tb.value = encodeURI(transactionid);
    ap_form.appendChild(ap_tb);

    ap_tb = document.createElement("INPUT");
    ap_tb.type = "TEXT";
    ap_tb.name = "secure[DATAGRAM]";
    ap_tb.value = JSON.stringify(token);
    ap_form.appendChild(ap_tb);

    ap_tb = document.createElement("INPUT");
    ap_tb.type = "TEXT";
    ap_tb.name = "secure[MNT_FIELDS]";
    ap_tb.value = "$MNT_ID.$MNT_TRANSACTION_ID.DATAGRAM";
    ap_form.appendChild(ap_tb);

    ap_tb = document.createElement("INPUT");
    ap_tb.type = "TEXT";
    ap_tb.name = "secure[MNT_FIELDS_HASH]";
    ap_tb.value = encodeURI(secsignature);
    ap_form.appendChild(ap_tb);

    let assistant_params = "";
    assistant_params = assistant_params + "MNT_ID=" + encodeURI(orderaccountid);
    assistant_params = assistant_params + "&amp;MNT_TRANSACTION_ID=" + encodeURI(transactionid);
    assistant_params = assistant_params + "&amp;MNT_AMOUNT=" + encodeURI(orderamount);
    assistant_params = assistant_params + "&amp;MNT_SIGNATURE=" + encodeURI(asssignature);
    assistant_params = assistant_params + "&amp;paymentSystem.limitIds=" + encodeURI(unitid) + "&amp;paymentSystem.unitId=" + encodeURI(unitid);
    assistant_params = assistant_params + "&amp;followup=true";
    ap_tb = document.createElement("INPUT");
    ap_tb.type = "TEXT";
    ap_tb.name = "MNT_ASSISTANT_PARAMS";
    ap_tb.value = assistant_params;
    ap_form.appendChild(ap_tb);

    document.body.appendChild(ap_form);
    ap_form.submit();
}

function getApplePaySession(validationURL, account, signature, salt, displayName) {
    return new Promise(function (resolve, reject) {
        let xhr = new XMLHttpRequest();
        let monetadomain = getValue("monetadomain");
        xhr.open("POST", "https://" + encodeURI(monetadomain) + "/applePayPaymentProcessing.htm");
        xhr.onload = function () {
            if (this.status >= 200 && this.status < 300) {
                resolve(JSON.parse(xhr.response));
            } else {
                reject({
                    status: this.status,
                    statusText: xhr.statusText
                });
            }
        };
        xhr.onerror = function () {
            reject({
                status: this.status,
                statusText: xhr.statusText
            });
        };

        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(JSON.stringify({
            "validationUrl": validationURL,
            "MNT_ID": account,
            "MNT_SIGNATURE": signature,
            "MNT_SALT": salt,
            "DISPLAY_NAME": displayName
        }));
    });
}

document.addEventListener("DOMContentLoaded", function () {
    let getVarNoapplepay = getUrlVars()["noapplepay"];
    if (window.ApplePaySession) {
        showApplePayButton();
        let ordername = getValue('ordername');
        let orderamount = getValue('orderamount');
        let orderaccountid = getValue('orderaccountid');
        let ordersignature = getValue('ordersignature');
        let ordersalt = getValue('ordersalt');
        document.getElementById("applePay").onclick = function (evt) {
            applePayButtonClicked(ordername, orderamount, orderaccountid, ordersignature, ordersalt);
        };
    } else if (getVarNoapplepay != "1") {
        let docreferer = window.location.href;
        let docdelimiter = "?";
        if (docreferer.lastIndexOf("?") > 0) docdelimiter = "&";
        window.location = docreferer + docdelimiter + "noapplepay=1";
    }

});