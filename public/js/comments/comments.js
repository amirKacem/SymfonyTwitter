$.ajax({
    url: "/api/comments",
    method:'GET',
    contentType:'content-type:application/json'
}).done(function(data) {
    console.log(data);

});