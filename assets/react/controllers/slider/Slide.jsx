import React from 'react';

const Slide = ({ img, title, description }) => (
    <section className="slide">
        <div className="slide-content">
            <img src={img} alt={title}/>
            <h1>{title}</h1>
            <p>{description}</p>
        </div>
    </section>
);

export default Slide;
