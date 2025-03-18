    <div id="loading-overlay" class="hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" style="z-index: 996 !important">
            <div class="text-white text-center">
                <div class="spinner"></div>
                <p class="mt-2" id="loading-text">Processing ...</p>
                <p id="overlay-timer" class="text-sm mt-2"></p>
            </div>
        </div>
    </div>
    <!-- Payment Canvas Overlay -->
    <div id="canvasOverlay" class="canvas-overlay" onclick="togglePaymentCanvas(event)"></div>
    <div id="canvasBox" class="fixed bottom-0 left-0 right-0 bg-white rounded-t-2xl p-4 shadow-lg hidden z-[998]" style="z-index: 998 !important">
        <div class="flex justify-between items-center">
            <button onclick="togglePaymentCanvas(event)" class="text-gray-500 text-2xl">&times;</button>
        </div>

        <!-- Amount Section -->
        <div class="text-2xl font-semibold text-center relative m-1 bottom-2 p-2">
            {{-- <span class="text-sm" id="nairaSign">₦</span> --}}
            <span id="servicePlan"></span><span id="decimal"></span>
        </div>
        <div class="text-center my-2">
            {{-- <span class="text-sm" id="nairaSign">₦</span>
            <span class="text-2xl font-semibold">AAA</span> --}}
        </div>

        <!-- Product Details -->
        <div class="space-y-2">
            <div class="flex justify-between">
                <p class="text-gray-500 text-xs">Product Name</p>
                <div class="flex items-center text-xs">
                    <img id="networkImage" src="" class="w-5 mr-2" />
                    <span id="serviceType"></span>
                </div>
            </div>
            <div class="flex justify-between">
                <p class="text-gray-500 text-xs">Amount</p>
                <p class="text-xs">{{ get_setting('currency') }}<span class="amountC"></span></p>
            </div>
            <div class="flex justify-between">
                <p class="text-gray-500 text-xs">Mobile/ID</p>
                <p class="text-xs"><span id="mobile"></span></p>
            </div>
        </div>

        <hr class="my-4" />

        <!-- Wallet Section -->
        <div class="flex justify-between items-center bg-gray-50 p-2 rounded-xl">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-green-200 rounded-full">
                    <img src="{{ static_asset('images/wallet.png') }}" alt="">
                </div>
                <p class="ml-2 text-xs">Wallet ({{get_setting('currency')}}<span id="walletBalance">{{ auth()->user()->balance }}</span>)</p>
            </div>
            <a href="{{ route('user.deposit') }}" id="addWalletMoney" class="text-primary text-xs">Add Money</a>
        </div>

        <!-- Pay Button -->
        <button id="pay-btn" class="w-full p-3 text-sm font-semibold rounded-lg text-white bg-primary hover:bg-alt mt-4 focus:outline-none"
            onclick="showPinEntry()">Pay</button>
    </div>

    <!-- PIN Overlay -->
    <div id="pinOverlay" class="canvas-overlay" onclick="hidePinEntry()"></div>
    <div id="pinCanvas" class="fixed bottom-0 left-0 right-0 bg-white hidden rounded-t-2xl z-[999]">
        <div class="flex flex-col items-center p-4">
            <h2 class="text-sm font-semibold">Enter Payment PIN</h2>

            <!-- PIN Inputs -->
            <div class="flex space-x-4 mb-3">
                <input type="password" class="pin-input w-10 h-10 text-center border-none rounded-md bg-gray-100" maxlength="1" id="pin-box-0" inputmode="none">
                <input type="password" class="pin-input w-10 h-10 text-center border-none rounded-md bg-gray-100" maxlength="1" id="pin-box-1" inputmode="none">
                <input type="password" class="pin-input w-10 h-10 text-center border-none rounded-md bg-gray-100" maxlength="1" id="pin-box-2" inputmode="none">
                <input type="password" class="pin-input w-10 h-10 text-center border-none rounded-md bg-gray-100" maxlength="1" id="pin-box-3" inputmode="none">
            </div>
            <a href="{{ route('user.profile.pin') }}" class="mb-2 text-xs text-primary">Forgot Payment PIN?</a>

            <!-- PIN Number Pad -->
            <div class="bg-gray-50 w-full rounded-t-lg p-1">
                <div class="grid grid-cols-3 gap-2 mt-4">
                    <button class="bg-secondary text-secondary-foreground p-3 rounded-lg bg-white" onclick="enterNumber(1)">1</button>
                    <button class="bg-secondary text-secondary-foreground p-3 rounded-lg bg-white" onclick="enterNumber(2)">2</button>
                    <button class="bg-secondary text-secondary-foreground p-3 rounded-lg bg-white" onclick="enterNumber(3)">3</button>
                    <button class="bg-secondary text-secondary-foreground p-3 rounded-lg bg-white" onclick="enterNumber(4)">4</button>
                    <button class="bg-secondary text-secondary-foreground p-3 rounded-lg bg-white" onclick="enterNumber(5)">5</button>
                    <button class="bg-secondary text-secondary-foreground p-3 rounded-lg bg-white" onclick="enterNumber(6)">6</button>
                    <button class="bg-secondary text-secondary-foreground p-3 rounded-lg bg-white" onclick="enterNumber(7)">7</button>
                    <button class="bg-secondary text-secondary-foreground p-3 rounded-lg bg-white" onclick="enterNumber(8)">8</button>
                    <button class="bg-secondary text-secondary-foreground p-3 rounded-lg bg-white" onclick="enterNumber(9)">9</button>
                    <button class="bg-secondary text-secondary-foreground p-3 rounded-lg col-span-2 bg-white" onclick="enterNumber(0)">0</button>
                    <button class="bg-secondary text-secondary-foreground p-3 rounded-lg text-2xl bg-white" onclick="clearPin()">×</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const togglePaymentCanvas = (event) => {
            // fetch balance
            fetchUserBalance();
            const canvasBox = document.getElementById("canvasBox");
            const overlay = document.getElementById("canvasOverlay");
            const contentWrapper = document.getElementById('contentWrapper');
            const walletBalance = parseFloat(document.getElementById('walletBalance').innerText.replace(/[^0-9.-]+/g, ""));

            canvasBox.classList.toggle("hidden");
            overlay.classList.toggle('canvas-overlay-active');
            contentWrapper.classList.toggle('blur');

            // Fix: Using correct selectors
            const amountElements = document.querySelectorAll(".amountC");
            const amountValue = document.getElementById("canvasAmount")?.value || '0';
            const numberValue = document.getElementById("canvasNumber")?.value;
            const productType = document.getElementById("canvasType")?.value || "Payment";
            const canvasImg = document.getElementById("canvasImage")?.value;
            const planValue = document.getElementById("canvasPlan")?.value;

            // Fix: Update all amount elements
            amountElements.forEach(el => el.textContent = amountValue);

            // Fix: Using correct IDs
            if (numberValue) document.getElementById("mobile").textContent = numberValue;
            document.getElementById("serviceType").textContent = productType;
            if (canvasImg) document.getElementById("networkImage").src = canvasImg;
            document.getElementById("servicePlan").textContent = planValue;


            if (walletBalance < amountValue) {
                document.getElementById('addWalletMoney').style.display = 'block';
            } else {
                document.getElementById('addWalletMoney').style.display = 'none';
            }


            const pinCanvas = document.getElementById('pinCanvas');
            const pinOverlay = document.getElementById('pinOverlay');

            if (pinOverlay.classList.contains('canvas-overlay-active') && !pinCanvas.contains(event.target)) {
                pinCanvas.classList.add('hidden');
                pinCanvas.classList.remove('slide-up-active');
                clearPin();
                pinOverlay.classList.remove('canvas-overlay-active');
            }

            setTimeout(() => {
                canvasBox.classList.toggle('slide-up-active');
            }, 3);
        };

        function showPinEntry() {
            clearPin();
            const pinCanvas = document.getElementById('pinCanvas');
            const pinOverlay = document.getElementById('pinOverlay');

            pinCanvas.classList.remove('hidden');
            pinOverlay.classList.remove('hidden'); // Fix: Show overlay
            pinOverlay.classList.add('canvas-overlay-active');
        }

        function hidePinEntry() {
            const pinCanvas = document.getElementById('pinCanvas');
            const pinOverlay = document.getElementById('pinOverlay');
            clearPin();

            pinCanvas.classList.add('hidden');
            pinOverlay.classList.add('hidden'); // Fix: Hide overlay
            pinOverlay.classList.remove('canvas-overlay-active');
        }

        // Clear PIN Function
        const clearPin = () => {
            document.querySelectorAll(".pin-input").forEach(input => input.value = "");
        };

        function enterNumber(num) {
            const inputs = document.querySelectorAll('.pin-input');
            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].value === '') {
                    inputs[i].value = num;
                    if (i < inputs.length - 1) {
                        inputs[i + 1].focus();
                    }
                    break;
                }
            }

            let pin = '';
            let hiddenPinInput = document.getElementById('pin');
            let loadingOverlay = document.getElementById('loading-overlay');
            inputs.forEach(el => pin += el.value);
            if (pin.length === inputs.length) {
                hiddenPinInput.value = pin;
                hidePinEntry();
                togglePaymentCanvas(event);
                loadingOverlay.classList.remove("hidden");
                document.getElementById('pay-now').click();
            }
        }
    </script>
    <script>
        document.querySelectorAll('.icon-text').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.icon-text').forEach(el => {
                    el.querySelector('i').classList.remove('text-primary');
                    el.querySelector('span').classList.add('hidden');
                    el.classList.remove('active');
                });
                item.querySelector('i').classList.add('text-primary');
                item.querySelector('span').classList.remove('hidden');
                item.classList.add('active');
            });
        });

        document.querySelectorAll('.icon-text').forEach(item => {
            if (item.href === window.location.href) {
                item.classList.add('active');
                item.querySelector('i').classList.add('text-primary');
                item.querySelector('span').classList.remove('hidden');
            }
        });

        // copy elemet
        function copyDivContent(element) {
            var aux = document.createElement("input");
            // Assign it the value of the specified element
            aux.setAttribute("value", element);
            document.body.appendChild(aux);
            aux.select();
            document.execCommand("copy");
            document.body.removeChild(aux);
            Toastify({
                text: "Copied Successfully",
                className: "toastify-success",
                duration: 3000,
                close: false,
                gravity: "top",
                position: "center"
            }).showToast();
        }
    </script>
<script>
    // Function to open a modal by ID
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
    }

    // Function to close a modal by ID
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
    }

    // Close modal on outside click
    window.addEventListener('click', function (e) {
        const modals = document.querySelectorAll('.fixed.inset-0.z-50');
        modals.forEach((modal) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
<script>
    // fetch user balance dynamically
    async function fetchUserBalance() {
        try {
            const response = await fetch("{{ route('user.balance') }}");
            const data = await response.json();
            document.getElementById('walletBalance').innerText = data.balance;
            let mainBalJ = document.getElementById('balance');
            if(mainBalJ) mainBalJ.innerText = data.fbal;
        } catch (error) {
            // console.error("Failed to fetch balance:", error);
        }
    }
    // Fetch balance periodically
    setInterval(fetchUserBalance, 120000); // Fetch every 2 minutes
</script>
