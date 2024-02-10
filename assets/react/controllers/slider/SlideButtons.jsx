import React from 'react';
import { useSpring, animated } from '@react-spring/web';

const SlideButtons = ({ currentSlide, totalSlides, goToPrevSlide, goToNextSlide }) => {
    const prevButtonStyle = useSpring({
        opacity: currentSlide > 0 ? 1 : 0,
        from: { opacity: 0 },
    });

    const nextButtonStyle = useSpring({
        opacity: currentSlide < totalSlides - 1 ? 1 : 0,
        from: { opacity: 0 },
    });


    console.log("CurrentSlide :" + currentSlide)
    console.log("totalSlides :" + totalSlides)

    return (
        <div>
            {currentSlide > 0 && (
                <animated.button
                    style={prevButtonStyle}
                    id="prev"
                    className="slide-button prev"
                    onClick={goToPrevSlide}
                >
                    <span className="slide-icon">👈🏻</span>
                    <span className="slide-button-text">Précédent</span>
                </animated.button>
            )}
            {currentSlide < totalSlides - 1 ? (
                <button
                    id="next"
                    className="slide-button next"
                    onClick={goToNextSlide}
                >
                    <span className="slide-button-text">Suivant</span>
                    <span className="slide-icon">👉🏻</span>
                </button>
            ) : (
                <a id="next" className="slide-button next" href="/">
                    <span className="slide-button-text">J'ai compris !</span>
                    <span className="slide-icon">👉🏻</span>
                </a>
            )}
        </div>
    );
};

export default SlideButtons;
