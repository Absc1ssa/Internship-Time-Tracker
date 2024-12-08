<!-- Bootstrap JS for any needed functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript -->
<script>
    document.getElementById("toggle-btn").addEventListener("click", function () {
        const sidebar = document.getElementById("sidebar");
        const content = document.getElementById("main-content");

        if (sidebar.classList.contains("sidebar-hidden")) {
            sidebar.classList.remove("sidebar-hidden");
            sidebar.classList.add("sidebar-active");
            content.classList.remove("ml-0");
            content.classList.add("ml-64");
        } else {
            sidebar.classList.add("sidebar-hidden");
            sidebar.classList.remove("sidebar-active");
            content.classList.remove("ml-64");
            content.classList.add("ml-0");
        }
    });

    
</script>

</body>

</html>
