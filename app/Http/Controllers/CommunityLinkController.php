<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\CommunityLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CommunityLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $channels = Channel::orderBy('title', 'asc')->get();
        $links = CommunityLink::paginate(25);
        return view('community/index', compact('links', 'channels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*
        dd($request);
        $request->path();
        $request->url();
        $request->input();
        $request->fullUrl();
        */
        $this->validate($request, [
            'title' => 'required',
            'link' => 'required|active_url',
            'channel_id' => 'required|exists:channels,id',
            'link' => 'required|active_url|unique:community_links'

        ]);

        request()->merge(['user_id' => Auth::id()]);
        CommunityLink::create($request->all());
        return back();
        //return response('Respuesta', 200);
        //return response('Error', 404);



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CommunityLink  $communityLink
     * @return \Illuminate\Http\Response
     */
    public function show(CommunityLink $communityLink)
    {
        //
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
}
