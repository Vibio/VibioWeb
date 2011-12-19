if(Drupal.jsEnabled){
  $(document).ready(function(){
    //The share button is clicked, initiating the FB dialog
    $('a#fb-request').click(function (event) {
      event.preventDefault();
      var $this = $(this);
      //Get the inviter's username
      var username = $this.attr('username');
      invite(username);
    });

    //Invite a user to vibio using FB Request Dialog
    //See http://developers.facebook.com/docs/reference/dialogs/requests/
    function invite(username) {
      //Facebook request dialog initiation.
      FB.init({
        appId: fb_settings.app_id,
        status: true,
        cookie: true,
        oauth: true
      });

      FB.ui({
        method: 'apprequests',
        message: "Join " + username + " on Vibio to share your stuff and see your friends' collections.",
        //passes along the uid; this can be used by the FB apps canvas page
        //to redirect to the inviting user's profile page
        display: 'popup'
       },
       function(response){
         console.log(response);
       }
      );
    }
  });
}

