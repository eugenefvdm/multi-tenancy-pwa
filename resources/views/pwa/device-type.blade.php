<div x-data="deviceType" x-init="getDeviceType()">
    <div class="flex items-center">
        Device:&nbsp;<p x-text="deviceType"></p>
    </div>
</div>

<script>
    function deviceType() {
        return {
            deviceType: 'N/A',
            async getDeviceType() {
                const userAgent = navigator.userAgent || navigator.vendor || window.opera;

                // Check for iPad or iPhone or iPod
                if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                    this.deviceType = 'iOS';
                    return 'iOS';
                }

                // Check for Android
                if (/android/i.test(userAgent)) {
                    this.deviceType = 'Android';
                    return 'Android';
                }

                // Check for Mac
                if (/Macintosh|MacIntel|MacPPC|Mac68K/.test(userAgent)) {
                    this.deviceType = 'Mac';
                    console.log(userAgent);
                    return 'Mac';
                }

                // Check for Windows
                if (/Win32|Win64|Windows|WinCE/.test(userAgent)) {
                    this.deviceType = 'Windows';
                    return 'Windows';
                }

                // Other devices
                this.deviceType = 'Unknown device';
                return 'Unknown device';
            }
        }
    }
</script>
