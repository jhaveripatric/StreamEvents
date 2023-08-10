<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" style="display: flex;">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Total Revenue: <span id="total-revenue"></span>
                </div>
            </div>
        </div>
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Total Followers Gained: <span id="total-followers"></span>
                </div>
            </div>
        </div>
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Top 3 Items: <ul id="top-items"></ul>
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
                    <button id="load-more-button" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                        Load More
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        const eventsPerPage = 100;
        const loadMoreButton = document.getElementById('load-more-button');
        function fetchAggregation(){
            $.ajax({
                url: '/api/aggregation',
                method: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    const topItemsList = $('#top-items');
                    response.topItems.forEach(item => {
                        const li = $('<li>').text(item.item_name + ' - $' + item.total_sales);
                        topItemsList.append(li);
                    });
                    console.log(response);
                    $('#total-revenue').text('$'+response.totalRevenue);
                    $('#total-followers').text(response.followersGained);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', error);
                }
            });
        }
        function fetchEvents(page) {
            $.ajax({
                url: '/api/events',
                method: 'GET',
                dataType: 'json',
                data: {
                    page:page
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    const eventsList = $('#events-list');
                    response.events.forEach(event => {
                        const li = $('<li>').text(event.message);
                        const button = $('<button>').text(event.read ? 'Mark Unread' : 'Mark Read');
                        if(event.read){
                            button.addClass('mark-read h-8 px-4 m-2 text-sm bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded-full');
                        }else{
                            button.addClass('mark-read h-8 px-4 m-2 text-sm bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full');
                        }

                        button.attr('data-event-id', event.id);
                        li.append(button);
                        eventsList.append(li);
                    });

                    if (currentPage < response.totalPages) {
                        loadMoreButton.style.display = 'block';
                    } else {
                        loadMoreButton.style.display = 'none';
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', error);
                }
            });
        }
        $(document).ready(function () {
            fetchAggregation();
            fetchEvents(currentPage);
            $('#load-more-button').on('click', function () {
                currentPage++;
                fetchEvents(currentPage);
            });
        });

        $(document).on('click', '.mark-read', function() {
            var eventId = $(this).data('event-id');
            console.log('clicked'+eventId);
            $.ajax({
                type: 'POST',
                url: '/markRead',
                data: {
                    eventId: eventId
                },
                success: function(response) {
                    // Handle success response
                }
            });
        });
    </script>
</x-app-layout>
