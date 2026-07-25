import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Dark Mode Toggle Logic
document.addEventListener('DOMContentLoaded', () => {
    const themeToggles = document.querySelectorAll('.theme-toggle');
    
    // Function to update toggle icons
    const updateIcons = (isDark) => {
        themeToggles.forEach(toggle => {
            const darkIcon = toggle.querySelector('.theme-toggle-dark-icon');
            const lightIcon = toggle.querySelector('.theme-toggle-light-icon');
            if (isDark) {
                darkIcon?.classList.add('hidden');
                lightIcon?.classList.remove('hidden');
            } else {
                darkIcon?.classList.remove('hidden');
                lightIcon?.classList.add('hidden');
            }
        });
    };

    // Initial icon state
    const isDark = document.documentElement.classList.contains('dark');
    updateIcons(isDark);

    // Helper function to toggle classes and local storage
    const toggleTheme = () => {
        const currentDark = document.documentElement.classList.contains('dark');
        const nextDark = !currentDark;

        if (nextDark) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        }

        updateIcons(nextDark);
        
        // Dispatch custom event if any components need to react
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: nextDark ? 'dark' : 'light' } }));
    };

    // Bind click events
    themeToggles.forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            // Check if browser supports View Transition API and isn't opting out of motion
            if (!document.startViewTransition || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                // Fallback to css fade transition
                document.documentElement.classList.add('theme-transition');
                toggleTheme();
                setTimeout(() => {
                    document.documentElement.classList.remove('theme-transition');
                }, 300);
                return;
            }

            const rect = toggle.getBoundingClientRect();
            const x = rect.left + rect.width / 2;
            const y = rect.top + rect.height / 2;
            
            const endRadius = Math.hypot(
                Math.max(x, window.innerWidth - x),
                Math.max(y, window.innerHeight - y)
            );

            const transition = document.startViewTransition(() => {
                toggleTheme();
            });

            transition.ready.then(() => {
                const clipPath = [
                    `circle(0px at ${x}px ${y}px)`,
                    `circle(${endRadius}px at ${x}px ${y}px)`
                ];
                
                document.documentElement.animate(
                    {
                        clipPath: clipPath
                    },
                    {
                        duration: 500,
                        easing: 'ease-in-out',
                        pseudoElement: '::view-transition-new(root)'
                    }
                );
            });
        });
    });
});
