@props(['on'])

<div  x-data="{ shown: false, timeout: null }"
    x-init="@this.on('{{ $on }}', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 4000);  })"
    x-show.transition.opacity.out.duration.1500ms="shown"
    style="display: none;"
    {{ $attributes->merge(['class' => 'btn btn-green mr-2 text-sm text-gray-600']) }}>
    {{ $slot ?? 'Updating.' }}
</div>
