<h2>Upcoming Tender Responds in 2 Days</h2>

<ul> 
    <li>
        <strong>{{ $tender_respond->subject }}</strong><br>
        Date: {{ \Carbon\Carbon::parse($tender_respond->date)->toFormattedDateString() }}<br>
        Subject: {{ $tender_respond->text }}
    </li> 
</ul>
