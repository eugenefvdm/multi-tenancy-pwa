<div x-data="onlineStatus">
    <template x-if="isOnline">
        <div class="flex items-center">
            <div title="Online" class="w-3 h-3 bg-green-500 rounded-full"></div>
            <span>&nbsp;Connection is online</span>
        </div>
    </template>
    <template x-if="!isOnline">
        <div title="Offline" class="w-3 h-3 bg-red-500 rounded-full">Connection Offline</div>
    </template>
</div>

<script>
    function onlineStatus() {
        return {
            isOnline: navigator.onLine,
            init() {
                window.addEventListener('online', () => {
                    this.isOnline = true;
                });
                window.addEventListener('offline', () => {
                    this.isOnline = false;
                });
            }
        }
    }
</script>
