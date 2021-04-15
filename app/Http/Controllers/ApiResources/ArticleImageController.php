<?php

namespace App\Http\Controllers\ApiResources;

use App\Http\Controllers\Controller;
use App\Models\ArticleImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleImageController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'article_id' => 'required|integer',
            'file' => 'required|file|mimes:jpeg,jpg,png|max:1024',
        ]);

        $image = $request->file('file');
        $name = Str::slug($request->input('article_id')).'_'.time();
        $folder = '/uploads/api-images/';
        $filePath = $name. '.' . $image->getClientOriginalExtension();
        $image->storeAs($folder, $filePath);

        $articleImage = ArticleImage::create([
            'article_id' => $request->input('article_id'),
            'file' => $folder.$filePath
        ]);

        return response()->json($articleImage, 201);
    }
}
