
function clamp(n, min, max){
return Math.min(Math.max(n, min), max);
}


function randomNumberBetween(min, max) {
    return Math.floor(Math.random() * (max-min + 1) + min);
}


class Captcha extends HTMLElement {
    constructor() {
    // Toujours appeler "super" d'abord dans le constructeur
        super();

    }

    connectedCallback() {
        //On va gérer l'affihage du puzzle
        const width = parseInt(this.getAttribute('width'), 10);
        const height = parseInt(this.getAttribute('height'), 10);
        const pieceWidth = parseInt(this.getAttribute('piece-width'), 10);
        const pieceHeight = parseInt(this.getAttribute('piece-height'), 10);
        const maxX = width - pieceWidth;
        const maxY = height - pieceHeight;

        this.classList.add('captcha');
        this.classList.add('captcha-waiting-interaction');
        this.style.setProperty('--width', `${width}px`);
        this.style.setProperty('--height', `${height}px`);
        this.style.setProperty('--pieceWidth', `${pieceWidth}px`);
        this.style.setProperty('--pieceHeight', `${pieceHeight}px`);
        this.style.setProperty('--image', `url(${this.getAttribute('src')})`);

        // On va créer l'élément qui va gérer la pièce à bouger
        const piece = document.createElement('div');
        piece.classList.add('captcha-piece');

        const captchaAnswer = this.querySelector('.captcha-answer');
        const captchaChallenge = this.querySelector('.captcha-challenge')

        this.appendChild(piece);

        // Nous allons écoter les événements
        let isDragging = false;
        // Pour la position de la pièce de puzzle
        let position = {x: randomNumberBetween(0,maxX), y: randomNumberBetween(0,maxY)};
        piece.style.setProperty('transform', `translate(${position.x}px, ${position.y}px)` );
        piece.style.setProperty('cursor', 'pointer');

        piece.addEventListener('pointerdown', e => {
            isDragging = true;
            document.body.style.setProperty('user-select', 'none'); // Pour éviter qu'on sélectionne le texte  dans la page
            this.classList.remove('captcha-waiting-interaction');
            piece.classList.add('is-moving');
            console.log('pointer down')
            piece.style.removeProperty('cursor');
            piece.style.setProperty('cursor', 'grab');
            window.addEventListener('pointerup', e => {
                document.body.style.removeProperty('user-select'); // On supprimer la propiété
                isDragging = false;
                piece.style.removeProperty('cursor');
                piece.style.setProperty('cursor', 'pointer');
                piece.classList.remove('is-moving');
            }, {once: true}); // Dernier paramètre pour que ça se face qu'une seule fois
        });


        // Pour gérer le déplacement de la pièce de pizzle;
        this.addEventListener('pointermove', e => {
            if(!isDragging) {
                return;
            }
            position.x = clamp( position.x + e.movementX, 0, maxX);
            position.y =clamp( position.y + e.movementY, 0, maxY);
            piece.style.setProperty('transform', `translate(${position.x}px, ${position.y}px)` );
            captchaAnswer.value = `${position.x}-${position.y}`;
            //console.log(captchaAnswer)
        })
    }
}

customElements.define("my-captcha", Captcha);


/*
const captcha = document.querySelector("my-captcha");
console.log(captcha);
// On ajoute une classe à notre captcha
captcha.setAttribute('class', 'captcha');
// Nous récupérons la largeur et la hauteur du puzzle
const width = parseInt(captcha.getAttribute('width'), 10);
const height = parseInt(captcha.getAttribute('height'), 10);
// Nous récupérons la largeur et la hauteur de la pièce à déplacer du puzzle
const pieceWidth = parseInt(captcha.getAttribute('piece-width'), 10);
const pieceHeight = parseInt(captcha.getAttribute('piece-height'), 10);
captcha.classList.add('captcha');
// NOPus récupérons l'image à traiter
captcha.style.setProperty('--image', `url(${captcha.getAttribute('src')})`);
// Nous passons les valeurs récupérée au dessus au css
captcha.style.setProperty('--width', `${width}px`);
captcha.style.setProperty('--height', `${height}px`);
captcha.style.setProperty('--pieceWidth', `${pieceWidth}px`);
captcha.style.setProperty('--pieceHeight', `${pieceHeight}px`);
/**/
console.log('Hello captcha.js ✊');
