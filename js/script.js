// Chờ cho toàn bộ nội dung HTML được tải xong
document.addEventListener('DOMContentLoaded', function () {

    // ==============================================
    // CHỨC NĂNG 1: BANNER SLIDER (Chỉ chạy ở Trang Chủ)
    // ==============================================
    
    // Chỉ chạy code banner nếu tìm thấy class 'slider'
    const slider = document.querySelector('.slider');
    
    // Cần kiểm tra kỹ hơn, vì prevBtn/nextBtn có thể không tồn tại
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
    } // Kết thúc If của code Slider


    // ================================================
    // CHỨC NĂNG 2: TÌM KIẾM (Chỉ chạy ở Trang Menu)
    // ================================================

    // Chỉ chạy code tìm kiếm nếu tìm thấy 'searchInput'
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        const menuList = document.getElementById('menuList');
        const items = menuList.querySelectorAll('.menu-item');

        // Lắng nghe sự kiện 'keyup' (mỗi khi gõ 1 phím)
        searchInput.addEventListener('keyup', function () {
            const searchTerm = searchInput.value.toLowerCase(); // Lấy từ khóa, chuyển thành chữ thường

            items.forEach(function (item) {
                // Lấy tên món ăn trong thẻ <h3>
                const itemName = item.querySelector('h3').textContent.toLowerCase();
                
                // So sánh tên món ăn với từ khóa tìm kiếm
                if (itemName.includes(searchTerm)) {
                    item.style.display = 'block'; // Hiện
                } else {
                    item.style.display = 'none'; // Ẩn
                }
            });
        });
    } // Kết thúc If của code Tìm kiếm

});