/**
 * @file:     regnick.js
 * @version:  0.1.2
 * @author:   Alexandru G. ( alex@no-spam-gentle.ro )
 */

function show_confirm()
{
    var r = confirm("Are you sure ?");
    return r;
}

function user_notify(message, type)
{
    tpl = '<div class="alert alert-'+ type +'"><a class="close" data-dismiss="alert">×</a>'+ message +'</div>';
    $(tpl).appendTo($(".user-notify"));
}

function json_request(_type, _url, _data, _datatype)
{
    var returnVal = false;
    
    $.ajax({
      async: false,
      type: _type,
      url: _url,
      data: _data,
      timeout: 2000,
      dataType: _datatype,
      success: function (response)
      {
        returnVal = response;
      },
      error: function (response)
      {
        returnVal = response;
      } 
    });

    return returnVal;
}

$(document).ready(function() { 
    
    // Menu highlight
    $("ul.nav li a").each(function() {
        if (window.location.href == this.href)
        {
            $(this).parent().addClass("active");
        }
    });
    
    // tooltips
    if(typeof(UI_TOOLTIPS) !== 'undefined' && UI_TOOLTIPS == true)
    {
        $("a[rel=tooltip]").tooltip();
    }
    
    // delete account
    $('a#del-account').click(function() {
        alert('not implemented in this version.');
        
        // hide modal
        $('#deleteAccount').modal('hide')
        
        // @TODO: redirect to delete account page, delete account, delete autologin cookie and terminate session
    });
    
    // delete group
    $("a[data-scope=del-group]").click(function() {
        
        if (show_confirm() == true)
        {
            var groupID   = $(this).attr("data-value");
            var $this     = $(this);
            var csrf      = $.cookie('csrf_cookie_name');
            var response  = json_request('POST', RN_URL+"acp/group/del/", "group_ID="+ groupID +"&csrf_test_name="+ csrf, 'json');
            
            if (response.status == 'ok')
            {
              $($this).parent().parent().fadeOut('normal', function(){ 
                    $($this).remove(); 
              });
              user_notify(response.message, "success");
            }
            else
            {
              user_notify(response.message, "error");
            }
        }

    });
    
    // delete server
    $("a[data-scope=del-server]").click(function() {
        
        if (show_confirm() == true)
        {
            var attrID    = $(this).attr("data-value");
            var $this     = $(this);
            var csrf      = $.cookie('csrf_cookie_name');
            var response  = json_request('POST', RN_URL+"acp/server/del/", "server_ID="+ attrID +"&csrf_test_name="+ csrf, 'json');
            
            if (response.status == 'ok')
            {
              $($this).parent().parent().fadeOut('normal', function(){ 
                    $($this).remove(); 
              });
              user_notify(response.message, "success");
            }
            else
            {
              user_notify(response.message, "error");
            }
        }

    });
    
    // delete user
    // TODO: refactor backend for ajax requests
    $("a[data-scope=del-user]").click(function() {
        
        if (show_confirm() == true)
        {
            var attrID  = $(this).data("value");
            var $this   = $(this);
            var csrf    = $.cookie('csrf_cookie_name');
            
            var action_del = $.post(RN_URL+"acp/user/del/", { 'user_ID': attrID, 'csrf_test_name': csrf }, function(){ 
                $($this).parent().parent().fadeOut('normal', function(){ 
                    $($this).remove(); 
                });
            })
            .success(function() { user_notify("Settings successfully saved.", "success"); })
            .error(function() { user_notify("Error saving new settings.", "error"); });
            
        }

    });
    
    // delete user access on a server
    $("a[data-scope=del-access]").click(function() {
        
        if (show_confirm() == true)
        {
            var userID    = $(this).data("userid");
            var serverID  = $(this).data("serverid");
            var $this     = $(this);
            var csrf      = $.cookie('csrf_cookie_name');
            var response  = json_request('POST', RN_URL+"acp/user/access/del/", "user_ID="+ userID +"&server_ID="+ serverID +"&csrf_test_name="+ csrf, 'json');
            
            if (response.status == 'ok')
            {
              // todo: refactor this
              $($this).parent().parent().fadeOut('normal', function(){ 
                  $("#"+serverID +" a.has-access").html('---');
              }).fadeIn('normal').fadeOut(400).fadeIn(400);
              
              user_notify(response.message, "success");
            }
            else
            {
              user_notify(response.message, "error");
            }
            
            /*            
            var action_del = $.post(RN_URL+"acp/user/access/del/", { user_ID: userID, server_ID: serverID }, function(){ 
                $($this).parent().parent().fadeOut('normal', function(){ 
                    $($this).remove(); 
                });
            })
            .success(function() { user_notify("Settings successfully saved.", "success"); })
            .error(function() { user_notify("Error saving new settings.", "error"); });
            */
        }

    });
    
    // export key button
    $('#export-key').click(function (e) {
        e.preventDefault();
        $(this).button('loading');
        
        var action_gen = $.post(RN_URL+"acp/settings/export/new-key/", { }, function(data){ 
            //console.log(data);
            $("#export_key").val(data);
        })
        .success(function() { user_notify("Settings successfully saved.", "success"); })
        .error(function() { user_notify("Error saving new settings.", "error"); });
        
        
        $(this).button('reset');
    });
    
});
