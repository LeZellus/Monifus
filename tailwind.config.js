/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',

    content: [
        './templates/**/*.html.twig',
        "./node_modules/flowbite/**/*.js"
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                    "50": "#fefce8",
                    "100": "#fef9c3",
                    "200": "#fef08a",
                    "300": "#fde047",
                    "400": "#facc15",
                    "500": "#eab308",
                    "600": "#ca8a04",
                    "700": "#a16207",
                    "800": "#854d0e",
                    "900": "#713f12"
                },
                bambeach: {
                    DEFAULT: '#C1B87F',
                    '50': '#F0EDDF',
                    '100': '#E9E6D1',
                    '200': '#DCD6B6',
                    '300': '#CEC79A',
                    '400': '#C1B87F',
                    '500': '#AFA359',
                    '600': '#8C8244',
                    '700': '#665F32',
                    '800': '#403C1F',
                    '900': '#1B190D'
                },
            }
        },
        fontFamily: {
            'body': [
                'Poppins',
                'ui-sans-serif',
                'system-ui',
                '-apple-system',
                'system-ui',
                'Segoe UI',
                'Roboto',
                'Helvetica Neue',
                'Arial',
                'Noto Sans',
                'sans-serif',
                'Apple Color Emoji',
                'Segoe UI Emoji',
                'Segoe UI Symbol',
                'Noto Color Emoji'
            ],
            'sans': [
                'Poppins',
                'ui-sans-serif',
                'system-ui',
                '-apple-system',
                'system-ui',
                'Segoe UI',
                'Roboto',
                'Helvetica Neue',
                'Arial',
                'Noto Sans',
                'sans-serif',
                'Apple Color Emoji',
                'Segoe UI Emoji',
                'Segoe UI Symbol',
                'Noto Color Emoji'
            ]
        }
    },
    plugins: [
        require('flowbite/plugin')
    ],
}
