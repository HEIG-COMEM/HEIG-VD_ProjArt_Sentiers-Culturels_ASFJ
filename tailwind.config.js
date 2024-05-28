import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, require('@tailwindcss/typography'), require('daisyui')],

    daisyui: {
        themes: [{
            mytheme: {
                "primary": "#4C8C2B",
                "primary-content": "#fdfdfa",
                "secondary": "#154B19",
                "secondary-content": "#fdfdfa",
                "accent": "#00D300",
                "accent-content": "#020705",
                "neutral": "#4C8C2B",
                "neutral-content": "#020705",
                "base-100": "#F6F6F6",
                "base-200": "#B8BBBE",
                "base-300": "#767774",
                "base-content": "#020705",
                "info": "#71D8F8",
                "info-content": "#020705",
                "success": "#77D779",
                "success-content": "#020705",
                "warning": "#F87171",
                "warning-content": "#020705",
                "error": "#F87171",
                "error-content": "#020705",
            },
        }], // false: only light + dark | true: all themes | array: specific themes like this ["light", "dark", "cupcake"]
        darkTheme: "dark", // name of one of the included themes for dark mode
        base: true, // applies background color and foreground color for root element by default
        styled: true, // include daisyUI colors and design decisions for all components
        utils: true, // adds responsive and modifier utility classes
        prefix: "", // prefix for daisyUI classnames (components, modifiers and responsive class names. Not colors)
        logs: true, // Shows info about daisyUI version and used config in the console when building your CSS
        themeRoot: ":root", // The element that receives theme color CSS variables
    },
};
