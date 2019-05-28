{% extends 'theme' %}

{% block content %}
    <h1>{{ page.title }}</h1>
    <p>Metadata (Not exists): {{ page.meta('not-existing-key') }}</p>
    <p>Metadata (Exists): {{ page.meta('thumbnail') }}</p>
{% endblock %}
