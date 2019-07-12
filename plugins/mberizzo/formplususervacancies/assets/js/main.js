function vacancyDetailsOnError(th, context, textStatus, jqXHR) {
    alert(jqXHR.responseJSON.error);
}
