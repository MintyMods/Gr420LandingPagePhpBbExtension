
function processHomepagePost(id, current) {
    var selection = $('#homepage-section-' + id + " option:selected");
    var section = selection.val();
    var desc = selection.text();
    
    if (section == 'none') {
        removePostFromLandingPageContent(id, section, desc, current);
    } else {
        addPostToLandingPageContent(id, section, desc, current);
    }
}

function addPostToLandingPageContent(id, section, desc, current) {
    dhtmlx.confirm({
        title: "Add to Homepage?",
        type:"confirm-warning",
        text: "Add Post to Homepage " + desc + " Content?",
        callback: function(confirmed) {
            if (confirmed) {
                const url = 'homepage/add/' + id + '/' + section;                
                $.ajax({ url, cache: false }).done(function(result, status, jqXHR) {
                    if (result) {
                        showMessage("Post " + id + " added to Homepage " + desc, "Homepage Updated ", section);
                        $('#homepage-icon-' + id).removeClass().addClass('icon ' + getStatusIcon(section));
                    } else {
                        showMessage("Failed to add Post " + id + " to Homepage " + desc, "Homepage Failed ", 'error');
                        $('#homepage-icon-' + id).removeClass().addClass('icon fas fa-bug icon-error');
                    }
               }).fail(function( result ) {
                   console.log("Add post to Homepage Update Failed", result);
                   PNotify.error({ title: 'Add Homepage Update Failed', text: result.statusText });
               });                
            }
        }
    });
}

function removePostFromLandingPageContent(id, section, desc, current) {
    dhtmlx.confirm({
        title: "Remove from Homepage?",
        type:"confirm",
        text: "Remove Post from Homepage Content?",
        callback: function(confirmed) {
            if (confirmed) {
                const url = 'homepage/remove/' + id;
                $.ajax({ url, cache: false }).done(function(result, status, jqXHR) {
                    if (result) {
                        showMessage("Post " + id + " removed from Homepage ", "Homepage Post Removed ", section);
                        $('#homepage-icon-' + id).removeClass().addClass('icon fas fa-plus-square');
                    } else {
                        showMessage("Failed to remove Post " + id + " from Homepage " + desc, "Homepage Remove Failed ", 'error');
                        $('#homepage-icon-' + id).removeClass().addClass('icon fas fa-bug icon-error');
                    }
               }).fail(function( result ) {
                   console.log("Remove post from Homepage Update Failed", result);
                   PNotify.error({ title: 'Remove Homepage Post Failed', text: result.statusText });
               });   


            }
        }
    });
}

function showMessage(message, title, section) {
    var icon = 'icon icon-large ' + getStatusIcon(section);
    if (PNotify) {
        PNotify.notice({ 
            icon, 
            title, 
            text: message, 
            delay:3000,
            styling: 'custom',
            maxTextHeight: null,
            addModelessClass: 'nonblock'
        });
    } else if (dhtmlx.confirm) {
        dhtmlx.confirm({
            title: title,
            type:"alert",
            text: message
        });        
    } else {
        alert(message);
    }
        
}

function getStatusIcon(section) {
    var image = 'fas fa-bug icon-error';
    switch (section) {
        case 'none' :
            image = 'fas fa-plus-square';
            break;
        case 'homepage' :
            image = 'fas fa-address-card';
            break;
        case 'tutorials' :
            image = 'fas fa-graduation-cap';
            break;
        case 'podcasts' :
            image = 'fas fa-podcast';
            break;
        case 'diaries' :
            image = 'fas fa-book';
            break;
        case 'reviews' :
            image = 'fas fa-binoculars';
            break;
        case 'sponsors' :
            image = 'fas fa-gift';
            break;
        case 'error' :
            image = 'fas fa-exclamation-triangle icon-error';
            break;
    }
    return image;
}
