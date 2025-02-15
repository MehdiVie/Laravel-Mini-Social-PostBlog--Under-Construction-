<?php

namespace App\Http\Controllers;

use App\Events\ProfileUpdated;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // Example method for updating the profile
    public function update(Request $request)
    {
        // Perform profile update logic here

        // For example, after updating the profile data:
        $data = [
            'theHTML' => '<p>Updated profile content</p>', // Updated HTML content for the profile
            'docTitle' => 'Profile Updated' // Title of the page
        ];

        // Trigger the event
        event(new ProfileUpdated($data));

        // Return response (you can customize this as needed)
        return response()->json(['message' => 'Profile updated successfully']);
    }
}
