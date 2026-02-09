<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CollectionController extends Controller
{
    public function __invoke(string $collectionUrl)
    {
        $creator = Creator::where('collection_url', $collectionUrl)
            ->with('user')
            ->firstOrFail();

        return view('collection.show', compact('creator'));
    }

    public function submit(Request $request, string $collectionUrl)
    {
        $creator = Creator::where('collection_url', $collectionUrl)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'author_name' => ['required', 'string', 'max:255'],
            'author_email' => ['nullable', 'email'],
            'author_title' => ['nullable', 'string', 'max:255'],
            'author_avatar_url' => ['nullable', 'string', 'max:2048', function ($attribute, $value, $fail) {
                if (empty($value)) {
                    return; // Allow null/empty
                }
                // Only allow https:// URLs, reject javascript:, data:, and other dangerous schemes
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    $fail('The '.$attribute.' must be a valid URL.');
                }
                $allowedSchemes = ['https'];
                $scheme = strtolower(parse_url($value, PHP_URL_SCHEME));
                if (!in_array($scheme, $allowedSchemes, true)) {
                    $fail('The '.$attribute.' must use the https:// scheme.');
                }
            }],
            'content' => ['required', 'string', 'min:10', 'max:2000'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $creator->testimonials()->create([
            'author_name' => $request->author_name,
            'author_email' => $request->author_email,
            'author_title' => $request->author_title,
            'author_avatar_url' => $request->author_avatar_url,
            'content' => $request->content,
            'rating' => $request->rating ?? 5,
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Thank you for your testimonial! It will be reviewed shortly.');
    }
}
