var config = {};
config.filtersUrl = '/wp-json/util/tax/faq_tax';
config.dataUrl = '/wp-json/wp/v2/faq';

angular.module('app', [])
    .controller('AppController', ['$scope', 'dataService', function($scope, dataService) {

        $scope.results = [];

        dataService.getFilters().then(function(data) {
            $scope.filters = data;
        });

        dataService.getData().then(function(data) {
            $scope.results = data;
        });

        $scope.updateFilters = function(term_id) {
        	
        	//Let angular keep track of the checkbox status.
        	$scope["cb_" + term_id] = !$scope["cb_" + term_id];

            //First track if the main node is being added or removed.
            var indexOfFilter = $scope.filtersToApply.indexOf(term_id);
            var removing = false;

            if (indexOfFilter > -1) {

                removing = true;
                $scope.filtersToApply.splice(indexOfFilter, 1);

            } else {

                $scope.filtersToApply.push(term_id);

            }

            //then take care of the children nodes and either remove/add all.
            var has_children = $scope.filters.filter(function(current) {
                if (current.term_id == term_id && current.children) {
                    return true;
                }
            });

            if (has_children.length > 0) {

                for (child in has_children[0].children) {

                    $scope["cb_" + has_children[0].children[child].term_id] = !removing;

                    if (!removing) {

                        $scope.filtersToApply.push(has_children[0].children[child].term_id);

                    } else {

                        var indexOfFilter = $scope.filtersToApply.indexOf(has_children[0].children[child].term_id);
                        $scope.filtersToApply.splice(indexOfFilter, 1);

                    }

                }
            }
        }

        $scope.filtersToApply = [];

        $scope.displayContent = function(id) {
            $scope["display_content_" + id] = true;
        }

    }]).filter('filteredItems', function() {


        return function(items, scope) {

            var filtered = [];

            if (scope.filtersToApply.length > 0) {

                for (var i = 0; i < items.length; i++) {

                    var item = items[i];
                    var intersection = _.intersection(scope.filtersToApply, items[i]['faq_tax']);

                    if (intersection.length > 0) {
                        filtered.push(item);
                    }

                }

            } else {

                filtered = items;

            }

            return filtered;

        }

    }).service('dataService', function($http) {

        function getFilters() {
            return (baseService(config.filtersUrl));
        }

        function getData() {
            return (baseService(config.dataUrl));
        }

        function baseService(url) {
            return $http.get(url).then(
                function(response) {
                    return response.data;
                }
            );
        }

        return ({
            getData: getData,
            getFilters: getFilters
        });

    });
