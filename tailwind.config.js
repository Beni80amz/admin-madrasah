import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                // Primary Emerald
                "primary": "#10B981",
                "primary-dark": "#059669",
                "primary-light": "#34D399",
                
                // Light Mode
                "background-light": "#F9FAFB",
                "surface-light": "#FFFFFF",
                "text-primary-light": "#111827",
                "text-secondary-light": "#4B5563",
                "border-light": "#E5E7EB",
                
                // Dark Mode
                "background-dark": "#0F172A",
                "surface-dark": "#1E293B",
                "text-primary-dark": "#F8FAFC",
                "text-secondary-dark": "#94A3B8",
                "border-dark": "#334155",
                
                // Semantic Colors
                "success": "#059669",
                "warning": "#F59E0B",
                "error": "#EF4444",
                "info": "#3B82F6",
            },
            fontFamily: {
                "display": ["Spline Sans", "sans-serif"],
                "body": ["Noto Sans", "sans-serif"],
                "sans": ["Noto Sans", ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {"DEFAULT": "1rem", "lg": "1.5rem", "xl": "2rem", "2xl": "3rem", "full": "9999px"},
        },
    },
    plugins: [],
};
