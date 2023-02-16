<?php

namespace App\Queries;

use App\Models\CommunityLink;

class CommunityLinksQuery
{
    public function getByChannel($channel)
    {
        return CommunityLink::with('channel')
            ->where('channel_id', $channel->id)
            ->orderByDesc('updated_at')
            ->paginate(20);
    }

    public function getAll()
    {
        return CommunityLink::with('channel')
            ->orderByDesc('updated_at')
            ->paginate(20);
    }

    public function getMostPopular()
    {
        return CommunityLink::with('channel')
        ->withCount('user')
        ->orderByDesc('votes_count')
        ->where('approved', 1)
        ->where('spam', 0)
        ->paginate(20);
    }

    public function get()
    {
        return CommunityLink::with('channel')
            ->orderByDesc('updated_at')
            ->paginate(20);
    }

    public function appends($request)
    {
        return $this->get()->appends($request);
    }

}