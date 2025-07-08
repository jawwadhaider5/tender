<h2>Upcoming Client Responds in 2 Days</h2>

<ul> 
    <li>
        <strong>{{ $client_respond->subject }}</strong><br>
        Date: {{ \Carbon\Carbon::parse($client_respond->date)->toFormattedDateString() }}<br>
        Subject: {{ $client_respond->text }}
    </li> 
</ul>
