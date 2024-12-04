<x-app-layout>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-center items-center mt-4">
                    <div class="flex flex-col items-center">
                        <img src="{{ Auth::user()->avatar ?? 'default-avatar.png' }}" alt="Profile" class="w-20 h-20 rounded-full">
                        <div class="mt-6 text-lg font-semibold">{{ Auth::user()->name }}</div>
                        <div class="mt-6 text-gray-900">
                            {{ __("You're logged in!") }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let countdown = 3;
        const countdownElement = document.createElement('div');
        countdownElement.className = 'mt-6 text-gray-900';
        countdownElement.innerText = `Redirecting in ${countdown} seconds...`;
        document.querySelector('.flex.flex-col.items-center').appendChild(countdownElement);

        const interval = setInterval(() => {
            countdown--;
            countdownElement.innerText = `Redirecting in ${countdown} seconds...`;
            if (countdown === 0) {
                clearInterval(interval);
                window.location.href = "{{ route('3DModel') }}";
            }
        }, 1000);
    });
</script>