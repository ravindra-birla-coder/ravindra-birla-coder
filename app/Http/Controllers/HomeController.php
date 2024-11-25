<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //This is home page
    public function index()
    {
        
        $categories = Category::orderBy('name','asc')->where('status', 1)->take(8)->get();
        
        $featuredJobs = Job::where('status', 1)
                      ->orderBy('created_at','DESC')
                      ->with('JobType')
                      ->where('isFeatured', 1)->take(6)->get();

        $letestJobs = Job::where('status', 1)
                      ->with('JobType')
                      ->orderBy('created_at','DESC')
                      ->take(6)->get();

        return view( 'front.home', 
        
        [ 'categories'=> $categories, 'featuredJobs' => $featuredJobs, 'letestJobs' => $letestJobs ]  

       );

    }
}
