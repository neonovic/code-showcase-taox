@props(['data', 'key'])

<div style="font-size: 28px;color: #44b6ae;font-weight: 300;padding: 50px 20px 20px 0;">{{ $data['display'] }}</div>
@if (isset($data['help']))
    <div class="detpodnadpis">{{ $data['help'] }}</div>
@endif
