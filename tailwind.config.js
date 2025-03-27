/** @type {import('tailwindcss').Config} */

export default {
  content: [
    './resources/**/*.{html,js,php,blade.php}',
  ], theme: {
    extend: {
      fontFamily: {
        'customer': ['JetBrains Mono'],
        'test': ['test']
      }
    },
  },
  plugins: [],
}

