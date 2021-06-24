
function createErrorLabel(text, id) {
    const element = document.createElement('p');
    element.setAttribute('class', 'errorMsg');
    element.setAttribute('tabindex', '0');
    element.setAttribute('id', id);
    element.textContent = text;
    return element;
}

const validator = (value, text, id, check) => {
    if(check(value)) {
        const oldErrorLabel = document.getElementById(id);
        if(oldErrorLabel) {
            oldErrorLabel.remove();
        }
        const errorLabel = createErrorLabel(text, id);
        return errorLabel;
    }
    return false;
}


function validateMail() {
    const mail = document.getElementById('mail');
    const mailValue = mail.value.trim();
    const regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    const mailLabel = document.getElementById('mailLabel');
    if(mailValue.length === 0 || !regex.test(mailValue)) {
        const oldErrorLabel = document.getElementById('mailErrorLabel');
        if(oldErrorLabel) oldErrorLabel.remove();
        const errorLabel = mailValue.length === 0 ? createErrorLabel('Campo obbligatorio', 'mailErrorLabel') : createErrorLabel('Mail non valida', 'mailErrorLabel');
        mailLabel.insertAdjacentElement('beforebegin', errorLabel);
        return false;
    } else {
        const oldErrorLabel = document.getElementById('mailErrorLabel');
        if(oldErrorLabel) oldErrorLabel.remove();
    }
    return true;
}

function validatePassword() {
    const password = document.getElementById('password');
    const passwordValue = password.value.trim();
    const passwordLabel = document.getElementById('passwordLabel');
    if(passwordValue.length === 0 || passwordValue.length < 4 || passwordValue.length > 64) {
        const oldErrorLabel = document.getElementById('passwordErrorLabel');
        if(oldErrorLabel) oldErrorLabel.remove();
        let errorLabel;
        if(passwordValue.length === 0) errorLabel = createErrorLabel('Campo obbligatorio', 'passwordErrorLabel');
        if(passwordValue.length < 4) errorLabel = createErrorLabel('La password deve contenere almeno 4 caratteri!', 'passwordErrorLabel');
        if(passwordValue.length === 0) errorLabel = createErrorLabel('La password non può avere più di 64 caratteri!', 'passwordErrorLabel');
        passwordLabel.insertAdjacentElement('beforebegin', errorLabel);
        return false;
    } else {
        const errorLabel = document.getElementById('passwordErrorLabel');
        if(errorLabel) errorLabel.remove();
    }
    return true;
}

function validateUsername() {
    const username = document.getElementById('username');
    const usernameValue = username.value.trim();
    const usernameLabel = document.getElementById('usernameLabel');
    let errorLabel;
    errorLabel = validator(usernameValue, 'Campo obbligatorio', 'usernameErrorLabel', (value) => {
        return value.length === 0;
    });
    if (!errorLabel) errorLabel = validator(usernameValue,  'Lo username non può avere meno di 3 caratteri', 'usernameErrorLabel', (value) => {
        return value.length < 3;
    });
    if (!errorLabel) errorLabel = validator(usernameValue, 'Lo username non può avere più di 10 caratteri', 'usernameErrorLabel', (value) => {
        return value.length > 10;
    });
    if (!errorLabel) errorLabel = validator(usernameValue,  'lo username deve contenere solo caratteri alfanumerici e iniziare con una lettera', 'usernameErrorLabel', (value) => {
        let regex = /^[a-zA-Z][a-zA-Z0-9]+$/;
        return !regex.test(value);
    });
    if(errorLabel) {
        usernameLabel.insertAdjacentElement('beforebegin', errorLabel);
        return false;
    } else {
        const errorLabel = document.getElementById('usernameErrorLabel');
        if(errorLabel) errorLabel.remove();
    }
    return true;
}

function validateName() {
    const name = document.getElementById('name');
    const nameValue = name.value.trim();
    const nameLabel = document.getElementById('nameLabel');
    let errorLabel;
    errorLabel = validator(nameValue,  'Campo obbligatorio', 'nameErrorLabel', (value) => {
        return value.length === 0;
    });
    if (!errorLabel) errorLabel = validator(nameValue,  'Il nome deve avere almeno 3 caratteri', 'nameErrorLabel', (value) => {
        return value.length < 3;
    });
    if (!errorLabel) errorLabel = validator(nameValue, 'Il nome non deve avere più di 24 caratteri', 'nameErrorLabel', (value) => {
        return value.length > 24;
    });
    if (!errorLabel) errorLabel = validator(nameValue, 'Nome non valido!', 'nameErrorLabel', (value) => {
        let regex = /[0-9!#$%&\'*+/=?^_\`{|}~\-]/;
        return regex.test(value);
    });
    if(errorLabel) {
        nameLabel.insertAdjacentElement('beforebegin', errorLabel);
        return false;
    } else {
        const errorLabel = document.getElementById('nameErrorLabel');
        if(errorLabel) errorLabel.remove();
    }
    return true;
}

function validateSurname() {
    const surname = document.getElementById('surname');
    const surnameValue = surname.value.trim();
    const surnameLabel = document.getElementById('surnameLabel');
    let errorLabel;
    errorLabel = validator(surnameValue,  'Campo obbligatorio', 'surnameErrorLabel', (value) => {
        return value.length === 0;
    });
    if (!errorLabel) errorLabel = validator(surnameValue,  'Il cognome deve avere almeno 3 caratteri', 'surnameErrorLabel', (value) => {
        return value.length < 3;
    });
    if (!errorLabel) errorLabel = validator(surnameValue, 'Il cognome non deve avere più di 24 caratteri', 'surnameErrorLabel', (value) => {
        return value.length > 24;
    });
    if (!errorLabel) errorLabel = validator(surnameValue, 'Cognome non valido!', 'surnameErrorLabel', (value) => {
        let regex = /[0-9!#$%&\'*+/=?^_\`{|}~\-]/;
        return regex.test(value);
    });
    if(errorLabel) {
        surnameLabel.insertAdjacentElement('beforebegin', errorLabel);
        return false;
    } else {
        const errorLabel = document.getElementById('surnameErrorLabel');
        if(errorLabel) errorLabel.remove();
    }
    return true;
}

function validateRegister() {
    const m = validateMail();
    const p = validatePassword();
    const u = validateUsername();
    const n = validateName();
    const s = validateSurname();
    return (s && n && u && p && m);
}

function validateTitle() {
    const title = document.getElementById('title');
    const titleValue = title.value.trim();
    const titleLabel = document.getElementById('titleLabel');
    let errorLabel;
    errorLabel = validator(titleValue,  'Campo obbligatorio', 'titleErrorLabel', (value) => {
        return value.length === 0;
    });
    if (!errorLabel) errorLabel = validator(titleValue,  'Il titolo deve avere almeno 3 caratteri', 'titleErrorLabel', (value) => {
        return value.length < 3;
    });
    if (!errorLabel) errorLabel = validator(titleValue, 'Il titolo non deve avere più di 48 caratteri', 'titleErrorLabel', (value) => {
        return value.length > 48;
    });
    if (!errorLabel) errorLabel = validator(titleValue, 'il titolo deve essere composto solo da caratteri alfanumerici!', 'titleErrorLabel', (value) => {
        let regex = /[$%<>*+/=^?^_\`{|}~\-]/;
        return regex.test(value);
    });
    if(errorLabel) {
        titleLabel.insertAdjacentElement('beforebegin', errorLabel);
        return false;
    } else {
        const errorLabel = document.getElementById('titleErrorLabel');
        if(errorLabel) errorLabel.remove();
    }
    return true;
}

function validateDescription() {
    const description = document.getElementById('description');
    const descriptionValue = description.value.trim();
    const descriptionLabel = document.getElementById('descriptionLabel');
    let errorLabel;
    errorLabel = validator(descriptionValue,  'Campo obbligatorio', 'descriptionErrorLabel', (value) => {
        return value.length === 0;
    });
    if (!errorLabel) errorLabel = validator(descriptionValue,  'La descrizione deve avere almeno 3 caratteri', 'descriptionErrorLabel', (value) => {
        return value.length < 3;
    });
    if (!errorLabel) errorLabel = validator(descriptionValue, 'La descrizione deve essere composta solo da caratteri alfanumerici!', 'descriptionErrorLabel', (value) => {
        let regex = /[$%<>*+/=^?^_\`{|}~\-]/;
        return regex.test(value);
    });
    if(errorLabel) {
        descriptionLabel.insertAdjacentElement('beforebegin', errorLabel);
        return false;
    } else {
        const errorLabel = document.getElementById('descriptionErrorLabel');
        if(errorLabel) errorLabel.remove();
    }
    return true;
}

function validateFile() {
    var file = document.getElementById('presepeImage').files[0];
    const imageLabel = document.getElementById('imageLabel');
    let errorLabel;
    errorLabel = validator(file.size,  'Dimensione massima del file 10 MB', 'imageErrorLabel', (value) => {
        return value > 10000000;
    });
    if(!errorLabel) errorLabel = validator(file.type,  'Estensioni non accettata: estensioni possibili: .jpg .png .jpeg .gif', 'imageErrorLabel', (value) => {
        return !(value === 'image/gif' || value === 'image/jpeg' || value === 'image/png');
    });
    if(errorLabel) {
        imageLabel.insertAdjacentElement('beforebegin', errorLabel);
        return false;
    } else {
        const errorLabel = document.getElementById('imageErrorLabel');
        if(errorLabel) errorLabel.remove();
    }
    return true;
}

function validateSelect() {
    const select = document.getElementById('selectCategory');
    const selectValue = select.value.trim();
    const selectLabel = document.getElementById('categoryLabel');
    let errorLabel;
    errorLabel = validator(selectValue,  'Campo obbligatorio', 'selectErrorLabel', (value) => {
        return value.length === 0;
    });
    if(errorLabel) {
        selectLabel.insertAdjacentElement('beforebegin', errorLabel);
        return false;
    } else {
        const errorLabel = document.getElementById('selectErrorLabel');
        if(errorLabel) errorLabel.remove();
    }
    return true;
}


function validateAdd() {
    const t = validateTitle();
    const d = validateDescription();
    const f = validateFile();
    const s = validateSelect();
    return t && d && f && s;
}