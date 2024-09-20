jQuery(document).ready(function ($) {
	$("#lead-capture-form").on("submit", function (e) {
		e.preventDefault()

		var email = $('input[name="lead_email"]').val()
		var messageDiv = $("#lead-capture-message")

		$.ajax({
			url: leadCapture.ajaxurl, // URL do AJAX do WordPress
			method: "POST",
			data: {
				action: "submit_lead_ajax",
				lead_email: email,
			},
			beforeSend: function () {
				// Limpar mensagem anterior e desativar botão
				messageDiv.html("")
				$("#lead-capture-form button").attr("disabled", true)
			},
			success: function (response) {
				if (response.success) {
					messageDiv.html(
						'<p class="success">' + response.data.message + "</p>"
					)
				} else {
					messageDiv.html('<p class="error">' + response.data.message + "</p>")
				}
			},
			complete: function () {
				// Reativar botão após a conclusão
				$("#lead-capture-form button").attr("disabled", false)
			},
			error: function () {
				messageDiv.html('<p class="error">Erro ao processar a solicitação.</p>')
				$("#lead-capture-form button").attr("disabled", false)
			},
		})
	})
})
