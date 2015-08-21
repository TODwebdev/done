var App = angular.module("done", ["ui.sortable", "LocalStorageModule"]);

App.run(function ($rootScope, $http) {

    $rootScope.username = angular.isDefined(username) ? username : 'Guest';
    $rootScope.currLang = angular.isDefined(currLang) ? currLang : 'EN';
    $rootScope.trans = JSON.parse(trans); console.log($rootScope.trans);
    $rootScope.categories = JSON.parse(cats); console.log($rootScope.categories);
    $rootScope.isCollapsed = 0;



    /* 
    * Залогинен-ли текущий пользователь
    **/
    $rootScope.isLoggedIn = function () {
        return true;
    };

    $rootScope.setLang = function (lang) {
        if (lang !== 'RU') {
            lang = 'EN';
        }
        $rootScope.currLang = lang;
    };

    $rootScope.tr = function (term) {
        if ($rootScope.trans[term]) {
            return $rootScope.trans[term][$rootScope.currLang];
        } else {
            return term;
        }

    };

    $rootScope.getList = function (period) {
        if (!period) {
            period = 'all';
        }
        return $http.get('/index.php?action=getlist&period=' + period);
    };

});


/**
* запросить с сервера данные о текущих задачах
**/
App.factory('getList', function ($http) {

    var list = {};

    list.getClosest = function (period) {
        if (!period) {
            period = 'all';
        }
        return $http.get('/index.php?action=getlist&period=' + period);
    };

    return list;

});


/**
 * сохранить на сервере новую задачу
 **/
App.factory('addTask', function ($http) {

    var task = {};

    task.add = function (newTask) {
        if (!newTask) {
            return false;
        }
        return $http.post('/index.php?action=add', {'task': newTask});
    };

    return task;

});

App.controller('CollapseCtrl', function ($scope, $rootScope) {
    $scope.isCollapsed = $rootScope.isCollapsed;

    $scope.setLang = function (lang) {
        if (lang !== 'RU') {
            lang = 'EN';
        }
        $rootScope.currLang = lang;
    }
});


App.controller('IoCtrl', ['$scope', '$rootScope', 'getList', function ($scope, $rootScope, getList) {
   $scope.model = $rootScope.categories;
    $scope.currentShow = 'unsorted';
    $scope.changeCat = function(activeCat) {
        $scope.currentShow = activeCat;
        console.log(activeCat);
        getList.getClosest().success(function (data) {
            $scope.model[$scope.currentShow].list = data;
        });
    }
} ]);



App.controller("TodoCtrl", function ($scope, addTask, getList) {

    $scope.init = function () {

        //if (!localStorageService.get("todoList")) {
            $scope.model = [
				{
				    name: "Олег", list: []
				},
				{
				    name: "Ирина", list: []
				}
			];
        getList.getClosest().success(function (data) {
            $scope.model[$scope.currentShow].list = data;
        });
        //} else {
        //    $scope.model = localStorageService.get("todoList");
        //}
        $scope.show = "Incomplete";
        $scope.currentShow = 0;
    };

    $scope.addTodo = function () {
        /*Should prepend to array*/
        var currTask = { header: $scope.newTodo };

        $scope.model[$scope.currentShow].list.splice(0, 0, currTask);
        /*Reset the Field*/
        $scope.newTodo = "";

        addTask.add(currTask).
            success(function(data) { console.log(data);
                $scope.model[$scope.currentShow].list = data;
            });
    };

    $scope.deleteTodo = function (index) {
        $scope.model[$scope.currentShow].list.splice(index, 1);
    };

    $scope.todoSortable = {
        containment: "parent", //Dont let the user drag outside the parent
        cursor: "move", //Change the cursor icon on drag
        tolerance: "pointer"//Read http://api.jqueryui.com/sortable/#option-tolerance
    };

    $scope.changeTodo = function (i) {
        $scope.currentShow = i;
    };

    /* Filter Function for All | Incomplete | Complete */
    $scope.showFn = function (todo) {
        if ($scope.show === "All") {
            return true;
        } else if (todo.isDone && $scope.show === "Complete") {
            return true;
        } else if (!todo.isDone && $scope.show === "Incomplete") {
            return true;
        } else {
            return false;
        }
    };

 /*   $scope.$watch("model", function (newVal, oldVal) {
        if (newVal !== null && angular.isDefined(newVal) && newVal !== oldVal) {
            localStorageService.add("todoList", angular.toJson(newVal));
        }
    }, true);*/

});