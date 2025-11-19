document.addEventListener('DOMContentLoaded', function () {
    const slider = document.querySelector('.slider');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    if (slider && prevBtn && nextBtn) {
        const slides = document.querySelectorAll('.slide');
        let currentIndex = 0;
        const totalSlides = slides.length;
        function updateSliderPosition() {
            slider.style.transform = `translateX(-${currentIndex * 100}%)`;
        }
        function goToNextSlide() {
            currentIndex++;
            if (currentIndex >= totalSlides) currentIndex = 0;
            updateSliderPosition();
        }
        function goToPrevSlide() {
            currentIndex--;
            if (currentIndex < 0) currentIndex = totalSlides - 1;
            updateSliderPosition();
        }
        let autoSlideInterval = setInterval(goToNextSlide, 3000);
        nextBtn.addEventListener('click', function () {
            goToNextSlide();
            clearInterval(autoSlideInterval);
            autoSlideInterval = setInterval(goToNextSlide, 3000);
        });

        prevBtn.addEventListener('click', function () {
            goToPrevSlide();
            clearInterval(autoSlideInterval);
            autoSlideInterval = setInterval(goToNextSlide, 3000);
        });
    } 
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        const menuList = document.getElementById('menuList');
        const items = menuList.querySelectorAll('.menu-item');
        searchInput.addEventListener('keyup', function () {
            const searchTerm = searchInput.value.toLowerCase();
            items.forEach(function (item) {
                const itemName = item.querySelector('h3').textContent.toLowerCase();
                if (itemName.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none'; 
                }
            });
        });
    } 
});