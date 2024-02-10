import React, { useState, useEffect } from 'react';

function ThemeToggle() {
    const [isDarkTheme, setIsDarkTheme] = useState(() => {
        return localStorage.getItem('color-theme') === 'dark' ||
            (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
    });

    useEffect(() => {
        document.body.style.backgroundImage = isDarkTheme ? 'url("/uploads/bgdark3.jpg")' : 'url("/uploads/bglight.jpg")';
        document.documentElement.classList.toggle('dark', isDarkTheme);
        localStorage.setItem('color-theme', isDarkTheme ? 'dark' : 'light');
    }, [isDarkTheme]);

    const toggleTheme = () => {
        setIsDarkTheme(!isDarkTheme);
    };

    return (
        <div className="fixed bottom-4 right-4 z-20">
            <label htmlFor="darkmode-checkbox" className="inline-flex relative items-center mr-5 cursor-pointer">
                <input type="checkbox" value="" id="darkmode-checkbox" className="sr-only peer" checked={isDarkTheme}
                       onChange={toggleTheme} readOnly/>
                <div
                    className="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-2 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-800 peer-checked:after:translate-x-full peer-checked:after:border-gray-800 after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-gray-800 after:border-gray-800 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-400"></div>
            </label>
        </div>
    );
}

export default ThemeToggle;
