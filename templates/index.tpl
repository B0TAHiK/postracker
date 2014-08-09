{% extends "base.tpl" %}

{% block title %}Index{% endblock %}

{% block pageName %}POS Monitor{% endblock %}
{% block switchButton %}
<style> .switch {
        margin-top: -30px;
    }
</style>
{% if showAnchored == 'old' %}
    <form action='index.php' method='post' align='right' class='switch'><button type=submit class="btn btn-default switch">Hide Anchored POSes</button></form>
{% else %}
    <form action='index.php' method='post' align='right' class='switch'><input type=hidden name='anchored' value='old'><button type=submit class="btn btn-default switch">Show Anchored POSes</button></form></form>
{% endif %}
{% endblock %}

{% block content %}
    {% if loggedIN > 0 %}
    <script type="text/javascript">
    $(document).ready(function(){
        $(".table a").popover({
            placement : 'top',
            html : 'true'
        });
        $(".table span").tooltip({
            placement : 'top'
        });
    });
    </script>
    {% for corp in data %}
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <h5>Owner: <b>{{ corp.corpName }}</b></h5>
            </div>
             <!-- Table -->
            <table class="table table-striped table-bordered table-hover">
                <thead><tr>
                    <th width="10%">System</th>
                    <th width="20%">Type</th>
                    <th width="20%">Moon</th>
                    <th width="15%">State</th>
                    <th width="10%">Fuel left</th>
                    <th width="10%"><span data-toggle="tooltip" data-original-title="Reinforce timer">Stront time left</span></th>
                    <th width="15%">Silo information</th>
                </tr></thead>
                <tbody>
                    {% for table in corp %}
                    {% if table is iterable %}
                        <tr {{table.status}}>
                            <td>{{table.locationName }}</td>
                            <td>{{table.typeName}}</td>
                            <td>{{table.moonName}}</td>
                            <td>{{table.state}}</td>
                            <td>{{table.time.d}}d {{table.time.h}}h</td>
                            <td>
                                {% if table.stateID == 3 %}
                                    {{table.stateTimestamp}}
                                {% else %}
                                    {{table.rftime.d}}d {{table.rftime.h}}h
                                {% endif %}
                            </td>
                            <td>
                                {% if table.numSilo > 1 %}
                                <a class="btn btn-{{table.popoverType}}" data-toggle="popover" title="Silos" data-content="
                                        {% for silo in table %}
                                        {% if silo.mmname is defined %}
                                        <b>{{silo.mmname}}</b>:<br>
                                        {{silo.quantity}}/{{silo.maximum}}<br>
                                        {% endif %}
                                        {% endfor %}">Show Silos</a>

                                {% else %}
                                    No Silo.
                                {% endif %}
                            </td>
                        </tr>
                    {% endif %}
                    {% endfor %}
                </tbody>
            </table>
        </div>
    {% endfor %}
{% else %}
    <div class="alert alert-danger" role="alert">Access denied. Autorization required.</div>
{% endif %}
{% endblock %}