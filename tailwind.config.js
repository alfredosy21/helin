/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // Mapeamos el color corporativo de Helin como la paleta primaria de la aplicación
        primary: {
          50: '#e6f9f7',
          100: '#b3ece6',
          200: '#80dfd4',
          300: '#4dd2c2',
          400: '#1ac5b1',
          500: '#09b6a2', // Tu verde/turquesa de marca exacto
          600: '#079282',
          700: '#056e61',
          800: '#044a41',
          900: '#022520',
          950: '#011310',
        },
        // Añadimos el tono exacto del Sidebar moderno para usarlo semánticamente
        sidebar: {
          dark: '#1e293b',      // Slate oscuro e impecable de las capturas
          hover: 'rgba(255, 255, 255, 0.05)',
          active: 'rgba(255, 255, 255, 0.1)',
        }
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      backdropBlur: {
        xs: '2px',
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'slide-up': 'slideUp 0.3s ease-out',
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(10px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}