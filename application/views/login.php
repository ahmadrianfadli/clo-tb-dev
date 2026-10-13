	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo base_url(); ?>asset/image/favicon.ico" type="image/gif">
	<link href="<?php echo base_url(); ?>asset/bootstrap/css/bootstrapce.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>asset/css/login.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>asset/font-awesome/css/font-awesome.min.css" rel="stylesheet" />

	<script src="<?php echo base_url(); ?>asset/js/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>asset/js/bootbox.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>asset/bootstrap/js/bootstrap.js"></script>
	<script src="<?php echo base_url(); ?>asset/js/fungsi_login.js?v=<?= time() ?>" type="text/javascript"></script>
	</head>

	<body>
	    <script type="text/javascript">
	        var base_url = "<?php echo base_url() ?>";
	        $.ajaxSetup({
	            cache: false
	        });
	        $(document).ready(function() {
	            resize();
	            //alamat();
	            $(window).resize(function() {
	                resize();
	            });
	        });
	    </script>
	    <header class="page-row">
	        <!--<div class="container-fluid" id="header_menu">
		<div id="logo"></div>
		<div id="judul">
			<?php echo $title ?>
		</div>
	</div>-->
	    </header>
	    <main class="page-row page-row-expanded">
	        <div id="body_login">
	            <div class="login">
	                <div class="col-xs-3" id="login_form">
	                    <div class="form-group  form-group-sm">
	                        <label for="username">Username</label>
	                        <input type="text" class="form-control" required="required" id="username" placeholder="Enter Username" />
	                    </div>
	                    <!-- TODO ESIGN -->
	                    <div class="form-group  form-group-sm">
	                        <label for="password">Password</label>
	                        <div class="input-group">
	                            <input type="password" class="form-control" required="required" id="password" placeholder="Password">
	                            <span class="input-group-btn">
	                                <button class="btn btn-default btn-sm" type="button" data-target="password" onclick="changePasswordVisibility(event, this)"><i class="fa fa-eye"></i></button>
	                            </span>
	                        </div><!-- /input-group -->
	                    </div>
	                    <div class="form-group  form-group-sm">
	                        <label for="passphare">Passphrase</label>
	                        <div class="input-group">
	                            <input type="password" class="form-control" required="required" id="passphrase" placeholder="Passphrase">
	                            <span class="input-group-btn">
	                                <button class="btn btn-default btn-sm" type="button" data-target="passphrase" onclick="changePasswordVisibility(event, this)"><i class="fa fa-eye"></i></button>
	                            </span>
	                        </div><!-- /input-group -->
	                    </div>
	                    <form role="form" id='form_log'>
	                        <input type="hidden" id="hiddenusername" />
	                        <input type="hidden" id="hiddenpassword" />
	                        <input type="hidden" id="hiddenpassphrase" /><!-- TODO ESIGN -->
	                        <button type="button" id='btnMasuk' class="btn btn-info btn-sm">Masuk</button>
	                        <button type="reset" class="btn btn-danger btn-sm">Batal</button>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </main>
	    <footer class="page-row">
	        <!--<div class="footer1">
		<div id="alamat"></div>
	</div>-->
	    </footer>
	    <script>
	        // TODO ESIGN
	        function changePasswordVisibility(e, elem) {
	            e.preventDefault();

	            let $btn = $(elem),
	                target = $btn.attr('data-target'),
	                targetElem = $('#' + target).get(0);

	            if ($(targetElem).is('[type="password"]')) {
	                targetElem.type = 'text';
	                $btn.html('<i class="fa fa-eye-slash"></i>');
	            } else {
	                targetElem.type = 'password';
	                $btn.html('<i class="fa fa-eye"></i>');
	            }
	        }
	    </script>
	</body>

	</html>