import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/**
 * Five Stitches brand tokens — mirror the Figma color & text styles.
 * Figma: https://www.figma.com/design/uYMS60Y6cbeJjjU7Sr3PWS
 */
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Enums/**/*.php', // status pill colors live in OrderStatus
    ],

    theme: {
        extend: {
            colors: {
                cream: '#F8F4EB',      // Brand/Cream — page background
                ink: '#273327',        // Brand/Ink Green — headings, footer, sidebar
                forest: {              // Brand/Forest — buttons, links, selected
                    DEFAULT: '#3E5641',
                    dark: '#334836',
                },
                sage: {                // Brand/Sage + Sage Light
                    DEFAULT: '#B2C09D',
                    light: '#E6ECDD',
                },
                coral: '#F16F61',      // Brand/Coral — small highlights only
                line: '#E3DDD0',       // Neutral/Border
                muted: '#4D564D',      // Neutral/Muted text
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                heading: ['"Zilla Slab"', ...defaultTheme.fontFamily.serif],
                script: ['Alegreya', ...defaultTheme.fontFamily.serif],
            },
        },
    },

    plugins: [forms],
};
