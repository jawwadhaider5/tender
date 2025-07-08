<h2>Upcoming Future Clients Starting in 2 Days</h2>

<ul>
@foreach ($futureclient as $fc)
    <li>
        <strong>{{ $fc->description }}</strong><br>
        Start Date: {{ \Carbon\Carbon::parse($fc->start_date)->toFormattedDateString() }}<br>
        Comming Date: {{ \Carbon\Carbon::parse($fc->coming_date)->toFormattedDateString() }}
    </li>
@endforeach
</ul>
