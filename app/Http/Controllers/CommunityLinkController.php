<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\CommunityLink;
use App\Models\User;
use App\Http\Requests\CommunityLinkForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function index(Request $request, Channel $channel = null)
    {
        $title = null;
        $linksQuery = new CommunityLinksQuery();
        $channels = Channel::orderBy('title', 'asc')->get();

        $links = $this->linksQuery->get();
        $trimmedQuery = trim($request->input('q'));
        $linksQuery = CommunityLink::query()
            ->where('approved', 1)
            ->where('spam', 0)
            ->when($trimmedQuery, function ($query, $trimmedQuery) {
                return $query->where('title', 'LIKE', '%' . $trimmedQuery . '%');
            })
            ->with('channel', 'creator')
            ->orderBy('updated_at', 'desc');
        if ($channel) {
            $links = $linksQuery->getByChannel($channel);
            $title = '- ' . $channel->title;
        } else if ($request->has('popular')) {
            $links = $linksQuery->paginate(10);
        } else {
            $links = $linksQuery->paginate(10);
        }
        return view('community.index', compact('links', 'channels', 'title'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($attributes)
    {
        $channels = Channel::orderBy('title', 'asc')->get();
        return view('community/create', compact('channels'))->with('flash', 'Tu enlace ha sido guardado!');
        // return redirect('/community')->with('flash', 'Tu enlace ha sido guardado!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'channel_id' => 'required|exists:channels,id',
            'title' => 'required',
            'link' => 'required|url',
        ]);

        $link = new CommunityLink();
        $link->user_id = Auth::id();
        if ($link->hasAlreadyBeenSubmitted($request->link)) {
            return back()->with('error', 'This link has already been submitted');
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

    public function vote(User $user, CommunityLink $communityLink)
    {
        if (!auth()->check()) {
            return back()->with('error', 'Debes iniciar sesión para votar.');
        }
        if ($user->hasVotedFor($communityLink)) {
            $user->removeVote($communityLink);
        } else {
            try {
                $user->voteFor($communityLink);
            } catch (\Exception $e) {
                return back()->with('error', 'Hubo un problema con tu voto. Por favor intenta de nuevo más tarde.');
            }
        }
        $communityLink->refresh();
        return back();
    }

    public function getByChannel(Channel $channel)
    {
        $links = $this->linksQuery->getByChannel($channel);
        return view('community.index', compact('links', 'channel'));
    }

    public function getMostPopular()
    {
        $links = CommunityLink::withCount('user')
            ->orderBy('votes_count', 'desc')
            ->paginate(25);
        return view('community.index', compact('links'));
    }
}
