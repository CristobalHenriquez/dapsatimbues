document.addEventListener("DOMContentLoaded", () => {
    const slides = document.querySelectorAll(".hotel-hero .slide")
    let currentSlide = 0
  
    function showSlide(index) {
      slides[currentSlide].classList.remove("active")
      slides[index].classList.add("active")
      currentSlide = index
    }
  
    function nextSlide() {
      const nextIndex = (currentSlide + 1) % slides.length
      showSlide(nextIndex)
    }
  
    // Change slide every 5 seconds
    setInterval(nextSlide, 5000)
  })
  
  