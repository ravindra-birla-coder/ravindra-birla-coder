<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    //This is registration page show
    public function registraion()
    {
        return view('front.account.registraion');
    }

    //This is registration process
    public function registraionProcess(Request $request)
    {
       $rules = 
       [
              'name'             => 'required' ,
              'email'            => 'required|email|unique:users,email' ,
              'password'         => 'required|min:5|same:confirm_password' ,
              'confirm_password' => 'required' 
       ];
       
       $validator = Validator::make($request->all(),$rules);
       if($validator->passes()) 
       { 
            $user = new User();
            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->password = hash::make($request->password);

            $user->save();
            Session()->flash('success','Record saved successfully!.');
            return response()->json([ 'status' => true ]); 
        }else
        {
            return response()->json([
            'status' => false ,
            'errors' =>  $validator->errors()
          ]); 
        }
    }

    //This is login page show
    public function login()
    {
        return view('front.account.login');
    }

    //This is login authenticate process
    public function authenticate(Request $request)
    {
        $validator = Validator::make( $request->all(),
        [ 
          'email'    => 'required|email', 
          'password' => 'required' 
        ] );

        if($validator->passes())
        {
           if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password ]))
           {
                return redirect()->route('account.profile');
           } else {
                return redirect()->route('account.login')->with('error', 'Invalid Email & Password');
           }
           }else
           {
              return redirect()->route('account.login')->withErrors($validator)->withInputs($request->only('email'));
           }
    }

    //This is profile page show
    public function profile()
    {
      $id = Auth::user()->id ;
      $user = User::find($id);
      return view('front.account.profile',
      [
          'user'=> $user
      ]);

    }

    //This is profile update proccess
    public function updateProfile(Request $request )
    {
      $id = Auth::user()->id ;
      $validator = Validator::make($request->all(),
      [
         'name'  => 'required|min:5|max:20' ,
         'email' => 'required|email|unique:users,email,'.$id.',id'  
      ]);

      if($validator->passes()) 
      { 
          $user = User::find($id);
          $user->name        = $request->name;
          $user->email       = $request->email;
          $user->designation = $request->designation;
          $user->mobile      = $request->mobile;

          $user->save();
          Session()->flash('success','Record updated successfully!');
          return response()->json([ 'status' => true  ]); 

      }else
      {
          return response()->json([
          'status' => false ,
          'errors' =>  $validator->errors()

        ]); 
      }

    }

    //This is profile update proccess
    public function updateProfilePic(Request $request )
    { 
      $validator = Validator::make($request->all(),
      [
         'image'=>  'required|image ' , 
      ]);
    
      $id = Auth::user()->id ;

      if($validator->passes()) 
      { 
          $image    = $request->image;
          $ext      = $image->getClientOriginalExtension();
          $fileName = $id."-".time().".".$ext;
          $image->move(public_path('profile_pic/'), $fileName);
          //File::delete(public_path('profile_pic/'.Auth::user()->image));
          User::where('id', $id)->UPDATE(['image' => $fileName]);
          
          Session()->flash('success','Picture updated successfully!');
          return response()->json([ 'status' => true ]);

      }
      else
      {
          return response()->json([
          'status' => false ,
          'errors' =>  $validator->errors()
        ]); 
      }
    }

    //This is create jobs
    public function createJob()
    {
       $categories = Category::orderBy('name','ASC')->where('status',1)->get();
       $Job_Type   = JobType::orderBy('name','ASC')->where('status',1)->get();

       return view('front.account.job.create',
       [
           'categories' =>  $categories,
           'jobtype'    =>  $Job_Type 
       ]); 
    }

    //This is save jobs
    public function saveJob(Request $request  )
    {
      $rules =
      [ 
         'title'=> 'required|min:3|max:200'
      ];

      $validator = Validator::make($request->all(), $rules);
      if($validator->passes()) 
      { 
       
        $job = new Job();
        $job->title = $request->title;
        $job->category_id  = $request->category;
        $job->job_type_id  = $request->Job_Type;
        $job->user_id  = Auth::user()->id;
        $job->vacancy = $request->vacancy;
        $job->salary = $request->salary;
        $job->location = $request->location;
        $job->description = $request->description;
        $job->benefits = $request->benefits;
        $job->responsibility = $request->responsibility;
        $job->qualifications = $request->qualifications;
        $job->keywords = $request->keywords;
        $job->experience = $request->experience;
        $job->company_name = $request->company_name;
        $job->company_location = $request->company_location;
        $job->company_website = $request->website;

        $job->save();
        Session()->flash('success','Record save successfully!');
        return response()->json([ 'status' => true   ]); 
      }else
      {
          return response()->json(
          [
            'status' => false ,
            'errors' =>  $validator->errors()
          ]); 

      }
    }
  
    //This is view jobs
    public function myJobs()
    {
      $jobs = Job::where('user_id', Auth::user()->id)->with('jobType')->orderBy('created_at', 'DESC')->paginate(10);
      return view('front.account.job.my-jobs', 
      [ 
         'jobs' => $jobs 
      ]);
    }

    //This is edit job
    public function editJob(Request $request, $id )
    {
      
      $category  = Category::orderBy('name','ASC')->where('status', 1)->get();
      $job_types = JobType::orderBy('name','ASC')->where('status', 1)->get();
      $job = Job::where(['user_id' => Auth::user()->id,'id' => $id ])->first();
      if($job == null)
      {
         abort(404);
      }
      return view('front.account.job.edit',
      [
         'categories' => $category,
         'job_types'  => $job_types,
         'job'        => $job
      ] 

      );
    }

    //This is update job 
    public function updateJob(Request $request, $id )
    {
      $rules =
      [ 
        'title'=> 'required|min:3|max:200'
      ];
   
      $validator = Validator::make($request->all(), $rules);
      if($validator->passes()) 
      { 
       
        $job = Job::find($id);
        $job->title            =  $request->title;
        $job->category_id      =  $request->category;
        $job->job_type_id      =  $request->Job_Type;
        $job->user_id          =  Auth::user()->id;
        $job->vacancy          =  $request->vacancy;
        $job->salary           =  $request->salary;
        $job->location         =  $request->location;
        $job->description      =  $request->description;
        $job->benefits         =  $request->benefits;
        $job->responsibility   =  $request->responsibility;
        $job->qualifications   =  $request->qualifications;
        $job->keywords         =  $request->keywords;
        $job->experience       =  $request->experience;
        $job->company_name     =  $request->company_name;
        $job->company_location =  $request->company_location;
        $job->company_website  =  $request->website;

        $job->save();
        Session()->flash('success','Data Updated Successfully.');
        return response()->json([ 'status' => true   ]); 
      }else
      {
          return response()->json(
          [
            'status' => false ,
            'errors' =>  $validator->errors()
          ]); 
      }
  }

  //This is Record delete
  public function deleteJob (Request $request )
  {
    $job = Job::where(['user_id' => Auth::user()->id,'id' => $request->jobId ])->first();
    if($job == null)
    { 
       Session()->flash('error','Id not found');
       return response()->json([ 'status' => true ]);
    }
    Job::where('id', $request->jobId)->delete();
    Session()->flash('success','Record deleted successfully!');
    return response()->json([ 'status' => true ]);

  }

  //This is logout process
  public function logout ()
  {
      Auth::logout();
      return redirect()->route('account.login');
  }

}
