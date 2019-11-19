@extends('layouts.app')

@section('content')
<div class="card-body border">
    <h5><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp; {{$user->name}}</h5>
    <span class="small-grey-text"> Total Tweet {{$tweet_count}}</span>
    
    
</div>
<div class="user_profile_cap">

    <div class="user_profile_cover">
        <img src="http://1.bp.blogspot.com/_Ym3du2sG3R4/S_-M_kTV9OI/AAAAAAAACZA/SNCea2qKOWQ/s1600/mac+os+x+wallpaper.jpg" alt="img"/>
    </div>

    <div class="user_profile_headline">
        <img src="http://1.bp.blogspot.com/_Ym3du2sG3R4/S_-M_kTV9OI/AAAAAAAACZA/SNCea2qKOWQ/s1600/mac+os+x+wallpaper.jpg" alt="img"/> 
        
        <h2>{{$user->name}}</h2><button class="btn btn-outline-success float-right">Update</button>
        <span><i class="fa fa-envelope" aria-hidden="true"></i> {{$user->email}}</span><span>
        <br><i class="fa fa-calendar" aria-hidden="true"></i> Joined {{$user->created_at->format('M d, Y')}}</span>
        
    </div>
  
</div>

<div id="append_container">
    @foreach ($tweets as $tweet)
        <div class="card">
            <div class="card-header post-header"><i class="far fa-user-circle"></i>&nbsp;
                <span class="strong">{{$tweet->user->name}} </span><span class="float-right">{{$tweet->created_at->format('d M, H:i')}}</span>
            </div>
            <div class="card-body">
                {{$tweet->tweet}} <br><br>
                <button type="button"  class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#commentModal" data-postid="{{$tweet->id}}">Comment</button>
            </div>
            <div class="card-footer comments_div">
                <h5>Comments:</h5>
                <div id="tweet_comment_div{{$tweet->id}}">
                @foreach ($tweet->comment as $item)
                    <div class="card-body border"><i class="far fa-user-circle"></i> <strong>{{$item->user->name}} : </strong> 
                        {{$item->comment}}
                        <span class="float-right small-text">{{$item->created_at->format('d M, H:i')}}</span>
                    </div>
                @endforeach
                </div>    
            </div>
        </div>
        <br>
    @endforeach
    
</div>



{{-- Modal Start --}}
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="commentModalLabel">Comment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" class="form-control" id="hidden_post_id">
            <div class="form-group">
                <textarea class="form-control textarea-autosize" id="textarea_comment" rows="1" placeholder="What do you say.."></textarea><br>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="comment_submit" class="btn btn-primary">Comment</button>
        </div>
        </div>
    </div>
</div>
{{-- Modal end --}}


@endsection


@push('script')
<script>
    var tweet_url = {!! json_encode(route('post.tweet')) !!};
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        autosize($('textarea'));

        // -----------------------------Set value on show modal start--------------------------------------
        $('#commentModal').on('show.bs.modal', function (event) {
            $('#textarea_comment').val('');
            var button = $(event.relatedTarget) // Button that triggered the modal
            var postid = button.data('postid') // Extract info from data-* attributes
            $('#hidden_post_id').val(postid);
            
        });

        //-------------------------------------------- submit comment -------------------------------------------
        $('#comment_submit').on('click', function (event) {
            event.preventDefault();
            
            var comment = $('#textarea_comment').val();
            var tweet_id = $('#hidden_post_id').val();
            
            $.ajax({
            type:'POST',
            url:{!! json_encode(route('post.comment')) !!},
            data:{comment:comment,tweet_id:tweet_id},
            dataType: 'json',
            success:function(data){
                if (data.success) {
                    $('#commentModal').modal('hide');
                    var str = '<div class="card-body border"><i class="far fa-user-circle"></i> <strong>'+ data.name +' : </strong>' +
                                    data.comment.comment +
                                    '<span class="float-right small-text">'+ data.posted_at +'</span>' +
                                '</div>';
                    $('#tweet_comment_div'+tweet_id).prepend(str);
                    
                }
            }

            });
            
        });

        // ------------------------------------ load more via ajax --------------------------------------------------------
      
        // ---------------------------------------------------end --------------------------------------------------------
        

    });

    
   
</script>
@endpush