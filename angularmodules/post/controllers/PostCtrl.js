PostModule
    .constant('icons', [
        {value: '1', label: 'Черновик'},
        {value: '2', label: 'Публикация'}
    ])
    .constant('postPath', 'frontend/web/restposts')
    .controller('PostIndexCtrl', ['$scope', 'RestFactory', '$sce', '$rootScope', 'postPath',
        function ($scope, RestFactory, $sce, $rootScope, postPath) {

            new RestFactory(postPath).getAll();

            $rootScope.$on('items:updated', function (event, args) {
                $scope.posts = args;
            });
        }])
    .controller('PostCreateCtrl', ['$scope', 'RestFactory', 'postPath', '$window', 'icons',
        function ($scope, RestFactory, postPath, $window, icons) {

            $scope.post = {};
            $scope.icons = icons;

            $scope.save = function () {
                new RestFactory(postPath).save($scope.post);
            };

            $scope.cancel = function () {
                $window.history.back();
            };
        }])
    .controller('PostViewCtrl', ['$scope', 'RestFactory', '$rootScope', 'postPath', '$location', '$routeParams',
        function ($scope, RestFactory, $rootScope, postPath, $location, $routeParams) {

            $scope.post = {};

            new RestFactory(postPath).getOne($routeParams);

            $rootScope.$on('item:updated', function (event, args) {
                $scope.post = args;
            });

            $scope.cancel = function () {
                $location.path('/posts');
            };
        }])
    .controller('PostEditCtrl', ['$scope', 'RestFactory', '$rootScope', 'postPath', '$location', 'icons', '$routeParams',
        function ($scope, RestFactory, $rootScope, postPath, $location, icons, $routeParams) {

            $scope.post = {};
            $scope.icons = icons;

            var rest = new RestFactory(postPath);
            rest.getOne($routeParams);

            $rootScope.$on('item:updated', function (event, args) {
                $scope.post = args;
            });

            $scope.save = function () {
                rest.save($scope.post);
            };

            $scope.cancel = function () {
                $location.path('/post/' + $scope.post.id);
            };
        }])
    .controller('PostDeleteCtrl', ['$scope', 'RestFactory', '$rootScope', 'postPath', '$location', '$routeParams',
        function ($scope, RestFactory, $rootScope, postPath, $location, $routeParams) {

            $scope.post = {};

            var rest = new RestFactory(postPath);
            rest.getOne($routeParams);

            $rootScope.$on('item:updated', function (event, args) {
                $scope.post = args;
            });

            $scope.delete = function () {
                rest.delete($scope.post);
            };

            $scope.cancel = function () {
                $location.path('/post/' + $scope.post.id);
            };
        }]);