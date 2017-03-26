/**
 * Created by kunle on 3/25/17.
 */

'use strict';

// Declare app level module which depends on views, and components
var app=angular.module('myApp', [
    'angular-loading-bar','App.Services',
    'ui.router','ngSanitize',
    'ngAnimate','ngStorage',
    'ui.tinymce','ngFileUpload','toaster','ui.utils.masks'
]).config(['$urlRouterProvider','$stateProvider','$locationProvider','$qProvider',
    function($urlRouterProvider,$stateProvider,$locationProvider,$qProvider) {

        $stateProvider.state({
            name:'auth',
            controller: 'AuthController',
            url:'/auth',
            templateUrl:'./angular/views/auth/index.html'
        });
        $stateProvider.state({
            name:'mfp',
            abstract: true,
            controller: 'AppController',
            url:'/mfp',
            templateUrl:'./views/base.html',
            resolve:{
                user:['authService','$q',function(authService,$q){
                    var authorizedUser=authService.getUser();
                    return authorizedUser || $q.reject({unAuthorized:true});
                }]
            }
        });
        $stateProvider.state({
            name:'dashboard',
            url:'/dashboard',
            templateUrl:'./angular/views/dashboard/index.html',
            controller:'DashboardController',
            resolve: {
                dashboard: function ($state,$q) {
                    return {};
                    /*return DashboardService.getDashboard().then(
                        function (success) {
                            return success.data.data.dashboard;
                        },function (error) {
                            return $q.reject(error.data);
                        }
                    );*/
                }
            }
        });
        $urlRouterProvider.otherwise(function ($injector) {
            var $state = $injector.get('$state');
            $state.go('auth');
        });

}]);

app.controller('AppController',['$rootScope', '$scope', '$state', '$window', '$timeout','cfpLoadingBar','authService','MfpApi','$interval','toaster',
    function($rootScope, $scope, $state, $window, $timeout,cfpLoadingBar,authService,MfpApi,$interval,toaster) {
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
        $scope.notifyAlert= function(type,data){
            toaster.pop({
                type: type,
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