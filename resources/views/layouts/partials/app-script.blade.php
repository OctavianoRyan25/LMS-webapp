{{-- layouts/partials/app-script.blade.php --}}
<script>
function appLayout() {
    return {
        // State
        darkMode:        localStorage.getItem('edupanel_dark') === 'true',
        sidebarCollapsed: localStorage.getItem('edupanel_sidebar_collapsed') === 'true',
        sidebarOpen:     false,
        isMobile:        window.innerWidth < 1024,

        init() {
            // Sync dark mode to localStorage
            this.$watch('darkMode', val => {
                localStorage.setItem('edupanel_dark', val);
            });

            // Sync sidebar state to localStorage
            this.$watch('sidebarCollapsed', val => {
                localStorage.setItem('edupanel_sidebar_collapsed', val);
            });

            // Handle window resize
            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 1024;
                if (!this.isMobile) this.sidebarOpen = false;
            });
        }
    }
}
</script>