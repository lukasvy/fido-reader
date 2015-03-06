<HTML>
<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	
	<style>
		
.article img
{
    max-width: 100%; /* 960 px */
    margin: 0 auto;
}
#wrapper
{
    max-width: 60em; /* 960 px */
    margin: 0 auto;
}

.article
{
    width: 30.303%; /* 300px */
    float: left;
    margin: 0 1.515% 1.875em; /* 15px 30px */
}

@media only screen and ( max-width: 40em ) /* 640px */
{
	.article
	{
	width: 46.876%; /* 305px */
	margin-bottom: 0.938em; /* 15px */
	}
}
@media only screen and ( max-width: 20em ) /* 640px */
{
	.article
	{
	    width: 100%;
	    margin-left: 0;
	    margin-right: 0;
	}
}
	</style>
</head>
<body>

<div id="wrapper">
	<div class="article">
	<img src="http://www.onlineconversion.com/rf_logo.gif"/>
	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin cursus consectetur quam eu consectetur. Praesent at elit tristique, hendrerit nibh a, hendrerit eros. Maecenas justo nisi, fermentum non semper sit amet, mollis et sem. Sed ultricies tempus risus, non fermentum est mattis in. In vitae metus pharetra, pharetra dolor a, fermentum neque. Integer vehicula sagittis odio eu pulvinar. Nulla auctor nulla eget felis mattis fermentum. Quisque nec justo erat. Donec mollis laoreet dignissim. Proin venenatis mauris vitae metus dignissim bibendum. Sed hendrerit ac magna nec aliquet.
	</div>
	<div class="article">
	<img src="http://www.tonypa.pri.ee/tbw/tut05_1.gif"/>
	attis in. In vitae metus pharetra, pharetra dolor a, fermentum neque. Integer vehicula sagittis odio eu pulvinar. Nulla auctor nulla eget felis mattis fermentum. Quisque nec justo erat. Donec mollis laoreet dignissim. Proin venenatis mauris vitae metus dignissim
	</div>
	<div class="article">
	Donec rhoncus quis augue in interdum. Curabitur ullamcorper quis massa eu pulvinar. Suspendisse quis odio dictum, auctor orci vel, tincidunt dui. Sed non congue nulla. Vestibulum nec metus et mauris sollicitudin ornare. Nam et pellentesque lacus. Nam in nisl ornare, interdum mi quis, congue orci. Ut urna erat, euismod quis augue sed, vehicula pulvinar mauris. Nam libero nulla, ornare et libero non, blandit pharetra est. Duis magna nibh, malesuada nec commodo in, ornare eget augue. Vivamus sit amet tempor lectus. Aliquam lacus arcu, laoreet in elit eu, volutpat suscipit mauris. Curabitur non semper dolor.

Vestibulum nec leo neque. Proin id mi sapien. Nunc pharetra eros ut tortor volutpat, non congue nunc ultricies. Sed porta tellus ac arcu rutrum, ut tincidunt erat pellentesque. Integer pharetra magna vitae quam pulvinar ultrices. Nam at erat et ligula posuere iaculis. Proin dignissim odio non aliquet mattis. Quisque quis tellus rhoncus, dictum leo ut, rhoncus libero. Duis non dolor nec elit scelerisque molestie aliquam ut nisl. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
	</div>
	<div class="article">
	<img src="http://www.onlineconversion.com/rf_logo.gif"/>
	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin cursus consectetur quam eu consectetur. Praesent at elit tristique, hendrerit nibh a, hendrerit eros. Maecenas justo nisi, fermentum non semper sit amet, mollis et sem. Sed ultricies tempus risus, non fermentum est mattis in. In vitae metus pharetra, pharetra dolor a, fermentum neque. Integer vehicula sagittis odio eu pulvinar. Nulla auctor nulla eget felis mattis fermentum. Quisque nec justo erat. Donec mollis laoreet dignissim. Proin venenatis mauris vitae metus dignissim bibendum. Sed hendrerit ac magna nec aliquet.
	</div>
	<div class="article">
	<img src="http://hss-prod.hss.aol.com/hss/storage/adam/8d1765acf76b732043374926587f383e/IMG_0590.jpg"/>
	attis in. In vitae metus pharetra, pharetra dolor a, fermentum neque. Integer vehicula sagittis odio eu pulvinar. Nulla auctor nulla eget felis mattis fermentum. Quisque nec justo erat. Donec mollis laoreet dignissim. Proin venenatis mauris vitae metus dignissim
	</div>
	<div class="article">
	Donec rhoncus quis augue in interdum. Curabitur ullamcorper quis massa eu pulvinar. Suspendisse quis odio dictum, auctor orci vel, tincidunt dui. Sed non congue nulla. Vestibulum nec metus et mauris sollicitudin ornare. Nam et pellentesque lacus. Nam in nisl ornare, interdum mi quis, congue orci. Ut urna erat, euismod quis augue sed, vehicula pulvinar mauris. Nam libero nulla, ornare et libero non, blandit pharetra est. Duis magna nibh, malesuada nec commodo in, ornare eget augue. Vivamus sit amet tempor lectus. Aliquam lacus arcu, laoreet in elit eu, volutpat suscipit mauris. Curabitur non semper dolor.

Vestibulum nec leo neque. Proin id mi sapien. Nunc pharetra eros ut tortor volutpat, non congue nunc ultricies. Sed porta tellus ac arcu rutrum, ut tincidunt erat pellentesque. Integer pharetra magna vitae quam pulvinar ultrices. Nam at erat et ligula posuere iaculis. Proin dignissim odio non aliquet mattis. Quisque quis tellus rhoncus, dictum leo ut, rhoncus libero. Duis non dolor nec elit scelerisque molestie aliquam ut nisl. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
	</div>
</div>

<script src="app/js/vendors/masonry.js"></script>
<script src="app/js/vendors/imagesLoaded.js"></script>
<script>

$(document).ready(function() {
	// or with jQuery
	var $container = $('#wrapper');
	// initialize Masonry after all images have loaded  
	$container.imagesLoaded( function() {
	  $container.masonry();
	});
});
	</script>

</body>
