/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        greentea: {
          50: '#f3f7f0',
          100: '#e8f1e2',
          200: '#d2e3c3',
          300: '#adc79a',
          400: '#8aaa71',
          500: '#688c50',
          600: '#4f703c',
          700: '#3d5731',
          800: '#34472b',
          900: '#2c3c25',
        },
        matcha: {
          50: '#f0f5e6',
          100: '#e1ebd1',
          200: '#c3d7a3',
          300: '#a5c275',
          400: '#87ad47',
          500: '#6b8c38',
          600: '#4f6d2b',
          700: '#3b5023',
          800: '#2f411e',
          900: '#27351a',
        },
        bamboo: {
          50: '#f7f6e7',
          100: '#efecd4',
          200: '#e0d8a8',
          300: '#d0c27c',
          400: '#c1ab51',
          500: '#ab9136',
          600: '#8c732c',
          700: '#6e5726',
          800: '#5a4822',
          900: '#4b3c20',
        }
      },
      fontFamily: {
        sans: ['Poppins', 'sans-serif'],
      },
      borderRadius: {
        'nature': '0.5rem',
      },
      boxShadow: {
        'tea': '0 4px 6px rgba(0, 0, 0, 0.1)',
        'tea-lg': '0 10px 15px rgba(0, 0, 0, 0.1)',
        'nature': '0 4px 6px rgba(0, 0, 0, 0.1)',
        'nature-hover': '0 10px 15px rgba(0, 0, 0, 0.1)',
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms')
  ],
} 