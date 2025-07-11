import React, { useState, useEffect } from 'react';

const AdvicePopup = () => {
    const [advice, setAdvice] = useState('');
    const [showPopup, setShowPopup] = useState(false);
    const [isAnimating, setIsAnimating] = useState(false); // État pour gérer l'animation

    // Function to fetch advice
    const fetchAdvice = () => {
        console.log('test');

        fetch('/advice')
            .then(response => response.text())
            .then(conseil => {
                setAdvice(conseil);
                setShowPopup(true);
                setIsAnimating(true); // Commence l'animation
                setTimeout(() => {
                    setShowPopup(false);
                }, 10000); // Démarre le processus de fermeture après 6 secondes
            });
    };

    // Gère la fin de l'animation de sortie
    useEffect(() => {
        if (!showPopup && isAnimating) {
            // Attend que l'animation de sortie se termine avant de cacher le popup
            const timeoutId = setTimeout(() => {
                setIsAnimating(false);
            }, 2000); // Correspond à la durée de l'animation (0.5s)
            return () => clearTimeout(timeoutId);
        }
    }, [showPopup, isAnimating]);

    // Effect hook to handle the interval
    useEffect(() => {
        const intervalId = setInterval(fetchAdvice, 300000); // Fetch advice every 10 minutes

        return () => clearInterval(intervalId); // Cleanup interval on component unmount
    }, []);

    return (
        <div id="advicePopup"
             style={{display: isAnimating ? 'block' : 'none'}}
             className={`popup ${showPopup ? 'popup-enter' : 'popup-exit'}`}
             role="alert">
            <div className="popup-content">
                <div>
                    <p className="font-bold">Point Conseil !</p>
                    <p id="adviceText" className="text-gray-600 dark:text-white">{advice}</p>
                </div>
                <button className="popup-close" onClick={() => setShowPopup(false)}>Fermer</button>
            </div>
        </div>
    );
};

export default AdvicePopup;
