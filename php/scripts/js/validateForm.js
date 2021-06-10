
function createErrorLabel(text, id) {
    const element = document.createElement('p');
    element.setAttribute('class', 'errorMsg');
    element.setAttribute('tabindex', '0');
    element.setAttribute('id', id);
    //const textNode = document.createTextNode(text);
    //element.appendChild(textNode);
    element.textContent = text;
    return element;
}

function validateMail() {
    const mail = document.getElementById('mail');
    const mailValue = mail.value.trim();
    const regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    console.log(mail.value);
    const mailLabel = document.getElementById('mailLabel');
    if(mailValue.length === 0 || regex.test(mailValue)) {
        const errorLabel = mailValue.length === 0 ? createErrorLabel('Campo obbligatorio', 'mailErrorLabel') : createErrorLabel('Mail non valida', 'mailErrorLabel');
        console.log(errorLabel);
        mailLabel.insertAdjacentElement('beforebegin', errorLabel);
        return false;
    } else {
        const errorLabel = document.getElementById(mailErrorLabel);
        if(errorLabel) errorLabel.remove();
    }
    return true;
}