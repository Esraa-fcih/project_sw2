<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Article;
use DB;
use  Illuminate\Support\Facades\Input;
use App\User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request ,$id)
    {
       $articles= Article::where('user_id','=',$id)->get();
        $ar=Array('articles'=>$articles);
        return view('userProfile',$ar);
    }
    
       
      public  function  delete(Request $request ,$id){
       

        $article=Article::find($id);
        $article->delete();
        return redirect("view" );
    }
    
    public  function  edit(Request $request ,$id){
        
$ar= Article::find($id);
        if ($request->isMethod('post')){
            
            $ar->title=$request->input('title');
            $ar->body=$request->input('body');
            $ar->user_id=Auth::user()->id;
            $ar->save();
            return redirect('view');
        }
        else{
        $arr=array('Article'=>$ar);
        return view('manage.edit',$arr);}
    }



public function search(){
    $q = Input::get ( 'q' );
    $articles = article::where('title','LIKE','%'.$q.'%')->orWhere('body','LIKE','%'.$q.'%')->get();
    if(count($articles) > 0)
        return view('manage.search')->withDetails($articles)->withQuery ( $q );
    
    else 
        return view ('manage.search')->withMessage('No Details found. Try to search again !');

}
public function categoryy($name)
{
  $cat= DB::table('categories')->where('name', $name)->value('id');
  $articles=DB::table('articles')->where('category_id', $cat)->get();
 return view('manage.category', compact('articles'));
}


/*public function deletee($id)
{
    DB::table('articles')->where('id',$id)->delete();
    return redirect ('admin');
}*/
}
