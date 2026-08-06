export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                'brand-primary': '#00C853',
                'brand-secondary': '#6200EA',
                'brand-green': '#00C853',
                'brand-violet': '#6200EA',
                'slate-850': '#1f2937',
                'slate-950': '#020617',
                'dark-surface': '#1e293b',
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                heading: ['Poppins', 'Inter', 'sans-serif'],
            },
            animation: {
                wiggle: 'wiggle 0.5s ease-in-out',
                pop: 'pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)',
                float: 'float 3s ease-in-out infinite',
                'fade-in': 'fadeIn 0.6s ease-out forwards',
                'slide-in': 'slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1)',
            },
            keyframes: {
                wiggle: {
                    '0%, 100%': { transform: 'translateX(0)' },
                    '25%': { transform: 'translateX(-5px)' },
                    '75%': { transform: 'translateX(5px)' },
                },
                pop: {
                    '0%': { transform: 'scale(0.8)', opacity: '0' },
                    '100%': { transform: 'scale(1)', opacity: '1' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                fadeIn: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideIn: {
                    '0%': { opacity: '0', transform: 'translateX(20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
            },
            backdropBlur: {
                sm: '4px',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
