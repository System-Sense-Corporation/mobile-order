// tailwind.config.js
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  safelist: [
    // --- badge colors (status) ---
    'bg-amber-50','text-amber-700','ring-amber-200',
    'bg-sky-50','text-sky-700','ring-sky-200',
    'bg-emerald-50','text-emerald-700','ring-emerald-200',
    'bg-slate-100','text-slate-800','ring-slate-200',

    // --- buttons / outlines used in palette ---
    'bg-accent','hover:bg-accent/90','focus-visible:outline-accent',
    'border-slate-300','text-slate-600','hover:bg-slate-100',
    'border-red-200','text-red-600','hover:bg-red-50',
    'bg-[#F4DADA]','hover:bg-[#f0caca]',
  ],
  theme: {
    extend: {
      colors: {
        background: '#ffffff',
        accent: '#b30000',
        text: '#111111',
      },
    },
  },
  plugins: [],
};
