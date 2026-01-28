// automatically send CSRF token for all AJAX and POST requests

function addCsrfField(form) {
	// Check if form is valid and has method property
	if (!form || !form.method || typeof form.method !== 'string') {
		return;
	}
	
	if (form.method.toUpperCase() !== 'GET') {
		// Check if the input with the name csrfParam already exists
		let input = form.querySelector(`input[name="${csrfParam}"]`);
		if (! input) {
			input = document.createElement('input');
			input.type = 'hidden';
			input.name = csrfParam;
			input.value = getCsrfToken();

			form.append(input);
		} else {
			// Update token value
			input.value = getCsrfToken();
		}
	}
}

function csrf_semua_form()
{
	document.querySelectorAll('form').forEach((form) => {
		addCsrfField(form)
		form.addEventListener('submit', (e) => addCsrfField(e.target))
	})	

	document.addEventListener('submit', (e) => {
		if (e.target.nodeName === 'FORM') {
			addCsrfField(e.target)
		}
	})
}

function refreshFormCsrf() {
	$('form')
		.find('input[type="hidden"]')
		.filter(`[name="${csrfParam}"]`)
		.val($.cookie(csrfParam));
}

// Wait for csrfParam to be available
function initCsrfProtection() {
	if (typeof csrfParam === 'undefined' || typeof getCsrfToken === 'undefined') {
		console.warn('CSRF parameters not yet loaded, retrying...');
		setTimeout(initCsrfProtection, 50);
		return;
	}

	$(document).ready(function() {
		csrf_semua_form();

		$(document).ajaxComplete(function() {
			refreshFormCsrf();
		});

		$.ajaxPrefilter((opts, origOpts, xhr) => {
			if (!opts.crossDomain && !['HEAD', 'GET', 'OPTIONS'].includes(opts.type)) {
				const csrfToken = $.cookie(csrfParam);

				if (opts.data instanceof FormData) {
					opts.data.append(csrfParam, csrfToken);
				} else {
					opts.data = `${opts.data || ''}&${csrfParam}=${csrfToken}`;
				}
			}
		})
	});
}

// Start initialization
initCsrfProtection();
