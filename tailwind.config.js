/** @type {import('tailwindcss').Config} */
export default {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
    ],
    theme: {
      extend: {
        fontFamily: {
          sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        },
        colors: {
          primary: "#dab540",
        },
      },
    },
    plugins: [require("daisyui")],
    daisyui: {
      themes: [
        {
          light: {
            "primary": "#dab540",
            "primary-focus": "#c2a030",
            "primary-content": "#ffffff",
          },
          dark: {
            "primary": "#dab540",
            "primary-focus": "#c2a030",
            "primary-content": "#000000",
          },
        },
      ],
      darkTheme: "dark",
      base: true,
      styled: true,
      utils: true,
      prefix: "",
      logs: false,
      themeRoot: ":root",
    },
  } 