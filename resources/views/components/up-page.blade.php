<button id="backToTop"
    class="fixed bottom-12 right-6 hidden text-white dark:text-white hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400 p-3 rounded-full z-1000 shadow-lg transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
    </svg>
</button>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        const btn = document.getElementById("backToTop");
        const main = document.querySelector("main.overflow-auto");

        if (!btn || !main) return;

        main.addEventListener("scroll", function () {
            if (main.scrollTop > 200) {
                btn.classList.remove("hidden");
            } else {
                btn.classList.add("hidden");
            }
        });

        btn.addEventListener("click", function () {
            main.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });

    });
</script>