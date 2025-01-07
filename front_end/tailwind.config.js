/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    screens: {
      'xs': '480px',
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1280px',
      '2xl': '1536px',
    },
    container: {
      center: true,
      padding: {
        DEFAULT: '1rem',
        sm: '2rem',
        lg: '4rem',
        xl: '5rem',
      },
    },
    extend: {
      fontFamily: {
        sans: ['Poppins', 'sans-serif'],
      },
      spacing: {
        '128': '32rem',
        '160': '40rem',
        '192': '48rem',
        '18': '4.5rem',
        '22': '5.5rem',
        '26': '6.5rem',
      },
      colors: {
        customRed: '#EC1111',
        customHover: '#EC1111',
      },
      aspectRatio: {
        '4/3': '4 / 3',
        '3/2': '3 / 2',
        '16/9': '16 / 9',
      },
    },
  },
  plugins: [
    require('@tailwindcss/aspect-ratio'),
  ],
};
