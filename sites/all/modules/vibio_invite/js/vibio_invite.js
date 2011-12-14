(function ($) {
  Drupal.behaviors.fbshare = {
    attach: function(context, setttings){

//The share button is clicked, initiating the FB dialog
$('a#fb-request').click(function (event) {
  event.preventDefault();
  var $this = $(this);
  //Get properties from the link. Assumes the custom nid attribute has been
  //set
  var link = $this.attr('href');
  var username = $this.attr('username');
  invite(link, username);
});

//Invite a user to vibio using FB Request Dialog
//See http://developers.facebook.com/docs/reference/dialogs/requests/
function invite(link, username, uid) {
    //Facebook request dialog initiation.
    FB.ui(
     {
      method: 'apprequests',
      message: "See " + username + "'s collections and share your stuff on Vibio",
      //passes along the uid; this can be used by the FB apps canvas page
      //to redirect to the inviting user's profile page
      data: uid,
      display: 'popup'
     },
     function(response){
       console.log(response);
     }
    );
}

}
}
})(jQuery);
