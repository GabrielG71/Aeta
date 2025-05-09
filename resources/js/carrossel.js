document.addEventListener('DOMContentLoaded', () => {
    const imagesContainer = document.getElementById('carousel-images');
    const images = imagesContainer.children;
    const totalSlides = images.length;
    let index = 0;

    function showSlide(i) {
        imagesContainer.style.transform = `translateX(-${i * 100}%)`;
    }

    document.getElementById('prev').addEventListener('click', () => {
        index = (index - 1 + totalSlides) % totalSlides;
        showSlide(index);
    });

    document.getElementById('next').addEventListener('click', () => {
        index = (index + 1) % totalSlides;
        showSlide(index);
    });

    setInterval(() => {
        index = (index + 1) % totalSlides;
        showSlide(index);
    }, 5000);
});