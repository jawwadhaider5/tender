@props([
    'title' => 'Data by City',
    'tableId' => 'city_data_table',
    'route' => '',
    'type' => 'clients' // can be 'clients', 'tenders', or 'future-clients'
])

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="space">
                        <div class="card-title">
                            <h4>{{ $title }}</h4>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="{{ $tableId }}" class="table table-hover table-bordered w-100">
                        <thead>
                            <tr class="bg-success text-white">
                                <th>City Code</th>
                                <th>City Name</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('javascript')
<script>
$(document).ready(function() {
    var table = $('#{{ $tableId }}').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ $route }}",
            type: "GET",
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            {
                data: 'code',
                render: function(data, type, row) {
                    return '<a href="#" class="code-link" data-code="' + data + '">' + data + '</a>';
                }
            },
            {
                data: 'cities',
                render: function(data, type, row) {
                    var html = '<div class="city-links" style="display: none;">';
                    data.forEach(function(city, index) {
                        html += '<a href="#" class="city-link" data-city-id="' + city.id + '">' + city.name + '</a>';
                        if (index < data.length - 1) {
                            html += '<br>';
                        }
                    });
                    html += '</div>';
                    return html;
                }
            },
            {
                data: 'cities',
                render: function(data, type, row) {
                    var html = '<div class="data-container">';
                    data.forEach(function(city) {
                        html += '<div class="data-list" id="data-' + city.id + '" style="display: none;">';
                        if (city.clients && city.clients.length > 0) {
                            city.clients.forEach(function(item) {
                                var link = '';
                                switch('{{ $type }}') {
                                    case 'clients':
                                        link = '/clients?highlight=' + item.id;
                                        break;
                                    case 'tenders':
                                        link = '/tenders?highlight=' + item.id;
                                        break;
                                    case 'future-clients':
                                        link = '/future-clients?highlight=' + item.id;
                                        break;
                                }
                                html += '<div class="data-item"><a href="' + link + '" class="data-link">' + item.name + '</a></div>';
                            });
                        } else {
                            html += '<div class="data-item">No data found</div>';
                        }
                        html += '</div>';
                    });
                    html += '</div>';
                    return html;
                }
            }
        ],
        order: [[0, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });

    // Handle code click
    $('#{{ $tableId }}').on('click', '.code-link', function(e) {
        e.preventDefault();
        var code = $(this).data('code');
        var row = $(this).closest('tr');

        // Toggle the city links in the same row
        row.find('.city-links').slideToggle();
    });

    // Handle city name click
    $('#{{ $tableId }}').on('click', '.city-link', function(e) {
        e.preventDefault();
        var cityId = $(this).data('city-id');

        // Hide all data lists in the same row
        $(this).closest('tr').find('.data-list').hide();

        // Show the clicked city's data
        $('#data-' + cityId).slideToggle();
    });
    
    // Handle client/data item click
    $('#{{ $tableId }}').on('click', '.data-link', function(e) {
        e.preventDefault();
        var link = $(this).attr('href');
        
        // For clients, future-clients, and tenders types, let the parent page handle the click
        if ('{{ $type }}' === 'clients' || '{{ $type }}' === 'future-clients' || '{{ $type }}' === 'tenders') {
            // Trigger a custom event that the parent page can listen to
            $(document).trigger('client-clicked', [link]);
        } else {
            // For other types, follow the original behavior
            window.location.href = link;
        }
    });
});
</script>

<style>
.code-link, .city-link, .data-link {
    color: #007bff;
    text-decoration: none;
    cursor: pointer;
}
.code-link:hover, .city-link:hover, .data-link:hover {
    text-decoration: underline;
}
.city-links {
    line-height: 1.8;
}
.data-item {
    padding: 5px 0;
    border-bottom: 1px solid #eee;
}
.data-item:last-child {
    border-bottom: none;
}
.data-container {
    min-height: 50px;
}
</style>
@endpush
