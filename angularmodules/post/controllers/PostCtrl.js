PostModule
    .constant('icons', [
        {value: '1', label: 'Черновик'},
        {value: '2', label: 'Публикация'}
    ])
    .controller('PostIndexCtrl', ['$scope', 'PostFactory', '$sce', '$rootScope',
        function ($scope, PostFactory, $sce, $rootScope) {

            PostFactory.getAll();

            $rootScope.$on('items:updated', function (event, args) {
                $scope.posts = args;
            });
        }])
    .controller('PostCreateCtrl', ['$scope', 'PostFactory', '$window', 'icons',
        function ($scope, PostFactory, $window, icons) {

            $scope.post = {};
            $scope.icons = icons;

            $scope.save = function (item) {
                PostFactory.save(item);
            };

            $scope.cancel = function () {
                $window.history.back();
            };
        }])
    .controller('PostViewCtrl', ['$scope', 'PostFactory', '$rootScope', '$location', '$routeParams',
        function ($scope, PostFactory, $rootScope, $location, $routeParams) {

            $scope.post = {};

            PostFactory.getOne($routeParams);

            $rootScope.$on('item:updated', function (event, args) {
                $scope.post = args;
            });

            $scope.cancel = function () {
                $location.path('/posts');
            };
        }])
    .controller('PostEditCtrl', ['$scope', 'PostFactory', '$rootScope', '$location', 'icons', '$routeParams',
        function ($scope, PostFactory, $rootScope, $location, icons, $routeParams) {

            $scope.post = {};
            $scope.icons = icons;

            PostFactory.getOne($routeParams);

            $rootScope.$on('item:updated', function (event, args) {
                $scope.post = args;
            });

            $scope.save = function (item) {
                PostFactory.save(item);
            };

            $scope.cancel = function () {
                $location.path('/post/' + $scope.post.id);
            };
        }])
    .controller('PostDeleteCtrl', ['$scope', 'PostFactory', '$rootScope', '$location', '$routeParams',
        function ($scope, PostFactory, $rootScope, $location, $routeParams) {

            $scope.post = {};

            PostFactory.getOne($routeParams);

            $rootScope.$on('item:updated', function (event, args) {
                $scope.post = args;
            });

            $scope.delete = function (item) {
                PostFactory.delete(item);
            };

            $scope.cancel = function () {
                $location.path('/post/' + $scope.post.id);
            };
        }]);