<script>
document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. LOGIKA TEMA (DARK MODE) ---
    const themeBtn = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');

    // Fungsi untuk set icon berdasarkan mode
    function updateIcon(isDark) {
        if (isDark) {
            themeIcon.classList.replace('ph-sun', 'ph-moon');
        } else {
            themeIcon.classList.replace('ph-moon', 'ph-sun');
        }
    }

    // Cek Local Storage saat pertama kali load
    // Prioritas: Local Storage > System Preference
    const isDarkMode = localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
    
    if (isDarkMode) {
        document.documentElement.classList.add('dark');
        updateIcon(true);
    } else {
        document.documentElement.classList.remove('dark');
        updateIcon(false);
    }

    // Event Listener Tombol
    if (themeBtn) {
        themeBtn.addEventListener('click', () => {
            // Animasi Putar Icon
            themeIcon.style.transform = 'rotate(360deg) scale(0.5)';
            themeIcon.style.opacity = '0';

            setTimeout(() => {
                // Toggle Class
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                    updateIcon(false);
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                    updateIcon(true);
                }
                
                // Reset Animasi (Muncul kembali)
                themeIcon.style.transform = 'rotate(0deg) scale(1)';
                themeIcon.style.opacity = '1';
            }, 200); // Tunggu 200ms agar animasi putar terlihat
        });
    }


    // --- 2. LOGIKA MOBILE MENU ---
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = document.querySelector('#mobile-menu-btn i'); // Ambil icon di dalam tombol

    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation(); // Mencegah klik tembus ke body
            
            const isHidden = mobileMenu.classList.contains('hidden');
            
            if (isHidden) {
                // BUKA MENU
                mobileMenu.classList.remove('hidden');
                // Timeout kecil agar transisi CSS berjalan
                setTimeout(() => {
                    mobileMenu.classList.remove('scale-y-95', 'opacity-0');
                    mobileMenu.classList.add('scale-y-100', 'opacity-100');
                }, 10);
                
                // Ubah Icon Hamburger -> Silang (X)
                if(hamburgerIcon) {
                    hamburgerIcon.classList.replace('ph-list', 'ph-x');
                    hamburgerIcon.style.transform = 'rotate(90deg)';
                }
            } else {
                // TUTUP MENU
                mobileMenu.classList.remove('scale-y-100', 'opacity-100');
                mobileMenu.classList.add('scale-y-95', 'opacity-0');
                
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300); // Sesuaikan dengan durasi transition CSS
                
                // Ubah Icon Silang -> Hamburger
                if(hamburgerIcon) {
                    hamburgerIcon.classList.replace('ph-x', 'ph-list');
                    hamburgerIcon.style.transform = 'rotate(0deg)';
                }
            }
        });

        // Klik di luar menu untuk menutup
        document.addEventListener('click', (e) => {
            if (!mobileMenu.contains(e.target) && !menuBtn.contains(e.target) && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('scale-y-100', 'opacity-100');
                mobileMenu.classList.add('scale-y-95', 'opacity-0');
                setTimeout(() => mobileMenu.classList.add('hidden'), 300);
                
                if(hamburgerIcon) {
                    hamburgerIcon.classList.replace('ph-x', 'ph-list');
                    hamburgerIcon.style.transform = 'rotate(0deg)';
                }
            }
        });
    }
});
</script>