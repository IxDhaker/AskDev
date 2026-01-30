@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel' || trim($slot) === 'AskDev')
                <div style="font-size: 32px; font-weight: bold; color: #6366f1; font-family: 'Segoe UI', Arial, sans-serif; letter-spacing: -0.5px;">
                    AskDev
                </div>
            @else
                {!! $slot !!}
            @endif
        </a>
    </td>
</tr>