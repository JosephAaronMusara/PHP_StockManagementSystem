document.addEventListener("DOMContentLoaded", () => {
  const navLinks = document.querySelectorAll(".nav-link");
  const sections = document.querySelectorAll(".content-section");

  navLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      // Remove active class from all links
      navLinks.forEach((link) => link.classList.remove("active"));

      // Add active class to clicked link
      this.classList.add("active");

      // Hide all sections
      sections.forEach((section) => section.classList.remove("active-section"));

      // Show the corresponding section
      const target = this.getAttribute("href");
      document.querySelector(target).classList.add("active-section");
    });
  });
});
