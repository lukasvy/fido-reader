angular.module('T',[])

.constant('templ',{

    // Common templates
    'common.search'         : 'app/js/app/common/search/search.html',
    'searchResult'   		: 'app/js/app/search/search_result.html',
    'common.error'          : 'app/js/app/common/error/error.html',
    'common.modal'          : 'app/js/app/common/modal/modal.html',
    // Menu templates
    'menu.loginForm'        : 'app/js/app/menu/login/login.html',
    // Container
    'container'				: 'app/js/app/container/container.html',
    // My Account
    'myaccount'				: 'app/js/app/myaccount/myaccount.html',
    // Admin
    'admin'					: 'app/js/app/admin/admin.html',
    // Loading
    'loading'				: 'app/js/app/common/loading/loading.html',
    // Table
    'table'					: 'app/js/app/table/table.html',
    'feeds.modal'			: 'app/js/app/admin/feeds-modal.html',
    'user.feed.modal'		: 'app/js/app/menu/userMenu/feeds-modal.html',
    'users.modal'			: 'app/js/app/admin/users-modal.html',
    'tags.modal'			: 'app/js/app/admin/tags-modal.html',
    // Article
    'article'				: 'app/js/app/articles/article.html',
    'modal.articlemodal'	: 'app/js/app/common/modal/articlemodal.html',
    'articles'				: 'app/js/app/articles/articles.html',
    'userMenu'				: 'app/js/app/menu/userMenu/userMenu.html'
    
})

.factory('T', function(templ){
    return function(key){
            if (templ[key]) {
                return templ[key];
            } else {
                throw ('T cannot find template : ' + key);
            }
    }
    
});

