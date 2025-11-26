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
    const cartForms = document.querySelectorAll('.ajax-cart-form');
    
    cartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('ajax', '1');P
            fetch('php/cart_action.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) 
            .then(data => {
                if (data.status === 'success') {
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = `(${data.total_items})`;
                    }
                    alert("Đã thêm món vào giỏ hàng thành công!"); 
                } else {
                    alert("Có lỗi xảy ra: " + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }); 
});