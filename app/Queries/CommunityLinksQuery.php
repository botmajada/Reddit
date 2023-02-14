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
            ->paginate(5);
    }

    public function getAll()
    {
        return CommunityLink::with('channel')
            ->orderByDesc('updated_at')
            ->paginate(5);
    }

    public function getMostPopular()
    {
        return CommunityLink::with('channel')
            ->withCount('votes')
            ->orderByDesc('votes_count')
            ->paginate(5);
    }
}