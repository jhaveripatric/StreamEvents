<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Stream Events") }}
                </div>
                <div class="p-6 text-gray-900">
                    <ul id="events-list"></ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fetchEvents() {
            $.ajax({
                url: '/api/events',
                method: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (events) {
                    const eventsList = $('#events-list');
                    events.forEach(event => {
                        const li = $('<li>').text(event);
                        eventsList.append(li);
                    });
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', error);
                }
            });
        }

        $(document).ready(function () {
            fetchEvents();
        });
    </script>
</x-app-layout>
