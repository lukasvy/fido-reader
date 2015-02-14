angular.module('L',[])

.constant('text',{
    // Common
    'common.search'                 : 'Search',
    'menu.text' 	                : 'Menu',
    'login.submit'                  : 'Login',
    'login.logout'                  : 'Logout',
    'login.username'                : 'Username',
    'login.password'                : 'Password',
    'login.invalid.username'        : 'Invalid username',
    'login.invalid.password'        : 'Invalid password',
    'login.invalid.upassword'       : 'Invalid username or password',
    'myaccount'						: 'My Account',
    'loading'						: 'Loading ...',
    'common.modal.close'            : 'Close',
    'common.modal.ok'               : 'Ok',
    'common.modal.defaultHeader'    : 'Proceed?',
    'common.modal.defaultBody'      : 'Perform this action?',
    'common.modal.save' 			: 'Save',
    'common.modal.cancel' 			: 'Cancel',
    'common.modal.edit' 			: 'Edit',
    'common.modal.remove' 			: 'Remove',
    'common.modal.lock' 			: 'Lock',    
    'common.modal.unlock' 			: 'Unlock',
    
    'feed.form.name' 				: 'Name',
    'feed.form.url' 				: 'Url',
    'feed.form.tags' 				: 'Tags',
    'feed.form.addnewfeed' 			: 'Add new feed',
    'feed.form.editfeed'			: 'Edit feed',
    'feed.form.showfeed' 			: 'Feed',    
    'feed.form.removeFeed' 			: 'Remove Feed?',
    
    'user.form.addnew' 			: 'Add new User',
    'user.form.edit'			: 'Edit User',
    'user.form.show' 			: 'User',    
    'user.form.remove' 			: 'Remove User?',
    'user.form.lock'			: 'Lock User?',
    'user.form.unlock'			: 'Unlock User?',
    'tag.form.lock'				: 'Lock Tag?',
    'tag.form.addnew'                           : 'Add new Tag',
    'tag.form.unlock'			: 'Unlock Tag?'



})

.factory('L', function(text){
    return function(key){
            if (text[key]) {
                return text[key];
            } else {
            	return key;
            }
        }
    
})