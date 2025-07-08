@extends('layouts.admin')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">


        <div class="container">
            <div class="row">
                <!-- Summary Cards -->
                <div class="col-md-4 p-2">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Tenders</h5>
                            <p class="card-text">
                                Approved: <span id="approved-tenders">0</span> | Not Approved: <span id="not-approved-tenders">0</span> | Pending: <span id="pending-tenders">0</span>
                            </p>
                            <a href="{{ route('tenders.index') }}" class="btn btn-light">View Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 p-2">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Clients</h5>
                            <p class="card-text"><span id="total-clients">0</span></p>
                            <a href="{{ route('clients.index') }}" class="btn btn-light">View Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 p-2">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Future Clients</h5>
                            <p class="card-text"><span id="total-future-clients">0</span></p>
                            <a href="{{ route('future-clients.index') }}" class="btn btn-light">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="row">
                <div class="col-md-6 p-2">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Recent Activities</h5>
                            <ul id="recent-activities" class="list-group">
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 p-2">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Tenders Overview</h5>
                            <div style="max-width: 300px; height: 300px; margin: auto;">
                                <canvas id="tendersChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Future Clients & Tenders -->
            <div class="row mt-4">
                <div class="col-md-6 p-2">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Upcoming Future Clients</h5>
                            <ul id="future-clients-list" class="list-group">
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Upcoming Tenders</h5>
                            <ul id="upcoming-tenders-list" class="list-group">
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>





    </div>

</div>
@endsection

@section('javascript')


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="{{ asset('js/billreport.js') }}"></script>

@endsection