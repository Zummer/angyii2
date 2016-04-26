/**
 * Created by afanasev on 26.04.16.
 */

PostModule
    .factory('PostFactory', ['RestFactory', 'path',
        function (RestFactory, path) {
    return new RestFactory(path.toWebApi + '/posts');
}]);