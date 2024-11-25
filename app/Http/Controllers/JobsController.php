<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;

use Illuminate\Http\Request;

class JobsController extends Controller
{
    //This method will shows jobs page
    public function index(Request $request ) 
    {
      $Categories = Category::where('status', 1)->get();
      $JobTypes   = JobType::where('status', 1)->get();
      $Jobs       = Job::where('status', 1);

     
      //Searching use of job keyword
      if(!empty($request->keywords ))
      {
         $Jobs = $Jobs->where(function($query) use($request) 
         {
            $query->orWhere('title','like', '%'.$request->keywords.'%');
            $query->orWhere('keywords','like', '%'.$request->keywords.'%');
         });
      }
      


      //Searching use of job location
      if(!empty($request->location ))
      {
           $Jobs = $Jobs->where('location',$request->location);
      }



      //Searching use of job category
      if(!empty($request->category ))
      {
           $Jobs = $Jobs->where('category_id',$request->category);
      }
            


      //Searching use of job types
      $jobTypeArray = [];
      if(!empty($request->jobType ))
      { 
           $jobTypeArray = explode(',',$request->jobType);

           $Jobs = $Jobs->whereIn('job_type_id',$jobTypeArray);
      }



      //Searching use of job experience
      if(!empty($request->experience ))
      {
           $Jobs = $Jobs->where('experience',$request->experience);
      }

      
      $Jobs = $Jobs->with('jobType','category')->orderBy('created_at', 'DESC')->paginate(9);

      return view('front.jobs', 
      [ 
          'Categories'=>$Categories, 
          'JobTypes'=>$JobTypes, 
          'Jobs'=>$Jobs, 
          'jobTypeArray'=>$jobTypeArray 
      ]);

    
    }
}
