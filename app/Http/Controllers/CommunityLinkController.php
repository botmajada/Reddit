<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\CommunityLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommynityLinkForm;
use App\Models\CommunityLinkUser;
use App\Queries\CommunityLinksQuery;


class CommunityLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $linksQuery;

    public function __construct(CommunityLinksQuery $linksQuery)
    {
        $this->linksQuery = $linksQuery;
    }

    public function index(Request $request, Channel $channel = null )
    {

        $title = null; // Titulo al lado de community
        $links = $this->linksQuery->getAll();
        if ($channel == null) {
            $orderBy = 'created_at';
            $orderDirection = 'desc';

            if (request()->exists('popular')) {
                $orderBy = 'votes_count';
                $orderDirection = 'desc';
            }

            $links = CommunityLink::withCount('users') ->orderBy($orderBy, $orderDirection) ->paginate(25);

        } else {

            $links = $channel->communitylinks()->where('approved', true)->latest('updated_at')->paginate(25);


            $title = '- ' . $channel->title;
        }

        $channels = Channel::orderBy('title', 'asc')->get();

        return view('community/index', compact('links', 'channels', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($attributes)
    {
        return redirect('/community')->with('flash', 'Tu enlace ha sido guardado!');
        $channels = Channel::orderBy('title', 'asc')->get();
        return view('community/create', compact('channels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'channel_id' => 'required|exists:channels,id',
            'title' => 'required',
            'link' => 'required|url',
        ]);

        $this->validate($request, (new CommynityLinkForm)->rules());
        $link = new CommunityLink();
        $link->user_id = Auth::id();
        if ($link->hasAlreadyBeenSubmitted($request->link)) {
            return back()->with('success', 'Link added successfully');
        }

        $approved = Auth::user()->trusted ? true : false;
        $request->merge(['user_id' => Auth::id(), 'approved' => $approved]);
        CommunityLink::create($request->all());
        return back()->with('success', 'Link added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CommunityLink  $communityLink
     * @return \Illuminate\Http\Response
     */
    public function show(CommunityLink $communityLink)
    {
        return view('community.show', compact('communityLink'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CommunityLink  $communityLink
     * @return \Illuminate\Http\Response
     */
    public function edit(CommunityLink $communityLink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CommunityLink  $communityLink
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommunityLink $communityLink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CommunityLink  $communityLink
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommunityLink $communityLink)
    {
        //
    }

    public function vote(CommunityLink $communityLink)
    {
        auth()->user()->VotedFor($communityLink);

        return back();
    }

    public function getByChannel(Channel $channel)
    {
        $links = $this->linksQuery->getByChannel($channel);
        return view('community.index', compact('links', 'channel'));
    }

    public function getMostPopular()
    {
        $links = $this->linksQuery->getMostPopular();
        return view('community.index', compact('links'));
    }
}
