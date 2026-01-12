/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './**/*.php',
    './style.css',
    './src/**/*.{js,css}'
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          600: '#6001D2'
        }
      }
    }
  },
  plugins: []
}

