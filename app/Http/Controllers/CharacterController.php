<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;
use App\Http\Requests\CharacterSortRequest;

class CharacterController extends Controller
{
    public function index(CharacterSortRequest $request)
    {
        $sortBy = $request->validated()['sort'];
        $characters = Character::with('user')->orderBy($sortBy, 'desc')->get();

        return view('characters', compact('characters'));
    }
}
