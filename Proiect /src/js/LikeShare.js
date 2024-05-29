function shareOnFacebook() {
    var shareUrl = "http://localhost:8080/index.html";
    var shareTitle = "Share Feane"; 

    var facebookShareUrl = "https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(shareUrl);

    window.open(facebookShareUrl, "_blank", "width=600,height=400");
  }

  function likeOnFacebook() {
    var facebookLikeUrl = "https://www.facebook.com/petrudecebal.sion"; 

    window.open(facebookLikeUrl, "_blank");
  }