(function () {
    var root = document.documentElement;
    var btn = document.getElementById('themeToggle');
    var timer = null;
    if (!btn) { return; }

    function label() {
        // The button names the mode you will switch to.
        btn.textContent = root.getAttribute('data-theme') === 'dark' ? 'LIGHT' : 'DARK';
    }

    btn.addEventListener('click', function () {
        var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';

        // Turn on the transition class, switch theme, then remove it again
        root.classList.add('theme-anim');
        root.setAttribute('data-theme', next);
        try { localStorage.setItem('theme', next); } catch (e) {}
        label();

        clearTimeout(timer);
        timer = setTimeout(function () { root.classList.remove('theme-anim'); }, 450);
    });

    label();
})();