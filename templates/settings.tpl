{% extends "base.tpl" %}

{% block title %}Settings{% endblock %}

{% block pageName %}Settings{% endblock %}

{% block content %}
    {% if settings.success == '1' %}
        <div class="alert alert-success" role="alert">Information updated.</div>
    {% endif %}
    <form action="settings.php" method="post" class="form-horizontal" style="width: 45%;margin: 0 auto;">
        <div class="form-group">
        <label>How you'd like to receive notifications?</label>
        <div class="checkbox">
            <label><input type="checkbox" name="email" value="1" {{settings.emailChecked}}>E-mail</label>
        </div>
        <div class="checkbox">
            <label><input type="checkbox" name="jabber" value="1" {{settings.jabberChecked}}>Jabber</label>
        </div>
        </div>
        <div class="form-group">
            <label>Please enter your JID below:</label>
            <div class="col-sm-10">
                <div class="input-group" id="inputJID">
                    <input type="text" class="form-control" placeholder="e.g. red-buaco-greg2010" name="login" value="{{settings.jabberLogin}}">
                    <span class="input-group-addon">@</span>
                    <input type="text" class="form-control" placeholder="redalliance.pw" name="server" value="{{settings.jabberServer}}">
                </div>
            </div>
        </div>
        <input type=hidden name="go" value="sent">
        <div class="form-group">
            <div class="col-sm-10">
                <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </div>
    </form>
{% endblock %}