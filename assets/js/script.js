document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. NAVBAR STICKY EFFECT ---
    const navbar = document.querySelector('nav');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            // Saat discroll ke bawah: Lebih gelap & bayangan tebal
            navbar.classList.add('shadow-2xl', 'bg-slate-900', '!py-2');
            navbar.classList.remove('bg-slate-900/90', 'backdrop-blur-md', 'border-b');
        } else {
            // Saat di paling atas: Transparan & blur
            navbar.classList.remove('shadow-2xl', 'bg-slate-900', '!py-2');
            navbar.classList.add('bg-slate-900/90', 'backdrop-blur-md', 'border-b');
        }
    });


    // --- 2. LIVE SEARCH GAME (Pencarian Cepat) ---
    const searchInput = document.getElementById('searchGame');
    const gameCards = document.querySelectorAll('.game-card');

    if (searchInput) {
        searchInput.addEventListener('keyup', (e) => {
            const term = e.target.value.toLowerCase();

            gameCards.forEach(card => {
                const title = card.querySelector('h4').innerText.toLowerCase();
                if (title.includes(term)) {
                    card.style.display = 'block';
                    // Animasi kecil saat ketemu
                    card.classList.add('animate-fade-in');
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }


    // --- 3. BANNER SLIDESHOW OTOMATIS ---
    const bannerImage = document.getElementById('bannerImage');
    if (bannerImage) {
        // Daftar gambar banner (Bisa diganti URL gambar lain)
        const images = [
            "https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=2070&auto=format&fit=crop", // Gambar 1
            "https://images.unsplash.com/photo-1538481199705-c710c4e965fc?q=80&w=2070&auto=format&fit=crop", // Gambar 2
            "https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=2070&auto=format&fit=crop"  // Gambar 3
        ];
        
        let currentIndex = 0;

        setInterval(() => {
            // Efek Fade Out
            bannerImage.style.opacity = '0';
            
            setTimeout(() => {
                currentIndex = (currentIndex + 1) % images.length;
                bannerImage.src = images[currentIndex];
                // Efek Fade In
                bannerImage.style.opacity = '1';
            }, 500); // Tunggu 0.5 detik (transisi)

        }, 5000); // Ganti gambar setiap 5 detik
    }


    // --- 4. SCROLL REVEAL ANIMATION (Muncul saat discroll) ---
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('opacity-100', 'translate-y-0');
                entry.target.classList.remove('opacity-0', 'translate-y-10');
            }
        });
    }, observerOptions);

    // Terapkan ke semua elemen dengan class 'reveal'
    document.querySelectorAll('.reveal').forEach(el => {
        el.classList.add('transition', 'duration-700', 'opacity-0', 'translate-y-10'); // Set kondisi awal (tersembunyi)
        observer.observe(el);
    });

});