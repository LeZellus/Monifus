import React from 'react';

const SlideButtons = ({ currentSlide, totalSlides, goToPrevSlide, goToNextSlide }) => (
    <div>
        {currentSlide > 0 && (
            <button
                id="prev"
                className="slide-button prev"
                onClick={goToPrevSlide}
            >
                <span className="slide-icon">👈🏻</span>
                <span className="slide-button-text">Précédent</span>
            </button>
        )}
        {currentSlide === totalSlides - 1 ? (
            <a id="next" className="slide-button next" href="/">
                <span className="slide-button-text">J'ai compris !</span>
                <span className="slide-icon">👉🏻</span>
            </a>
        ) : (
            <button
                id="next"
                className="slide-button next"
                onClick={goToNextSlide}
            >
                <span className="slide-button-text">Suivant</span>
                <span className="slide-icon">👉🏻</span>
            </button>
        )}
    </div>
);

export default SlideButtons;