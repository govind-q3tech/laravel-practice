<x-layouts.master>
    @section('title', 'Dashborad')
    <!-- Content Header (Page header) -->
    <x-slot name="breadcrumb">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                {{ Breadcrumbs::render('common',['append' => [['label'=> 'Dashboard']]]) }}
            </div>
        </div>
    </x-slot>
    
    <x-slot name="content">
        <div class="row">
        @can('check-user', "users-index")
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $consumers }}</h3>
                        <p>Total Customers</p>
                    </div>
                    <div class="icon">
                        <i class="nav-icon fas fa-users"></i>
                    </div>
                    <a href="{{ route('admin.users.index', ['type' => 'customers']) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endcan    
       
        </div>
    </x-slot>
    
</x-layouts.master>