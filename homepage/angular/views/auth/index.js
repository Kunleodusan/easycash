/**
 * Created by DATA INFOSEC on 1/19/2017.
 */

app.controller('AuthController',['$rootScope','$scope','$state','authService','$localStorage','toaster',function ($rootScope,$scope,$state,authService,$localStorage,toaster) {

    $scope.message='';

    $scope.login=true;
    $scope.register=false;

    $scope.LoginProcess= {
        process:{
            class:'btn btn-info btn-raised',
            name:'Logging In'
        },
        start:{
            class:'btn btn-primary btn-raised',
            name:'Login'
        },
        success:{
            class:'btn btn-primary btn-raised',
            name:'Login'
        },
        failed:{
            class:'btn btn-danger btn-raised',
            name:'Login'
        }
    };
    $scope.button=$scope.LoginProcess.start;

    $scope.toggleDiv=function (show) {
        if(show=='login'){
            $scope.login=true;
            $scope.register=false;
        }
        if(show=='register'){
            $scope.login=false;
            $scope.register=true;
        }
    };

    $scope.loginReq = function (userLogin) {
        $scope.notifyAlert('logging in');
        var user=userLogin;
        console.log(user);
        authService.login(user)
            .then(function (success) {
                console.log(success.data);
                /*Successful Login*/
                /*Add user details to Localstorage*/

                authService.saveUser(success.data.data);
                $scope.successAlert('Welcome '+success.data.data.customer.name);
                $state.go('dashboard');

            },function (error) {
                /*Failed login*/
                console.log(error);
                $scope.message=error.data.error;
                $scope.errorAlert(error.data.error);
                //$state.go('auth');
            });
    };

    $scope.registerReq = function (userRegister) {
        $scope.notifyAlert('Registering '+userRegister.name);
        var user=userRegister;
        console.log(user);
        authService.register(user)
            .then(function (success) {
                console.log(success.data);
                /*Successful Login*/
                /*Add user details to Localstorage*/

                authService.saveUser(success.data.data);
                $scope.successAlert('Welcome '+userRegister.name);
                $state.go('dashboard');
            },function (error) {
                /*Failed login*/
                console.log(error);
                $scope.message=error.data.error;
                $scope.errorAlert(error.data.error);
                //$state.go('auth');
            });
    };

    $scope.logout=function () {
        authService.logout();
        $state.go('auth');
    };

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