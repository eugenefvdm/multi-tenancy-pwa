<div x-data="batteryLevel" x-init="getBatteryLevel()">
    <div class="flex items-center">
        Battery level: <p x-text="batteryLevel + '%'"></p>
    </div>
</div>

<script>
    function batteryLevel() {
        return {
            batteryLevel: 'N/A',
            async getBatteryLevel() {
                if ('getBattery' in navigator) {

                    const battery = await navigator.getBattery();

                    this.batteryLevel = battery.level * 100;

                    battery.addEventListener('levelchange', () => {
                        this.batteryLevel = battery.level * 100;
                    });
                }
            }
        }
    }
</script>
