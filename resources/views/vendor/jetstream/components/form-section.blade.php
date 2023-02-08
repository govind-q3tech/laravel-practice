@props(['submit'])
<div {{ $attributes->merge(['class' => 'col-md-12']) }}>
    <form wire:submit.prevent="{{ $submit }}">
        <div class="{{ isset($actions) ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md' }}">
            <div class="grid grid-cols-8 gap-4">
                {{ $form }}
            </div>
        </div>

        @if (isset($actions))
            <div class="">
                {{ $actions }}
            </div>
        @endif
    </form>
</div>
