<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return ArticleResource::collection($articles->loadMissing(['author:id,name', 'comments:id,article_id,user_id,comments_content']));
    }

    public function show($id)
    {
        $article = Article::with('author:id,name')->findOrFail($id);
        return new ArticleResource($article->loadMissing(['author:id,name', 'comments:id,article_id,user_id,comments_content']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'articles_content' => 'required',
        ]);

        $image = null;
        if ($request->file) {
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $image = $fileName.'.'.$extension;

            $path = $request->file->storeAs('public/image', $image);
        }

        $request['image'] = $image;
        $request['author_id'] = Auth::user()->id;
        $article = Article::create($request->all());
        return new ArticleResource($article->loadMissing('author:id,name'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'articles_content' => 'required',
        ]);

        $article = Article::findOrFail($id);

        if ($request->file) {
            if ($article->image && \Storage::exists('public/image/' . $article->image)) {
                \Storage::delete('public/image/' . $article->image);
            }

            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $newImage = $fileName . '.' . $extension;

            $path = $request->file->storeAs('public/image', $newImage);

            $request['image'] = $newImage;
        }

        $article->update($request->all());

        return new ArticleResource($article->loadMissing('author:id,name'));
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return new ArticleResource($article->loadMissing('author:id,name'));
    }

    function generateRandomString($length = 30) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
    
        return $randomString;
    }
}
