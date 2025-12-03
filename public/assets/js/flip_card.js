/**
 * Gestion du flip 3D 360° de la carte
 * public/assets/js/flip-card.js
 */

class FlipCard {
    constructor() {
        this.card = document.querySelector('.flip-card-inner');
        this.isFlipped = false;
        this.flipButton = document.querySelector('.flip-button');
        
        this.init();
    }
    
    init() {
        if (this.flipButton) {
            this.flipButton.addEventListener('click', () => this.flip());
        }
    }
    
    flip() {
        this.isFlipped = !this.isFlipped;
        
        if (this.isFlipped) {
            this.card.style.transform = 'rotateY(180deg)';
        } else {
            this.card.style.transform = 'rotateY(0deg)';
        }
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    new FlipCard();
});