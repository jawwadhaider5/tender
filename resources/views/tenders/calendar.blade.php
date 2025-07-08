@extends('layouts.admin')


@section('content')

<style>
    .event-long-text {
      white-space: normal !important;
      overflow-wrap: break-word;
      font-size: 12px;
    }
</style>

<div class="main-panel">
    <div class="content-wrapper">

        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ Session::get('message') }}</p>
        </div>
        @endif

        <div class="row">

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <div class="container">
                            <div id="calendar"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')

<script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script>

      document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar')
        const calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        })
        calendar.render()
      })

    </script>


<script>
    $(document).ready(function() {

        function loadCalendar() {
            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                firstDay: 1, // Monday as first day
                height: "auto",
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    let year = fetchInfo.start.getFullYear();
                    let month = fetchInfo.start.getMonth() + 2; // Ensure correct month


                    $.ajax({
                        url: '/get-closing-dates',
                        data: {
                            year: year,
                            month: month
                        },
                        success: function(response) {
                            console.log("API Response:", response);

                            console.log(response);

                            // if (!Array.isArray(response.tenders)) {
                            //     console.error("Invalid response format:", response);
                            //     failureCallback();
                            //     return;
                            // }

                            let events = [];
                            

                            events.push(...response.future_clients.map(event => ({
                                title: `Future Clients:\n${event.description}`,
                                start: event.start_date,
                                color: 'red',
                                allDay: true
                            })));

                            events.push(...response.tenders.map(event => ({
                                title: `Tender:\n${event.description} \nAnnounced Date: ${event.announce_date}`,
                                start: event.close_date,
                                color: 'green',
                                allDay: true
                            })));

                            events.push(...response.client_responds.map(event => ({
                                title: `Clients Responds:\nSubject: ${event.subject} \nResponse: ${event.text}`,
                                start: event.date,
                                color: 'blue',
                                allDay: true
                            })));

                            events.push(...response.future_client_responds.map(event => ({
                                title: `Future Clients Responds:\nSubject: ${event.subject} \nResponse: ${event.text}`,
                                start: event.date,
                                color: 'gray',
                                allDay: true
                            })));
                            events.push(...response.tender_responds.map(event => ({
                                title: `Tender Responds:\nSubject: ${event.subject} \nResponse: ${event.text}`,
                                start: event.date,
                                color: 'orange',
                                allDay: true
                            })));
 
                            successCallback(events);
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error);
                            failureCallback();
                        }
                    });
                },
                eventDidMount: function (info) {
                    let el = info.el;
                    el.classList.add('event-long-text');

                    const formattedHtml = info.event.title.replace(/\n/g, '<br>');
                    info.el.querySelector('.fc-event-title').innerHTML = formattedHtml;
                }
            });

            calendar.render();
        }

        loadCalendar();


    });
</script>

@endsection