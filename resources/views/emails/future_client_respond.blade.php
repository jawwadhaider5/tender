<h2>Upcoming Future Client Responds in 2 Days</h2>

<ul> 
    <li>
        <strong>{{ $future_client_respond->subject }}</strong><br>
        Date: {{ \Carbon\Carbon::parse($future_client_respond->date)->toFormattedDateString() }}<br>
        Subject: {{ $future_client_respond->text }}
    </li> 
</ul>
