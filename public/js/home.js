

function follow(data,callback) {
    $.ajax({
        url: "/api/follow/add",
        method: 'POST',
        dataType: 'json',
        data: JSON.stringify(data),

        success: function (data) {
            console.log(data);

            if (callback) {
                callback(data);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            console.log(thrownError);
        }
    });
}

$('.follow').click(function(){
    console.log('click');
    var token = $("#follow__token").val();
    var id = $(this).data('id');
    var data = {
        id:id,
        _csrf_token :token

    };
    follow(data);
    $(this).remove();
});