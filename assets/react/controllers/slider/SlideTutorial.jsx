import React, { useState, useEffect, useCallback, memo } from 'react';
import Slide from './Slide';
import NavigationButtons from './SlideButtons';
import NavigationDots from './SlideDots';
import slides from './SlideDatas';

const SlideTutorial = memo(() => {
    const [currentSlide, setCurrentSlide] = useState(0);
    const totalSlides = slides.length;

    const goToSlide = useCallback((slideNumber) => {
        setCurrentSlide(slideNumber);
    }, []);

    const goToPrevSlide = useCallback(() => {
        setCurrentSlide(prev => Math.max(prev - 1, 0));
    }, []);

    const goToNextSlide = useCallback(() => {
        setCurrentSlide(prev => Math.min(prev + 1, totalSlides - 1));
    }, [totalSlides]);

    const [slideStyle, setSlideStyle] = useState({});

    useEffect(() => {
        setSlideStyle({
            transform: `translateX(-${currentSlide * 100}%)`,
            transition: 'transform 0.5s ease',
        });
    }, [currentSlide]);

    console.log("bawi")

    return (
        <div>
            <section id="slider" className="slider">
                <div className="slide-container" style={slideStyle}>
                    {slides.map((slide, index) => (
                        <Slide
                            key={index}
                            img={slide.img}
                            title={slide.title}
                            description={slide.description}
                        />
                    ))}
                </div>

                <NavigationButtons
                    currentSlide={currentSlide}
                    totalSlides={totalSlides}
                    goToPrevSlide={goToPrevSlide}
                    goToNextSlide={goToNextSlide}
                />

                <NavigationDots
                    totalSlides={totalSlides}
                    currentSlide={currentSlide}
                    goToSlide={goToSlide}
                />
            </section>
        </div>
    );
});

export default SlideTutorial;
