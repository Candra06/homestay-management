<!DOCTYPE html>
<html lang="en">
	@include('template.head-frontend')

	<body class="main-body">

		<!-- Loader -->
		<div id="global-loader">
			<img src="{{ url('/') }}/assets/img/loader.svg" class="loader-img" alt="Loader">
		</div>
		<!-- /Loader -->

		<!-- Page -->
		<div class="page ">

			<!-- main-header opened -->
			<div class="main-header nav nav-item hor-header" style="padding-left: -1px!important;">
				<div class="container">
					<div class="main-header-left ">
						
						<a class="header-brand" href="{{url('/')}}">

							<img src="{{ url('/') }}/assets/img/brand/logo.png" class="desktop-logo">
							<img src="{{ url('/') }}/assets/img/brand/logo.png" class="desktop-logo-1">
						</a>

					</div><!-- search -->

				</div>
			</div>
			<!-- /main-header -->

			<!-- main-content opened -->
			<div class="main-content horizontal-content">
                @yield('main')
				<!-- container opened -->

			</div>
			<!-- Container closed -->

			<!-- Footer opened -->
			<div class="main-footer ht-40">
				<div class="container-fluid pd-t-0-f ht-100p">
					<span>Copyright © {{date('Y')}} <a href="{{url('/')}}">Sukalelang.id</a>. All rights reserved.</span>
				</div>
			</div>
			<!-- Footer closed -->

		</div>
		<!-- End Page -->
        @include('template.script-frontend')
	</body>
</html>