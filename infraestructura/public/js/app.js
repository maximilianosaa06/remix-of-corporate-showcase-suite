document.addEventListener('DOMContentLoaded', function () {
    var btn = document.querySelector('.hamburger');
    var nav = document.querySelector('.mobile-nav');
    if (!btn || !nav) return;

    btn.addEventListener('click', function () {
        nav.classList.toggle('open');
        var expanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!expanded));
    });
});
