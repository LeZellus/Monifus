let themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
let themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
let bodyStyle = document.body.style;

let darkThemeButton = document.getElementById('darkmode-button');
let darkThemeCheckbox = document.getElementById('darkmode-checkbox');

// Change the icons inside the button based on previous settings
if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    themeToggleDarkIcon.classList.remove('hidden');
    darkThemeCheckbox.checked = true;
    bodyStyle.backgroundImage = 'url("uploads/bgdark3.jpeg")';
} else {
    themeToggleLightIcon.classList.remove('hidden');
    darkThemeCheckbox.checked = false;
    bodyStyle.backgroundImage = 'url("uploads/bglight.jpeg")';
}

darkThemeButton.addEventListener('click', function() {

    // toggle icons inside button
    themeToggleDarkIcon.classList.toggle('hidden');
    themeToggleLightIcon.classList.toggle('hidden');

    // if set via local storage previously
    if (localStorage.getItem('color-theme')) {
        if (localStorage.getItem('color-theme') === 'light') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
            darkThemeCheckbox.checked = true;
            bodyStyle.backgroundImage = 'url("uploads/bgdark3.jpeg")';
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
            darkThemeCheckbox.checked = false;
            bodyStyle.backgroundImage = 'url("uploads/bglight.jpeg")'
        }

        // if NOT set via local storage previously
    } else {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
            darkThemeCheckbox.checked = false;
            bodyStyle.backgroundImage = 'url("uploads/bglight.jpeg")';
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
            darkThemeCheckbox.checked = true;
            bodyStyle.backgroundImage = 'url("uploads/bgdark3.jpeg")';
        }
    }

});