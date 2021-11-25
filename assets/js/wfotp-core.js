(function ($) {
	"use strict";
	jQuery(document).ready(function ($) {
		init();
		function init() {
			var otpWrapper = document.querySelector(".rq-otp-phone-wrapper");
			// check if shortcode is available on the page
			if (otpWrapper) {
				showPhoneLoginModal();
				intlPhoneInitialize();
				inputLetteringInitialize();
				firebaseInitialize();
				firebaseOtpLogin();
				firebaseOtpVerification();
			}
		}

		/**
		 * show rq otp modal on phone btn click
		 */
		function showPhoneLoginModal() {
			$(".rq-otp-phone-button-toggle").click(function (e) {
				var phoneButtonId = e.target.id;
				var uniqueId = phoneButtonId.replace("rq-otp-phone-button-toggle-", "");

				// get rq modal state from local storage
				var rqOtpModalState = localStorage.getItem(
					"rq-otp-modal-state-" + uniqueId
				);

				// remove hide class and add show class on phone number block
				if (rqOtpModalState !== "rq-otp-code-" + uniqueId) {
					$("#rq-otp-phone-wrapper-" + uniqueId).removeClass("rq-hide");
					$("#rq-otp-phone-wrapper-" + uniqueId).addClass("rq-show");

					$("#rq-otp-code-" + uniqueId).addClass("rq-hide");
					$("#rq-otp-code-" + uniqueId).removeClass("rq-show");
				} else {
					$("#rq-otp-phone-wrapper-" + uniqueId).addClass("rq-hide");
					$("#rq-otp-phone-wrapper-" + uniqueId).removeClass("rq-show");

					$("#rq-otp-code-" + uniqueId).removeClass("rq-hide");
					$("#rq-otp-code-" + uniqueId).addClass("rq-show");
				}

				// set otp modal state in local storage
				$("#rq-otp-modal-" + uniqueId).modal({
					fadeDuration: 200,
					fadeDelay: 0.1,
				});
			});
		}

		/**
		 * Initialize Intl Phone library input
		 * by default ip look up enabled to show country flag
		 */
		function intlPhoneInitialize() {
			window.intTelData = {};
			$(".rq-otp-phone-input").each(function (index, element) {
				var elementId = element.id;
				var uniqueId = elementId.replace("rq-otp-phone-input-", "");
				// initialize plugin
				if (elementId) {
					var elmentSelector = document.querySelector("#" + elementId);
					intTelData[uniqueId] = window.intlTelInput(elmentSelector, {
						initialCountry: "auto",
						geoIpLookup: function (callback) {
							$.get("https://ipinfo.io", function () {}, "jsonp").always(
								function (resp) {
									var countryCode = resp && resp.country ? resp.country : "";
									callback(countryCode);
								}
							);
						},
						utilsScript: `${WFOTP_DATA.library_path}intl-tel-input/js/utils.js`,
					});
				}
			});
		}

		/**
		 * Initialize input lettering
		 */
		function inputLetteringInitialize() {
			$(".rq-otp-phone-code-input").each(function (index, element) {
				var elementId = element.id;
				$("#" + elementId).letteringInput({
					inputClass: "letter",
					hiddenInputWrapperID:
						"rq-otp-verify-section-" +
						elementId.replace("rq-otp-phone-code-input-", ""),
					hiddenInputName: "rq-otp-verify-phone",
				});
			});
		}

		/**
		 * Initialize Firebase
		 * @param {*} params - params
		 */
		function firebaseInitialize(params) {
			const {
				api_key,
				auth_domain,
				database_url,
				project_id,
				storage_bucket,
				messaging_sender_id,
				app_id,
			} = WFOTP_DATA.settings;

			// Your web app's Firebase configuration
			var firebaseConfig = {
				apiKey: api_key,
				authDomain: auth_domain,
				databaseURL: database_url,
				projectId: project_id,
				storageBucket: storage_bucket,
				messagingSenderId: messaging_sender_id,
				appId: app_id,
			};
			// Initialize Firebase
			firebase.initializeApp(firebaseConfig);
			// Initialize Re captcha
			$(".rq-otp-recaptcha-section").each(function (index, element) {
				var elementId = element.id;
				var showCaptcha = $("#" + elementId).data("recaptcha");

				//get the unique id from the element
				var captchaElementId = elementId.replace(
					"rq-otp-recaptcha-section-",
					""
				);
				var recaptchaContainer =
					"rq-otp-recaptcha-container-" + captchaElementId;
				window.isRecaptchaSolved = showCaptcha === "invisible" ? true : false;
				window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(
					recaptchaContainer,
					{
						size: showCaptcha || "normal",
						callback: function (response) {
							// reCAPTCHA solved, allow signInWithPhoneNumber.
							window.isRecaptchaSolved = true;
						},
						"expired-callback": function () {
							// Response expired. Ask user to solve reCAPTCHA again.
							window.isRecaptchaSolved = false;
						},
					}
				);
				recaptchaVerifier.render().then(function (widgetId) {
					window.recaptchaWidgetId = widgetId;
				});
			});
		}

		/**
		 * Send OTP to the phone number
		 */
		function firebaseOtpLogin() {
			$(".firebase-otp-phone-button").click(function (e) {
				var elementId = e.target.id;
				var uniqueId = elementId.replace("firebase-otp-phone-button-", "");
				var phone_number = intTelData[uniqueId].getNumber();
				var phoneNumber = phone_number;
				var appVerifier = window.recaptchaVerifier;

				if (phoneNumber && isRecaptchaSolved) {
					window.isRecaptchaSolved = false;
					// start loader
					$("#firebase-otp-phone-button-" + uniqueId).addClass("has-spinner");
					firebase
						.auth()
						.signInWithPhoneNumber(phoneNumber, appVerifier)
						.then(function (confirmationResult) {
							// SMS sent. Prompt user to type the code from the message, then sign the
							// user in with confirmationResult.confirm(code).
							// end loader
							$("#firebase-otp-phone-button-" + uniqueId).removeClass(
								"has-spinner"
							);

							window.confirmationResult = confirmationResult;

							// update otp modal state in local storage
							localStorage.setItem(
								"rq-otp-modal-state-" + uniqueId,
								"rq-otp-code-" + uniqueId
							);

							$("#rq-otp-phone-wrapper-" + uniqueId).addClass("rq-hide");
							$("#rq-otp-phone-wrapper-" + uniqueId).removeClass("rq-show");

							$("#rq-otp-code-" + uniqueId).removeClass("rq-hide");
							$("#rq-otp-code-" + uniqueId).addClass("rq-show");
						})
						.catch(function (error) {
							// Error; SMS not sent
							$("#rq-otp-error-" + uniqueId + " div").html("");
							$("#rq-otp-error-" + uniqueId).text(
								WFOTP_DATA.settings.phone_error_translation ||
									"Invalid phone number"
							);
							// end loader
							$("#firebase-otp-phone-button-" + uniqueId).removeClass(
								"has-spinner"
							);

							// reset the captcha
							window.recaptchaVerifier.render().then(function (widgetId) {
								grecaptcha.reset(widgetId);
							});
						});
				}
			});
		}

		/**
		 * Confirm OTP & Login
		 */
		function firebaseOtpVerification() {
			$(".rq-otp-verify-button").click(function (e) {
				var elementId = e.target.id;
				var uniqueId = elementId.replace("rq-otp-verify-button-", "");
				var code = $("#rq-otp-firebase-verify-phone-" + uniqueId).val();
				if (code) {
					// start loader
					$("#rq-otp-verify-button-" + uniqueId).addClass("has-spinner");
					window.confirmationResult
						.confirm(code)
						.then(function (result) {
							// User signed in successfully.
							var user = result.user;
							var phoneNumber = result.user.phoneNumber;
							var uid = result.user.uid;
							// ajax login
							doLoginRegister(phoneNumber, uid, uniqueId);
						})
						.catch(function (error) {
							// User couldn't sign in (bad verification code?)
							$("#rq-otp-modal-error-" + uniqueId + " div").html("");
							$("#rq-otp-modal-error-" + uniqueId).text(
								WFOTP_DATA.settings.otp_code_error_translation || "Invalid code"
							);
							// end loader
							$("#rq-otp-verify-button-" + uniqueId).removeClass("has-spinner");
						});
				}
			});
		}

		/**
		 * Login Or Register
		 * @param {string} phoneNumber - phone number
		 * @param {string} uid - firebase user id
		 * @param {string} uniqueId - dom unique id
		 */
		function doLoginRegister(phoneNumber, uid, uniqueId) {
			// start loader
			$("#rq-otp-verify-button-" + uniqueId).addClass("has-spinner");

			// define function here
			$.ajax({
				type: "post",
				dataType: "json",
				url: WFOTP_DATA.ajaxUrl,
				data: {
					action: WFOTP_DATA.action,
					action_type: "firebase_otp_login",
					nonce: WFOTP_DATA.nonce,
					phone_number: phoneNumber,
					uid,
				},
				success: function (response) {
					if (response.status === 200) {
						// delete otp modal state in local storage
						localStorage.removeItem("rq-otp-modal-state-" + uniqueId);

						// end loader
						$("#rq-otp-verify-button-" + uniqueId).removeClass("has-spinner");

						// Success message
						$("#rq-otp-modal-success-" + uniqueId).addClass("rq-otp-success");
						// reload page after login
						window.location.reload();
					}
				},
				error: function (request, status, error) {
					// error
					$("#rq-otp-modal-error-" + uniqueId + " div").html("");
					$("#rq-otp-modal-error-" + uniqueId).text(
						WFOTP_DATA.settings.login_failed_text ||
							"Login failed, please try again!"
					);
					// end loader
					$("#rq-otp-verify-button-" + uniqueId).removeClass("has-spinner");
				},
			});
		}
	});
})(jQuery);
