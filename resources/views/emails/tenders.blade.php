<h2>Upcoming Tenders Closing in 2 Days</h2>

<ul>
@foreach ($tenders as $tender)
    <li>
        <strong>{{ $tender->description }}</strong><br>
        Announce Date: {{ \Carbon\Carbon::parse($tender->announce_date)->toFormattedDateString() }}<br>
        Closing Date: {{ \Carbon\Carbon::parse($tender->close_date)->toFormattedDateString() }}
    </li>
@endforeach
</ul>
