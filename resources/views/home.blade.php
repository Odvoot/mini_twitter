@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Home</h5>
    </div>
</div>
<div class="card">
    <div class="card-header">Dashboard 2</div>
    <div class="card-body">
        You are logged in!
    </div>
</div>
@for ($i = 1 ; $i < 20 ; $i++)
<div class="card">
    <div class="card-header">post {{$i}}</div>
    <div class="card-body">
        This is post
    </div>
</div>
    
@endfor

<script>
    //alert('ok');
</script>
@endsection
