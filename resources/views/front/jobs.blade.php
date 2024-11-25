@extends('front.layouts.app')
@section('main')
<section class="section-3 py-5 bg-2 ">
    <div class="container">     
        <div class="row">
            <div class="col-6 col-md-10 ">
                <h2>Find Jobs</h2>  
            </div>
            <div class="col-6 col-md-2">
                <div class="align-end">
                    <select name="sort" id="sort" class="form-control">
                        <option value="">Latest</option>
                        <option value="">Oldest</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row pt-5">
          <div class="col-md-4 col-lg-3 sidebar mb-4">
            <form action="" name="searchForm" id="searchForm" >
                <div class="card border-0 shadow p-4">
                <div class="mb-4">
                <h2>Keywords</h2>
                <input value ="{{ Request::get('keywords') }}" type="text" placeholder="keywords" name="keywords" id="keyword" class="form-control">
                </div>

                <div class="mb-4">
                <h2>Location</h2>
                <input value ="{{ Request::get('location') }}" type="text" placeholder="location" name="location" id="location" class="form-control">
                </div>

                <div class="mb-4">
                <h2>Category</h2>
                <select name="category" id="category" class="form-control">
                 <option value ="">Select a Category</option>
                 @if($Categories )
                    @foreach($Categories as $category)
                      <option  {{ ( Request::get('category') == $category->id ) ? 'selected' : '' }}  value="{{ $category->id }}" 
                       >{{ $category->name }}</option>
                    @endforeach
                 @endif
                 </select>
                 </div> 

                <div class="mb-4">
                <h2>Job Type</h2>
                 @if($JobTypes->isNotEmpty() )
                   @foreach($JobTypes as $jobType)
                    <div class="form-check mb-2"> 
                      <input {{ in_array($jobType->id, $jobTypeArray)  ? 'checked' : '' }} type="checkbox" name="job_type" id="job_type" value="{{ $jobType->id }}" class="form-check-input" />
                      <label class="form-check-label" for="job_type-{{ $jobType->id }}">{{ $jobType->name }}</label>
                    </div>
                   @endforeach
                 @endif
                </div>


                <div class="mb-4">
                <h2>Experience</h2>
                <select name="experience" id="experience" class="form-control">
                <option value="">Select Experience</option>
                <option value="1"  {{ (Request::get('experience') ==  1  ) ? 'selected' : '' }} >1   Year </option>
                <option value="2"  {{ (Request::get('experience') ==  2  ) ? 'selected' : '' }} >2   Years</option>
                <option value="3"  {{ (Request::get('experience') ==  3  ) ? 'selected' : '' }} >3   Years</option>
                <option value="4"  {{ (Request::get('experience') ==  4  ) ? 'selected' : '' }} >4   Years</option>
                <option value="5"  {{ (Request::get('experience') ==  5  ) ? 'selected' : '' }} >5   Years</option>
                <option value="6"  {{ (Request::get('experience') ==  6  ) ? 'selected' : '' }} >6   Years</option>
                <option value="7"  {{ (Request::get('experience') ==  7  ) ? 'selected' : '' }} >7   Years</option>
                <option value="8"  {{ (Request::get('experience') ==  8  ) ? 'selected' : '' }} >8   Years</option>
                <option value="9"  {{ (Request::get('experience') ==  9  ) ? 'selected' : '' }} >9   Years</option>
                <option value="10" {{ (Request::get('experience') ==  10 ) ? 'selected' : '' }} >10  Years</option>
                <option value="11" {{ (Request::get('experience') ==  11 ) ? 'selected' : '' }} >10+ Years</option>
                </select>
             </div> 
             
              <button type="submit" class="btn btn-primary">Search </button>
            </div>
         </form>
     </div>

    <div class="col-md-8 col-lg-9 ">
      <div class="job_listing_area">                    
          <div class="job_lists">
               <div class="row">
                     @if($Jobs->isNotEmpty() )
                        @foreach($Jobs as $Job )
                        <div class="col-md-4">
                            <div class="card border-0 p-3 shadow mb-4">
                                <div class="card-body">
                                    <h3 class="border-0 fs-5 pb-2 mb-0">{{ $Job->title }}</h3>
                                    <p>{{ Str::words($Job->description, 8, '...' ) }}</p>
                                    <div class="bg-light p-3 border">
                                        <p class="mb-0">
                                            <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                            <span class="ps-1">{{ $Job->location }}</span>
                                        </p>
                                        <p class="mb-0">
                                            <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                            <span class="ps-1">{{ $Job->jobType->name }}</span>
                                        </p>

                                        <p>{{ $Job->keywords }}</p>  
                                        <p>{{ $Job->category_id }}</p> 
                                        <p>{{ $Job->experience }}</p>  

                                        @if (!is_null($Job->salary))
                                        <p class="mb-0">
                                            <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                            <span class="ps-1">{{ $Job->salary }}</span>
                                        </p>
                                        @endif
                                    </div>

                                    <div class="d-grid mt-3">
                                        <a href="#" class="btn btn-primary btn-lg">Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
    
                        @else
                        <div class="col-md-12"><h5>Records not found</h5> </div>
                        @endif
                  </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('costomJs');
<script>
$('#searchForm').submit( function(e) )
{
     e.priventDefault();

     var url = '{{ route("jobs") }}?';

     var keywords    =  $("#keywords").val();
     var location    =  $("#location").val();
     var category    =  $("#category").val();
     var experience  =  $("#experience").val();
    
     //Experience Filter Code
     var checkedJobtypes =  $("input[name='job_type']:checked").map(function(){   
        return $(this).val(); 
      }).get();

     
     if(checkedJobtypes.length > 0)
     {  
         url +='&jobType='+checkedJobtypes;
     }

     //keyword Filter Code
     if(keywords !="")
     {  
         url +='&keywords='+keywords;
     }

     //Location Filter Code
     if(location !="")
     {  
         url +='&location='+location;
     }

     //Category Filter Code
     if(category !="")
     {  
         url +='&category='+category;
     }
    
    //Experience Filter Code
     if(experience !="")
     {  
         url +='&experience='+experience;
     }
     
}
</script>
@endsection