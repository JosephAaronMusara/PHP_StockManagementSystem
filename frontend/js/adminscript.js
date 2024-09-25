document.addEventListener("DOMContentLoaded", () => {
    const navLinks = document.querySelectorAll(".nav-link");
    const sections = document.querySelectorAll(".content-section");
  
    navLinks.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault();
  
        navLinks.forEach((link) => link.classList.remove("active"));
  
        this.classList.add("active");
  
        sections.forEach((section) => section.classList.remove("active-section"));
  
        const target = this.getAttribute("href");
        document.querySelector(target).classList.add("active-section");
      });
    });
  });