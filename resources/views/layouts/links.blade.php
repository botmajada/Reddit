<div class="col-md-8">
    <h1><a href="/community">Community {{ $title }}</a></h1>


    <ul class="nav">
        <li class="lista mr-3 mb-3 nav-item">
            <a class="nav-link{{ !request()->exists('popular') ? ' active' : '' }}" href="{{ url()->current() }}">Más recientes</a>
        </li>
        <li class="lista ml-3 mb-3 nav-item">
            <a class="nav-link{{ request()->exists('popular') ? ' active' : '' }}"
               href="{{ request()->fullUrlWithQuery(['popular' => true]) }}">Más populares</a>
        </li>
    </ul>


    @foreach ($links as $link)
        <li>
            <span class="label label-default" style="background: {{ $link->channel->color }}">
                <a href="/community/{{ $link->channel->title }}" target="_blank">{{ $link->channel->title }} </a>

            </span>
            <a href="{{ $link->link }}" target="_blank">{{ $link->title }} </a>


            <form method="POST" action="/community/vote/{{ $link->id }}">
                {{ csrf_field() }}

                <button type="submit"
                    class=" bi bi-hand-thumbs-up like {{ Auth::check() && Auth::user()->votedFor($link) ? 'btn-success' : 'btn-secondary' }}"
                    {{ Auth::guest() ? 'disabled' : '' }}>
                 {{ $link->user()->count()}}
                </button>

                <input type="hidden" name="vote" value="upvote">
            </form>

            <small>Contributed by: {{ $link->creator->name }} {{ $link->updated_at->diffForHumans() }}</small>
        </li>
    @endforeach

</div>
