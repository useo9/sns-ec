<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TranslationController extends Controller
{
    public function translate(Request $request): JsonResponse
    {
        $text = $request->validate(['text' => 'required|string|max:500'])['text'];

        $response = Http::get('https://api.mymemory.translated.net/get', [
            'q'        => $text,
            'langpair' => 'auto|ja',
        ]);

        if (! $response->ok()) {
            return response()->json(['error' => '翻訳に失敗しました'], 500);
        }

        $data = $response->json();

        if (($data['responseStatus'] ?? 0) !== 200) {
            return response()->json(['error' => '翻訳に失敗しました'], 500);
        }

        return response()->json([
            'translated' => $data['responseData']['translatedText'],
        ]);
    }
}
