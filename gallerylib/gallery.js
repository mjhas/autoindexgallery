;;
(function(angular, $j) {
	var galleryApp = angular.module('autoindexgallery', [ 'ngAnimate',
			'ngTouch' ]);
	galleryApp
			.controller(
					'galleryController',
					function($scope) {
						$scope.pictures = [];
						$scope.links = angular.element(document).find('a');

						angular.forEach($scope.links, function(link) {
							if (/jpg|JPG|png|PNG|JPEG|jpeg|gif|GIF$/
									.test(link.href)) {
								$scope.pictures.push({
									src : link.href,
									desc : link.innerHTML
								});
								angular.element(link).addClass("hide");
							}
						});

						$scope.currentPicture = 0;
						$scope.pictureActive = function(index) {
							return $scope.currentPicture === index;
						};
						$scope.pictureActiveClass = function(index) {
							if ($scope.pictureActive()) {
								return 'active';
							} else {
								return '';
							}
						};
						$scope.prevPicture = function() {
							$scope.currentPicture = ($scope.currentPicture > 0) ? --$scope.currentPicture
									: $scope.pictures.length - 1;
							$scope.checkNextDisabled();
							$scope.checkPrevDisabled();
						};

						$scope.nextPicture = function() {
							$scope.currentPicture = ($scope.currentPicture < $scope.pictures.length - 1) ? ++$scope.currentPicture
									: 0;
							$scope.checkNextDisabled();
							$scope.checkPrevDisabled();
						};
						$scope.checkPrevDisabled = function() {
							if ($scope.currentPicture == 0) {
								$scope.prevDisabled = true;
							} else {
								$scope.prevDisabled = false;
							}
						};
						$scope.checkNextDisabled = function() {
							if ($scope.currentPicture == ($scope.pictures.length - 1)
									| $scope.pictures.length == 0) {
								$scope.nextDisabled = true;
							} else {
								$scope.nextDisabled = false;
							}
						};
						$scope.showPicture = function(index) {
							$scope.currentPicture = index;
						};
						$scope.checkPrevDisabled();
						$scope.checkNextDisabled();
					});
	galleryApp.directive("gallery", function() {
		return {
			restrict : "E",
			replace  : true,
			templateUrl : "/gallerylib/gallery.html",
		};
	});
})(angular, jQuery);

;
(function($j, undefined) {
	$j(document).ready(function() {
		$j('#list').next().addClass('dropdown-menu');

	});
}(jQuery));