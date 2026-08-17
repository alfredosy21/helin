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
        // Paleta turquesa unificada con el frontend público (Helin brand)
        primary: {
          DEFAULT: '#6BC2C3',
          50: '#f0fafa',
          100: '#d8f2f2',
          200: '#b0e6e6',
          300: '#8fdada',
          400: '#7cd0cf',
          500: '#6BC2C3', // Turquesa de marca exacto (igual al frontend)
          600: '#4AA8A9',
          700: '#3A9A9B',
          800: '#2A7A7B',
          900: '#1A5A5B',
          950: '#0d3435',
        },
        heading: '#123F4A',
        body: '#2D3740',
        soft: '#f2f2f4',
        line: '#E5E7EB',
        // Sidebar claro, alineado con la estética del frontend
        sidebar: {
          light: '#ffffff',
          hover: '#f8fafc',
          active: 'rgba(107, 194, 195, 0.1)',
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
