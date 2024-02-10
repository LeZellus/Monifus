import React from 'react';

const NavigationDots = ({ totalSlides, currentSlide, goToSlide }) => (
    <div className="slide-dots">
        {[...Array(totalSlides).keys()].map(index => (
            <button key={index} className={`slide-dot ${index === currentSlide ? 'active' : ''}`} onClick={() => goToSlide(index)}></button>
        ))}
    </div>
);

export default NavigationDots;