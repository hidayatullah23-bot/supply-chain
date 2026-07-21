@props(['country', 'size' => 40])
@php
    $code = strtolower($country->country_code ?? '');
    $name = $country->country_name ?? 'Negara';
@endphp
<span {{ $attributes->merge(['class' => 'd-inline-grid align-items-center justify-content-center overflow-hidden bg-light border']) }} style="width:{{ $size }}px;height:{{ round($size * .72) }}px;border-radius:8px;box-shadow:0 2px 7px rgba(15,23,42,.12);flex:0 0 auto">
    @if(strlen($code) === 2)
        <img src="https://flagcdn.com/w80/{{ $code }}.png" srcset="https://flagcdn.com/w160/{{ $code }}.png 2x" width="{{ $size }}" height="{{ round($size * .72) }}" alt="Bendera {{ $name }}" loading="lazy" style="width:100%;height:100%;object-fit:cover" onerror="this.style.display='none';this.nextElementSibling.style.display='grid'">
    @endif
    <span style="display:{{ strlen($code) === 2 ? 'none' : 'grid' }};place-items:center;width:100%;height:100%;font-size:11px;font-weight:800;color:#6156dc">{{ strtoupper($country->country_code ?? '--') }}</span>
</span>
