@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Home</h5>
        
        
    </div>
    
</div>
@if (auth()->check())
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-1">
                    <div class="thumb_image">
                    <img  src="{{$user->photo ? url($user->photo) : url('img/default.png')}}" alt="i"/>
                    </div>
                </div>
                <div class="col-md-11">
                    <textarea class="form-control textarea-autosize" id="textarea_tweet" rows="1" placeholder="Share your Thoughts.."></textarea><br>
                    <button class="btn btn-success float-right" id="tweet_submit">Share</button>
                </div>

            </div>
            

        </div>
    </div>
@endif

<div id="append_container">
    @foreach ($tweets as $tweet)
        <div class="card">
            <div class="card-header post-header"><i class="far fa-user-circle"></i>&nbsp;
                <span class="strong">{{$tweet->user->name}} </span>&nbsp;&nbsp;&nbsp;
                @if (auth()->check() && $tweet->user->id != auth()->id())
                    @if ($followed_list->has($tweet->user->id))
                        <button type="button" class="follow-btn btn btn-info btn-sm" value="{{$tweet->user->id}}">following</button>
                    @else
                        <button type="button" class="follow-btn btn btn-outline-info btn-sm" value="{{$tweet->user->id}}">follow</button>
                    @endif
                    
                @endif
                
                <span class="float-right">{{$tweet->created_at->format('d M H:i')}}</span>
            </div>
            <div class="card-body">
                {{$tweet->tweet}} <br><br>
                @if (auth()->check())
                <button type="button"  class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#commentModal" data-postid="{{$tweet->id}}">Comment</button>
                @endif
            </div>
            <div class="card-footer comments_div">
                <h5>Comments:</h5>
                <div id="tweet_comment_div{{$tweet->id}}">
                @foreach ($tweet->comment as $item)
                    <div class="card-body border"><i class="far fa-user-circle"></i> <strong>{{$item->user->name}} : </strong> 
                        {{$item->comment}}
                        <span class="float-right small-text">{{$item->created_at->format('d M H:i')}}</span>
                    </div>
                @endforeach
                </div>    
            </div>
        </div>
        <br>
    @endforeach
    
</div>

@if ($tweets->nextPageUrl())
<div id="show_more_div" class="card">
    <button class="btn btn-link" id="show_more_btn" data-url="{{$tweets->nextPageUrl()}}">Show more</button>
</div>
@endif


<br>
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

@push('script')
<script>
    var tweet_url = {!! json_encode(route('post.tweet')) !!};
    var followed = {!!json_encode($followed_list)!!};
    var my_id = {!!json_encode(auth()->id())!!};
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        autosize($('textarea'));
        //------------------------- submit post--------------------------------------
        $("#tweet_submit").click(function(e){
            e.preventDefault();
            var tweet = $("#textarea_tweet").val();
            
            $.ajax({
            type:'POST',
            url:tweet_url,
            data:{tweet:tweet},
            dataType: 'json',
            success:function(data){
                if (data.success) {
                    $("#textarea_tweet").val('');
                    var s = '<div class="card">' +
                                '<div class="card-header post-header"><i class="far fa-user-circle"></i>&nbsp;' +
                                  '<span class="strong">'+data.name +'</span><span class="float-right">'+ data.posted_at +'</span>' +
                                '</div>' +
                                '<div class="card-body">' +
                                    data.tweet.tweet + '<br><br>' +
                                    '<button type="button"  class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#commentModal" data-postid="'+data.tweet.id+'">Comment</button>' +
                                '</div>' +
                                '<div class="card-footer comments_div">'+
                                    '<h5>Comments:</h5>'+
                                    '<div id="tweet_comment_div'+data.tweet.id+'"></div>'+   
                                '</div>'+
                            '</div><br>';

                    $('#append_container').prepend(s);
                    //autosize.destroy(document.querySelectorAll('textarea'));
                    autosize.destroy($('textarea'));
                    autosize($('textarea'));
                }
            }

            });

        });

        // ----------------------------- Follow or Unfollow with a single Click--------------------------------------
        $(document).on('click', '.follow-btn', function (event) {
            
            var button = $(this); // Button that triggered the modal
            var userid = button.val();
            $.ajax({
            type:'POST',
            url:{!! json_encode(route('follow')) !!},
            data:{followed_user:userid},
            dataType: 'json',
            success:function(data){
                if (data.success) {
                    $(".follow-btn[value|="+userid+"]").toggleClass( "btn-outline-info" );
                    $(".follow-btn[value|="+userid+"]").toggleClass( "btn-info" );
                    $(".follow-btn[value|="+userid+"]").text( (button.text() == "follow") ? "following" : "follow" );
                    
                }
            }

            });
            
        });

        // -----------------------------Set value on show modal start--------------------------------------
        $('#commentModal').on('show.bs.modal', function (event) {
            $('#textarea_comment').val('');
            var button = $(event.relatedTarget); // Button that triggered the modal
            var postid = button.data('postid'); // Extract info from data-* attributes
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
        $('#show_more_btn').on('click', function (event) {
            
            var paging_url = $(this).data('url'); 

            $.ajax({
            type:'GET',
            url:paging_url,
            //data:{comment:comment,tweet_id:tweet_id},
            dataType: 'json',
            success:function(resp){
                if (resp.success) {
                    if(resp.next_page){
                        $('#show_more_btn').data('url', resp.next_page);
                    }
                    else{
                        $('#show_more_div').remove();
                    }
                    

                    resp.tweets.data.forEach(tweet => {
                        var s = '<div class="card">'+
                                    '<div class="card-header post-header"><i class="far fa-user-circle"></i>&nbsp;'+
                                        '<span class="strong">'+ tweet.user.name +'</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                        if (resp.auth && tweet.user.id != my_id) {
                                        s +='<button type="button" class="follow-btn btn ' + (followed.hasOwnProperty(tweet.user.id) ? 'btn-info' : 'btn-outline-info') +' btn-sm" value="'+ tweet.user.id +'">' +
                                            (followed.hasOwnProperty(tweet.user.id) ? 'following' : 'follow') +
                                            '</button>';
                                        }
                                        
                                    s += '<span class="float-right">'+tweet.created_at+'</span>'+
                                    '</div>'+
                                    '<div class="card-body">'+ tweet.tweet + '<br><br>' +
                                        (resp.auth ? '<button type="button"  class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#commentModal" data-postid="'+ tweet.id +'">Comment</button>' : '') +
                                   ' </div>' +
                                    '<div class="card-footer comments_div">'+
                                       ' <h5>Comments:</h5>'+
                                        '<div id="tweet_comment_div'+tweet.id+'">';
                                        tweet.comment.forEach(comment => {
                                            s +=  '<div class="card-body border"><i class="far fa-user-circle"></i> <strong>'+ comment.user.name +' : </strong>'+ 
                                                comment.comment +
                                                '<span class="float-right small-text">'+comment.created_at+'</span>' +
                                            '</div>';
                                        });
                                    s += '</div>' +
                                    '</div>'+
                                '</div>'+
                                '<br>';
                        $('#append_container').append(s);
                        
                    });
                    
                            
                }
            }

            });

            //alert(paging);
            
            
        });
        // ---------------------------------------------------end --------------------------------------------------------
        

    });

    
   
</script>
@endpush
@endsection
