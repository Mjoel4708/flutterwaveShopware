{% sw_extends '@Storefront/storefront/page/checkout/confirm/index.html.twig' %}

{% block page_checkout_confirm_form_submit %}

<button id="confirmFormSubmit" class="btn btn-primary btn-block btn-lg" form="confirmOrderForm" {% if page.cart.errors.blockOrder %} disabled {% endif %} type="submit">
    {{ "checkout.confirmSubmit"|trans|sw_sanitize }}
</button>
{% endblock %}