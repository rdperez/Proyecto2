<!DOCTYPE html>
<html lang="en">
<head>
	<title>Color Admin | Login Page</title>
    <?= $this->include('partials/head') ?>
</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show">
		<div class="material-loader">
			<svg class="circular" viewBox="25 25 50 50">
				<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
			</svg>
			<div class="message">Loading...</div>
		</div>
	</div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade">
		<!-- begin login -->
		<div class="login login-v1">
			<!-- begin login-container -->
			<div class="login-container">
				<!-- begin login-header -->
				<div class="login-header">
					<div class="brand">
						<span class="logo"></span> <b>Admin</b>
						<!-- <span class="logo"></span> <b>Color</b> Admin -->
						<!-- <small>responsive bootstrap 4 admin template</small> -->
					</div>

					<div class="icon"><i class="fa fa-lock"></i></div>
				</div>
				<!-- end login-header -->

				<!-- begin login-body -->
				<div class="login-body">
					<!-- begin login-content -->
					<div class="login-content">
						<form id="formSession">
							<div class="form-group m-b-20">
								<input type="text" name="username" class="form-control form-control-lg inverse-mode" placeholder="Email" required />
							</div>
							<div class="form-group m-b-20">
								<input type="password" name="password" class="form-control form-control-lg inverse-mode" placeholder="Password" required />
							</div>
							
							<div class="login-buttons">
								<button type="submit" class="btn btn-aqua btn-block btn-lg">Inicio Sesión</button>
							</div>
						</form>
					</div>
					<!-- end login-content -->
				</div>
				<!-- end login-body -->
			</div>
			<!-- end login-container -->
		</div>
		<!-- end login -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
    <?= $this->include('partials/scripts') ?>
	<!-- ================== END BASE JS ================== -->
</body>
</html>