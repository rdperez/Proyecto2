/*global $*/
$(function(){
    $('#formSession').submit(function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        console.log('data',data)
        // return false
        $.ajax({
            url: 'users/signin',
            type: 'POST',
            data: data,
            // dataType: 'json',
            success: function(result){
                console.log('result', result);
                window.location.href = '/users';
            },
            error: function(error){
                console.log('error', error);
            }
        })
    })
})