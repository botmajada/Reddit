<?php

namespace App\Http\Controllers;

use App\Models\CommunityLink;
use App\Models\CommunityLinkUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityLinkUserController extends Controller
{

  // Función que se ejecuta cuando se hace click en el botón de voto
  public function store(CommunityLink $link)
  {

    // Si el usuario no está autenticado, redirige a la página de login
    $vote = CommunityLinkUser::firstOrNew([ // Busca el voto
      'user_id' => Auth::id(), // Busca el voto por el id del usuario
      'community_link_id' => $link->id]); // Busca el voto por el id del usuario y el id del link

    // Si el usuario ya ha votado por el link, lo borra
    $this->toggle($vote); // Llama a la función toggle
    return back(); // Redirige a la página anterior
  }


  public function toggle($vote)
    {
      if ($vote->id) { // Si existe el voto, lo borra
        $vote->delete(); // Borra el voto
      } else { // Si no existe, lo crea
        $vote->save(); // Guarda el voto
      }
      return back(); // Redirige a la página anterior
    }
}

