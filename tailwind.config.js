/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Plus Jakarta Sans', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      colors: {
        can: {
          ink:        '#1C2522', // Primary text, navbar text, headings, footer
          pine:       '#21483C', // Primary brand color, main CTA, active nav, route lines
          forest:     '#2F6252', // Hover, secondary interactive, active route
          'paper-warm': '#F5F1E8', // Editorial background, hero background, section bg
          'paper-soft': '#FBFAF6', // Primary page background, content background
          stone:      '#D9D5CA', // Divider, border, timetable line, container border
          muted:      '#66716C', // Muted text
          terracotta: '#B96545', // Signal color only: status, departure indicator, warning
          success:    '#357A62', // Success
          danger:     '#B94A48', // Danger
          warning:    '#A87935', // Warning
          white:      '#FFFFFF',
        },
      },
    },
  },
  plugins: [],
}
