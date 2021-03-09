<li class=" nav-item @if($item->getItemClass()){{ $item->getItemClass() }}@endif @if($active)menu-open @endif @if($item->hasItems())has-treeview @endif ">
    <a href="{{ $item->getUrl() }}" class="nav-link @if(count($appends) > 0) hasAppend @endif @if($active) active @endif" @if($item->getNewTab())target="_blank"@endif>
        <i class="nav-icon {{ $item->getIcon() }}"></i>
        <p>
            {{ $item->getName() }}

            @foreach($badges as $badge)
                {!! $badge !!}
            @endforeach

            @if($item->hasItems()) <i class="fas fa-angle-left right"></i>@endif
        </p>

    </a>

    @foreach($appends as $append)
        {!! $append !!}
    @endforeach

    @if(count($items) > 0)
        <ul class="nav nav-treeview">
            @foreach($items as $item)
                {!! $item !!}
            @endforeach
        </ul>
    @endif
</li>
