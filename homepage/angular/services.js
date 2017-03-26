/**
 * Created by DATA INFOSEC on 1/19/2017.
 */
'use strict';
angular.module('App.Services',['ngFileUpload'
])
    .service('AppApi',['$http','Upload','$localStorage',function ($http,Upload,$localStorage) {
    /*API Details*/
    var apiInfo={
        baseUrl:"http://api.recodenigeria.tk/api/v1/",
        clientId:"web-007",
        clientSecret:"938CAAB3E20C9F7B7A4E744DEA51845F98595A349BAF855562E1AA2D96C9C56B"
    };
    /*Make a GET request to API*/
    this.get=function (endpoint,data) {
        console.log('making a GET request');
        return $http({
            url:apiInfo.baseUrl+endpoint,
            method: 'GET',
            params:data
        });
    };
    
    /*Make a post request to the api*/
    this.post=function (endpoint,data) {
        console.log('Making a post request');
        return $http({
            url:apiInfo.baseUrl+endpoint,
            method: 'POST',
            params:data
        });
    };
    
    /*Upload a file to the API*/
    this.uploadFile=function (endpoint,data) {
        console.log('uploading file');
        console.log(data);
        return Upload.upload({
            url: apiInfo.baseUrl+endpoint,
            data: data,
            method: 'POST'
        });
    };

    this.paginate=function (url,data) {
        console.log('making paginate request');
        return $http({
            url:url,
            method: 'GET',
            headers: {
                authorization: this.authData,
                'Access-Token': $localStorage.mfp_user.auth.access_token
            },
            params:data
        });
    };
    
}])
    .service('authService',['$localStorage','AppApi','$state',function ($localStorage,AppApi,$state) {

        /*Error Service*/
        this.catchError=
        function errorRedirect (error,$q) {
            return $q.reject({unAuthorized:true});
        };

        this.saveUser=function (user) {
            /*Save the User data*/
            this.user=user;
            /*Save the user auth information*/
            this.userAuth=user.auth;
            $localStorage.mfp_user=user;
        };

        /*Get the user details*/

        this.getUser=function(){
            return $localStorage.mfp_user;
        };
        this.RefreshToken=function () {
            var data={
                refresh_key:this.userAuth.refresh_key
            };
            console.log(data);
            AppApi.get('token/refresh',this.addAuth(data)).then(function (success) {
                console.log(success.data.data.Token);
                $localStorage.mfp_user.auth=success.data.data.Token;
                //this.updateAuth(success.data.data.Token);
                /*Update Auth Data*/
            },function (error) {
               console.log(error);
                /*Something went wrong*/
                $state.go('auth');
            });
        };
        this.userData=$localStorage.mfp_user;
        /*Get UserAuth details*/
        this.userAuth=function () {
            return $localStorage.mfp_user.auth;
        };
        /*this.updateAuth=function (data) {

        };*/

        this.addAuth=function (input) {
            var data=this.userAuth.access_token;
            //console.log('Adding access token to api call');
            input.access_token=$localStorage.mfp_user.auth.access_token;
            //console.log(input);
            return input;
        };

        /*Login call into the api*/
        this.login=function (userInfo) {
            delete $localStorage.mfp_user;
            this.user=false;
            this.userAuth=false;
            /*Consulting API Service to make request*/
            return AppApi.post('customer/login',userInfo);
        };
        /*Login call into the api*/
        this.register=function (userInfo) {
            delete $localStorage.mfp_user;
            this.user=false;
            this.userAuth=false;
            /*Consulting API Service to make request*/
            return AppApi.post('customer/register',userInfo);
        };
        this.logout=function () {
            delete $localStorage.mfp_user;
            this.user=false;
            this.userAuth=false;
        }
    }])
    .service('DashboardService',['$localStorage','AppApi','$state','authService',function ($localStorage,AppApi,$state,authService) {
        /*Delete card*/
        this.deleteCard=function (id) {
            /*Consulting API Service to make request*/
            return AppApi.get('card/'+id+'/delete',authService.addAuth({}));
        };

        this.setKYC=function (data) {
            /*Consulting API Service to make request*/
            return AppApi.post('kyc/set',authService.addAuth(data));
        };
    }]).filter('mydate', function($filter){
    return function(input)
    {
        if(input == null){ return ""; }

        var _date = $filter('date')(new Date(input), 'h:m a, MMM dd');
        return _date;
        //return _date.toUpperCase();

    };
});
;