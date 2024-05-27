<?php

namespace App\Http\Controllers\Anime;
use App\Models\Show\Show;
use App\Models\comment\Comment;
use App\Models\Following\Following;;
use App\Http\Controllers\Controller;
use App\Models\View\View;
use Illuminate\Http\Request;
use App\Models\Episode\Episode;
use Auth;
use Redirect;

class AnimeController extends Controller
{
    public function details($id){
        $show = Show::find($id);
        $randomShow = Show::select()->orderBy('id', 'desc')->take('5')->where('id', '!=', $id)->get();

        $comments = Comment::select()->orderBy('id', 'desc')->take('8')->where('show_id',$id)->get();

        if(isset(auth::user()->id)) {
            //validating following shows
        $validateFollowing = Following::where('user_id', Auth::user()->id)->where('show_id', $id)->count();
        

        }
        
        $numberComment = Comment::where('show_id', $id)->count();
        // getting number of views
        $numberViews = View::where('show_id', $id)->count();

        //getting new views
        if(isset(Auth::user()->id)) {

            //validating following views
            $validateView = View::where('user_id', Auth::user()->id)->where('show_id', $id)->count();

        
            if($validateView == 0){
            
                $views = View::create([
                    "show_id" => $id,
                    "user_id" => Auth::user()->id,
                ]);
    
            }
            return view('shows.anime-details', compact('show', 'randomShow', 'comments', 'validateFollowing', 'validateView','numberViews', 'numberComment'));
        

        } else {
            return view('shows.anime-details', compact('show', 'randomShow', 'comments','validateView','numberViews', 'numberComment'));
        
        }

        
        
        
        
    }
    

    public function insertcomment(Request $request, $id){
        
        $insertcomment = Comment::create([
            "show_id" => $id,
            "user_name" => Auth::user()->name,
            "image" => Auth::user()->image,
            "comment" => $request->comment
        ]);
        if($insertcomment) {
            return Redirect::route('details', $id)->with(['success' => 'Comment added successfully' ]);
            // return redirect()->route('details', $id)->wth(['success' => 'Comment added successfully' ]);
        }
        return view('shows.anime-details', compact('show', 'randomShow', 'comments'));
    }

    public function follow(Request $request, $id){
        
        $follow = Following::create([
            "show_id" => $id,
            "user_id" => Auth::user()->id,
            "show_image" => $request->show_image,
            "show_name" => $request->show_name,

        ]);
        if($follow) {
            return Redirect::route('details', $id)->with(['follow' => 'You followed this show successfully' ]);
            // return redirect()->route('details', $id)->wth(['success' => 'Comment added successfully' ]);
        }

    }

    public function watching(Request $request, $show_id, $episode_id){
        $show = Show::find($show_id);
        $episode = Episode::where('episode_name',$episode_id)->where('show_id', $show_id)->first();
        $episodes = Episode::select()->where('show_id', $show_id)->get();

        //comments
        $comments = Comment::select()->orderBy('id', 'desc')->take('8')->where('show_id',$show_id)->get();

        return view('shows.anime-watching', compact('show', 'episode', 'episodes', 'comments'));

    }

    public function category($category_name){

        $shows = Show::where('genre', $category_name)->get();

        $forYouShows = Show::select()->orderBy('name', 'asc')->take(4)->get();

        return view('shows.category', compact('shows', 'category_name','forYouShows'));

    }


    public function searchShows(Request $request){

        $show = $request->get('show');

        $searches = Show::where('name', 'like', "%$show%")->orWhere('genre', 'like', "%$show%")->get();

        return view('shows.searches', compact('searches'));

    }
    
}



