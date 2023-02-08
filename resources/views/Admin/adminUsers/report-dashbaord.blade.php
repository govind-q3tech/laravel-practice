<x-layouts.master>

  <!-- Content Header (User header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Report Manager </h1>
      </div>
      <div class="col-sm-6">
      {{ Breadcrumbs::render('common',['append' => [['label'=> "Report Manager",'route'=> 'admin.report.report-dashboard']]]) }}
      </div>
    </div>
  </x-slot>

  <x-slot name="content">
                        {{ Form::open(['url' => route('admin.report.report-dashboard'),'method' => 'get']) }}
                    
                        <div class="row">
                            <div id="start"   class="col-md-6 form-group"> {{ Form::text('start_date', $dateF, ['class' => 'form-control','placeholder'=>'Start Date','readonly'=>'readonly','id'=>'reservation','required'=>true]) }} </div>
                            <div class="col-md-5 form-group">
                                <button class="btn btn-success" title="Search" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                 <a href="{{ route('admin.report.report-dashboard') }}" class="btn btn-warning" title="Reset"><i class="fa fa-refresh"></i> Reset</a> 
                            </div>
                        </div> <br><br>
                    
                         {{ Form::close() }}

                         <div class="row">
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ $consumers}}</h3>
                                        <p>Total Customers</p>
                                    </div>
                                    <div class="icon">
                                        <i class="nav-icon fas fa-users"></i>
                                    </div>
                                    <a href="{{ route('admin.users.index', ['type' => 'customers']) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ $advertiserFreeUsers }}</h3>
                                        <p>Total Advertisers</p>
                                    </div>
                                    <div class="icon">
                                        <i class="nav-icon fas fa-users"></i>
                                    </div>
                                    <a href="{{ route('admin.users.index', ['type' => 'advertisers']) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                                    <!-- ./col -->
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{$advertisements }}</h3>
                                        <p>Total Advertisements</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="{{ route('admin.advertisements.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>  
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{$activeAdvertisements }}</h3>
                                        <p>Total Active Advertisements</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="{{ route('admin.advertisements.index') }}?keyword=1" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                     </div>
                     <div class="row">                                 
                            
                            
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{$draftAdvertisements }}</h3>
                                        <p>Total Draft Advertisements</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="{{ route('admin.advertisements.index') }}?keyword=0" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{$reviewAdvertisements }}</h3>
                                        <p>Total Reviewing Advertisements</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="{{ route('admin.advertisements.index') }}?keyword=2" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{$declineAdvertisements }}</h3>
                                        <p>Total Declined Advertisements</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="{{ route('admin.advertisements.index') }}?keyword=3" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>{{$closeAdvertisements }}</h3>
                                        <p>Total Closed Advertisements</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="{{ route('admin.advertisements.index') }}?keyword=4" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                     </div>
                     <br/>
                    <div class="row" style="min-height:500px !important;display: none;">

                    <div class="col-lg-12">
                    <canvas id="oilChart" width="400" height="200"></canvas>
                    </div>
                    </div>

                       
  </x-slot>
</x-layouts.master>
<link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}" >
<script    src="{{ asset('js/chart.min.js') }}"></script>
<script src="{{ asset('js/daterangepicker.js') }}"></script>
<script type="text/javascript">
// $('.content-wrapper').css('min-height', '1200px');


var oilCanvas = document.getElementById("oilChart");

Chart.defaults.global.defaultFontFamily = "Lato";
Chart.defaults.global.defaultFontSize = 18;

var oilData = {
labels: [
"Customers",
"Advertiser Users",
"Advertisements",
"Active Advertisements",
"Draft Advertisements",
"Review Advertisements",
"Decline Advertisements",
"Closed Advertisements",

],
datasets: [
{
data: [{{ $consumers??'0' }}, {{ $advertiserFreeUsers??'0' }},{{ $advertisements??'0' }}, {{ $activeAdvertisements??'0' }}, {{ $draftAdvertisements??'0' }}, {{ $reviewAdvertisements??'0' }}, {{ $declineAdvertisements??'0' }}, {{ $closeAdvertisements??'0' }}],
backgroundColor: [
"#28a745",
"#63FF84",
"#846663",
"#8463FF",
"#6384FF",
"#FFC0CB",
"#FFFF00",
"#dc3545",
]
}]
};

var pieChart = new Chart(oilCanvas, {
type: 'pie',
data: oilData
});
</script>

<script>
    $(function() {
        $('#datetimepicker1').datepicker({
            changeMonth: true,
            changeYear: true,
            maxDate: '0'
        });
    });
    $(function() {
        $('#datetimepicker2').datepicker({
            changeMonth: true,
            changeYear: true,
            maxDate: '0'
        });

        $('#reservation').daterangepicker();
    });
</script>