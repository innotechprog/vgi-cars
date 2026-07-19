function initTestimonials() {
  const slider = document.getElementById("testimonialSlider");
  if (!slider) {
    return;
  }

  const slides = Array.from(slider.querySelectorAll(".testimonial"));
  const dotsWrap = document.getElementById("sliderDots");

  if (!slides.length || !dotsWrap) {
    return;
  }

  let index = 0;

  function showSlide(nextIndex) {
    slides.forEach((slide, slideIndex) => {
      slide.classList.toggle("active", slideIndex === nextIndex);
    });

    dotsWrap.querySelectorAll("button").forEach((dot, dotIndex) => {
      dot.classList.toggle("active", dotIndex === nextIndex);
    });

    index = nextIndex;
  }

  slides.forEach((_, dotIndex) => {
    const dot = document.createElement("button");
    dot.type = "button";
    dot.setAttribute("aria-label", `Show testimonial ${dotIndex + 1}`);
    dot.addEventListener("click", () => showSlide(dotIndex));
    dotsWrap.appendChild(dot);
  });

  showSlide(0);
  setInterval(() => {
    showSlide((index + 1) % slides.length);
  }, 4500);
}

document.addEventListener("DOMContentLoaded", initTestimonials);
