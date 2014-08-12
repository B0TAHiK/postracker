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
            .table th {
                text-align: center;
                vertical-align: middle;
            }
            .table td {
                text-align: center;
                vertical-align: middle;
            }
        </style>
            <title>{% block title %}{% endblock %} - POS Monitor</title>
        {% endblock %}
    </head>
    <body>
        {% block header %}{% include 'header.tpl' %}{% endblock %}
        <div class="container">
        <style>.page-header{
            margin-top: 20px;
            }
        </style>
        <div class="page-header">
        <h1>{% block pageName %}{% endblock %}</h1>
        {% block switchButton %}{% endblock %}
        </div>
            {% block content %}
            {% endblock %}
            <hr>
        </div>
        {% block footer %}
            <div class="row">
                <div class="col-xs-12">
                    <footer>
                        <div class="container" align='center'>
                        © 2014 greg2010, atap
                        </div>
                </div>
            </div>
        {% endblock %}
    </body>
</html>