<div class="alert 
@if(session('typeOperation') == "success") alert-success @endif 
@if(session('typeOperation') == "error") alert-danger @endif" >
    {{ session('resultOperation') }}
</div>