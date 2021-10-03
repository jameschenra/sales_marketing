<script>
/* Init FB app */
window.fbAsyncInit = function () {
    FB.init({
        appId: '{{env('FACEBOOK_APP_ID')}}', status: true, cookie: true, xfbml: true
    });
};

(function (d, debug) {
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
    ref.parentNode.insertBefore(js, ref);
}(document, /*debug*/ false));

function openShareModal(event) {
    var shareButton = $(event.target);
    var title = shareButton.attr('data-title');
    var description = shareButton.attr('data-desc');
    var url = shareButton.attr('data-url');
    var image = shareButton.attr('data-photo');

    $('#shareModal').attr('data-title', title);
    $('#shareModal').attr('data-desc', description);
    $('#shareModal').attr('data-url', url);
    $('#shareModal').attr('data-image', image);

    $('#shareModal').modal();
}

function onShare(type) {
    var title = $('#shareModal').attr('data-title');
    var desc = $('#shareModal').attr('data-desc');
    var url = $('#shareModal').attr('data-url');
    var image = $('#shareModal').attr('data-image');
    var shareUrl = '';

    switch (type) {
        case 'facebook':
            postToFeed(title, desc, url, image);
            return;
        case 'linkedin':
            shareUrl = 'https://www.linkedin.com/shareArticle?mini=true&' +
                'url=' + url + '&' +
                'title=' + encodeURIComponent(title) + '&' +
                'summary=' + encodeURIComponent(desc) + '&' +
                'media=' + image + '&' +
                'source={{ env('APP_URL') }}';
            break;
        case 'twitter':
            shareUrl = 'http://twitter.com/share?' +
                'text=' + title + '&' +
                'url=' + url;
            break;
        case 'pinterest':
            shareUrl = 'https://www.pinterest.com/pin/create/button/?' +
                'url=' + encodeURIComponent(url) + '&' +
                'media=' + encodeURIComponent(image) + '&' +
                'description=' + encodeURIComponent(desc) + '&';
                'title=' + encodeURIComponent(title);
            break;
        case 'whatsapp':
            shareUrl = 'https://wa.me/?text=' + encodeURI(title + ' ' + '{{URL::current()}}');
            window.open(shareUrl, '_blank');
            return;
        default:
            break;
    }

    var width = 575,
        height = 400,
        left = ($(window).width() - width) / 2,
        top = ($(window).height() - height) / 2,
        opts = 'status=1' +
            ',width=' + width +
            ',height=' + height +
            ',top=' + top +
            ',left=' + left;

    window.open(shareUrl, type, opts);
}

function postToFeed(title, desc, url, image) {
    var obj = {method: 'feed', link: url, picture: image, name: title, description: desc};

    function callback(response) {
    }
    FB.ui(obj, callback);
}
</script>