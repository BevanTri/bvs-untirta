import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Oswald', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', 'ui-monospace', 'SFMono-Regular', 'monospace'],
            },
            colors: {
                brand: {
                    navy: '#0F172A',
                    'navy-2': '#1E293B',
                    'navy-3': '#334155',
                    blue: '#2563EB',
                    'blue-light': '#3B82F6',
                    gold: '#F59E0B',
                    'gold-light': '#FBBF24',
                    'gold-dark': '#D97706',
                    warm: '#F8FAFC',
                    'warm-2': '#F1F5F9',
                    ink: '#1E293B',
                    'ink-muted': '#64748B',
                    'ink-faint': '#94A3B8',
                    border: '#E2E8F0',
                }
            },
            borderWidth: {
                '3': '3px',
            }
        },
    },

    plugins: [forms],
};
