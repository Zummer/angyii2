/**
 * Created by afanasev on 14.04.16.
 */

PostModule.factory('RestFactory', ['$resource', '$rootScope', 'toaster', '$window',
    function ($resource, $rootScope, toaster, $window) {

        return function (path) {
           // expand при наличии превратиться в ?expand=
            var itemsResource = $resource(path + '/:id', {id: '@id', expand: '@expand'}, {
                put: {
                    method: 'PUT'
                }
            });

            this.getAll = function () {
                itemsResource.query().$promise.then(fulfilled, rejected);

                function fulfilled(response) {
                    //toaster.pop('success', "Успешный запрос!");
                    $rootScope.$broadcast('items:updated', response);
                }

                function rejected(error) {
                    console.log(error);
                }
            };
            this.getOne = function (routeParams) {
                itemsResource.get(routeParams).$promise.then(fulfilled, rejected);
                // но, можно вот так { id: routeParams.id, expand: routeParams.expand }

                function fulfilled(response) {
                    //toaster.pop('success', "post.model");
                    response.status = response.status.toString();
                    $rootScope.$broadcast('item:updated', response);
                }

                function rejected(error) {
                    toaster.clear();
                    toaster.pop('error', "status: " + error.data.status + " " + error.data.name, error.data.message);
                    $window.setTimeout(function () {
                        $window.location = '/posts';
                    }, 1000);
                    console.log(error);
                }
            };
            this.save = function (item) {
                if (undefined !== item.id && parseInt(item.id) > 0) {
                    this.update(item);
                }
                else {
                    this.add(item);
                }
            };
            this.update = function (item) {
                itemsResource.put(item).$promise.then(fulfilled, rejected);

                function fulfilled(response) {
                    //toaster.pop('success', "post.update");
                    //$rootScope.$broadcast('item:updated');
                    $window.location = '/post/' + response.id;
                }

                function rejected(error) {
                    //$rootScope.$broadcast('item:error', error);
                    console.log(error);
                }
            };
            this.add = function (item) {
                itemsResource.save(item).$promise.then(fulfilled, rejected);

                function fulfilled(response) {
                    toaster.pop('success', "Создали!");
                    $window.setTimeout(function () {
                        $window.location = '/post/' + response.id;
                    }, 1000);
                }

                function rejected(data) {
                    toaster.clear();
                    if (data.status == undefined) {
                        angular.forEach(data, function (error) {
                            toaster.pop('error', "Field: " + error.field, error.message);
                        });
                    } else {
                        toaster.pop('error', "status: " + data.status + " " + data.name, data.message);
                    }
                    console.log(data);
                }

            };
            this.delete = function (item) {
                itemsResource.delete(item).$promise.then(fulfilled, rejected);

                function fulfilled(response) {
                    $rootScope.$broadcast('item:deleted');
                    toaster.pop('success', "post.delete");

                    $window.setTimeout(function () {
                        $window.location = '/posts';
                    }, 1000);
                }

                function rejected(data) {
                    toaster.clear();
                    toaster.pop('error', "status: " + data.status + " " + data.data.name, data.data.message);
                    //$rootScope.$broadcast('item:error', error);
                    console.log(data);
                }
            };
        }
    }]);