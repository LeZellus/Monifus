import React, { useState, useEffect, useCallback, memo } from 'react';
import { useSpring, animated } from '@react-spring/web';
import Slide from './Slide';
import NavigationButtons from './SlideButtons';
import NavigationDots from './SlideDots';
import slides from './SlideDatas';

const SlideTutorial = memo(() => {
    const [currentSlide, setCurrentSlide] = useState(0);
    const totalSlides = slides.length;

    console.log(slides)

    const goToSlide = useCallback((slideNumber) => {
        setCurrentSlide(slideNumber);
    }, []);

    const goToPrevSlide = useCallback(() => {
        setCurrentSlide(prev => Math.max(prev - 1, 0));
    }, []);

    const goToNextSlide = useCallback(() => {
        setCurrentSlide(prev => Math.min(prev + 1, totalSlides - 1));
    }, [totalSlides]);

    const slideStyle = useSpring({
        transform: `translateX(-${currentSlide * 100}%)`,
        from: { transform: 'translateX(0%)' },
    });

    return (
        <div>
            <section id="slider" className="slider">
                <animated.div className="slide-container" style={slideStyle}>
                    {slides.map((slide, index) => (
                        <Slide
                            key={index}
                            img={slide.img}
                            title={slide.title}
                            description={slide.description}
                        />
                    ))}
                </animated.div>

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
