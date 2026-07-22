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
            borderRadius: {
                sm: '0.375rem',
                DEFAULT: '0.5rem',
                md: '0.625rem',
                lg: '0.75rem',
                xl: '0.875rem',
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
            boxShadow: {
                'level-1': '0 1px 2px 0 rgb(0 0 0 / 0.04)',
                'level-2': '0 1px 3px 0 rgb(0 0 0 / 0.06), 0 1px 2px -1px rgb(0 0 0 / 0.06)',
                'level-3': '0 4px 6px -1px rgb(0 0 0 / 0.08), 0 2px 4px -2px rgb(0 0 0 / 0.06)',
                'level-4': '0 10px 15px -3px rgb(0 0 0 / 0.08), 0 4px 6px -4px rgb(0 0 0 / 0.04)',
                'card-hover': '0 12px 24px -8px rgb(0 0 0 / 0.12)',
            },
        },
    },

    plugins: [forms],
};
