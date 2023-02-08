@if ($errors->any())
    <div {{ $attributes }}>
        <div class="alert alert-danger" role="alert">
            <ul class="mt-3 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
          </div>
    </div>
@endif
