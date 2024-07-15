// Cette partie peut être améliorée
class RepeatedPassword extends HTMLElement {
    constructor() {
        // Toujours appeler "super" d'abord dans le constructeur
            super();
        }

    connectedCallback() {
        let messagePassword = document.getElementById('message-pwd');
        let bar = document.getElementById('progress');
        //Nous ajoutons les nouveaux éléments concernant la gestion du mot de passe
        messagePassword.hidden = false;
        bar.hidden = false;
        // Onlance la gestion du contrôle du mot de passe
        let nodeBadPwd = document.getElementById('bad-password');
        let password = '';
        //const regEx = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-;:+-]).{8,16}$/;
        onchange = (event) => {
            if(event.target.id == 'registration_form_password') {
                password = event.target.value;
            }
            if(event.target.id == 'registration_form_confirm_password') {
                if (password != event.target.value) {
                    nodeBadPwd.style.color = 'red';
                    nodeBadPwd.innerText = "Les deux mots de passes ne correspondent pas";
                }
                else if (password == event.target.value) {
                    nodeBadPwd.style.color = 'green';
                    nodeBadPwd.innerText = "Les deux mots de passe sont identiques";
                }
            }
        }

        document.getElementById('registration_form_password').addEventListener('focusin', (event) => {

        },);
        document.getElementById('registration_form_password').addEventListener('keyup', (event) => {
            //console.log(event.target.value);
            messagePassword.hidden = false;
            bar.hidden = false;
            checkPasswordStrength(event.target.value);
        });
        document.getElementById('registration_form_confirm_password').addEventListener('focusin', (event) => {
            //console.log(event.target);

        },);
        document.getElementById('registration_form_confirm_password').addEventListener('keyup', (event) => {
            //checkPasswordStrength(event.target.value);
        });
    }

}


customElements.define("repeated-password", RepeatedPassword);

    //let cookies = document.cookie;
/**
 * Méthode permettant de vérifier la force du mot de passe
 *
 * @param {*} password : string le mot de passe
 */
function checkPasswordStrength(password) {
    let strength = 0;

    // Check password length
    if (password.length == 8) {
        strength += 1;
    }
    else if(password.length > 8 && password.length <=16) {
        strength += 1;
    }else if(password.length > 16) {
        // On regrade le input actif, et en fonction de ce champ on bloque la frappe dessus
        //document.getElementById('registration_form_plainPassword').disabled = true;
    }
    // Check for lowercase
    if (password.match(/[a-z]/) ) {
        strength += 1;
    }
    // check for uppercase
    if(password.match(/[A-Z]/)) {
        strength += 1;
    }
    // Check for numbers
    if (password.match(/\d/)) {
        strength += 1;
    }
    // Check for special characters
    if (password.match(/[#?!@$%^&*-;:+-]/)) {
        strength += 1;
    }
    console.log(strength);
    console.log(password.length);

    updateProgressBar(strength, password.length)
}

/**
 * Méthode qui permet de modifier la barre de pregression en fonction de lafore du mot de passe
 * @param {*} strength int la force du ot de passe
 */
function updateProgressBar(strength, length) {
    let info = document.getElementById('info');
    const progressBar = document.getElementById('progress-bar');
    if(strength == 0) {
        info.innerText = '';
        info.style.color = "white";
        progressBar.setAttribute('class', 'bg-danger');
        progressBar.setAttribute('style', 'width: 0%');
        progressBar.setAttribute('aria-valuenow', '0');
    }
    if (strength < 2) {
        info.innerText = 'bad';
        info.style.color = "red";
        progressBar.setAttribute('class', 'bg-danger');
        progressBar.setAttribute('style', 'width: 10%');
        progressBar.setAttribute('aria-valuenow', '10');
    } else if (strength === 2) {
        info.innerText = "very easy";
        info.style.color = "red";
        progressBar.setAttribute('class', 'bg-danger');
        progressBar.setAttribute('style', 'width: 20%');
        progressBar.setAttribute('aria-valuenow', '20');
    } else if (strength === 3) {
        info.innerText = "easy ";
        info.style.color = "yellow";
        progressBar.setAttribute('class', 'bg-warning');
        progressBar.setAttribute('style', 'width: 30%');
        progressBar.setAttribute('aria-valuenow', '30');
    } else if (strength === 4) {
        info.innerText = "medium ";
        info.style.color = "yellow";
        progressBar.setAttribute('class', 'bg-warning');
        progressBar.setAttribute('style', 'width: 40%');
        progressBar.setAttribute('aria-valuenow', '40');
    } else if(strength => 5) {
        info.innerText = "strong ";
        info.style.color = "green";
        progressBar.setAttribute('class', 'bg-success');
        checkLenghtPassword(length, progressBar);
    }
    else if( strength > 5 ) {

    }
}

/**
 * Méthode qui permet de modifier la barre de progression  en fonction de la taille du mot de passe quand son format st correct et plus grand que la taille minimale
 * @param password : string le mot de passe
 * @param progressBar : objet pointant sur la barre de progression
 */
function checkLenghtPassword(length, progressBar) {
    if (length == 8) {
        progressBar.setAttribute('style', 'width: 60%;');
        progressBar.setAttribute('aria-valuenow', '60');
    }
    else if(length > 8 && length <= 10) {
        progressBar.setAttribute('style', 'width: 70%;');
        progressBar.setAttribute('aria-valuenow', '70');
    }
    else if(length > 10 && length <= 12) {
        progressBar.setAttribute('style', 'width: 80%;');
        progressBar.setAttribute('aria-valuenow', '80');
    }
    else if(length > 12 && length <= 14) {
        progressBar.setAttribute('style', 'width: 90%;');
        progressBar.setAttribute('aria-valuenow', '90');
    }
    else if(length > 14 && length <= 16) {
        progressBar.setAttribute('style', 'width: 100%;');
        progressBar.setAttribute('aria-valuenow', '100');
    }
    else {
        
    }
}

