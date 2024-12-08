<script>
    const menuToggle = document.getElementById('menuToggle');
    const closeMenu = document.getElementById('closeMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const mainContent = document.getElementById('mainContent');

    function toggleMenu() {
        mobileMenu.classList.toggle('translate-x-full');
        mainContent.classList.toggle('overlay');
    }

    menuToggle.addEventListener('click', toggleMenu);
    closeMenu.addEventListener('click', toggleMenu);

</script>



</body>
</html>