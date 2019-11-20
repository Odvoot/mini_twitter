@extends('layouts.app')

@section('content')
<div class="card-body border">
    <h5><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp; {{$user->name}}</h5>
    <span class="small-grey-text"> Total Tweet {{$tweet_count}}</span>
</div>
<div class="user_profile_cap">

    <div class="user_profile_cover">
        <img src="{{url('img/cover.jpg')}}" alt="img"/>
        
    </div>
    <div class="user_profile_headline">
        <div>
            <img id="profile_image_page" src="{{$user->photo ? url($user->photo) : url('img/default.png')}}" alt="img"/> 
        </div>
        <h2>{{$user->name}}</h2><button class="btn btn-outline-success float-right" data-toggle="modal" data-target="#profile_edit_modal">Update</button>
        <span><i class="fa fa-envelope" aria-hidden="true"></i> {{$user->email}}</span><br>
        <span><i class="fa fa-calendar" aria-hidden="true"></i> Joined {{$user->created_at->format('M d, Y')}}</span>
    </div>
  
</div>
<div class="card">
    <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="posts-tab" data-toggle="tab" href="#append_container" role="tab" aria-controls="append_container" aria-selected="true">Posts</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="followers-tab" data-toggle="tab" href="#followers" role="tab" aria-controls="followers" aria-selected="false">Followers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="following-tab" data-toggle="tab" href="#following" role="tab" aria-controls="following" aria-selected="false">Following</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active"  role="tabpanel" aria-labelledby="append_container-tab" id="append_container">
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
            
            @endforeach
            <br>
        </div>
        <div class="tab-pane fade"  role="tabpanel" aria-labelledby="followers-tab" id="followers">
            <br>
            <div class="row">
                @foreach ($followers as $user)
                    <div class="profile-list col-md-11 card-body">
                        <div>
                            <img id="profile_image_page" src="{{$user->photo ? url($user->photo) : url('img/default.png')}}" alt="img"/> 
                        </div>
                        <p>{{$user->name}}</p>
                        @if ($followed_list->has($user->id))
                            <button type="button" class="follow-btn btn btn-info btn-sm float-right" value="{{$user->id}}">following</button>
                        @else
                            <button type="button" class="follow-btn btn btn-outline-info btn-sm float-right" value="{{$user->id}}">follow</button>
                        @endif
                        <span><i class="fa fa-envelope" aria-hidden="true"></i> {{$user->email}}</span><br>
                        <span><i class="fa fa-calendar" aria-hidden="true"></i> Joined {{$user->created_at->format('M d, Y')}}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="tab-pane fade"  role="tabpanel" aria-labelledby="following-tab" id="following">
            <br>
            <div class="row">
                @foreach ($followed as $user)
                    <div class="profile-list col-md-11 card-body">
                        <div>
                            <img id="profile_image_page" src="{{$user->photo ? url($user->photo) : url('img/default.png')}}" alt="img"/> 
                        </div>
                        <p>{{$user->name}}</p>
                        <button type="button" class="follow-btn btn btn-info btn-sm float-right" value="{{$user->id}}">following</button>
                        <span><i class="fa fa-envelope" aria-hidden="true"></i> {{$user->email}}</span><br>
                        <span><i class="fa fa-calendar" aria-hidden="true"></i> Joined {{$user->created_at->format('M d, Y')}}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>





{{-- Comment Modal Start --}}
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

{{-- Profile Edit Modal Start --}}
<div class="modal fade bd-example-modal-lg" id="profile_edit_modal" tabindex="-1" role="dialog" aria-labelledby="profile_edit_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="user_profile_cover">
                <img src="http://1.bp.blogspot.com/_Ym3du2sG3R4/S_-M_kTV9OI/AAAAAAAACZA/SNCea2qKOWQ/s1600/mac+os+x+wallpaper.jpg" alt="img"/>
                
            </div>
            <form id="image_form" method="post" enctype="multipart/form-data">
            <div class="user_profile_headline">
                <div>
                    <img id="profile_image_display" src="{{$user->photo ? url($user->photo) : url('img/default.png')}}" alt="img"/> 
                    <p id="validation_message" class="text-danger"></p>
                </div>
                <input type="file" accept="image/png, image/jpeg" class="form-control-file" id="profile_picture" name="photo">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Change</button>
            </div>
            </form>
        </div>
        
    </div>
</div>
    {{-- Modal end --}}


@endsection


@push('script')
<script>
    var tweet_url = {!! json_encode(route('post.tweet')) !!};
    var base_url = {!! json_encode(url('/')) !!};
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        autosize($('textarea'));

        // -----------------------------Set value on show comment modal start--------------------------------------
        $('#commentModal').on('show.bs.modal', function (event) {
            $('#textarea_comment').val('');
            var button = $(event.relatedTarget) // Button that triggered the modal
            var postid = button.data('postid') // Extract info from data-* attributes
            $('#hidden_post_id').val(postid);
            
        });

        // -----------------------------Set value on show comment modal start--------------------------------------
        $('#profile_edit_modal').on('show.bs.modal', function (event) {
            $('#validation_message').text('');
            
        });

        

        //-------------------------------------------- submit comment -------------------------------------------
        $('#comment_submit').on('click', function (event) {
            event.preventDefault();
            
            var comment = $('#textarea_comment').val();
            var tweet_id = $('#hidden_post_id').val();
            ///alert(tweet_id);
            
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

        // ------------------------------------ Update Image --------------------------------------------------------

        $('#image_form').submit(function(event){

            event.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: "{{route('profile.update_photo')}}",
                data: formData,
                type: 'post',
                processData: false,
                contentType: false,
                success:function(response){
                    console.log(response);
                    if (response.success) {
                        $('#profile_image_page').attr('src',response.path);
                        $('#profile_edit_modal').modal('hide');
                    }
                    else{
                        $('#validation_message').text(response.message);
                    }
                   
                }
            });

        });

        //------------------------Image show------------------------------------------------------------------------------
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#profile_image_display').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#profile_picture").change(function(){
            readURL(this);
        });
      
        // ---------------------------------------------------end --------------------------------------------------------
        

    });

    
   
</script>
@endpush