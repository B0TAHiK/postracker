{% extends "base.tpl" %}

{% block title %}Supercapital Monitoring{% endblock %}

{% block pageName %}Supercapital Monitoring{% endblock %}
{% block switchButton %}
<style> .switch {
        margin-top: -30px;
    }
</style>
{% if showOld == 'old' %}
    <form action='superCapitalMonitoring.php' method='post' align='right' class='switch'><input type=hidden value=''><button type=submit class="btn btn-default switch">Hide old faggots</button></form>
{% else %}
    <form action='superCapitalMonitoring.php' method='post' align='right' class='switch'><button type=submit name='showOld' value='old' class="btn btn-default switch">Show old faggots</button></form>
    
{% endif %}
{% endblock %}

{% block content %}
    {% if loggedIN > 0 %}
    {% for corp in data %}
        {% if corp.corpName != '' %}
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <h5>Owner: <b>{{ corp.corpName }}</b></h5>
                </div>
                 <!-- Table -->
                <table class="table table-striped table-bordered table-hover">
                    <thead><tr>
                        <th width = 10%>Pilot</th>
                        <th width = 10%>Ship</th>
                        <th width = 15%>Class</th>
                        <th width = 10%>System</th>
                        <th width = 10%>Region</th>
                        <th width = 5%>SS</th>
                        <th width = 20%>Last Login</th>
                        <th width = 20%>Last Logout</th>
                    </tr></thead>
                    <tbody>
                        {% for table in corp %}
                        {% if table is iterable %}
                            <tr>
                                <td>{{table.characterName }}</td>
                                <td>{{table.shipTypeName}}</td>
                                <td>{{table.shipClass}}</td>
                                <td>{{table.locationName}}</td>
                                <td>{{table.regionName}}</td>
                                <td>{{table.SS}}</td>
                                <td>{{table.logonDateTime}}</td>
                                <td>{{table.logoffDateTime}}</td>
                            </tr>
                        {% endif %}
                        {% endfor %}
                    </tbody>
                </table>
            </div>
            {% endif %}
    {% endfor %}
{% else %}
    <div class="alert alert-danger" role="alert">Access denied. Autorization required.</div>
{% endif %}
{% endblock %}