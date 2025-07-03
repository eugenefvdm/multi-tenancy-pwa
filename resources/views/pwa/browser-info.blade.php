<div class="flex flex-col items-center mt-2">
    Browser name & version:&nbsp;
    <br>
    <div x-data="browserInfo" x-init="initBrowserInfo()">
        <div x-text="browserName + ' ' + browserVersion"></div>
        <p x-show="warning" x-text="warning" class="text-red-500 mt-2"></p>
    </div>
</div>

<script>    
    function browserInfo() {
        return {
            browserName: 'checking...takes 1 second',
            browserVersion: '',
            warning: null,
            initBrowserInfo() {
                setTimeout(() => {
                    this.getBrowserInfo();
                }, 1000);
            },
            getBrowserInfo() {
                const userAgent = navigator.userAgent;
                let browserName = 'Unknown';
                let browserVersion = '';

                // Check for Arc browser using CSS variables
                if (getComputedStyle(document.documentElement).getPropertyValue('--arc-palette-background')) {
                    browserName = 'Arc Browser';
                    browserVersion = userAgent.match(/Chrome\/(\d+\.\d+\.\d+)/)?.[1] || '';
                    this.warning = "The Arc browser doesn't support PWA.";
                }
                // Chrome
                else if (userAgent.indexOf('Chrome') > -1 && userAgent.indexOf('Edg') === -1 && userAgent.indexOf('OPR') === -1) {
                    browserName = 'Chrome';
                    browserVersion = userAgent.match(/Chrome\/(\d+\.\d+\.\d+\.\d+)/)[1];
                }
                // Edge
                else if (userAgent.indexOf('Edg') > -1) {
                    browserName = 'Edge';
                    browserVersion = userAgent.match(/Edg\/(\d+\.\d+\.\d+\.\d+)/)[1];
                }
                // Firefox
                else if (userAgent.indexOf('Firefox') > -1) {
                    browserName = 'Firefox';
                    browserVersion = userAgent.match(/Firefox\/(\d+\.\d+)/)[1];
                }
                // Safari
                else if (userAgent.indexOf('Safari') > -1 && userAgent.indexOf('Chrome') === -1) {
                    browserName = 'Safari';
                    browserVersion = userAgent.match(/Version\/(\d+\.\d+)/)[1];
                }
                // Opera
                else if (userAgent.indexOf('OPR') > -1) {
                    browserName = 'Opera';
                    browserVersion = userAgent.match(/OPR\/(\d+\.\d+\.\d+)/)[1];
                }

                this.browserName = browserName;
                this.browserVersion = browserVersion;
            }
        }
    }
</script>