var phoneAppModule = angular.module('phoneApp', []);

phoneAppModule.run(function($rootScope){
    $rootScope.modelInRoot = 'TrintaPorUmaLinha';
});

phoneAppModule.controller('PhoneListCtrl', function( $scope, $timeout ){
    $scope.phoneFilter = 'Moooo';

    $scope.phones = [
        {name:'Daniel', number: 03939393939},
        {name:'Maria', number: 245843646},
        {name:'Manel', number: 5348953489}
    ];

    $scope.orderProp = 'name';

    $timeout(function(){
        $scope.orderProp = 'number';
    }, 3000);
});

phoneAppModule.controller('ChildController', function($scope){
    $scope.modelCloneOfParent = $scope.phoneFilter;
});

phoneAppModule.directive('helloWorld', function(){
    return {
        replace: true,
        template: '<a href="#">Click Me</a>',
        link: function(scope, elem, attrs){
            elem.bind('click', function() {
                scope.$apply(function() {
                    scope.phoneFilter = "Dani";
                });
            });
        }
    };
});