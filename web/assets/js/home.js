/**
 * Created by Xps_Sam on 17/04/2017.
 */
var popular = 5;
var recent = 5;

function showEditForm(){
    $('#edit_modal .editBox').fadeOut('fast',function(){
        $('.editBox').fadeIn('fast');

        $('.modal-title').html('Edit profile');
    });
    $('.error').removeClass('alert alert-danger').html('');
}

function openEditModal(){
    showEditForm();
    setTimeout(function(){
        $('#edit_modal').modal('show');
    }, 230);

}

$('#add5MorePopular').on('click',function (e) {
    e.preventDefault();
    console.log(popular);
    $.ajax({
        type: 'post',
        url: '/getFiveMorePop',
        data: {myData: popular},
        success: function (result) {
            if(result != 0 && result != 1){
                $('#gallery_pop').append(result);
                $('#gallery_pop img.likeImg').on('click',function(e){
                    e.preventDefault();
                    setLikeListenerImage(this,'#gallery_pop','#gallery_recent');
                });
                $('#gallery_pop #commentButton').on('click',function (e){
                    e.preventDefault();
                    actionCommentListenerImage(this,'#gallery_pop','#gallery_recent');
                });
                popular = popular + 5;
            }else{
                $('#add5MorePopular i').hide();
                $('#add5MorePopular').hide();
            }
        }
    })
});

$('#add5MoreRecent').on('click',function (e) {
    e.preventDefault();
    $.ajax({
        type: 'post',
        url: '/getFiveMoreRec',
        data: {myData: recent},
        success: function (result) {
            console.log((result));
            if(result != 0 && result != 1){
                $('#gallery_recent').append(result);
                $('#gallery_recent img.likeImg').on('click',function(e){
                    e.preventDefault();
                    setLikeListenerImage(this,'#gallery_recent','#gallery_pop');
                });
                $('#gallery_recent #commentButton').on('click',function (e){
                    e.preventDefault();
                    actionCommentListenerImage(this,'#gallery_recent','#gallery_pop');
                });

                recent = recent + 5;
            }else{
                $('#add5MoreRecent i').hide();
                $('#add5MoreRecent').hide();
            }
        }
    })
});

$('#gallery_pop .moreCommentsBtn').on('click',function (e) {
    e.preventDefault();
    var dataSend = {};
    dataSend.image_id = $(this).attr('data-content');
    dataSend.size = ($("#comentaris"+$(this).attr('data-content')).children().size()-1);
    $.ajax({
        type: 'post',
        url: '/moreCommentsBox',
        data: {data:JSON.stringify(dataSend)},
        success: function ($response) {
            console.log($response);
            var response = JSON.parse($response);
            for(var i = 0;i<response.length && i<3;i++){
                var comentari = new Array();
                comentari.push(new Array())
                comentari[0].push(response[i].username);
                comentari[0].push(response[i].text);
                comentari[0].push(response[i].img_path);
                comentari[0].push(response[i].created_at);
                var append = getImageComments(comentari,dataSend.image_id,true);
                $("#gallery_pop #comentaris"+dataSend.image_id+" #ulMoreCommentsBtn").before(append);
            }
            if(response.length < 4){
                $("#gallery_pop #comentaris"+dataSend.image_id+" #ulMoreCommentsBtn").hide();
            }
        }
    });
});

$('#gallery_recent .moreCommentsBtn').on('click',function (e) {
    e.preventDefault();
    var dataSend = {};
    dataSend.image_id = $(this).attr('data-content');
    dataSend.size = ($("#comentaris"+$(this).attr('data-content')).children().size()-1);
    $.ajax({
        type: 'post',
        url: '/moreCommentsBox',
        data: {data:JSON.stringify(dataSend)},
        success: function ($response) {
            console.log($response);
            var response = JSON.parse($response);
            for(var i = 0;i<response.length && i<3;i++){
                var comentari = new Array();
                comentari.push(new Array())
                comentari[0].push(response[i].username);
                comentari[0].push(response[i].text);
                comentari[0].push(response[i].img_path);
                comentari[0].push(response[i].created_at);
                var append = getImageComments(comentari,dataSend.image_id,true);
                $("#gallery_recent #comentaris"+dataSend.image_id+" #ulMoreCommentsBtn").before(append);
            }
            if(response.length < 4){
                $("#gallery_recent #comentaris"+dataSend.image_id+" #ulMoreCommentsBtn").hide();
            }
        }
    });
});