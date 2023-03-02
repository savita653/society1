
function validateForm(form, options) {
	let defaults = {
		ignore: [],
		rules: {},
		errorElement: "div",
		errorClass: "invalid-feedback",
		// errorPlacement: function (error, element) {
		//     error.addClass('invalid-feedback');
		//     element.closest('.form-group').append(error);
		// },
		highlight: function(element, errorClass, validClass) {
			$(element).addClass("is-invalid");
			if ($(element).hasClass("select")) {
				$(element)
					.next()
					.addClass("border-danger");
			}
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).removeClass("is-invalid");
			if ($(element).hasClass("select")) {
				$(element)
					.next()
					.removeClass("border-danger");
			}
		},
		messages: {},
		errorPlacement: function(error, element) {
			if (element.hasClass("select")) {
				element = element.next();
			}
			if (element.hasClass("checkbox")) {
				element = element.parent();
			}
			if (element.hasClass("checkbox-custom")) {
				element = $(".checkbox-custom-error");
			}
			error.insertAfter(element);
		},
	};
	options = $.extend(defaults, options);
	return form.validate(options);
}

function readURL(input, el) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function(e) {
			el.attr("src", e.target.result);
		};

		reader.readAsDataURL(input.files[0]); // convert to base64 string
	}
}

function log(message) {
	let debug = true;
	if (debug) {
		console.log(message);
	}
}

function copyToClipboard(text) {
    var $temp = $("<input class='form-control copy-input'>");
    $("body").append($temp);
	try {
		$(".dynamic-content").append($temp);
	} catch(e) {
		console.warn(e);
	}
    $temp.val(text).select();
    document.execCommand("copy");
    $temp.remove();

	setAlert({
		code: "success",
		title: "Copied to Clipboard",
		// message: "Resource not found!",
	});
}


function isJson(str) {
	try {
		JSON.parse(str);
	} catch (e) {
		return false;
	}
	return true;
}

/**
 *
 * @param {json} url Url to get content from
 * @param {function} callback called callback function after ajax request complete.
 */
function getContent(options) {
	window.canBlock = true;
	let defaults = {
		beforeSend: function() {
			$(".dynamic-body").html("");
			setTimeout(function() {
				if (window.canBlock) {
					$(".modal-content").block({
						message:
							'<div class="spinner-border text-white" role="status"></div>',
						css: {
							backgroundColor: "transparent",
							border: "0",
						},
						overlayCSS: {
							opacity: 0.1,
						},
					});
				}
			}, 500);
		},
		complete: function() {
			setTimeout(function() {
				$(".modal")
					.find("form")
					.find("input[type='text']:first")
					.focus();
			}, 500);
			$(".modal-content").unblock();
		},
	};
	options = $.extend(defaults, options);
	hideMessage();
	$.ajax(options);
}

/**
 *
 * @param {string} url Url to get content from
 * @param {function} callback called callback function after ajax request complete.
 */
function submitForm(form, options) {
	if (form.length === 0) {
		return;
	}
	hideMessage();
	let defaults = {
		beforeSubmit: function() {
			submitLoader(options.submitBtn);
		},
		complete: function() {
			submitReset(options.submitBtn);
		},
		success: function(data) {
			setAlert(data);
			if (data.success) {
				updateDataTables();
				$("#dynamic-modal").modal("hide");
				try {
					options.successCallback(data);
				} catch(e) {}
			}
		},
		error: function(data) {
			if (data.status == 422) {
				try {
					let json = data.responseJSON;
					let errors = json.errors;
					displayErrors(errors, options.formValidator);
				} catch (e) {}
			} else if (data.status == 404) {
				let json = data.responseJSON;
				setAlert({
					code: "error",
					title: "Oops!",
					message: "Resource not found!",
				});

				console.warn(json.message);
			}  else if(data.status == 403) {
				let json = data.responseJSON;
				setAlert({
					code: "error",
					title: "Oops!",
					message: "Access Denied!",
				});

				console.warn(json.message);
			} else if(data.status == 500) {
				let json = data.responseJSON;
				setAlert({
					code: "error",
					title: "Oops!",
					message: "Something went wrong!",
				});

				console.warn(json.message);
			}
		},
	};
	options = $.extend(defaults, options);
	form.ajaxForm(options);
}

function submitAjax(options, extra) {
	let defaults = {
		beforeSend: function() {
			submitLoader(extra.submitBtn);
		},
		headers: {
			"X-CSRF-Token": window.Laravel.csrfToken,
		},
		complete: function() {
			submitReset(extra.submitBtn);
			if(extra.hasOwnProperty('complete')) {
				extra.complete();
			}
		},
		success: function(data) {
			setAlert(data);
			if (data.success) {
				updateDataTables();
			}
			if(extra.hasOwnProperty('success')) {
				extra.success(data);
			}
		},
		error: function(data) {
			if (data.status == 422) {
				setAlert({
					code: "error",
					title: "Oops!",
					message: "You have validation error.",
				});
				try {
					let json = data.responseJSON;
					let errors = json.errors;
					displayErrors(errors, options.formValidator);
				} catch (e) {}
			} else if (data.status == 404) {
				let json = data.responseJSON;
				setAlert({
					code: "error",
					title: "Oops!",
					message: "Resource not found!",
				});

				console.warn(json.message);
			} else {
				setAlert({
					code: "error",
					title: "Oops!",
					message: "Something went wrong!",
				});
			}
			if(extra.hasOwnProperty('error')) {
				extra.error(data);
			}
		},
	};
	options = $.extend(defaults, options);
	$.ajax(options);
}

function showMessage(msg, msgType) {
	let button = "";
	button +=
		'<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
	button += '<span aria-hidden="true">&times;</span>';
	button += "</button>";
	let message =
		"<div role='alert' class='text-center alert alert-dismissible alert-" +
		msgType +
		"'>" +
		msg +
		button +
		"</div>";
	$("#ajax-message")
		.html(message)
		.removeClass("hide")
		.addClass("show");
}

function initSelectTag() {
	$(".select-tags").each(function() {
		let obj = {
			dropdownAutoWidth: true,
			width: '100%',
			dropdownParent: $(this).parent()
		}

		if($(this).attr('data-nosearch') === "1") {
			obj['minimumResultsForSearch'] = -1;
		}
		if($(this).attr('data-tags') === "1") {
			obj['tags'] = true;
		}

		$(this).select2(obj);
	})
}

try {
	initSelectTag();
} catch(e) { console.warn("Safe Callback:", e); }
/**
 *
 * @param {type} type success|error|info|warning
 * @param {msg} msg Message
 * @param {Title} title Title
 */
function toast(type, msg, title, positionClass = 'toast-top-center') {
	toastr[type](msg, title, {
		closeButton: true,
		tapToDismiss: false,
		positionClass: positionClass,
		timeOut: 5000,
	});
}

function displayErrors(errors, formValidator) {
	for (let error in errors) {
		try {
			formValidator.showErrors({
				[error]: errors[error],
			});
		} catch (e) {
			console.error("Error: " + e);
		}
	}
}

function hideMessage() {
	$("#ajax-message")
		.addClass("hide")
		.removeClass("show");
}

function modalLoader() {
	$(".dynamic-body").html("<h1>Loading...</h1>");
}

function modalReset() {
	$(".dynamic-body").html("");
	$("#dynamic-modal").modal("hide");
}

function initDatatable() {
	if ($(".custom-row").find("select").length > 0) {
		$(".custom-row")
			.find("select")
			.selectpicker();
	}
}

function setAlert(data) {
	try {
		if (data.code) {
			if(data.hasOwnProperty('positionClass')) {
				toast(data.code, data.message, data.title, data.positionClass);
			} else {
				toast(data.code, data.message, data.title);
			}
		}
	} catch (e) {
		console.error("Toaster Error:", e);
	}
}

function setServerAlert() {
	let code = $("meta[name='code']").attr("content");
	if (code !== "") {
		let title = $("meta[name='title']").attr("content");
		let message = $("meta[name='message']").attr("content");
		toast(code, message, title);
	}
}

function submitLoader(ele = "") {
	if (ele === "") {
		$("*[type='submit']")
			.attr("disabled", true)
			.html("Loading...");
	} else {
		$(ele)
			.attr("disabled", true)
			.prepend("<i class='spinner-border spinner-border-sm'></i> ");
	}
}

function submitReset(ele, text) {
	$(ele)
		.find("i:first")
		.remove();
	$(ele)
		.removeAttr("disabled")
		.html(text);
}

function submitLoaderFull(ele = "") {
	if (ele === "") {
		$("*[type='submit']")
			.attr("disabled", true)
			.html("Loading...");
	} else {
		$(ele)
			.attr("disabled", true)
			.html("<i class='fa fa-spinner fa-spin'></i> ");
	}
}

function submitResetFull(el, text) {
	el.html(text);
	el.removeAttr("disabled");
}

function updateDataTables() {
	try {
		$.fn.dataTable.tables().forEach((element) => {
			return $(element).length > 0
				? $(element)
						.DataTable()
						.draw("page")
				: "";
			// if($(element).attr('id') != "chat-table") {
			// }
			// return;
		});
	} catch (e) {
		console.log("error", e);
	}
}

function logMessage(msg) {
	if (true) {
		console.warn(msg);
	}
}

function initTooltip() {
	$(".tooltip").remove();
	$("*[data-toggle='tooltip']").tooltip();
}

function sweetAlert(options) {
	let defaults = {
		title: "Are you sure?",
		text: "You won't be able to revert this!",
		icon: "warning",
		showCancelButton: true,
		// confirmButtonText: "Yes, delete it!",
		// customClass: {
		// 	confirmButton: "btn btn-primary",
		// 	cancelButton: "btn btn-outline-danger ml-1",
		// },
		// buttonsStyling: false,
	};
	options = $.extend(defaults, options);
	Swal.fire(options).then(function(result) {
		if (result.value) {
			try {
				extra.yes();
			} catch(e) {}
		} else if (result.dismiss === Swal.DismissReason.cancel) {
			// Close
		}
	});
}

function confirm(options, extra) {
	let defaults = {
		title: "Are you sure?",
		text: "You won't be able to revert this!",
		icon: "warning",
		showCancelButton: true,
		confirmButtonText: "Yes, delete it!",
		customClass: {
			confirmButton: "btn btn-primary",
			cancelButton: "btn btn-outline-danger ml-1",
		},
		buttonsStyling: false,
	};
	options = $.extend(defaults, options);
	Swal.fire(options).then(function(result) {
		if (result.value) {
			try {
				extra.yes();
			} catch(e) { console.error(e); }
		} else if (result.dismiss === Swal.DismissReason.cancel) {
			// Close
		}
	});
}

function url(location) {
	return window.Laravel.appUrl + "/" + location;
}

$(".confirm-redirect").click(function() {
	let href = $(this).data('href');

	confirm({
		text: "Are you sure want to exit?",
		icon: "danger",
		confirmButtonText: "Yes"
	},{
		yes: function() {
			window.location = href;
		}
	});

});


// Overriding export behaviour of jQuery Datatable.
var oldExportAction = function (self, e, dt, button, config) {
	if (button[0].className.indexOf('buttons-excel') >= 0) {
		if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
			$.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
		}
		else {
			$.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
		}
	} else if (button[0].className.indexOf('buttons-print') >= 0) {
		$.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
	} else if(button[0].className.indexOf('buttons-csv') >= 0) {
		if ($.fn.dataTable.ext.buttons.csvHtml5.available(dt, config)) {
			$.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config);
		} else {
			$.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
		}
	}
};

var newExportAction = function (e, dt, button, config) {
	
	var self = this;
	var oldStart = dt.settings()[0]._iDisplayStart;

	dt.one('preXhr', function (e, s, data) {
		// Just this once, load all data from the server...
		data.start = 0;
		data.length = 2147483647;

		dt.one('preDraw', function (e, settings) {
			// Call the original action function 
			oldExportAction(self, e, dt, button, config);

			dt.one('preXhr', function (e, s, data) {
				// DataTables thinks the first item displayed is index 0, but we're not drawing that.
				// Set the property to what it was before exporting.
				settings._iDisplayStart = oldStart;
				data.start = oldStart;
			});

			// Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
			setTimeout(dt.ajax.reload, 0);

			// Prevent rendering of the full data to the DOM
			return false;
		});
	});
	// Requery the server with the new one-time export settings
	dt.ajax.reload();

};

/**
 * Common Scripts
 */
/**
 * Default options for datatable
 */
if($.fn.dataTable) {
	$.extend(true, $.fn.dataTable.defaults, {
		dom:
			'<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
		orderCellsTop: true,
		processing: true,
		serverSide: true,
	
		responsive: {
			details: {
				display: $.fn.dataTable.Responsive.display.modal({
					header: function(row) {
						var data = row.data();
						return "Details";
					},
				}),
				type: "column",
				renderer: $.fn.dataTable.Responsive.renderer.tableAll({
					tableClass: "table",
				}),
			},
		},
		language: {
			paginate: {
				// remove previous & next text from pagination
				previous: "&nbsp;",
				next: "&nbsp;",
			},
			"emptyTable": `<h4 class="my-1">Oops! Nothing found.</h4>`,
			"zeroRecords": `<h4 class="my-1">Oops! Nothing found.</h4>`,
		},
		drawCallback: function() {

			setTimeout(function() {
				initTooltip();
				feather.replace({
					width: 14,
					height: 14,
				});
			}, 1000);
		},
		createdRow: function() {},
	});
}

$("body").on("click", ".copy-me", function(e) {
	let copyText = $(this).data('copy');
	copyToClipboard(copyText);
});

/**
 * Get content via ajax in side modal
 */
$("body").on("click", ".get-content", function() {
	$("#dynamic-modal").modal();
	$("#dynamic-modal")
		.find(".modal-title")
		.html($(this).data("title"));

	getContent({
		url: $(this).data("url"),
		success: function(html) {
			$(".dynamic-content").html(html);
			window.canBlock = false;
			try {
				initTooltip();
				dynamicScript();
				feather.replace({
					width: 14,
					height: 14,
				});
			} catch (e) { console.error("Error:", e) }
		},
	});
});

//Custom File Input
$("body").on("change", ".custom-file-input", function(e) {
	$(this).siblings('.custom-file-label').html(e.target.files[0].name);
});

try {
	$(".btn-primary").addClass('btn-hover-primary');
	$(".btn-danger").addClass('btn-hover-danger');
	$(".btn-success").addClass('btn-hover-success');
	$(".btn-info").addClass('btn-hover-info');
	$(".btn-dark").addClass('btn-hover-dark');
	$(".btn-secondary").addClass('btn-hover-secondary');
  } catch(e) { console.warn(e); }
/**
 * Very bad code - I will remove this, this is only for testing as my mind is not working for better solution.
 */

// setInterval(function() {
// 	feather.replace({
// 		width: 14,
// 		height: 14,
// 	});
// }, 1000);

