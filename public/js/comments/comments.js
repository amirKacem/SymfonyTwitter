var postId = $('#postId').val();
var token = $("input[name='_csrf_token']").val();

$('#addComment').click(function(e){
    e.preventDefault();
    var date = new Date();
    var content =$("#comment").val();
    console.log(content);
    var data = {
        post:postId,
        content:content,
        createdat:date,
        _csrf_token :token
    };
    addComment(data,showComment);

});

$('#deleteComment').click(function(e){
   e.preventDefault();
   var data = {
       _csrf_token :token,
       id:$(this).data('id')
   };
   removeComment(data);

});


function addComment(data,callback){
$.ajax({
    url: "http://127.0.0.1:8000/api/comment/add",
    method:'POST',
    dataType: 'json',
    data:JSON.stringify(data),

    success:function(data){
        console.log(data);
        data = JSON.parse(data);
       if(callback){
           callback(data);
       }
    },
    error: function (xhr, ajaxOptions, thrownError) {
        console.log(xhr.responseText);
        console.log(thrownError);
    }
});

}

function removeComment(data){

        $.ajax({
            url: "/api/comment/remove",
            method:'POST',
            dataType: 'json',
            data:JSON.stringify(data),
            success:function(data){
                console.log(data);


            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
                console.log(thrownError);
            }
        });
}

function showComment(data){
    console.log(data);
    var date = new Date();

    var dateformat = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDay()+" "+
    date.getHours()+"-"+date.getMinutes()+"-"+date.getSeconds();
    var comment=`<li><div class="comment-list"><div class="bg-img"> 
<img src="http://via.placeholder.com/50x50" alt="">
</div>
                                                    <div class="comment">
                                                        <h3>${data.commentBy.username}</h3>       
 <span><img src="images/clock.png" alt="">${dateformat}</span>

                                                        <span><img src="images/clock.png" alt=""></span>
                                                        <p>${data.content}</p>
                            
                                                    </div>
                                                </div><!--comment-list end-->
                                            </li>`;
$('#comments').append(comment);
$('#comment').val('');
}





