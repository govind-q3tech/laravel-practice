<x-layouts.master>

  <!-- Content Header (User header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Click Reports</h1>
      </div>
      <div class="col-sm-6">
      {{ Breadcrumbs::render('common',['append' => [['label'=> $getController,'route'=> 'admin.admin-users.index'],['label' => 'View User Detail']]]) }}
      </div>
    </div>
  </x-slot>

  <x-slot name="content">
    {{ Form::open(['url' => route('admin.report.index'),'method' => 'get']) }}
                    
                        <div class="row">
                            <div class="col-md-3 form-group">
                            <select class="form-control" id="type"  onchange="hideshow()" name="type">
                            <option value="">Select</option>
                            <option @if($type=="last week") selected @endif value="last week">last week</option>
                            <option @if($type=="last month") selected @endif value="last month">last month</option>
                            <option @if($type=="date") selected @endif value="date">date </option>
                            <option @if($type=="range") selected @endif value="range">range</option>
                            </select> 
                            </div>
                            <div id="start"  style="display:none;" class="col-md-2 form-group"> {{ Form::text('start_date', $start_date, ['class' => 'form-control','placeholder'=>'Start Date','readonly'=>'readonly','id'=>'datetimepicker1','required'=>true]) }} </div>
                            <div id="end" style="display:none;" class="col-md-2 form-group"> {{ Form::text('end_date', $end_date, ['class' => 'form-control','placeholder'=>'End Date','readonly'=>'readonly','id'=>'datetimepicker2','required'=>true]) }} </div>
                            <div class="col-md-5 form-group">
                                <button class="btn btn-success" title="Search" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                 <a href="{{ route('admin.report.index') }}" class="btn btn-warning" title="Reset"><i class="fa fa-fw fa-refresh"></i> Reset</a> 
                            </div>
                        </div>
                    
                    {{ Form::close() }}
    <div class="row">
                        <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="lbl">Number of Views</label>
                                    <br>
                                    <label class="lbl"><strong>{{ $recordType[1]??'NA' }}</strong></label>
                                </div>
                            </div> 
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="lbl">Number of Phone Clicks</label>
                                    <br>
                                    <label class="lbl"><strong>{{ $recordType[2]??'NA' }}</strong></label>
                                </div>
                            </div> 
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="lbl">Number of Email Clicks</label>
                                    <br>
                                    <label class="lbl"><strong>{{ $recordType[3]??'NA' }}</strong></label>
                                </div>
                            </div> 
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="lbl">Number of Website Clicks</label>
                                    <br>
                                    <label class="lbl"><strong>{{ $recordType[4]??'NA' }}</strong></label>
                                </div>
                            </div> 

                        </div>
                        <div class="row">

                         <div class="col-lg-10">
                            <canvas id="oilChart" width="400" height="200"></canvas>
                         </div>
                        </div>
  </x-slot>
</x-layouts.master>
 <script    src="{{ asset('js/chart.min.js') }}"></script>
<script type="text/javascript">

var oilCanvas = document.getElementById("oilChart");

Chart.defaults.global.defaultFontFamily = "Lato";
Chart.defaults.global.defaultFontSize = 18;

var oilData = {
labels: [
"Views",
"Phone Clicks",
"Email Clicks",
"Website Clicks",

],
datasets: [
{
data: [{{ $recordType[1]??'0' }}, {{ $recordType[2]??'0' }},{{ $recordType[3]??'0' }}, {{ $recordType[4]??'0' }}],
backgroundColor: [
"#c00a57",
"#63FF84",
"#846663",
"#8463FF",
"#6384FF"
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
    var type = $('#type').children("option:selected").val();
      
       if(type=="range"){
        $('#start').show();
        $('#end').show();
       }else if(type=="date"){
        $('#start').show();
        $('#end').hide();
       }else{
        $('#start').hide();
        $('#end').hide();
       }
    });
    
    function hideshow() {
       var type = $('#type').children("option:selected").val();
      
       if(type=="range"){
        $('#start').show();
        $('#end').show();
       }else if(type=="date"){
        $('#start').show();
        $('#end').hide();
       }else{
        $('#start').hide();
        $('#end').hide();
       }

    }
   
      
   
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
    });
</script>