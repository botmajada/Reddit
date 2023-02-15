<?php

namespace App\Http\Controllers;

use App\Models\CommunityLink;
use App\Models\CommunityLinkUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityLinkUserController extends Controller
{


  public function store(CommunityLink $link)
  {
    $vote = CommunityLinkUser::firstOrNew([
      'user_id' => Auth::id(),
      'community_link_id' => $link->id
    ]);
    $this->toggle($vote);
    return back();
  }

  public function toggle($vote)
  {
    if ($vote->id) {
      $vote->delete();
    } else {
      $vote->save();
    }
    return back();
  }
  public function upvote(CommunityLink $link)
  {
    $link->votes()->attach(auth()->user());
    $link->increment('votes_count');
    return back();
  }

  public function downvote(CommunityLink $link)
  {
    $link->votes()->detach(auth()->user());
    $link->decrement('votes_count');
    return back();
  }
}
