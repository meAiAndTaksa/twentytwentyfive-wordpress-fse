// tailwind.config.js
/** @type {import('tailwindcss').Config} */
module.exports = {
  // Для Tailwind CSS v3+ используется 'content' вместо 'purge'
  content: [
    './**/*.php', // Сканировать все PHP-файлы в корне темы и подпапках
    './**/*.html', // Сканировать все HTML-шаблоны
    './js/**/*.js', // Если у вас есть JavaScript, который динамически добавляет классы Tailwind
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}