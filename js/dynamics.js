
$(document).ready(function(){

    // Autohide elements
    function hideElements(){
        // Note buttons, container and password input
        $("#addCat").hide();
        $("#addNote").hide();
        $("#addNoteContainer").hide();
        $("#lockNotes").hide();
        $("#noteForm").hide();
        $("#unlockNoteContainer").hide();
        $("#unlockNotes").hide();
        $("#unlockPassword").hide();
        
        // Bookmark buttons, container and checkboxes (selection/delete)
        $("#addUrl").hide();
        $("#addUrlContainer").hide();
        $("#delUrl").hide();
        $("#delUrls").hide();
        $("#linkForm").hide();
        $(".delUrlCheck").hide();

        // Main Rows
        $(".linkRow").hide();
        $(".noteRow").hide();
        $(".settingsRow").hide();
        $(".weatherRow").hide();
    }
    // Hide em'
    hideElements();
    
    // Get URL # parameter and display the page accordingly 
    var hash = document.URL.split('#')[1];
    if(hash === 'notes'){ 
        $("#addNote").show();
        $("#lockNotes").show();
        $("#unlockNotes").show();
        $(".noteRow").show();
        window.history.replaceState(null,null, window.location.pathname);
    } else { // First (landing page)
        $("#addUrl").show();
        $("#delUrl").show();
        $(".linkRow").show();
    }

    // Close error messages
    $(".closeErr").click(function(){
        $(".errorContainer").slideToggle(200);
    });

    // Select URLs for deletion
    $("#delUrl").click(function(){
        $(".delUrlCheck").toggle(0);
        $("#delUrls").toggle(0);
        $("#allUrlsDel").trigger('reset');

        // Change the margin on the links, so it won't jump around
        var margin = $(".urlHyper");
        if(margin.css("margin-left") === "20px")
            $(".urlHyper").css("margin-left", "1px");
        else
            $(".urlHyper").css("margin-left", "20px");

        // Change the value of the del urls button (close)
        var open = $("#delUrl");
        if(open.html() === '<i class="fa fa-trash" aria-hidden="true"></i>'){
          open.html('<i class="fa fa-times" aria-hidden="true"></i>');
          open.prop('title','Cancel');
        } else {
          open.html('<i class="fa fa-trash" aria-hidden="true"></i>');
          open.prop('title','Select');
        }
        return false;
    });

    // Switch between chosing/creating new bookmark category
    $(".catSwitch").click(function(){
        $("#chooseCat").toggle(0, function(){
            $("#chooseCat").focus();
        });
        $("#addCat").toggle(0, function(){
            $("#addCat").focus();
        });

        if($("#chooseCat").prop('name') === 'category'){
            $("#chooseCat").prop('name','');
            $("#addCat").prop('name','category');
        }else{
            $("#chooseCat").prop('name','category');
            $("#addCat").prop('name','');
        }
        return false;
    });

    // Open Weather panel
    $("#openWeather").click(function(){
        hideElements(); 
        $(".weatherRow").show();
    });

    // Open note panel
    $("#openNotes").click(function(){
        hideElements();
        $("#addNote").html('<i class="fa fa-plus-square" aria-hidden="true"></i>');
        $("#addNote").prop('title','New');
        $("#addNote").prop('title','Unlock');
        $("#addNote").show();
        $("#lockNotes").show();
        $("#unlockNotes").html('<i class="fa fa-unlock" aria-hidden="true"></i>');
        $("#unlockNotes").show();
        $(".noteRow").show();
    });

    // Open bookmark panel
    $("#openLinks").click(function(){
        hideElements();
        $("#addUrl").html('<i class="fa fa-plus-square" aria-hidden="true"></i>');
        $("#addUrl").prop('title','New');
        $("#addUrl").show();
        $("#delUrl").show();
        $(".linkRow").show();
    });

    // Open Settings panel
    $("#openSettings").click(function(){
        hideElements();
        $(".settingsRow").show();
    });

    // Add url - toggle slide, fade and focus
    $("#addUrl").click(function(){
        $("#addUrlContainer").slideToggle(50, function(){
            $("#chooseCat").show();
            $("#linkForm").fadeToggle(300);
            $(".inputUrl").focus();
        });
        
        // When changing between adding/chossing category, reset input names
        $("#linkForm").trigger('reset');
        $("#chosseCat").prop('name', 'category');
        $("#addCat").prop('name', '');

        // Change the value of the add url button (close)
        var open = $("#addUrl");
        if(open.html() === '<i class="fa fa-plus-square" aria-hidden="true"></i>'){
          open.html('<i class="fa fa-times" aria-hidden="true"></i>');
          open.prop('title','Close');
        } else {
          open.html('<i class="fa fa-plus-square" aria-hidden="true"></i>');
          open.prop('title','New');
        }
        return false;
    });

    // When add note is clicked, toggle slide, fade and focus
    $("#addNote").click(function(){
        $("#addNoteContainer").slideToggle(50, function(){
            $("#noteForm").fadeToggle(300);
            $("#noteArea").focus();
        });
        
        // When changing between adding/chossing category, reset input names
        $("#noteForm").trigger('reset');
        
        // Change the value of the add url button (close)
        var open = $("#addNote");
        if(open.html() === '<i class="fa fa-plus-square" aria-hidden="true"></i>'){
          open.html('<i class="fa fa-times" aria-hidden="true"></i>');
          open.prop('title','Close');
        } else {
          open.html('<i class="fa fa-plus-square" aria-hidden="true"></i>');
          open.prop('title','New');
        }
        return false;
    });

    // Password drop down when unlocking notes
    $("#unlockNotes").click(function(){
        $("#unlockNoteContainer").slideToggle(50, function(){
            $("#unlockPassword").fadeToggle(300);
            $("#unlockPassword").focus();
        });
        
        // Change the value of the add url button (close)
        var open = $("#unlockNotes");
        if(open.html() === '<i class="fa fa-unlock" aria-hidden="true"></i>'){
          open.html('<i class="fa fa-times" aria-hidden="true"></i>');
          open.prop('title','Close');
        } else {
          open.html('<i class="fa fa-unlock" aria-hidden="true"></i>');
          open.prop('title','Unlock');
        }
        return false;
    });

    // Confirm when dropping DB
    // TODO: Not being used right now
    $('#dropDb').submit(function(){
        return confirm("Be aware that this will delete the 'startpage' database. This action is irreversible! Are you sure you want to continue?");
    });

    // Function that updates the time 
    function updateTime(){
        var currentTime = new Date();
        var hours       = currentTime.getHours();
        var minutes     = currentTime.getMinutes();

        if (minutes < 10)
            minutes = "0" + minutes;
        if(hours < 10)
            hours = "0" + hours;

        var time = hours + ":" + minutes;
        document.getElementById('timeCon').innerHTML = time;
    }
    setInterval(updateTime, 1000);
});
