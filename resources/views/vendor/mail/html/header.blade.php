@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
<img src="{{ asset('img/icon.png') }}" class="logo" alt="Logo" style="height: 40px; width: 40px; vertical-align: middle; margin-right: 8px;">
<span style="color: #18181b; font-size: 19px; font-weight: bold; vertical-align: middle;">{!! $slot !!}</span>
</a>
</td>
</tr>
