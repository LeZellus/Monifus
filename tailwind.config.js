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
                'primary': {
                    DEFAULT: '#FE5F00',
                    50: '#FFD2B7',
                    100: '#FFC5A2',
                    200: '#FFAB79',
                    300: '#FF9251',
                    400: '#FF7828',
                    500: '#FE5F00',
                    600: '#C64A00',
                    700: '#8E3500',
                    800: '#562000',
                    900: '#1E0B00',
                    950: '#020100'
                },
                'bambeach': {
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
                'secondary': {
                    DEFAULT: '#0A0A0A',
                    50: '#666666',
                    100: '#5C5C5C',
                    200: '#474747',
                    300: '#333333',
                    400: '#1E1E1E',
                    500: '#0A0A0A',
                    600: '#000000',
                    700: '#000000',
                    800: '#000000',
                    900: '#000000',
                    950: '#000000'
                },
                'tertiary': {
                    DEFAULT: '#0285B1',
                    50: '#6DD9FD',
                    100: '#59D4FD',
                    200: '#31C9FD',
                    300: '#08BFFC',
                    400: '#02A3D9',
                    500: '#0285B1',
                    600: '#015B7A',
                    700: '#013242',
                    800: '#00080B',
                    900: '#000000',
                    950: '#000000'
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
