/**
 * Zoom plugin for Craft CMS
 *
 * Zoom JS
 *
 * @author    Fatfish
 * @copyright Copyright (c) 2020 Fatfish
 * @link      www.fatfish.com.au
 * @package   Zoom
 * @since     1.0.0
 */

let EditUserDialogBox=$("#edituserdialog").html();

$("#edituser").on('click',function (e) {

    e.preventDefault();

    let userid=$(this).data('id');
    let firstname=$(this).data('firstname');
    let lastname=$(this).data('lastname');
    let email = $(this).data('email');


    let $formdialog = $('<div class="modal fitted"/>');
    $(EditUserDialogBox).appendTo($formdialog);
    let $modal = new Garnish.Modal($formdialog,{
        onShow:function(){
            $("input#first_name").val(firstname);
            $("input#last_name").val(lastname);
            $("input#emailaddress").val(email);


            $("#exit").on('click',function (e) {
                $modal.hide();
                $modal.destroy();
            })

            $("#UpdateBtn").on('click',function (e) {

                var data={
                    userid:userid,
                    fname:$("input#first_name").val(),
                    lname:$("input#last_name").val(),
                    ename:$("input#emailaddress").val(),
                    usertype:$("#usertype").val(),
                };
                Craft.postActionRequest('/admin/zoom/user/update',{data:data},function (result) {
                    if(result)
                    {
                        Craft.cp.displayNotice("User updated");
                        Craft.redirectTo('/admin/zoom/user');
                        $modal.hide();
                        $modal.destroy();
                    }
                })
            });

        }
    });
});

$("#deleteuser").on('click',function (e) {

    let userid=$(this).data('userid');
    $("#deleteuser").text("Please Wait..")
    Craft.postActionRequest('/admin/zoom/user/delete',{data:userid},function(result)
    {
        if(result)
        {
            Craft.cp.displayNotice("User Deleted");
            Craft.redirectTo('/admin/zoom/user');
        }
        else
        {
            Craft.cp.displayNotice(result);
        }



    });




});
