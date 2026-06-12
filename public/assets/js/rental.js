(function () {
  const nav = document.getElementById('mainNav');
  const menuButton = document.getElementById('mobileMenuButton');
  const mobileMenu = document.getElementById('mobileMenu');
  const form = document.getElementById('feedbackForm');
  const toast = document.getElementById('successToast');

  function updateNav() {
    if (window.scrollY > 100) {
      nav.classList.add('scrolled');
    } else {
      nav.classList.remove('scrolled');
    }
  }

  window.addEventListener('scroll', updateNav);
  updateNav();

  menuButton.addEventListener('click', function () {
    mobileMenu.hidden = !mobileMenu.hidden;
  });

  document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (event) {
      const target = document.querySelector(link.getAttribute('href'));

      if (!target) {
        return;
      }

      event.preventDefault();
      nav.classList.add('scrolled');
      const navHeight = window.innerWidth >= 1024 ? 49 : nav.offsetHeight;
      const targetTop = target.getBoundingClientRect().top + window.pageYOffset - navHeight;

      window.scrollTo({ top: targetTop, behavior: 'smooth' });
      mobileMenu.hidden = true;
    });
  });

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    toast.hidden = false;
    window.setTimeout(function () {
      toast.hidden = true;
      form.reset();
    }, 4000);
  });
})();
