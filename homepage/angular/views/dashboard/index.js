/**
 * Created by DATA INFOSEC on 1/19/2017.
 */

app.controller('DashboardController',['$rootScope','$scope','$state','authService','DashboardService','$localStorage','toaster','dashboard',function ($rootScope,$scope,$state,authService,DashboardService,$localStorage,toaster,dashboard) {

    $scope.dashboard=authService.getUser();

    $scope.login=true;
    $scope.register=false;

    $scope.nav='one-page-menu';

    $scope.toggleNav=function () {
        if($scope.nav=='one-page-menu'){
            console.log($scope.nav);
            $scope.nav='one-page-menu show';
        }
        //if($scope.nav=='one-page-menu show'){
        else{
            console.log($scope.nav);
            $scope.nav='one-page-menu';
        }
    };

    $scope.deleteCard=function ($card) {
      $scope.notifyAlert('deleting card');
        DashboardService.deleteCard($card.id).then(function (success) {

            $scope.errorAlert('card deleted');
            $scope.removeItem($scope.dashboard.cards,$card);
        },function (error) {

            $scope.notifyAlert('something went wrong');

        });
    };

    $scope.logout=function () {
        authService.logout();
        $state.go('auth');
    };

    /*Helper functions*/
    $scope.successAlert= function(data){
        toaster.pop({
            type: 'success',
            body: data,
            timeout: 3000
        });
    };

    $scope.notifyAlert= function(data){
        toaster.pop({
            type: "info",
            body: data,
            timeout: 3000
        });
    };

    $scope.removeItem=function(array,item) {
        var index = array.indexOf(item);
        array.splice(index,1);
    };

    $scope.errorAlert= function(data){
        toaster.pop({
            type: 'error',
            body: data,
            timeout: 3000
        });
    };
}]);