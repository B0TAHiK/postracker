<!DOCTYPE html>
<html>
    <head>
        {% block head %}
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
        <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <style>
            .table td {
                text-align: center;   
            }
        </style>
            <title>{% block title %}{% endblock %} - POS Monitor</title>
        {% endblock %}
    </head>
    <body>
        {% block header %}{% include 'header.tpl' %}{% endblock %}
        <div class="container">
        <div class="page-header">
        <h1>{% block pageName %}{% endblock %}</h1>
        </div>
            {% block content %}
            {% endblock %}
        </div>
        {% block footer %}
            <hr>
            <div class="row">
                <div class="col-xs-12">
                    <footer>
                        <div class="container">
                        Made by greg2010 & atap
                        </div>
                    </footer>
                </div>
            </div>
        {% endblock %}
    </body>
</html>