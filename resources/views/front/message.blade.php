
@if(Session::has('success'))
    <div class="alert alert-success" style='text-align:center'>
         <p class="mb-0 pb-0">{{ Session::get('success') }} </p>
    </div>
 @endif

@if(Session::has('error'))
    <div class="alert alert-danger" style='text-align:center'>
       <p class="mb-0 pb-0">{{ Session::get('error') }} </p>
   </div>
@endif