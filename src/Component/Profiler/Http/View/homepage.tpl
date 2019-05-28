{% extends '@profiler/layout.tpl' %}

{% block content %}
    <h1>Profile</h1>
    {{ dump(profile) }}
{% endblock %}
