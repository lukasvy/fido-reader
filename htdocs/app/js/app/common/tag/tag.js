angular.module('common.tag',[])

.controller('TagCtrl', function($scope){
  $scope.groups = [];
  
  $scope.onChange = function ( data ) {
    //console.log('controller on change', data);
  };
})

.directive('tagInput', function(){
  return {
    restrict: 'E',
    template: '<input type="hidden" style="width:300px" placeholder="add tags ...">',
    replace: true,
    require: '?ngModel',
    controller : 'TagCtrl',
    link: function ( scope, element, attrs, ngModel ){
     
      var drivenByModel = false;
      
      $(element).select2({
        tags: [],
        tokenSeparators: [",", " "],
        formatNoMatches: function(){ return '';} 
      }).on('change', function(e){
          if (!drivenByModel) { 
            ngModel.$setViewValue(e.val);
            scope.$apply();
          } else {
          }
         drivenByModel = false;
       });
      
      
      ngModel.$render = function(){
        drivenByModel = true;
        var data = ngModel.$viewValue;
        $(element).val(data).trigger('change');
      };
    }
  };
});