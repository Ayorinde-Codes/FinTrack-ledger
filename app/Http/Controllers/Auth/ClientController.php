<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\UtilHelper;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientKey;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'required|string|max:15',
            'website' => 'nullable|url',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'industry' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $client = Client::create($validatedData);

        // Generate API keys
        $keys = UtilHelper::generateApiKey();
        ClientKey::create([
            'client_id' => $client->id,
            'public_key' => $keys['public_key'],
            'private_key' => $keys['private_key'],
        ]);

        return response()->json(['client' => $client, 'keys' => $keys], 201);
    }
}
