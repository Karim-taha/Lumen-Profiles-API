<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ArticleController extends Controller
{
    public function index()
    {
        $results = DB::select('select u.name, u.email, u.phone,u.created_at from users u where u.id=?',[auth()->user()->id]);
        $articles = DB::select('select a.title, a.content, a.created_at from articles a where a.user_id=?',[auth()->user()->id]);
        return response()->json(compact('results','articles'));
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|json',
            'content'  => 'required|json',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user = $request->all();
        $user['user_id'] = auth()->user()->id;
        $user = Article::create($user);
        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required|json',
            'content'  => 'required|json',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $article = Article::findorfail($id);
        $article->title = $request->title;
        $article->content = $request->content;
        $article->save();
        return response()->json($article, 200);
    }

    public function destroy($id)
    {
        $article = Article::findorfail($id);
        $article->delete();
        return response()->json(null, 204);
    }
}
