<?php

namespace App\Http\Controllers\Admins;
use App\Models\Admin\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Show\Show;
use App\Models\comment\Comment;
use App\Models\Category\Category;
use App\Models\View\View;
use App\Models\Episode\Episode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Redirect;
use File;
class AdminController extends Controller
{
    public function viewLogin(){

        return view('admin.login');
        
    }

    public function checkLogin(Request $request){

        $remember_me = $request->has('remember_me') ? true : false;

        if (auth()->guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input("password")], $remember_me)) {
            
            return redirect() -> route('admin.dashboard');
        }
        return redirect()->back()->with(['error' => 'error logging in']);
        
    }

    public function index(){
        $shows = Show::select()->count();
        $episodes = Episode::select()->count();
        $admins = Admin::select()->count();
        $categories = Category::select()->count();

        return view('admin.index', compact('shows', 'episodes', 'admins', 'categories'));
        
    }

    public function  allAdmins(){
        $allAdmins = Admin::select()->orderBy('id', 'desc')->get();
        
        return view('admin.all', compact('allAdmins'));
        
    }

    public function  createAdmins(){
        
        
        return view('admin.create');
        
    }

    public function storeAdmins(Request $request){
        $storeAdmins = Admin::create([
            "email" => $request->email,
            "name" => $request->name,
            'password' => Hash::make($request->password),
        ]);
        
        if ($storeAdmins) {
            return Redirect::route('admin.all')->with(['success' => "Admin Created Successfully"]);
        }
        // return view('admin.create');
        
    }

    public function allshows(Request $request){
        $allShows = Show::select()->orderBy('id', 'desc')->get();
        
        return view('admin.allshows', compact('allShows'));
        
    }
    
    public function createShows(){
        $categories = Category::all();
        
        
        return view('admin.createshow', compact('categories'));
        
    }
    
    
    public function storeShows(Request $request){
        Request()->validate([
            "name" => "required",
            "image" => "required|max:600",
            "description" => "required",
            "date_aired" => "required|max:40",
            "type" => "required",
            "studios" => "required",
            "status" => "required",
            "genre" => "required",
            "duration" => "required|max:40",
            "quality" => "required|max:40",
        ]);
        $destinationPath = 'assets/img/';
        $myimage = $request->image->getClientOriginalName();
        $request->image->move(public_path($destinationPath), $myimage);
        $storeShows = Show::create([
            "name" => $request->name,
            "image" => $myimage,
            "description" => $request->description,
            "date_aired" => $request->date_aired,
            "type" => $request->type,
            "studios" => $request->studios,
            "status" => $request->status,
            "genre" => $request->genre,
            "duration" => $request->duration,
            "quality" => $request->quality,
        ]);
        
        if ($storeShows) {
            return Redirect::route('shows.all')->with(['success' => "Show Created Successfully"]);
        }
        // return view('admin.create');
        
    }

    public function deleteShows($id){
        $show = Show::find($id);
        if(File::exists(public_path('assets/img/' . $show->image))){
            File::delete(public_path('assets/img/' . $show->image));
        }else{
            //dd('File does not exists.');
        }
        $show->delete();
        
        if ($show) {
            return Redirect::route('shows.all')->with(['delete' => "Show Deleted Successfully"]);
        }
        
        // return Redirect::route('shows.all')->with(['delete' => "Show Deleted Successfully"]);

    }

    public function allGenres(){
        $allGenres = Category::all();
        
        
        return view('admin.allgenres', compact('allGenres'));
        
    }
    
    

    public function deleteGenres($id){
        $genre = Category::find($id);
        $genre->delete();
        
        if ($genre) {
            return Redirect::route('genres.all')->with(['delete' => "Genre Deleted Successfully"]);
        }
    }

    public function createGenres(){
        
        
        
        return view('admin.creategenres');
        
    }

    public function storeGenres(Request $request){
        Request()->validate([
            "name" => "required|max:40",
            
        ]);
        
        $storeGenres = Category::create([
            "name" => $request->name,
           
        ]);
        
        if ($storeGenres) {
            return Redirect::route('genres.all')->with(['success' => "Genre Created Successfully"]);
        }
        // return view('admin.create');
        
    }
    public function allEpisodes(){
        $allEpisodes = Episode::all();
        
        
        return view('admin.allepisodes', compact('allEpisodes'));
        
    }

    public function createEpisodes(){
        $shows = Show::all();
        
        
        return view('admin.createEpisodes', compact('shows'));
        
    }
    
    public function storeEpisodes(Request $request){
        Request()->validate([
            "name" => "required",
            "image" => "required|max:600",
            "video" => "required",
            "show_id" => "required|max:40",
        ]);
        $destinationPath = 'assets/thumbnails/';
        $myimage = $request->image->getClientOriginalName();
        $request->image->move(public_path($destinationPath), $myimage);

        $destinationPath = 'assets/videos/';
        $myvideo = $request->video->getClientOriginalName();
        $request->video->move(public_path($destinationPathVideo), $myvideo);

        $storeEpisodes = Episode::create([
            "episode_name" => $request->name,
            "thumbnail" => $myimage,
            "video" => $myvideo,
            "show_id" => $request->show_id,
            
        ]);
        
        if ($storeEpisodes) {
            return Redirect::route('episodes.all')->with(['success' => "Episode Created Successfully"]);
        }
        // return view('admin.create');
        
    }

    public function deleteEpisodes($id){
        $episode = Episode::find($id);
        if(File::exists(public_path('assets/videos/' . $episode->video))){
            File::delete(public_path('assets/videos/' . $episode->video));
        }else{
            //dd('File does not exists.');
        }
        $episode->delete();
        
        if ($episode) {
            return Redirect::route('episodes.all')->with(['delete' => "Episode Deleted Successfully"]);
        }
        
        // return Redirect::route('shows.all')->with(['delete' => "Show Deleted Successfully"]);

    }
    
}
