import React from 'react';
import { useSpring, animated } from '@react-spring/web';

const SlideDots = ({ totalSlides, currentSlide, goToSlide }) => {
    return (
        <div className="slide-dots">
            {[...Array(totalSlides).keys()].map(index => {
                const dotStyle = useSpring({
                    backgroundColor: index === currentSlide ? 'orange' : 'grey',
                    from: { backgroundColor: 'grey' },
                });

                return (
                    <animated.button
                        key={index}
                        style={dotStyle}
                        className={`slide-dot ${index === currentSlide ? 'active' : ''}`}
                        onClick={() => goToSlide(index)}
                    ></animated.button>
                );
            })}
        </div>
    );
};

export default SlideDots;
