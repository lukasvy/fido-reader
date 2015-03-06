<?php ?>
<!doctype html>
<html lang="en" ng-app="myApp">
<head>
    <meta charset="UTF-8">
    <title>Fido</title>

<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap.min.css" rel="stylesheet"/> 

    <link rel="stylesheet" href="app/css/font-awesome/css/font-awesome.min.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.1/css/font-awesome.css" rel="stylesheet">
    <link data-require="ng-table@*" data-semver="0.3.0" rel="stylesheet" href="http://bazalt-cms.com/assets/ng-table/0.3.0/ng-table.css" />
    
        
    <script src="app/js/vendors/jquery.js"></script> 
    
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>

    <script src="http://ivaynberg.github.com/select2/select2-3.2/select2.js"></script>
    <link href="http://ivaynberg.github.com/select2/select2-3.2/select2.css" rel="stylesheet" type="text/css"/>
    
    <link rel="stylesheet" href="app/css/desktop-app-new.css">
</head>
<body ng-controller="AppCtrl" >
<div id='start'>
Loading...
</div>
<div id='continue' style="display:none;">
    <lv-snapper>   
        <lv-snapper-content class="snap-content" lv-fixed>
		<div class="fixed">
        	<div id="toolbar" class="navbar-fixed-top">
                <div class="">
                        <lv-snapper-toggler side="left" class="pull-left menu"><a href=""><i class="icon-reorder"> {{menutext}}</i></a></lv-snapper-toggler><lv-selection></lv-selection>
                        <lv-snapper-toggler side="right" class="pull-right menu"><a href="">{{logintext}} <i class="icon-user"></i></a></lv-snapper-toggler>
                </div>
        	</div> 
		</div>
            <div class="page-container"><lv-loading event="loading"><ng-view></ng-view></lv-loading></div>
        </lv-snapper-content>
        <lv-snapper-menu side="left" class="snap-drawer snap-drawer-left">
            <lv-search type="main-search" width="100">Search</lv-search>
            <lv-user-menu></lv-user-menu>
        </lv-snapper-menu>
        <lv-snapper-menu side="right" class="snap-drawer snap-drawer-right">
            <lv-login-form/>
        </lv-snapper-menu>
    </lv-snapper>
</div>
<script>
	var documentUrl = '<?php echo route('home'); ?>';
</script>
<!-- ANGULAR SCRIPTS -->
<script src="app/js/vendors/angular-latest.js"></script>
<script src="app/js/vendors/angular-route.min.js"></script>
<script src="app/js/vendors/ui-bootstrap-tpls-0.6.0.min.js"></script>
<script src="app/js/vendors/underscore-min.js"></script>
<script src="app/js/vendors/restangular.js"></script>
<script src="app/js/vendors/ng-table.js"></script>
<script src="app/js/vendors/masonry.js"></script>

<script src="app/js/app/menu/menu-app.js"></script>
<script src="app/js/app/menu/menu-directives.js"></script>

<script src="app/js/app/container/container-app.js"></script>

<script src="app/js/app/search/search-app.js"></script>

<script src="app/js/app/common/template/template.js"></script>
<script src="app/js/app/common/lexicon/lexicon.js"></script>
<script src="app/js/app/common/lvHttp/lvHttp.js"></script>
<script src="app/js/app/common/modal/modal.js"></script>
<script src="app/js/app/common/modal/articlemodal.js"></script>
<script src="app/js/app/common/search/search.js"></script>
<script src="app/js/app/common/loading/loading.js"></script>
<script src="app/js/app/common/error/error.js"></script>
<script src="app/js/app/common/security/security.js"></script>
<script src="app/js/app/common/tag/tag.js"></script>
<script src="app/js/app/menu/login/login-app.js"></script>
<script src="app/js/app/menu/userMenu/userMenu-app.js"></script>
<script src="app/js/app/table/table-app.js"></script>
<script src="app/js/app/myaccount/myaccount-app.js"></script>
<script src="app/js/app/admin/admin-app.js"></script>
<script src="app/js/app/articles/articles.js"></script>
<script src="app/js/app/articles/userArticles.js"></script>
<script src="app/js/app/feeds/feeds-app.js"></script>
<script src="app/js/app/common/registry/registry.js"></script>
<script src="app/js/app/app.js"></script>

<script>
	document.getElementById('start').style.display='none';
	document.getElementById('continue').style.display='block';	
</script>
</body>
</html>