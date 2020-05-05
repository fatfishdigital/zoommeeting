/**
 * Zoom plugin for Craft CMS
 *
 * Index Field JS
 *
 * @author    Fatfish
 * @copyright Copyright (c) 2020 Fatfish
 * @link      www.fatfish.com.au
 * @package   Zoom
 * @since     1.0.0
 */
$(".deletemeeting").on('click',function(e){

    var MeetingId = $(this).data('id');

    swal("Are you sure you want to delete ?")
        .then((value) => {
            if(value)
            {
                Craft.postActionRequest('/admin/deletemeeting',{data:MeetingId},function result(e){
                    if(e)
                    {
                        Craft.cp.displayNotice("Meeting Deleted");
                        Craft.redirectTo("/admin/zoom");
                    }

                });
            }
        });


});


