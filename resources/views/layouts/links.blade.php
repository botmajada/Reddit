<div class="col-md-8">
    <h1><a href="/community">Community {{ $title }}</a></h1>


    <ul class="nav">
        <li class=" lista mr-3 mb-3 nav-item">
            <a class="nav-link {{ !request()->exists('popular') ? '' : 'disabled' }}" href="{{ request()->url() }}">Mas
                recientes</a>
        </li>
        
        <li class=" lista ml-3 mb-3 nav-item">
            <a class="nav-link {{ request()->exists('popular') ? 'disabled' : '' }}"
                href="?popular{{ request()->exists('channel') ? '&channel=' . request()->input('channel') : '' }}">Mas
                populares</a>
        </li>
    </ul>


    @foreach ($links as $link)
        <li>
            <span class="label label-default" style="background: {{ $link->channel->color }}">
                <a href="/community/{{ $link->channel->title }}" target="_blank">{{ $link->channel->title }} </a>

            </span>
            <a href="{{ $link->link }}" target="_blank">{{ $link->title }} </a>


            <form method="POST" action="/community/votes/{{ $link->id }}">
                {{ csrf_field() }}
                <button type="submit"
                    class=" like {{ Auth::check() && Auth::user()->votedFor($link) ? 'btn-success' : 'btn-secondary' }}"
                    {{ Auth::guest() ? 'disabled' : '' }}>
                   👍 {{ $link->users()->count() }}
                </button>
            </form>

            <small>Contributed by: {{ $link->creator->name }} {{ $link->updated_at->diffForHumans() }}</small>
        </li>
    @endforeach

</div>
