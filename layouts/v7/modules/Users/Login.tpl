{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{* modules/Users/views/Login.php *}

{strip}
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

		/* Full screen overlay to override legacy framework wrappers */
		.login-page-root {
			position: fixed !important;
			top: 0 !important;
			left: 0 !important;
			right: 0 !important;
			bottom: 0 !important;
			width: 100vw !important;
			height: 100vh !important;
			z-index: 999999 !important;
			background: #f8fafc;
			display: flex !important;
			font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
			overflow: hidden !important;
			color: #0f172a;
		}

		.login-page-root * {
			box-sizing: border-box !important;
		}

		/* Left side backdrop */
		.login-left-backdrop {
			flex: 1;
			background: url('layouts/v7/resources/Images/yespl-bg.jpeg');
			position: relative;
			background-size: cover;
		}

		.login-left-backdrop::after {
			content: '';
			position: absolute;
			top: 0; left: 0; right: 0; bottom: 0;
			background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
			background-size: 28px 28px;
			opacity: 0.4;
		}

		/* Right side form card */
		.login-right-card {
			width: 100%;
			max-width: 440px;
			height: 69vh;
			background: #ffffff;
			display: flex;
			flex-direction: column;
			justify-content: center;
			padding: 48px 40px;
			margin-left: auto;
			border-left: 1px solid #e2e8f0;
			box-shadow: -10px 0 35px rgba(15, 23, 42, 0.05);
			position: absolute;
			right: 190px;
			top: 130px;
			z-index: 2;
		}

		.logo-wrapper {
			text-align: center;
			margin-bottom: 36px;
		}

		.user-logo {
			max-height: 100px;
			max-width: 220px;
			object-fit: contain;
			margin: 0 auto;
			display: block;
		}

		.failureMessage, .successMessage {
			display: block;
			text-align: center;
			padding: 10px 14px;
			border-radius: 8px;
			font-size: 13px;
			font-weight: 500;
			margin-bottom: 20px;
			line-height: 1.4;
		}

		.failureMessage {
			background-color: #fef2f2;
			border: 1px solid #fee2e2;
			color: #dc2626;
		}

		.successMessage {
			background-color: #f0fdf4;
			border: 1px solid #dcfce7;
			color: #16a34a;
		}

		.form-group-item {
			margin-bottom: 22px;
			text-align: left;
		}

		.form-group-item label {
			display: block;
			font-size: 13px;
			font-weight: 600;
			color: #334155;
			margin-bottom: 8px;
		}

		.form-group-item input, .form-group-item select {
			width: 100% !important;
			height: 44px !important;
			background-color: #ffffff !important;
			border: 1px solid #cbd5e1 !important;
			border-radius: 8px !important;
			padding: 8px 14px !important;
			font-size: 14px !important;
			color: #0f172a !important;
			outline: none !important;
			transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
			box-shadow: none !important;
		}

		.form-group-item input:focus, .form-group-item select:focus {
			border-color: #2563eb !important;
			box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
		}

		.form-group-item select {
			cursor: pointer;
			background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%252364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") !important;
			background-repeat: no-repeat !important;
			background-position: right 12px center !important;
			background-size: 16px !important;
			padding-right: 36px !important;
			appearance: none;
			-webkit-appearance: none;
		}

		.submit-btn {
			width: 100% !important;
			height: 44px !important;
			border: none !important;
			border-radius: 8px !important;
			font-size: 14px !important;
			font-weight: 600 !important;
			color: #ffffff !important;
			cursor: pointer !important;
			background-color: #2563eb !important;
			transition: background-color 0.15s ease, box-shadow 0.15s ease !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			margin-top: 8px !important;
		}

		.submit-btn:hover {
			background-color: #1d4ed8 !important;
			box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
		}

		.submit-btn:active {
			background-color: #1e40af !important;
		}

		.forgotPasswordLink {
			display: inline-block;
			margin-top: 16px;
			font-size: 13px;
			font-weight: 500;
			color: #2563eb !important;
			text-decoration: none !important;
			cursor: pointer;
			transition: color 0.15s ease;
		}

		.forgotPasswordLink:hover {
			color: #1d4ed8 !important;
			text-decoration: underline !important;
		}

		.hide {
			display: none !important;
		}

		@media (max-width: 640px) {
			.login-left-backdrop {
				display: none;
			}
			.login-right-card {
				max-width: 100%;
				border-left: none;
			}
		}
	</style>

	<div class="login-page-root">
		<!-- Left Backdrop Area -->
		<div class="login-left-backdrop"></div>

		<!-- Right Side Form Card -->
		<div class="login-right-card">
			<div class="logo-wrapper">
				<img class="img-responsive user-logo" src="public.php?type=logo&key=yes-removebg-preview.png" alt="Logo">
			</div>

			<div>
				<span class="{if !$ERROR}hide{/if} failureMessage" id="validationMessage">{$MESSAGE}</span>
				<span class="{if !$MAIL_STATUS}hide{/if} successMessage">{$MESSAGE}</span>
			</div>

			<div id="loginFormDiv">
				<form class="form-horizontal" method="POST" action="index.php">
					<input type="hidden" name="module" value="Users"/>
					<input type="hidden" name="action" value="Login"/>
					
					<div class="form-group-item">
						<label for="username">Username</label>
						<input id="username" type="text" name="username" placeholder="Username" autocomplete="username">
					</div>

					<div class="form-group-item">
						<label for="password">Password</label>
						<input id="password" type="password" name="password" placeholder="Password" autocomplete="current-password">
					</div>

					{assign var="CUSTOM_SKINS" value=Vtiger_Theme::getAllSkins()}
					{if !empty($CUSTOM_SKINS)}
					<div class="form-group-item">
						<label for="skin">Theme Skin</label>
						<select id="skin" name="skin">
							<option value="">Default Skin</option>
							{foreach item=CUSTOM_SKIN from=$CUSTOM_SKINS}
							<option value="{$CUSTOM_SKIN}">{$CUSTOM_SKIN}</option>
							{/foreach}
						</select>
					</div>
					{/if}

					<div class="form-group-item" style="margin-bottom: 0;">
						<button type="submit" class="submit-btn button buttonBlue">Sign in</button>
						<div style="text-align: center;">
							<a class="forgotPasswordLink">Forgot password?</a>
						</div>
					</div>
				</form>
			</div>

			<div id="forgotPasswordDiv" class="hide">
				<form class="form-horizontal" action="forgotPassword.php" method="POST">
					<div class="form-group-item">
						<label for="fusername">Username</label>
						<input id="fusername" type="text" name="username" placeholder="Username">
					</div>
					<div class="form-group-item">
						<label for="email">Email</label>
						<input id="email" type="email" name="emailId" placeholder="Email">
					</div>
					<div class="form-group-item" style="margin-bottom: 0;">
						<button type="submit" class="submit-btn button buttonBlue forgot-submit-btn">Reset Password</button>
						<div style="text-align: center; margin-top: 14px;">
							<a class="forgotPasswordLink">Back to Login</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
			jQuery(document).ready(function () {
				var validationMessage = jQuery('#validationMessage');
				var forgotPasswordDiv = jQuery('#forgotPasswordDiv');

				var loginFormDiv = jQuery('#loginFormDiv');
				loginFormDiv.find('#password').focus();

				loginFormDiv.find('a').click(function () {
					loginFormDiv.toggleClass('hide');
					forgotPasswordDiv.toggleClass('hide');
					validationMessage.addClass('hide');
				});

				forgotPasswordDiv.find('a').click(function () {
					loginFormDiv.toggleClass('hide');
					forgotPasswordDiv.toggleClass('hide');
					validationMessage.addClass('hide');
				});

				loginFormDiv.find('button').on('click', function () {
					var username = loginFormDiv.find('#username').val();
					var password = jQuery('#password').val();
					var result = true;
					var errorMessage = '';
					if (username === '') {
						errorMessage = 'Please enter valid username';
						result = false;
					} else if (password === '') {
						errorMessage = 'Please enter valid password';
						result = false;
					}
					if (errorMessage) {
						validationMessage.removeClass('hide').text(errorMessage);
					}
					return result;
				});

				forgotPasswordDiv.find('button').on('click', function () {
					var username = jQuery('#forgotPasswordDiv #fusername').val();
					var email = jQuery('#email').val();

					var email1 = email.replace(/^\s+/, '').replace(/\s+$/, '');
					var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/;
					var illegalChars = /[\(\)\<\>\,\;\:\\\"\[\]]/;

					var result = true;
					var errorMessage = '';
					if (username === '') {
						errorMessage = 'Please enter valid username';
						result = false;
					} else if (!emailFilter.test(email1) || email == '') {
						errorMessage = 'Please enter valid email address';
						result = false;
					} else if (email.match(illegalChars)) {
						errorMessage = 'The email address contains illegal characters.';
						result = false;
					}
					if (errorMessage) {
						validationMessage.removeClass('hide').text(errorMessage);
					}
					return result;
				});
				jQuery('input').blur(function (e) {
					var currentElement = jQuery(e.currentTarget);
					if (currentElement.val()) {
						currentElement.addClass('used');
					} else {
						currentElement.removeClass('used');
					}
				});

				var ripples = jQuery('.ripples');
				ripples.on('click.Ripples', function (e) {
					jQuery(e.currentTarget).addClass('is-active');
				});

				ripples.on('animationend webkitAnimationEnd mozAnimationEnd oanimationend MSAnimationEnd', function (e) {
					jQuery(e.currentTarget).removeClass('is-active');
				});
				loginFormDiv.find('#username').focus();

				var slider = jQuery('.bxslider').bxSlider({
					auto: true,
					pause: 4000,
					nextText: "",
					prevText: "",
					autoHover: true
				});
				jQuery('.bx-prev, .bx-next, .bx-pager-item').live('click',function(){ slider.startAuto(); });
				jQuery('.bx-wrapper .bx-viewport').css('background-color', 'transparent');
				jQuery('.bx-wrapper .bxslider li').css('text-align', 'left');
				jQuery('.bx-wrapper .bx-pager').css('bottom', '-40px');

				var params = {
					theme		: 'dark-thick',
					setHeight	: '100%',
					advanced	:	{
										autoExpandHorizontalScroll:true,
										setTop: 0
									}
				};
				jQuery('.scrollContainer').mCustomScrollbar(params);
			});
		</script>
		</div>
	{/strip}