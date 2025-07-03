<div wire:ignore>
    {{--  The file is dependant on the app manifest.json file and very sensitive to issues within it, .
        for example, missing icons. Copy the web route for the manifest.
    --}}
    <div id="install-button" hidden>
        <x-filament::button>
            Installation Button
        </x-filament::button>
    </div>

    <div id="install-status-message">
        <small>The App is installed.</small>
        {{-- TODO Conditionally if there is an Android installation other advice
         Use chrome://apps to uninstall it. --}}
    </div>
    <script>
        console.log("The initial JavaScript on the install-app blade file is now running.");

        console.log("DOMContentLoaded. Initialising on-page scripts.");
        let installPrompt = null;

        // Select the Toggle button
        const installButton = document.querySelector("#install-button");
        const installStatus = document.querySelector("#install-status-message");

        // Hide the button initially
        if (installButton) {
            installButton.setAttribute("hidden", "");
        }
        if (installStatus) {
            installStatus.setAttribute("hidden", "");
        }

        // Event listener for the beforeinstallprompt event
        window.addEventListener("beforeinstallprompt", (event) => {
            console.log("EVENT: beforeinstallprompt: Install button is visible.");

            event.preventDefault();
            installPrompt = event;

            // Show the button by removing the hidden attribute on the div.
            if (installButton) {
                installButton.removeAttribute("hidden");
            }

            // Hide the status message when install is available
            if (installStatus) {
                installStatus.setAttribute("hidden", "");
            }
        });
        // End event listener for the beforeinstallprompt event

        // Handle click event on the button
        if (installButton) {
            console.log("Adding click event listener to the install button.");
            installButton.addEventListener("click", async () => {
                console.log("Button clicked.");
                if (!installPrompt) {
                    console.log("No install prompt available.");
                    return;
                }

                const result = await installPrompt.prompt();
                console.log(`Install prompt was: ${result.outcome}`);

                // Reset the prompt and hide the button
                installPrompt = null;
                installButton.setAttribute("hidden", "");

                // Show the status message
                installStatus.removeAttribute("hidden");
            });

            const isHidden = installButton.hasAttribute("hidden");

            if (isHidden) {
                installStatus.removeAttribute("hidden");
            }
        }
    </script>
</div>
