$(document).ready(function () {
    // Fetch dashboard data
    $.get("dashboard/data", function (data) {
        $("#approved-tenders").text(data.approved_tenders);
        $("#not-approved-tenders").text(data.not_approved_tenders);
        $("#pending-tenders").text(data.pending_tenders);
        $("#total-clients").text(data.total_clients);
        $("#total-future-clients").text(data.total_future_clients);

        // Load recent activities
        let activities = data.recent_activities.map(activity => `<li class="list-group-item">${activity}</li>`);
        $("#recent-activities").html(activities.join(''));

        // Load future clients & tenders
        let futureClients = data.future_clients.map(client => `<li class="list-group-item"><b class="text-primary">${client.description}</b> - ${client.coming_date}</li>`);
        $("#future-clients-list").html(futureClients.join(''));

        let upcomingTenders = data.upcoming_tenders.map(tender => `<li class="list-group-item"><b class="text-primary">${tender.description}</b> - ${tender.close_date}</li>`);
        $("#upcoming-tenders-list").html(upcomingTenders.join(''));

        // Chart Data
        let ctx = document.getElementById('tendersChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Approved', 'Not Approved', 'Pending'],
                datasets: [{
                    data: [data.approved_tenders, data.not_approved_tenders, data.pending_tenders],
                    backgroundColor: ['blue', 'red', 'orange']
                }]
            }
        });
    });
});