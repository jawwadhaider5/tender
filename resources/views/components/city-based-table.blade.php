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
                                <th>Group</th>
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
                    return '<div class="code-selected" style="display: none;"></div>' +
                           '<a href="#" class="code-link" data-code="' + data + '">' + data + '</a>';
                }
            },
            {
                data: 'cities',
                render: function(data, type, row) {
                    var html = '<div class="city-selected" style="display: none;"></div>';
                    html += '<div class="city-links" style="display: none;">';
                    data.forEach(function(city, index) {
                        html += '<a href="#" class="city-link" data-city-id="' + city.id + '" data-city-name="' + city.name + '">' + city.name + '</a>';
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
                    var html = '<div class="group-selected" style="display: none;"></div>';
                    data.forEach(function(city) {
                        html += '<div class="group-links" id="groups-' + city.id + '" style="display: none;">';
                        if (city.groups && city.groups.length > 0) {
                            city.groups.forEach(function(group, index) {
                                html += '<a href="#" class="group-link" data-group-id="' + group.id + '" data-city-id="' + city.id + '" data-group-name="' + group.name + '">' + group.name + '</a>';
                                if (index < city.groups.length - 1) {
                                    html += '<br>';
                                }
                            });
                        } else {
                            html += '<div class="group-item">No groups found</div>';
                        }
                        html += '</div>';
                    });
                    return html;
                }
            },
            {
                data: 'cities',
                render: function(data, type, row) {
                    var html = '<div class="data-container">';
                    data.forEach(function(city) {
                        city.groups.forEach(function(group) {
                            html += '<div class="data-list" id="data-' + group.id + '-' + city.id + '" style="display: none;">';
                            if (group.clients && group.clients.length > 0) {
                                group.clients.forEach(function(item) {
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
                    });
                    html += '</div>';
                    return html;
                }
            }
        ],
        order: [[0, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        drawCallback: function(settings) {
            // Maintain row visibility state after table redraw
            if (activeCode !== null) {
                // Re-apply the hiding of other rows
                setTimeout(function() {
                    hideOtherRows(activeCode);
                }, 10);
            } else {
                // No active code, show all rows
                setTimeout(function() {
                    showAllRows();
                }, 10);
            }
        }
    });

    // Track which city code is active
    var activeCode = null;

    // Function to hide other rows (keep active row visible)
    function hideOtherRows(activeCodeValue) {
        // Hide entire rows for all city codes except the active one
        $('#{{ $tableId }} tbody tr').each(function() {
            var $row = $(this);
            // Get code from either code-link or code-selected
            var $codeLink = $row.find('.code-link');
            var $codeSelected = $row.find('.code-selected');
            var rowCode = null;
            
            if ($codeLink.length) {
                rowCode = $codeLink.data('code');
            }
            
            if (!rowCode && $codeSelected.length) {
                // Try to get from code-selected
                rowCode = $codeSelected.data('code');
                if (!rowCode) {
                    // Get from text content
                    var selectedText = $codeSelected.find('.selected-text').text();
                    if (selectedText) {
                        rowCode = selectedText.trim();
                    }
                }
            }
            
            // If this is not the active row, hide the entire row
            if (rowCode !== activeCodeValue) {
                $row.addClass('other-row-hidden');
            } else {
                // This is the active row, ensure it's visible
                $row.removeClass('other-row-hidden');
            }
        });
    }

    // Function to show all rows
    function showAllRows() {
        // Show all rows
        $('#{{ $tableId }} tbody tr').each(function() {
            $(this).removeClass('other-row-hidden');
        });
    }

    // Function to hide details section based on type
    function hideDetailsSection() {
        var type = '{{ $type }}';
        if (type === 'clients') {
            $('#client_details_section').hide();
        } else if (type === 'tenders') {
            $('#tender_details_section').hide();
        } else if (type === 'future-clients') {
            $('#future_client_details_section').hide();
        }
    }

    // Handle code link click - convert to selected button and hide other rows' columns
    $('#{{ $tableId }}').on('click', '.code-link', function(e) {
        e.preventDefault();
        var code = $(this).data('code');
        var row = $(this).closest('tr');
        var codeSelected = row.find('.code-selected');
        var codeLink = row.find('.code-link');
        var citySelected = row.find('.city-selected');
        var cityLinks = row.find('.city-links');

        // Convert code link to selected button
        codeSelected.html('<span class="selected-item"><span class="selected-text">' + code + '</span><i class="mdi mdi-close selected-close"></i></span>')
            .data('code', code)
            .show();
        codeLink.hide();
        
        // Set active code first
        activeCode = code;
        
        // Show city links for this row
        cityLinks.slideDown();
        
        // Hide other rows immediately and also after a short delay to ensure DOM is updated
        hideOtherRows(code);
        setTimeout(function() {
            hideOtherRows(code);
        }, 100);
        citySelected.hide().html('');
        
        // Hide groups and data
        row.find('.group-links').hide();
        row.find('.group-selected').hide().html('');
        row.find('.data-list').hide();
        
        // Hide details section
        hideDetailsSection();
    });

    // Handle selected code click - convert back to link and show all columns
    $('#{{ $tableId }}').on('click', '.code-selected .selected-item', function(e) {
        // Don't trigger if clicking the close button
        if ($(e.target).hasClass('mdi-close') || $(e.target).hasClass('selected-close') || $(e.target).closest('.selected-close').length) {
            return;
        }
        e.preventDefault();
        e.stopPropagation();
        var row = $(this).closest('tr');
        var codeSelected = row.find('.code-selected');
        var codeLink = row.find('.code-link');
        var citySelected = row.find('.city-selected');
        var cityLinks = row.find('.city-links');
        
        // Convert back to link
        codeSelected.hide().html('').removeData('code');
        codeLink.show();
        
        // Show all columns for all rows
        activeCode = null;
        showAllRows();
        
        // Hide city links
        cityLinks.hide();
        citySelected.hide().html('');
        
        // Hide groups and data
        row.find('.group-links').hide();
        row.find('.group-selected').hide().html('');
        row.find('.data-list').hide();
        
        // Hide details section
        hideDetailsSection();
    });

    // Handle selected code close button - same as clicking the selected item
    $('#{{ $tableId }}').on('click', '.code-selected .selected-close', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var row = $(this).closest('tr');
        var codeSelected = row.find('.code-selected');
        var codeLink = row.find('.code-link');
        var citySelected = row.find('.city-selected');
        var cityLinks = row.find('.city-links');
        
        // Convert back to link
        codeSelected.hide().html('').removeData('code');
        codeLink.show();
        
        // Show all columns for all rows
        activeCode = null;
        showAllRows();
        
        // Hide city links
        cityLinks.hide();
        citySelected.hide().html('');
        
        // Hide groups and data
        row.find('.group-links').hide();
        row.find('.group-selected').hide().html('');
        row.find('.data-list').hide();
        
        // Hide details section
        hideDetailsSection();
    });

    // Handle city name click - shows groups
    $('#{{ $tableId }}').on('click', '.city-link', function(e) {
        e.preventDefault();
        var cityId = $(this).data('city-id');
        var cityName = $(this).data('city-name');
        var row = $(this).closest('tr');
        var citySelected = row.find('.city-selected');
        var cityLinks = row.find('.city-links');
        var groupSelected = row.find('.group-selected');

        // Select this city and hide others
        citySelected.html('<span class="selected-item"><span class="selected-text">' + cityName + '</span><i class="mdi mdi-close selected-close"></i></span>')
            .data('city-id', cityId)
            .show();
        cityLinks.hide();
        
        // Hide all group links and data lists in the same row
        row.find('.group-links').hide();
        row.find('.data-list').hide();
        groupSelected.hide().html('').removeData('group-id');
        
        // Hide details section when data is hidden
        hideDetailsSection();

        // Show the clicked city's groups
        $('#groups-' + cityId).slideDown();
        
        // Ensure this row remains visible (in case code is selected)
        row.removeClass('other-row-hidden');
    });

    // Handle selected city click - expand to show all cities
    $('#{{ $tableId }}').on('click', '.city-selected .selected-item', function(e) {
        // Don't trigger if clicking the close button
        if ($(e.target).hasClass('mdi-close') || $(e.target).hasClass('selected-close') || $(e.target).closest('.selected-close').length) {
            return;
        }
        e.preventDefault();
        e.stopPropagation();
        var row = $(this).closest('tr');
        var citySelected = row.find('.city-selected');
        var cityLinks = row.find('.city-links');
        
        // Hide selected city and show all city links
        citySelected.hide().html('').removeData('city-id');
        cityLinks.slideDown();
        
        // Hide groups and data
        row.find('.group-links').hide();
        row.find('.group-selected').hide().html('').removeData('group-id');
        row.find('.data-list').hide();
        // Hide details section when data is collapsed
        hideDetailsSection();
    });

    // Handle selected city close button
    $('#{{ $tableId }}').on('click', '.city-selected .selected-close', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var row = $(this).closest('tr');
        var citySelected = row.find('.city-selected');
        var cityLinks = row.find('.city-links');
        
        citySelected.hide().html('').removeData('city-id');
        cityLinks.slideDown();
        row.find('.group-links').hide();
        row.find('.group-selected').hide().html('').removeData('group-id');
        row.find('.data-list').hide();
        // Hide details section when data is collapsed
        hideDetailsSection();
    });

    // Handle group click - shows data
    $('#{{ $tableId }}').on('click', '.group-link', function(e) {
        e.preventDefault();
        var groupId = $(this).data('group-id');
        var cityId = $(this).data('city-id');
        var groupName = $(this).data('group-name');
        var row = $(this).closest('tr');
        var groupSelected = row.find('.group-selected');
        var groupLinks = row.find('.group-links');

        // Select this group and hide others
        groupSelected.html('<span class="selected-item"><span class="selected-text">' + groupName + '</span><i class="mdi mdi-close selected-close"></i></span>')
            .data('group-id', groupId)
            .show();
        
        // Hide all group links in the same row
        row.find('.group-links').hide();
        
        // Hide all data lists in the same row first
        row.find('.data-list').hide();
        
        // Note: We don't hide details section here because we're about to show data
        // Details section will be shown when user clicks on a data item

        // Show the clicked group's data
        $('#data-' + groupId + '-' + cityId).slideDown();
    });

    // Handle selected group click - expand to show all groups
    $('#{{ $tableId }}').on('click', '.group-selected .selected-item', function(e) {
        // Don't trigger if clicking the close button
        if ($(e.target).hasClass('mdi-close') || $(e.target).hasClass('selected-close') || $(e.target).closest('.selected-close').length) {
            return;
        }
        e.preventDefault();
        e.stopPropagation();
        var row = $(this).closest('tr');
        var groupSelected = row.find('.group-selected');
        var cityId = row.find('.city-selected').data('city-id');
        
        // Hide selected group and show all group links
        groupSelected.hide().html('').removeData('group-id');
        if (cityId) {
            // Show groups for the selected city
            $('#groups-' + cityId).slideDown();
        } else {
            // Show all group links if no city is selected
            row.find('.group-links').slideDown();
        }
        row.find('.data-list').hide();
        // Hide details section when data is collapsed
        hideDetailsSection();
    });

    // Handle selected group close button
    $('#{{ $tableId }}').on('click', '.group-selected .selected-close', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var row = $(this).closest('tr');
        var groupSelected = row.find('.group-selected');
        var cityId = row.find('.city-selected').data('city-id');
        
        groupSelected.hide().html('').removeData('group-id');
        if (cityId) {
            $('#groups-' + cityId).slideDown();
        } else {
            row.find('.group-links').slideDown();
        }
        row.find('.data-list').hide();
        // Hide details section when data is collapsed
        hideDetailsSection();
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
.code-link, .city-link, .group-link, .data-link {
    color: #007bff;
    text-decoration: none;
    cursor: pointer;
}
.code-link:hover, .city-link:hover, .group-link:hover, .data-link:hover {
    text-decoration: underline;
}
.city-links, .group-links {
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
.group-item {
    padding: 5px 0;
    color: #6c757d;
    font-style: italic;
}
.selected-item {
    display: flex;
    width: 100%;
    padding: 5px 10px;
    background-color: #e7f3ff;
    border: 1px solid #007bff;
    border-radius: 4px;
    color: #007bff;
    font-weight: 500;
    cursor: pointer;
    font-size: 14px;
    line-height: 1.5;
    align-items: center;
    justify-content: space-between;
    box-sizing: border-box;
}
.selected-item:hover {
    background-color: #d0e7ff;
}
.selected-text {
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.selected-close {
    cursor: pointer;
    margin-left: 8px;
    flex-shrink: 0;
    font-size: 16px;
    opacity: 0.8;
}
.selected-close:hover {
    opacity: 1;
}
.city-selected, .group-selected, .code-selected {
    margin-bottom: 5px;
    margin-top: 0;
    padding: 0;
    width: 100%;
    display: block;
    min-height: 32px;
}
.city-selected .selected-item, .group-selected .selected-item, .code-selected .selected-item {
    margin: 0;
    width: 100%;
}
.other-row-hidden {
    display: none !important;
}
</style>
@endpush
