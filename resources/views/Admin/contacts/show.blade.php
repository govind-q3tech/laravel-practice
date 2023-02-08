<x-layouts.master>
@section('title', 'Enquiry')
  <!-- Content Header (Contact header) -->
  <x-slot name="breadcrumb">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Admin Enquiry Manager</h1>
      </div>
      <div class="col-sm-6">
      {{ Breadcrumbs::render('common',['append' => [['label'=> 'Enquiries','route'=> 'admin.contacts.index'],['label' => 'View Enquiry Detail']]]) }}
      </div>
    </div>
  </x-slot>

  <x-slot name="content">
    <!-- Main content -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Admin Enquiry Manager</h3>

            <div class="card-tools">
              <a href="{{ route('admin.contacts.index') }}" class="btn btn-default pull-right" title="Cancel"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
          <table class="table table-hover table-striped">
          <tr>
                    <th scope="row">{{ __('Name') }}</th>
                    <td>{{ $contact->name }}</td>
                </tr>
          

                <tr>
                    <th scope="row">{{ __('Email') }}</th>
                    <td>{{ $contact->email }}</td>
                </tr>

                <tr>
                    <th scope="row">{{ __('Subject') }}</th>
                    <td>{{ $contact->subject }}</td>
                </tr>

                <tr>
                    <th scope="row">{{ __('Message') }}</th>
                    <td>{{ $contact->message }}</td>
                </tr>                
                <tr>
                    <th scope="row"><?= __('Created') ?></th>
                    <td>{{ $contact->created_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ __('Modified') }}</th>
                    <td>{{ $contact->updated_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</td>
                </tr>
                
            </table>
          </div>
   
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.content -->
  </x-slot>
</x-layouts.master>