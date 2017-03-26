/**
 * Created by DATA INFOSEC on 1/19/2017.
 */

app.controller('DashboardController',['$rootScope','$scope','$state','authService','DashboardService','$localStorage','toaster','dashboard',function ($rootScope,$scope,$state,authService,DashboardService,$localStorage,toaster,dashboard) {

    $scope.dashboard=authService.getUser();

    $scope.login=true;
    $scope.task={};
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
    $scope.createTask=function () {
        var $pending=$scope.task;
        var $data={};
        console.log($pending);
        /*1. check if user selected card/entered card.*/
        if($scope.selectCard){
            $data.amount=$pending.amount;
            $data.cardid=$pending.cardid;
            $data.action='WITHDRAWAL';
            $data.customer_id=$scope.dashboard.customer.id;

        }
        else{
            $data.amount=$pending.amount;
            $data.cardno=$pending.cardno;
            $data.action='WITHDRAWAL';
            $data.customer_id=$scope.dashboard.customer.id;
        }

        /*2. check if user wants to save card*/
        if($pending.saveCard){
            $scope.notifyAlert('Saving card');
            var $cardData={
                customer_id:$scope.dashboard.customer.id,
                save:1,
                cardno:$pending.cardno
            };
            DashboardService.saveCard($cardData).then(function (success) {
                console.log(success);
                $scope.successAlert(success.data.data.message);

                $scope.dashboard.cards.push(success.data.data.card);
                //$scope.removeItem($scope.dashboard.pending,$pending);

            },function (error) {

                $scope.notifyAlert(error.data.error);
                $scope.notifyAlert('something went wrong');

            });
        }
        /*3. save card.*/

        /*4. Make request.*/
      $scope.notifyAlert('Creating transaction');

        DashboardService.createTransaction($data).then(function (success) {
            console.log(success);
            $scope.successAlert(success.data.data.message);
            $scope.dashboard.pending.push(success.data.data.task);
            //$scope.removeItem($scope.dashboard.pending,$pending);

        },function (error) {

            $scope.notifyAlert(error.data.error);
            $scope.notifyAlert('something went wrong');

        });
    };

    $scope.cancelTxn=function ($pending) {
      $scope.notifyAlert('Canceling transaction');
        DashboardService.cancelTransaction($pending.id).then(function (success) {

            $scope.errorAlert('Transaction Canceled');
            $scope.removeItem($scope.dashboard.pending,$pending);

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