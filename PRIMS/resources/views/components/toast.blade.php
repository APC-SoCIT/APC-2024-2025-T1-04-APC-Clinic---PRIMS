<div 
    x-data="{ show: false, message: '', style: 'success' }"
    x-on:notify.window="
        style = $event.detail.style; 
        message = $event.detail.message; 
        show = true; 
        setTimeout(() => show = false, 3000)
    "
    x-show="show"
    x-transition
    class="fixed inset-0 flex items-center justify-center pointer-events-none"
    style="z-index: 9999;"
>
    <!-- Popup Card -->
    <div
        class="px-6 py-4 rounded-xl shadow-2xl text-center w-80 pointer-events-auto font-semibold text-lg text-white"
        :class="{
            'bg-green-500': style === 'success',
            'bg-red-500': style === 'danger',
            'bg-yellow-500': style === 'warning'
        }"
    >
        <span x-text="message"></span>
    </div>
</div>

@if (session('toast'))
    <script>
        document.addEventListener("alpine:init", () => {
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        style: "{{ session('toast')['style'] }}",
                        message: "{{ session('toast')['message'] }}"
                    }
                }));
            }, 100); // delay a bit so Alpine is ready
        });
    </script>
@endif
